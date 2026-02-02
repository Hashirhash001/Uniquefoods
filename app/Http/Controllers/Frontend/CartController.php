<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get cart (session or database based on auth)
     */
    private function getCart()
    {
        if (Auth::check()) {
            // Get or create cart for authenticated user
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            // Load items with product details
            $items = $cart->items()
                ->with(['product.primaryImage', 'product.category', 'product.brand'])
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->product_id,
                        'cart_item_id' => $item->id,
                        'name' => $item->product->name,
                        'slug' => $item->product->slug,
                        'price' => (float) $item->price,
                        'image' => $item->product->image_url,
                        'quantity' => $item->quantity,
                        'weight' => $item->weight,
                        'is_weight_based' => $item->product->is_weight_based,
                        'stock' => $item->product->stock,
                        'subtotal' => (float) ($item->price * $item->quantity)
                    ];
                })
                ->toArray();

            return $items;
        } else {
            // Get from session for guests
            return session()->get('cart', []);
        }
    }

    /**
     * Add product to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'weight' => 'nullable|numeric|min:0.001'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;
        $weight = $request->weight;

        $product = Product::with('category', 'brand')->find($productId);

        if (!$product || !$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Product not available'
            ], 404);
        }

        // Stock validation (for non-weight based)
        if (!$product->is_weight_based && $product->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Only ' . $product->stock . ' items available.'
            ], 400);
        }

        // Calculate price
        $price = $product->is_weight_based && $weight
            ? ($product->price_per_kg * $weight)
            : $product->price;

        if (Auth::check()) {
            // Database cart for authenticated users
            return $this->addToDatabase($product, $quantity, $weight, $price);
        } else {
            // Session cart for guests
            return $this->addToSession($product, $quantity, $weight, $price);
        }
    }

    /**
     * Add to database (authenticated)
     */
    private function addToDatabase($product, $quantity, $weight, $price)
    {
        DB::beginTransaction();
        try {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            $existingItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            if ($existingItem) {
                $existingItem->quantity += $quantity;
                if ($weight) {
                    $existingItem->weight = $weight;
                }
                $existingItem->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'weight' => $weight,
                    'price' => $product->price,
                    'price_per_kg' => $product->price_per_kg
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $product->name . ' added to cart',
                'cart' => $this->getCartData()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add to cart'
            ], 500);
        }
    }

    /**
     * Add to session (guest)
     */
    private function addToSession($product, $quantity, $weight, $price)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            // Update existing item
            $cart[$product->id]['quantity'] += $quantity;
            if ($weight) {
                $cart[$product->id]['weight'] = $weight;
            }
        } else {
            // Add new item
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => (float) $product->price,
                'image' => $product->image_url,
                'quantity' => $quantity,
                'weight' => $weight,
                'is_weight_based' => $product->is_weight_based,
                'stock' => $product->stock
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => $product->name . ' added to cart',
            'cart' => $this->getCartData()
        ]);
    }

    /**
     * Remove product from cart
     */
    public function remove(Request $request)
    {
        $productId = $request->product_id;

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $cartItem = CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $productId)
                    ->first();

                if ($cartItem) {
                    $productName = $cartItem->product->name;
                    $cartItem->delete();

                    return response()->json([
                        'success' => true,
                        'message' => $productName . ' removed from cart',
                        'cart' => $this->getCartData()
                    ]);
                }
            }
        } else {
            $cart = session()->get('cart', []);

            if (isset($cart[$productId])) {
                $productName = $cart[$productId]['name'];
                unset($cart[$productId]);
                session()->put('cart', $cart);

                return response()->json([
                    'success' => true,
                    'message' => $productName . ' removed from cart',
                    'cart' => $this->getCartData()
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ], 404);
    }

    /**
     * Update cart quantity
     */
    public function update(Request $request)
    {
        $productId = $request->product_id;
        $action = $request->action; // 'plus' or 'minus'
        $quantity = $request->quantity; // direct quantity update

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $cartItem = CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $productId)
                    ->first();

                if ($cartItem) {
                    if ($quantity) {
                        $cartItem->quantity = $quantity;
                    } elseif ($action === 'plus') {
                        $cartItem->quantity++;
                    } elseif ($action === 'minus') {
                        $cartItem->quantity--;
                    }

                    if ($cartItem->quantity <= 0) {
                        $cartItem->delete();
                    } else {
                        $cartItem->save();
                    }

                    return response()->json([
                        'success' => true,
                        'cart' => $this->getCartData()
                    ]);
                }
            }
        } else {
            $cart = session()->get('cart', []);

            if (isset($cart[$productId])) {
                if ($quantity) {
                    $cart[$productId]['quantity'] = $quantity;
                } elseif ($action === 'plus') {
                    $cart[$productId]['quantity']++;
                } elseif ($action === 'minus') {
                    $cart[$productId]['quantity']--;
                }

                if ($cart[$productId]['quantity'] <= 0) {
                    unset($cart[$productId]);
                }

                session()->put('cart', $cart);

                return response()->json([
                    'success' => true,
                    'cart' => $this->getCartData()
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to update cart'
        ], 400);
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $cart->items()->delete();
            }
        } else {
            session()->forget('cart');
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
            'cart' => $this->getCartData()
        ]);
    }

    /**
     * Get cart count
     */
    public function count()
    {
        $cart = $this->getCart();
        $count = count($cart);
        $total = $this->calculateTotal($cart);

        return response()->json([
            'success' => true,
            'count' => $count,
            'total' => $total
        ]);
    }

    /**
     * Get full cart data
     */
    public function get()
    {
        return response()->json([
            'success' => true,
            'cart' => $this->getCartData()
        ]);
    }

    /**
     * Cart index page
     */
    public function index()
    {
        $cartData = $this->getCartData();
        return view('frontend.cart', compact('cartData'));
    }

    /**
     * Get formatted cart data
     */
    private function getCartData()
    {
        $cart = $this->getCart();
        $items = array_values($cart);
        $subtotal = $this->calculateTotal($cart);

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'tax' => 0,
            'shipping' => 0,
            'total' => $subtotal
        ];
    }

    /**
     * Calculate total
     */
    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            if (isset($item['subtotal'])) {
                $total += $item['subtotal'];
            } else {
                $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
            }
        }
        return round($total, 2);
    }

    /**
     * Merge session cart to database (called after login)
     */
    public static function mergeSessionCartToDatabase($userId)
    {
        $sessionCart = session()->get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        DB::beginTransaction();
        try {
            $cart = Cart::firstOrCreate(['user_id' => $userId]);

            foreach ($sessionCart as $productId => $item) {
                $product = Product::find($productId);
                if ($product) {
                    $existingItem = CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $productId)
                        ->first();

                    if ($existingItem) {
                        $existingItem->quantity += ($item['quantity'] ?? 1);
                        $existingItem->save();
                    } else {
                        CartItem::create([
                            'cart_id' => $cart->id,
                            'product_id' => $productId,
                            'quantity' => $item['quantity'] ?? 1,
                            'weight' => $item['weight'] ?? null,
                            'price' => $product->price,
                            'price_per_kg' => $product->price_per_kg
                        ]);
                    }
                }
            }

            // Clear session cart
            session()->forget('cart');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cart merge failed: ' . $e->getMessage());
        }
    }
}
