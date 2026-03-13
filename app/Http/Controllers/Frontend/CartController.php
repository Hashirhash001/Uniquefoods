<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected PricingService $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    // =====================================================================
    //  PRIVATE HELPERS
    // =====================================================================

    private function getCart(): array
    {
        if (!Auth::check()) {
            return array_values(session()->get('cart', []));
        }

        $user = Auth::user();

        // ✅ Use where() not firstOrCreate() — don't create on reads
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart) {
            return [];
        }

        return $cart->items()
            ->with(['product.primaryImage', 'product.category', 'product.brand'])
            ->get()
            ->filter(fn ($item) => $item->product && $item->product->is_active)
            ->map(function ($item) use ($user) {
                $product    = $item->product;
                $unitPrice  = (float) $this->pricingService->getCustomerPrice($product, $user);
                $isWeight   = (bool) $product->is_weight_based;

                // Weight-based: stored price IS the line total (unit × weight)
                // Qty-based:    line total = unit price × quantity
                $price    = $isWeight ? (float) $item->price : $unitPrice;
                $subtotal = $isWeight ? (float) $item->price : $unitPrice * $item->quantity;

                return [
                    'id'              => $item->product_id,
                    'cart_item_id'    => $item->id,
                    'name'            => $product->name,
                    'slug'            => $product->slug,
                    'price'           => $price,
                    'base_price'      => (float) $product->price,
                    'image'           => $product->image_url,
                    'quantity'        => (int) $item->quantity,
                    'weight'          => $item->weight,
                    'is_weight_based' => $isWeight,
                    'stock'           => (int) $product->stock,
                    'subtotal'        => round($subtotal, 2),
                ];
            })
            ->values()
            ->toArray();
    }

    private function getCartData(): array
    {
        $items    = $this->getCart();
        $subtotal = round(array_sum(array_column($items, 'subtotal')), 2);

        return [
            'items'    => $items,
            'subtotal' => $subtotal,
            'tax'      => 0,
            'shipping' => 0,
            'total'    => $subtotal,
        ];
    }

    private function makeLinePrice(bool $isWeightBased, float $unitPrice, ?float $weight): float
    {
        return $isWeightBased
            ? round($unitPrice * (float) $weight, 2)
            : round($unitPrice, 2);
    }

    // =====================================================================
    //  PUBLIC ACTIONS
    // =====================================================================

    public function index()
    {
        $cartData = $this->getCartData();
        return view('frontend.cart', compact('cartData'));
    }

    public function get()
    {
        return response()->json(['success' => true, 'cart' => $this->getCartData()]);
    }

    public function count()
    {
        $items = $this->getCart();
        $subtotal = round(array_sum(array_column($items, 'subtotal')), 2);

        return response()->json([
            'success' => true,
            'count'   => count($items),
            'total'   => $subtotal,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1',
            'weight'     => 'nullable|numeric|min:0.001',
        ]);

        $product = Product::with('category', 'brand')->find($request->product_id);

        if (!$product || !$product->is_active) {
            return response()->json(['success' => false, 'message' => 'Product not available'], 404);
        }

        $quantity = max(1, (int) ($request->quantity ?? 1));
        $weight   = $request->filled('weight') ? (float) $request->weight : null;

        if ($product->is_weight_based) {
            if (!$weight) {
                return response()->json(['success' => false, 'message' => 'Please select a weight.'], 422);
            }
            if ($product->min_weight && $weight < (float) $product->min_weight) {
                return response()->json(['success' => false, 'message' => "Minimum order is {$product->min_weight}kg."], 422);
            }
            if ($product->max_weight && $weight > (float) $product->max_weight) {
                return response()->json(['success' => false, 'message' => "Maximum order is {$product->max_weight}kg."], 422);
            }
        } else {
            if ($product->stock < $quantity) {
                return response()->json(['success' => false, 'message' => "Only {$product->stock} items available."], 400);
            }
        }

        $unitPrice = (float) $this->pricingService->getCustomerPrice($product, Auth::user());

        return Auth::check()
            ? $this->addToDatabase($product, $quantity, $weight, $unitPrice)
            : $this->addToSession($product, $quantity, $weight, $unitPrice);
    }

    public function remove(Request $request)
    {
        $productId = (int) $request->product_id;

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();

            if ($cart) {
                $item = CartItem::with('product')
                    ->where('cart_id', $cart->id)
                    ->where('product_id', $productId)
                    ->first();

                if ($item) {
                    $name = $item->product->name ?? 'Item';
                    $item->delete();

                    return response()->json([
                        'success' => true,
                        'message' => "{$name} removed from cart",
                        'cart'    => $this->getCartData(),
                    ]);
                }
            }
        } else {
            $cart = session()->get('cart', []);

            if (isset($cart[$productId])) {
                $name = $cart[$productId]['name'] ?? 'Item';
                unset($cart[$productId]);
                session()->put('cart', $cart);

                return response()->json([
                    'success' => true,
                    'message' => "{$name} removed from cart",
                    'cart'    => $this->getCartData(),
                ]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Product not found in cart'], 404);
    }

    public function update(Request $request)
    {
        $productId = (int) $request->product_id;
        $action    = $request->action;
        $quantity  = $request->filled('quantity') ? (int) $request->quantity : null;

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();

            if (!$cart) {
                return response()->json(['success' => false, 'message' => 'Cart not found'], 404);
            }

            $item = CartItem::with('product')
                ->where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->first();

            if (!$item || !$item->product) {
                return response()->json(['success' => false, 'message' => 'Item not found'], 404);
            }

            // ✅ Weight-based items can't be qty-updated here
            if ($item->product->is_weight_based) {
                return response()->json([
                    'success' => false,
                    'message' => 'Change weight from the product page.',
                ], 422);
            }

            $newQty = $this->resolveQty((int) $item->quantity, $quantity, $action);

            if ($newQty > $item->product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$item->product->stock} items available.",
                ], 422);
            }

            if ($newQty <= 0) {
                $item->delete();
            } else {
                $item->quantity = $newQty;
                // ✅ For qty-based items, price = unit price (not line total)
                $item->price = (float) $this->pricingService->getCustomerPrice(
                    $item->product, Auth::user()
                );
                $item->save();
            }

            return response()->json(['success' => true, 'cart' => $this->getCartData()]);
        }

        // Guest
        $cart = session()->get('cart', []);

        if (!isset($cart[$productId])) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        if (!empty($cart[$productId]['is_weight_based'])) {
            return response()->json([
                'success' => false,
                'message' => 'Change weight from the product page.',
            ], 422);
        }

        $product = Product::find($productId);

        if (!$product || !$product->is_active) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            return response()->json(['success' => false, 'message' => 'Product no longer available.'], 404);
        }

        $newQty = $this->resolveQty((int) ($cart[$productId]['quantity'] ?? 1), $quantity, $action);

        if ($newQty > $product->stock) {
            return response()->json(['success' => false, 'message' => "Only {$product->stock} items available."], 422);
        }

        if ($newQty <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $newQty;
            $cart[$productId]['stock']    = (int) $product->stock;
            $cart[$productId]['subtotal'] = $cart[$productId]['price'] * $newQty;
        }

        session()->put('cart', $cart);

        return response()->json(['success' => true, 'cart' => $this->getCartData()]);
    }

    public function clear()
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->first()?->items()->delete();
        } else {
            session()->forget('cart');
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
            'cart'    => $this->getCartData(),
        ]);
    }

    // =====================================================================
    //  PRIVATE STORE HELPERS
    // =====================================================================

    private function addToDatabase(Product $product, int $quantity, ?float $weight, float $unitPrice)
    {
        DB::beginTransaction();
        try {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            $linePrice = $this->makeLinePrice($product->is_weight_based, $unitPrice, $weight);

            if ($item) {
                if ($product->is_weight_based) {
                    $item->quantity = 1;
                    $item->weight   = $weight;
                    $item->price    = $linePrice;
                } else {
                    $newQty = $item->quantity + $quantity;

                    // ✅ Stock check on accumulation
                    if ($newQty > $product->stock) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Only {$product->stock} items available.",
                        ], 400);
                    }

                    $item->quantity = $newQty;
                    $item->price    = $unitPrice;
                }

                $item->price_per_kg = $product->price_per_kg ?? null;
                $item->save();
            } else {
                CartItem::create([
                    'cart_id'      => $cart->id,
                    'product_id'   => $product->id,
                    'quantity'     => $product->is_weight_based ? 1 : $quantity,
                    'weight'       => $weight,
                    'price'        => $linePrice,
                    'price_per_kg' => $product->price_per_kg ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$product->name} added to cart",
                'cart'    => $this->getCartData(),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Cart add (DB) failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to add to cart'], 500);
        }
    }

    private function addToSession(Product $product, int $quantity, ?float $weight, float $unitPrice)
    {
        $cart      = session()->get('cart', []);
        $linePrice = $this->makeLinePrice($product->is_weight_based, $unitPrice, $weight);

        if (isset($cart[$product->id])) {
            if ($product->is_weight_based) {
                $cart[$product->id]['weight']   = $weight;
                $cart[$product->id]['quantity'] = 1;
                $cart[$product->id]['price']    = $linePrice;
                $cart[$product->id]['subtotal'] = $linePrice;
            } else {
                $newQty = (int) $cart[$product->id]['quantity'] + $quantity;

                if ($newQty > $product->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => "Only {$product->stock} items available.",
                    ], 400);
                }

                $cart[$product->id]['quantity'] = $newQty;
                $cart[$product->id]['price']    = $unitPrice;
                $cart[$product->id]['subtotal'] = round($unitPrice * $newQty, 2);
            }
        } else {
            $subtotal = $product->is_weight_based ? $linePrice : round($unitPrice * $quantity, 2);

            $cart[$product->id] = [
                'id'              => $product->id,
                'name'            => $product->name,
                'slug'            => $product->slug,
                'price'           => $linePrice,
                'image'           => $product->image_url,
                'quantity'        => $product->is_weight_based ? 1 : $quantity,
                'weight'          => $weight,
                'is_weight_based' => (bool) $product->is_weight_based,
                'stock'           => (int) $product->stock,
                'subtotal'        => $subtotal,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => "{$product->name} added to cart",
            'cart'    => $this->getCartData(),
        ]);
    }

    private function resolveQty(int $current, ?int $direct, ?string $action): int
    {
        if (!is_null($direct)) return $direct;
        if ($action === 'plus')  return $current + 1;
        if ($action === 'minus') return $current - 1;
        return $current;
    }

    // =====================================================================
    //  STATIC: MERGE SESSION CART ON LOGIN
    // =====================================================================

    public static function mergeSessionCartToDatabase(int $userId): void
    {
        $sessionCart = session()->get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        DB::beginTransaction();
        try {
            $cart           = Cart::firstOrCreate(['user_id' => $userId]);
            $user           = \App\Models\User::find($userId);
            $pricingService = app(PricingService::class);

            foreach ($sessionCart as $productId => $item) {
                $product = Product::find($productId);

                if (!$product || !$product->is_active) {
                    continue;
                }

                $unitPrice    = (float) $pricingService->getCustomerPrice($product, $user);
                $isWeightBased = (bool) $product->is_weight_based;
                $weight       = isset($item['weight']) ? (float) $item['weight'] : null;

                // ✅ Weight-based: line price = unitPrice × weight
                $linePrice = $isWeightBased
                    ? round($unitPrice * (float) $weight, 2)
                    : $unitPrice;

                $existing = CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $productId)
                    ->first();

                if ($existing) {
                    if ($isWeightBased) {
                        // Replace weight-based item
                        $existing->quantity = 1;
                        $existing->weight   = $weight;
                        $existing->price    = $linePrice;
                    } else {
                        // Accumulate qty, cap at stock
                        $newQty = $existing->quantity + (int) ($item['quantity'] ?? 1);
                        $existing->quantity = min($newQty, (int) $product->stock);
                        $existing->price    = $unitPrice;
                    }
                    $existing->price_per_kg = $product->price_per_kg ?? null;
                    $existing->save();
                } else {
                    CartItem::create([
                        'cart_id'      => $cart->id,
                        'product_id'   => $productId,
                        'quantity'     => $isWeightBased ? 1 : min((int) ($item['quantity'] ?? 1), (int) $product->stock),
                        'weight'       => $weight,
                        'price'        => $linePrice,
                        'price_per_kg' => $product->price_per_kg ?? null,
                    ]);
                }
            }

            session()->forget('cart');
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Cart merge failed: ' . $e->getMessage());
        }
    }
}
