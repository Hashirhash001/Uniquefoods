<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmation;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\UserAddress;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function __construct(private ShippingService $shipping) {}

    public function index()
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $subtotal     = $this->calculateSubtotal($cart);
        $shippingCost = $this->shipping->calculate($subtotal, $this->getDefaultPostcode());

        // If out of range on index, just show 0 — don't block page load
        if ($shippingCost < 0) $shippingCost = 0;

        $tax   = $this->calculateTax($cart);
        $total = $subtotal + $shippingCost + $tax;

        // Delivery message for the UI banner
        $deliveryMessage    = $this->shipping->getDeliveryMessage($subtotal);
        $isDistanceBased    = $this->shipping->isDistanceBased();
        $freeThreshold      = $this->shipping->getFreeThreshold();

        $savedAddresses = collect();
        $lastAddress    = null;

        if (Auth::check()) {
            $savedAddresses = UserAddress::where('user_id', Auth::id())
                ->orderByDesc('is_default')
                ->orderByDesc('updated_at')
                ->get();

            $lastAddress = $savedAddresses->firstWhere('is_default', true)
                ?? $savedAddresses->first();
        }

        return view('frontend.checkout', compact(
            'cart', 'subtotal', 'shippingCost', 'tax', 'total',
            'savedAddresses', 'lastAddress',
            'deliveryMessage', 'isDistanceBased', 'freeThreshold'
        ));
    }

    public function processOrder(Request $request)
    {
        $key = 'checkout:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many order attempts. Please try again in ' . RateLimiter::availableIn($key) . ' seconds.'
            ], 429);
        }

        RateLimiter::hit($key, 60);

        try {
            $validated = $request->validate([
                'customer_name'    => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'customer_email'   => 'required|email|max:255',
                'customer_phone'   => 'required|string|min:10|max:20|regex:/^[0-9+\s\-()]+$/',
                'address_line1'    => 'required|string|max:255',
                'address_line2'    => 'nullable|string|max:255',
                'restaurant_store' => 'nullable|string|max:255',
                'city'             => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'county'           => 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'postcode'         => 'required|string|max:10|regex:/^[A-Z]{1,2}[0-9]{1,2}[A-Z]?\s?[0-9][A-Z]{2}$/i',
                'payment_method'   => 'required|in:cash_on_delivery',
                'cod_delivery_method'  => 'required|in:cash,bank_transfer',
                'customer_notes'   => 'nullable|string|max:1000',
                'save_address'     => 'nullable|boolean',
                'address_label'    => 'nullable|string|max:50',
            ], [
                'customer_name.required'  => 'Full name is required',
                'customer_name.regex'     => 'Name can only contain letters and spaces',
                'customer_email.required' => 'Email address is required',
                'customer_email.email'    => 'Please enter a valid email address',
                'customer_phone.required' => 'Phone number is required',
                'customer_phone.regex'    => 'Please enter a valid UK phone number',
                'address_line1.required'  => 'Address line 1 is required',
                'city.required'           => 'Town/City is required',
                'postcode.required'       => 'Postcode is required',
                'postcode.regex'          => 'Please enter a valid UK postcode',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please check the form for errors',
                'errors'  => $e->errors()
            ], 422);
        }

        // ── Sanitize ───────────────────────────────────────────────
        $validated['customer_name']    = strip_tags($validated['customer_name']);
        $validated['customer_email']   = filter_var($validated['customer_email'], FILTER_SANITIZE_EMAIL);
        $validated['customer_phone']   = strip_tags($validated['customer_phone']);
        $validated['address_line1']    = strip_tags($validated['address_line1']);
        $validated['address_line2']    = isset($validated['address_line2'])    ? strip_tags($validated['address_line2'])    : null;
        $validated['restaurant_store'] = isset($validated['restaurant_store']) ? strip_tags($validated['restaurant_store']) : null;
        $validated['city']             = strip_tags($validated['city']);
        $validated['county']           = isset($validated['county'])           ? strip_tags($validated['county'])           : null;
        $validated['postcode']         = strtoupper(strip_tags($validated['postcode']));
        $validated['customer_notes']   = isset($validated['customer_notes'])   ? strip_tags($validated['customer_notes'])   : null;

        DB::beginTransaction();
        try {
            $cart = $this->getCart();

            if (empty($cart)) {
                throw new \Exception('Your cart is empty. Please add items before checkout.');
            }

            // ✅ Load ALL products in ONE query instead of Product::find() per item
            $productIds = array_column($cart, 'id');
            $products   = Product::whereIn('id', $productIds)
                ->get()
                ->keyBy('id'); // keyed by ID for O(1) lookup

            // ── Validate stock ─────────────────────────────────────
            foreach ($cart as $item) {
                $product = $products->get($item['id']);

                if (!$product)            throw new \Exception("Product '{$item['name']}' is no longer available.");
                if (!$product->is_active) throw new \Exception("Product '{$item['name']}' is currently unavailable.");

                if ($product->is_weight_based) {
                    $weight = floatval($item['weight'] ?? 0);
                    if ($weight <= 0)
                        throw new \Exception("Please select a valid weight for '{$item['name']}'.");
                    if ($product->min_weight && $weight < $product->min_weight)
                        throw new \Exception("Minimum order weight for '{$item['name']}' is {$product->min_weight}kg.");
                    if ($product->max_weight && $weight > $product->max_weight)
                        throw new \Exception("Maximum order weight for '{$item['name']}' is {$product->max_weight}kg.");
                } else {
                    if ($product->stock < $item['quantity'])
                        throw new \Exception("Insufficient stock for '{$item['name']}'. Only {$product->stock} available.");
                }
            }

            $subtotal     = $this->calculateSubtotal($cart);
            $shippingCost = $this->shipping->calculate($subtotal, $validated['postcode']);

            if ($shippingCost < 0) {
                throw new \Exception('Sorry, we currently do not deliver to your postcode. Please contact us.');
            }

            $tax          = $this->calculateTax($cart);
            $total        = $subtotal + $shippingCost + $tax;

            // ── Duplicate order guard ──────────────────────────────
            if (Auth::check()) {
                $recentOrder = Order::where('user_id', Auth::id())
                    ->where('customer_email', $validated['customer_email'])
                    ->where('total', $total)
                    ->where('created_at', '>=', now()->subMinutes(5))
                    ->exists(); // ✅ exists() instead of first() — no row hydration needed
                if ($recentOrder) throw new \Exception('A similar order was recently placed. Please check your orders.');
            }

            $fullAddress = $validated['address_line1'];
            if ($validated['address_line2']) {
                $fullAddress .= ', ' . $validated['address_line2'];
            }

            $order = Order::create([
                'order_number'             => Order::generateOrderNumber(),
                'user_id'                  => Auth::id(),
                'customer_name'            => $validated['customer_name'],
                'customer_email'           => $validated['customer_email'],
                'customer_phone'           => $validated['customer_phone'],
                'shipping_address'         => $fullAddress,
                'shipping_city'            => $validated['city'],
                'shipping_postcode'        => $validated['postcode'],
                'shipping_country'         => 'UK',
                'subtotal'                 => $subtotal,
                'shipping_cost'            => $shippingCost,
                'tax'                      => $tax,
                'total'                    => $total,
                'payment_method'           => 'cash_on_delivery',
                'cod_delivery_method' => $validated['cod_delivery_method'],
                'payment_status'           => 'pending',
                'stripe_payment_intent_id' => null,
                'paid_at'                  => null,
                'status'                   => 'pending',
                'customer_notes'           => $validated['customer_notes'],
                'restaurant_store'         => $validated['restaurant_store'],
            ]);

            // ✅ Build all order items and batch insert — 1 query instead of N
            $orderItems  = [];
            $stockUpdates = []; // [product_id => qty_to_decrement]
            $now         = now();

            foreach ($cart as $item) {
                $product       = $products->get($item['id']);
                $isWeightBased = !empty($item['weight']) && floatval($item['weight']) > 0;
                $itemSubtotal  = $isWeightBased
                    ? $item['price'] * floatval($item['weight'])
                    : $item['price'] * $item['quantity'];

                $orderItems[] = [
                    'order_id'     => $order->id,
                    'product_id'   => $item['id'],
                    'product_name' => $item['name'],
                    'price'        => $item['price'],
                    'quantity'     => $isWeightBased ? null : $item['quantity'],
                    'weight'       => $isWeightBased ? floatval($item['weight']) : null,
                    'subtotal'     => round($itemSubtotal, 2),
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];

                if (!$product->is_weight_based) {
                    $stockUpdates[$item['id']] = $item['quantity'];
                }
            }

            OrderItem::insert($orderItems); // ✅ single INSERT for all items

            // ✅ Decrement stock per product — one query each but only for qty-based
            foreach ($stockUpdates as $productId => $qty) {
                Product::where('id', $productId)->decrement('stock', $qty);
            }

            // ── Save address if requested ──────────────────────────
            if (Auth::check() && !empty($validated['save_address'])) {
                if ($request->boolean('set_as_default')) {
                    UserAddress::where('user_id', Auth::id())
                        ->update(['is_default' => false]);
                }

                $existing = UserAddress::where('user_id', Auth::id())
                    ->where('address_line1', $validated['address_line1'])
                    ->where('postcode', $validated['postcode'])
                    ->first();

                $addressData = [
                    'user_id'          => Auth::id(),
                    'label'            => $validated['address_label'] ?? null,
                    'recipient_name'   => $validated['customer_name'],
                    'phone'            => $validated['customer_phone'],
                    'address_line1'    => $validated['address_line1'],
                    'address_line2'    => $validated['address_line2'],
                    'restaurant_store' => $validated['restaurant_store'],
                    'city'             => $validated['city'],
                    'county'           => $validated['county'],
                    'postcode'         => $validated['postcode'],
                    'country'          => 'UK',
                    'is_default'       => $request->boolean('set_as_default'),
                ];

                if ($existing) {
                    $existing->update($addressData);
                } else {
                    // ✅ Single query to enforce 5-address limit
                    $count = UserAddress::where('user_id', Auth::id())->count();
                    if ($count >= 5) {
                        UserAddress::where('user_id', Auth::id())
                            ->orderBy('updated_at')
                            ->limit(1)
                            ->delete(); // ✅ one DELETE instead of find + delete
                    }
                    UserAddress::create($addressData);
                }
            }

            // ── Clear cart ─────────────────────────────────────────
            if (Auth::check()) {
                $userCart = Cart::where('user_id', Auth::id())->first();
                if ($userCart) $userCart->items()->delete();
            } else {
                session()->forget('cart');
            }

            DB::commit();

            // ── Send confirmation emails (all queued — never blocks response) ──
            try {
                $order->load('items.product');

                $recipients = array_filter([
                    $validated['customer_email'],                    // customer
                    config('mail.order_notification_email'),         // company (from .env)
                    app()->environment('local') ? 'hashmvhashmuhammed007@gmail.com' : null, // dev only
                ]);

                Mail::to(array_shift($recipients))                   // primary recipient
                    ->bcc($recipients)                               // company + dev as BCC
                    ->queue(new OrderConfirmation($order));

            } catch (\Exception $mailEx) {
                Log::warning('Order confirmation email failed', [
                    'order_number' => $order->order_number,
                    'error'        => $mailEx->getMessage(),
                ]);
            }

            Log::info('Order placed successfully', [
                'order_number' => $order->order_number,
                'user_id'      => Auth::id(),
                'total'        => $total,
                'ip'           => $request->ip(),
            ]);

            RateLimiter::clear($key);

            return response()->json([
                'success'      => true,
                'message'      => 'Your order has been placed successfully!',
                'order_number' => $order->order_number,
                'redirect'     => route('orders.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order processing failed', [
                'error'   => $e->getMessage(),
                'user_id' => Auth::id(),
                'ip'      => $request->ip(),
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ── Private helpers ───────────────────────────────────────────
    private function getCart(): array
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if (!$cart) return [];

            return $cart->items()                          // ← query builder, not property
                ->with(['product' => function ($q) {       // eager load with only needed columns
                    $q->select([
                        'id', 'name', 'slug', 'stock',
                        'is_weight_based', 'tax_rate',
                        'min_weight', 'max_weight',
                        'is_active',
                    ])
                    ->with(['primaryImage:id,product_id,image_path']);
                }])
                ->get()
                ->map(function ($item) {
                    if (!$item->product || !$item->product->is_active) return null;

                    return [
                        'id'              => $item->product_id,
                        'cart_item_id'    => $item->id,
                        'name'            => $item->product->name,
                        'slug'            => $item->product->slug,
                        'price'           => (float) $item->price,
                        'quantity'        => (int) $item->quantity,
                        'weight'          => $item->weight,
                        'image'           => $item->product->image_url,
                        'stock'           => $item->product->stock,
                        'is_weight_based' => (bool) $item->product->is_weight_based,
                        'tax_rate'        => (float) ($item->product->tax_rate ?? 20),
                    ];
                })
                ->filter()
                ->values()
                ->toArray();
        }

        // ── Guest cart from session ──
        // Session cart items won't have tax_rate, default to 20
        return collect(session()->get('cart', []))
            ->map(function ($item) {
                return array_merge($item, [
                    'tax_rate' => (float) ($item['tax_rate'] ?? 20),
                ]);
            })
            ->toArray();
    }

    private function calculateSubtotal(array $cart): float
    {
        return round(array_reduce($cart, function ($carry, $item) {
            $isWeightBased = !empty($item['weight']) && floatval($item['weight']) > 0;
            return $carry + ($isWeightBased
                ? $item['price'] * floatval($item['weight'])
                : $item['price'] * $item['quantity']);
        }, 0), 2);
    }

    private function calculateTax(array $cart): float
    {
        $tax = 0;

        foreach ($cart as $item) {
            $isWeightBased = !empty($item['weight']) && (float)$item['weight'] > 0;
            $lineSubtotal  = $isWeightBased
                ? (float)$item['price'] * (float)$item['weight']
                : (float)$item['price'] * (int)$item['quantity'];

            $rate = (float)($item['tax_rate'] ?? 20); // fallback 20% if missing
            $tax += $lineSubtotal * ($rate / 100);
        }

        return round($tax, 2);
    }

    public function orders()
    {
        if (!Auth::check()) return redirect()->route('login');

        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product.primaryImage'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('frontend.orders', compact('orders'));
    }

    public function orderDetails($orderNumber)
    {
        if (!Auth::check()) return redirect()->route('login');

        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['items.product.primaryImage'])
            ->firstOrFail();

        return view('frontend.order-details', compact('order'));
    }

    public function getSavedAddresses()
    {
        if (!Auth::check()) return response()->json(['addresses' => []]);

        $addresses = UserAddress::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get();

        return response()->json(['success' => true, 'addresses' => $addresses]);
    }

    public function deleteAddress(Request $request, $id)
    {
        $address = UserAddress::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $address->delete();
        return response()->json(['success' => true]);
    }

    public function cancelOrder(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending orders can be cancelled.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Restore stock for qty-based items
            foreach ($order->items as $item) {
                if ($item->product && !$item->product->is_weight_based && $item->quantity) {
                    Product::where('id', $item->product_id)
                        ->increment('stock', $item->quantity);
                }
            }

            $order->update([
                'status'      => 'cancelled',
                'admin_notes' => 'Cancelled by customer at ' . now()->toDateTimeString(),
            ]);

            DB::commit();

            Log::info('Order cancelled by customer', [
                'order_number' => $order->order_number,
                'user_id'      => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Order #{$order->order_number} has been cancelled.",
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Order cancellation failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel the order. Please try again.',
            ], 500);
        }
    }

    public function addressBook()
    {
        $addresses = UserAddress::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get();

        return view('frontend.account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        try {
            $validated = $request->validate([
                'label'            => 'nullable|string|max:50',
                'recipient_name'   => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'phone'            => 'required|string|min:10|max:20|regex:/^[0-9+\s\-()]+$/',
                'address_line1'    => 'required|string|max:255',
                'address_line2'    => 'nullable|string|max:255',
                'restaurant_store' => 'nullable|string|max:255',
                'city'             => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'county'           => 'nullable|string|max:255',
                'postcode'         => 'required|string|max:10|regex:/^[A-Z]{1,2}[0-9]{1,2}[A-Z]?\s?[0-9][A-Z]{2}$/i',
                'is_default'       => 'nullable|boolean',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }

        $count = UserAddress::where('user_id', Auth::id())->count();
        if ($count >= 5) {
            return response()->json(['success' => false, 'message' => 'Maximum 5 addresses allowed.'], 422);
        }

        DB::beginTransaction();
        try {
            $validated['postcode'] = strtoupper($validated['postcode']);

            if (!empty($validated['is_default']) || $count === 0) {
                UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
                $validated['is_default'] = true;
            }

            $address = UserAddress::create(array_merge($validated, [
                'user_id' => Auth::id(),
                'country' => 'UK',
            ]));

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Address saved.', 'address' => $address]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to save address.'], 500);
        }
    }

    public function updateAddress(Request $request, $id)
    {
        $address = UserAddress::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        try {
            $validated = $request->validate([
                'label'            => 'nullable|string|max:50',
                'recipient_name'   => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'phone'            => 'required|string|min:10|max:20|regex:/^[0-9+\s\-()]+$/',
                'address_line1'    => 'required|string|max:255',
                'address_line2'    => 'nullable|string|max:255',
                'restaurant_store' => 'nullable|string|max:255',
                'city'             => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'county'           => 'nullable|string|max:255',
                'postcode'         => 'required|string|max:10|regex:/^[A-Z]{1,2}[0-9]{1,2}[A-Z]?\s?[0-9][A-Z]{2}$/i',
                'is_default'       => 'nullable|boolean',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $validated['postcode'] = strtoupper($validated['postcode']);

            if (!empty($validated['is_default'])) {
                UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
            }

            $address->update(array_merge($validated, ['country' => 'UK']));
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Address updated.', 'address' => $address->fresh()]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update address.'], 500);
        }
    }

    public function setDefaultAddress($id)
    {
        $address = UserAddress::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        DB::beginTransaction();
        try {
            UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
            $address->update(['is_default' => true]);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Default address updated.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update default.'], 500);
        }
    }

    // ── New private helper for getting postcode on index page ────────
    private function getDefaultPostcode(): ?string
    {
        if (!Auth::check()) return null;

        return UserAddress::where('user_id', Auth::id())
            ->where('is_default', true)
            ->value('postcode');
    }

    public function shippingEstimate(Request $request): \Illuminate\Http\JsonResponse
    {
        // ✅ Add rate limit — 30 estimates per minute per IP
        $key = 'shipping_estimate:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 30)) {
            return response()->json(['error' => 'Too many requests.'], 429);
        }
        RateLimiter::hit($key, 60);

        $postcode = strtoupper(strip_tags($request->input('postcode', '')));
        $subtotal = (float) $request->input('subtotal', 0);

        $cost = $this->shipping->calculate($subtotal, $postcode);

        if ($cost < 0) {
            return response()->json(['out_of_range' => true]);
        }

        if ($cost === 0.0) {
            return response()->json(['free' => true, 'cost' => 0]);
        }

        return response()->json([
            'free'    => false,
            'cost'    => $cost,
            'message' => $this->shipping->getDeliveryMessage($subtotal),
        ]);
    }

}
