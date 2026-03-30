<?php

namespace App\Services;

use App\Models\ShippingSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    // ─────────────────────────────────────────────────────────────────
    // No constructor, no $settings property.
    // Settings are always pulled fresh from cache on every call.
    // When admin saves → ShippingSetting::booted() clears the cache
    // → next call re-fetches from DB automatically.
    // ─────────────────────────────────────────────────────────────────

    // ─────────────────────────────────────────────────────────────────
    // PUBLIC — Main entry point
    // Returns:
    //   0.00     = free delivery
    //   positive = charge in £
    //  -1.0      = outside delivery radius (reject order)
    // ─────────────────────────────────────────────────────────────────
    public function calculate(float $subtotal, ?string $customerPostcode = null): float
    {
        return match ($this->getMode()) {
            'free'                   => 0.00,
            'free_above_threshold'   => $this->calcThreshold($subtotal),
            'distance_based'         => $this->calcDistance($subtotal, $customerPostcode),
            'threshold_and_distance' => $this->calcThresholdAndDistance($subtotal, $customerPostcode),
            default                  => 0.00,
        };
    }

    // ─────────────────────────────────────────────────────────────────
    // PUBLIC — Delivery message for checkout UI
    // ─────────────────────────────────────────────────────────────────
    public function getDeliveryMessage(float $subtotal): string
    {
        $threshold = $this->getFreeThreshold();
        $mode      = $this->getMode();

        if ($mode === 'free') {
            return '🎉 Free delivery on this order!';
        }

        if (in_array($mode, ['free_above_threshold', 'threshold_and_distance', 'distance_based'])) {
            if ($subtotal >= $threshold) {
                return '🎉 Free delivery on this order!';
            }

            $remaining = number_format($threshold - $subtotal, 2);
            return "Add £{$remaining} more to your order for free delivery!";
        }

        return '';
    }

    // ─────────────────────────────────────────────────────────────────
    // PUBLIC — Getters (all read fresh from cache)
    // ─────────────────────────────────────────────────────────────────
    public function getMode(): string
    {
        return $this->setting('mode', 'free');
    }

    public function getFreeThreshold(): float
    {
        return (float) $this->setting('free_threshold', 50);
    }

    public function isDistanceBased(): bool
    {
        return in_array($this->getMode(), ['distance_based', 'threshold_and_distance']);
    }

    public function getSettings(): array
    {
        return ShippingSetting::allCached();
    }

    // ─────────────────────────────────────────────────────────────────
    // PRIVATE — Mode: free_above_threshold
    // ─────────────────────────────────────────────────────────────────
    private function calcThreshold(float $subtotal): float
    {
        return $subtotal >= $this->getFreeThreshold()
            ? 0.00
            : (float) $this->setting('base_rate', 2.99);
    }

    // ─────────────────────────────────────────────────────────────────
    // PRIVATE — Mode: distance_based
    // ─────────────────────────────────────────────────────────────────
    private function calcDistance(float $subtotal, ?string $customerPostcode, bool $skipThresholdCheck = false): float
    {
        if (!$skipThresholdCheck && $subtotal >= $this->getFreeThreshold()) {
            return 0.00;
        }

        if (!$customerPostcode) {
            return (float) $this->setting('base_rate', 2.99);
        }

        $distance = $this->getDistanceInMiles($customerPostcode);

        // API failed → fall back to base rate
        if ($distance === null) {
            return (float) $this->setting('base_rate', 2.99);
        }

        $maxMiles    = (float) $this->setting('max_delivery_miles', 10);
        $baseRate    = (float) $this->setting('base_rate', 2.99);
        $ratePerMile = (float) $this->setting('rate_per_mile', 0.50);

        if ($distance > $maxMiles) {
            return -1.0; // outside delivery radius
        }

        return round($baseRate + ($distance * $ratePerMile), 2);
    }

    // ─────────────────────────────────────────────────────────────────
    // PRIVATE — Mode: threshold_and_distance
    // ─────────────────────────────────────────────────────────────────
    private function calcThresholdAndDistance(float $subtotal, ?string $customerPostcode): float
    {
        if ($subtotal >= $this->getFreeThreshold()) {
            return 0.00;
        }

        return $this->calcDistance($subtotal, $customerPostcode, skipThresholdCheck: true);
    }

    // ─────────────────────────────────────────────────────────────────
    // PRIVATE — Get distance in miles
    // Store postcode coordinates cached for 24h
    // Only customer postcode is looked up live per order
    // ─────────────────────────────────────────────────────────────────
    private function getDistanceInMiles(string $customerPostcode): ?float
    {
        $storeCoords = $this->getStoreCoordinates();

        if (!$storeCoords) {
            return null;
        }

        $to = strtoupper(preg_replace('/\s+/', '', $customerPostcode));

        if (empty($to)) {
            return null;
        }

        try {
            $response = Http::timeout(5)
                ->get("https://api.postcodes.io/postcodes/{$to}")
                ->json();

            if (($response['status'] ?? 0) !== 200) {
                Log::warning('Shipping: customer postcode lookup failed', ['postcode' => $to]);
                return null;
            }

            return $this->haversine(
                $storeCoords['lat'],
                $storeCoords['lon'],
                $response['result']['latitude'],
                $response['result']['longitude'],
            );

        } catch (\Exception $e) {
            Log::warning('Shipping: postcode API error', [
                'postcode' => $to,
                'error'    => $e->getMessage(),
            ]);
            return null;
        }
    }

    // ─────────────────────────────────────────────────────────────────
    // PRIVATE — Cache store postcode coordinates for 24 hours
    // ─────────────────────────────────────────────────────────────────
    private function getStoreCoordinates(): ?array
    {
        $postcode = strtoupper(preg_replace('/\s+/', '', $this->setting('store_postcode', '')));

        if (empty($postcode)) {
            return null;
        }

        return Cache::remember("store_coords_{$postcode}", 86400, function () use ($postcode) {
            try {
                $res = Http::timeout(5)
                    ->get("https://api.postcodes.io/postcodes/{$postcode}")
                    ->json();

                if (($res['status'] ?? 0) !== 200) {
                    return null;
                }

                return [
                    'lat' => $res['result']['latitude'],
                    'lon' => $res['result']['longitude'],
                ];

            } catch (\Exception $e) {
                Log::warning('Shipping: store postcode lookup failed', [
                    'postcode' => $postcode,
                    'error'    => $e->getMessage(),
                ]);
                return null;
            }
        });
    }

    // ─────────────────────────────────────────────────────────────────
    // PRIVATE — Haversine formula, returns miles
    // ─────────────────────────────────────────────────────────────────
    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 3958.8;
        $dLat        = deg2rad($lat2 - $lat1);
        $dLon        = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return round($earthRadius * 2 * asin(sqrt($a)), 2);
    }

    // ─────────────────────────────────────────────────────────────────
    // PRIVATE — Always reads fresh from cache, never from a property
    // ─────────────────────────────────────────────────────────────────
    private function setting(string $key, mixed $default = null): mixed
    {
        return (ShippingSetting::allCached())[$key] ?? $default;
    }
}
