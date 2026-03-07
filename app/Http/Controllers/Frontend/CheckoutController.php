<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $subtotal = $this->calculateSubtotal($cart);
        $shippingCost = $this->calculateShipping($subtotal);
        $tax = $this->calculateTax($subtotal);
        $total = $subtotal + $shippingCost + $tax;

        return view('frontend.checkout', compact('cart', 'subtotal', 'shippingCost', 'tax', 'total'));
    }

    // =====================================================
    // SHARED HELPER: calculate subtotal from cart array
    // Handles both weight-based and standard products
    // =====================================================
    private function calculateSubtotal(array $cart): float
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $isWeightBased = !empty($item['weight']) && floatval($item['weight']) > 0;
            $subtotal += $isWeightBased
                ? $item['price'] * floatval($item['weight'])
                : $item['price'] * $item['quantity'];
        }
        return round($subtotal, 2);
    }

    private function getCart()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if (!$cart) return [];

            return $cart->items()
                ->with(['product.primaryImage'])
                ->get()
                ->map(function ($item) {
                    if (!$item->product || !$item->product->is_active) {
                        return null;
                    }

                    return [
                        'id'           => $item->product_id,
                        'cart_item_id' => $item->id,
                        'name'         => $item->product->name,
                        'slug'         => $item->product->slug,
                        'price'        => (float) $item->price,  // already group-priced
                        'quantity'     => $item->quantity,
                        'weight'       => $item->weight,
                        'image'        => $item->product->image_url,
                        'stock'        => $item->product->stock,
                        'is_weight_based' => (bool) $item->product->is_weight_based,
                    ];
                })
                ->filter()
                ->values()
                ->toArray();
        } else {
            return session()->get('cart', []);
        }
    }

    private function calculateShipping(float $subtotal): float
    {
        return $subtotal >= 500 ? 0 : 5.99;
    }

    private function calculateTax(float $subtotal): float
    {
        return round($subtotal * 0.20, 2);
    }

    public function processOrder(Request $request)
    {
        $key = 'checkout:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Too many order attempts. Please try again in {$seconds} seconds."
            ], 429);
        }

        RateLimiter::hit($key, 60);

        try {
            $validated = $request->validate([
                'customer_name'  => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|min:10|max:20|regex:/^[0-9+\s\-()]+$/',
                'address_line1'  => 'required|string|max:255',
                'address_line2'  => 'nullable|string|max:255',
                'city'           => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'county'         => 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'postcode'       => 'required|string|max:10|regex:/^[A-Z]{1,2}[0-9]{1,2}[A-Z]?\s?[0-9][A-Z]{2}$/i',
                'payment_method' => 'required|in:cash_on_delivery',
                'customer_notes' => 'nullable|string|max:1000',
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

        // Sanitize inputs
        $validated['customer_name']  = strip_tags($validated['customer_name']);
        $validated['customer_email'] = filter_var($validated['customer_email'], FILTER_SANITIZE_EMAIL);
        $validated['customer_phone'] = strip_tags($validated['customer_phone']);
        $validated['address_line1']  = strip_tags($validated['address_line1']);
        $validated['address_line2']  = $validated['address_line2'] ? strip_tags($validated['address_line2']) : null;
        $validated['city']           = strip_tags($validated['city']);
        $validated['county']         = $validated['county'] ? strip_tags($validated['county']) : null;
        $validated['postcode']       = strtoupper(strip_tags($validated['postcode']));
        $validated['customer_notes'] = $validated['customer_notes'] ? strip_tags($validated['customer_notes']) : null;

        DB::beginTransaction();
        try {
            $cart = $this->getCart();

            if (empty($cart)) {
                throw new \Exception('Your cart is empty. Please add items before checkout.');
            }

            // ── Validate each item ──────────────────────────────────
            foreach ($cart as $item) {
                $product = Product::find($item['id']);

                if (!$product) {
                    throw new \Exception("Product '{$item['name']}' is no longer available.");
                }

                if (!$product->is_active) {
                    throw new \Exception("Product '{$item['name']}' is currently unavailable.");
                }

                if (!$product->is_weight_based && $product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for '{$item['name']}'. Only {$product->stock} available.");
                }

                // Validate weight bounds for weight-based products
                if ($product->is_weight_based) {
                    $weight = floatval($item['weight'] ?? 0);
                    if ($weight <= 0) {
                        throw new \Exception("Please select a valid weight for '{$item['name']}'.");
                    }
                    if ($product->min_weight && $weight < $product->min_weight) {
                        throw new \Exception("Minimum order weight for '{$item['name']}' is {$product->min_weight}kg.");
                    }
                    if ($product->max_weight && $weight > $product->max_weight) {
                        throw new \Exception("Maximum order weight for '{$item['name']}' is {$product->max_weight}kg.");
                    }
                }
            }

            // ── Recalculate totals server-side ─────────────────────
            // Note: item prices are already group-priced (set by PricingService at add-to-cart)
            $subtotal     = $this->calculateSubtotal($cart);
            $shippingCost = $this->calculateShipping($subtotal);
            $tax          = $this->calculateTax($subtotal);
            $total        = $subtotal + $shippingCost + $tax;

            // ── Duplicate order guard ──────────────────────────────
            if (Auth::check()) {
                $recentOrder = Order::where('user_id', Auth::id())
                    ->where('customer_email', $validated['customer_email'])
                    ->where('total', $total)
                    ->where('created_at', '>=', now()->subMinutes(5))
                    ->first();

                if ($recentOrder) {
                    throw new \Exception('A similar order was recently placed. Please check your orders.');
                }
            }

            $fullAddress = $validated['address_line1'];
            if ($validated['address_line2']) {
                $fullAddress .= ', ' . $validated['address_line2'];
            }

            // ── Create order ───────────────────────────────────────
            $order = Order::create([
                'order_number'              => Order::generateOrderNumber(),
                'user_id'                   => Auth::id(),
                'customer_name'             => $validated['customer_name'],
                'customer_email'            => $validated['customer_email'],
                'customer_phone'            => $validated['customer_phone'],
                'shipping_address'          => $fullAddress,
                'shipping_city'             => $validated['city'],
                'shipping_postcode'         => $validated['postcode'],
                'shipping_country'          => 'UK',
                'subtotal'                  => $subtotal,
                'shipping_cost'             => $shippingCost,
                'tax'                       => $tax,
                'total'                     => $total,
                'payment_method'            => 'cash_on_delivery',
                'payment_status'            => 'pending',
                'stripe_payment_intent_id'  => null,
                'paid_at'                   => null,
                'status'                    => 'pending',
                'customer_notes'            => $validated['customer_notes'],
            ]);

            // ── Create order items ─────────────────────────────────
            foreach ($cart as $item) {
                $product = Product::find($item['id']);

                $isWeightBased = !empty($item['weight']) && floatval($item['weight']) > 0;
                $itemSubtotal  = $isWeightBased
                    ? $item['price'] * floatval($item['weight'])
                    : $item['price'] * $item['quantity'];

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['id'],
                    'product_name' => $item['name'],
                    'price'        => $item['price'],
                    'quantity'     => $isWeightBased ? null : $item['quantity'],
                    'weight'       => $isWeightBased ? floatval($item['weight']) : null,
                    'subtotal'     => round($itemSubtotal, 2),
                ]);

                // Only decrement stock for non-weight-based products
                if (!$product->is_weight_based) {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            // ── Clear cart ─────────────────────────────────────────
            if (Auth::check()) {
                $userCart = Cart::where('user_id', Auth::id())->first();
                if ($userCart) {
                    $userCart->items()->delete();
                }
            } else {
                session()->forget('cart');
            }

            DB::commit();

            Log::info('Order placed successfully', [
                'order_number' => $order->order_number,
                'user_id'      => Auth::id(),
                'subtotal'     => $subtotal,
                'tax'          => $tax,
                'shipping'     => $shippingCost,
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

    public function orders()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view your orders');
        }

        $orders = Order::where('user_id', Auth::id())
            ->with([
                'items' => function ($query) {
                    $query->with(['product' => function ($q) {
                        $q->with(['primaryImage', 'images']);
                    }]);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('frontend.orders', compact('orders'));
    }

    public function orderDetails($orderNumber)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view order details');
        }

        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with([
                'items' => function ($query) {
                    $query->with(['product' => function ($q) {
                        $q->with(['primaryImage', 'images']);
                    }]);
                }
            ])
            ->firstOrFail();

        return view('frontend.order-details', compact('order'));
    }
}
