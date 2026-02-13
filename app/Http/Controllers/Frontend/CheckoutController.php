<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shippingCost = $this->calculateShipping($subtotal);
        $tax = $this->calculateTax($subtotal);
        $total = $subtotal + $shippingCost + $tax;

        return view('frontend.checkout', compact('cart', 'subtotal', 'shippingCost', 'tax', 'total'));
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
                    return [
                        'id' => $item->product_id,
                        'cart_item_id' => $item->id,
                        'name' => $item->product->name,
                        'price' => (float) $item->price,
                        'quantity' => $item->quantity,
                        'weight' => $item->weight,
                        'image' => $item->product->image_url,
                    ];
                })->toArray();
        } else {
            return session()->get('cart', []);
        }
    }

    private function calculateShipping($subtotal)
    {
        if ($subtotal >= 500) {
            return 0; // Free shipping over £500
        }
        return 5.99;
    }

    private function calculateTax($subtotal)
    {
        // 20% VAT
        return round($subtotal * 0.20, 2);
    }

    public function createPaymentIntent(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $amount = $request->amount; // Amount in pence (e.g., 1000 = £10.00)

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'gbp',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return response()->json([
                'success' => true,
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function processOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:255',
            'shipping_postcode' => 'required|string|max:20',
            'payment_method' => 'required|in:stripe,cash_on_delivery',
            'stripe_payment_intent_id' => 'required_if:payment_method,stripe',
        ]);

        DB::beginTransaction();
        try {
            $cart = $this->getCart();
            if (empty($cart)) {
                throw new \Exception('Cart is empty');
            }

            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $shippingCost = $this->calculateShipping($subtotal);
            $tax = $this->calculateTax($subtotal);
            $total = $subtotal + $shippingCost + $tax;

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_postcode' => $request->shipping_postcode,
                'shipping_country' => 'UK',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'stripe' ? 'paid' : 'pending',
                'stripe_payment_intent_id' => $request->stripe_payment_intent_id,
                'paid_at' => $request->payment_method === 'stripe' ? now() : null,
                'status' => 'pending',
                'customer_notes' => $request->customer_notes,
            ]);

            // Create order items
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'weight' => $item['weight'] ?? null,
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            // Clear cart
            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $cart->items()->delete();
                }
            } else {
                session()->forget('cart');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'order_number' => $order->order_number,
                'redirect' => route('checkout.success', $order->order_number)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order processing failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to process order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        return view('frontend.checkout-success', compact('order'));
    }
}
