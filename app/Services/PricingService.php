<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\GroupProductOffer;

class PricingService
{
    /**
     * Get the price for a product based on customer's group memberships
     */
    public function getCustomerPrice(Product $product, ?User $customer = null)
    {
        if (!$customer) {
            return $product->price;
        }

        $customerGroups = $customer->groups()->where('is_active', 1)->get();

        if ($customerGroups->isEmpty()) {
            return $product->price;
        }

        $today = Carbon::today();

        // Priority 1: Check for active product-specific offers
        foreach ($customerGroups as $group) {
            $offer = $product->groupOffers()
                ->where('customer_group_id', $group->id)
                ->where('offer_type', 'product')
                ->where('starts_at', '<=', $today)
                ->where('ends_at', '>=', $today)
                ->first();

            if ($offer && $offer->offer_price) {
                return $offer->offer_price;
            }
        }

        // Priority 2: Check for active category offers
        if ($product->category_id) {
            foreach ($customerGroups as $group) {
                $categoryOffer = GroupProductOffer::where('customer_group_id', $group->id)
                    ->where('offer_type', 'category')
                    ->where('category_id', $product->category_id)
                    ->where('starts_at', '<=', $today)
                    ->where('ends_at', '>=', $today)
                    ->first();

                if ($categoryOffer) {
                    return $categoryOffer->calculateDiscountedPrice($product->price);
                }
            }
        }

        // Priority 3: Check for active brand offers
        if ($product->brand_id) {
            foreach ($customerGroups as $group) {
                $brandOffer = GroupProductOffer::where('customer_group_id', $group->id)
                    ->where('offer_type', 'brand')
                    ->where('brand_id', $product->brand_id)
                    ->where('starts_at', '<=', $today)
                    ->where('ends_at', '>=', $today)
                    ->first();

                if ($brandOffer) {
                    return $brandOffer->calculateDiscountedPrice($product->price);
                }
            }
        }

        // Priority 4: Check for group-specific pricing
        $lowestPrice = null;
        foreach ($customerGroups as $group) {
            $groupPrice = $product->groupPrices()
                ->where('customer_group_id', $group->id)
                ->first();

            if ($groupPrice) {
                if ($lowestPrice === null || $groupPrice->price < $lowestPrice) {
                    $lowestPrice = $groupPrice->price;
                }
            }
        }

        if ($lowestPrice !== null) {
            return $lowestPrice;
        }

        // Default: Return standard price
        return $product->price;
    }


    /**
     * Calculate group-based discount for order subtotal
     */
    public function applyGroupDiscounts($subtotal, ?User $customer = null)
    {
        if (!$customer || $subtotal <= 0) {
            return 0;
        }

        $customerGroups = $customer->groups()->where('is_active', 1)->get();

        if ($customerGroups->isEmpty()) {
            return 0;
        }

        $maxDiscount = 0;

        foreach ($customerGroups as $group) {
            $discounts = $group->groupDiscounts()
                ->where('is_active', 1)
                ->where(function($q) use ($subtotal) {
                    $q->whereNull('min_order_amount')
                      ->orWhere('min_order_amount', '<=', $subtotal);
                })
                ->get();

            foreach ($discounts as $discount) {
                $amount = $discount->type === 'percentage'
                    ? ($subtotal * $discount->value / 100)
                    : $discount->value;

                $maxDiscount = max($maxDiscount, $amount);
            }
        }

        return $maxDiscount;
    }

    /**
     * Get all applicable group names for a customer
     */
    public function getCustomerGroupNames(?User $customer = null)
    {
        if (!$customer) {
            return [];
        }

        return $customer->groups()
            ->where('is_active', 1)
            ->pluck('name')
            ->toArray();
    }

    /**
     * Check if customer belongs to a specific group
     */
    public function isInGroup(User $customer, string $groupSlug)
    {
        return $customer->groups()
            ->where('is_active', 1)
            ->where('slug', $groupSlug)
            ->exists();
    }
}
