<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\PricingService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Display shop page (main view)
     */
    public function index()
    {
        return view('frontend.shop');
    }

    /**
     * AJAX endpoint for filtering products
     */
    public function filter(Request $request, PricingService $pricingService)
    {
        $query = Product::with(['category', 'brand', 'primaryImage', 'images'])
            ->where('is_active', 1);

        // Price filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // ===== SMART CATEGORY FILTER =====
        if ($request->filled('categories') && is_array($request->categories)) {
            $categoryIds = $request->categories;

            // For each selected category, include its subcategories
            $allCategoryIds = [];
            foreach ($categoryIds as $catId) {
                $allCategoryIds[] = $catId;

                // Get subcategories
                $subcategories = Category::where('parent_id', $catId)
                    ->where('is_active', 1)
                    ->pluck('id')
                    ->toArray();

                $allCategoryIds = array_merge($allCategoryIds, $subcategories);
            }

            // Remove duplicates
            $allCategoryIds = array_unique($allCategoryIds);

            $query->whereIn('category_id', $allCategoryIds);
        }

        // Brands filter
        if ($request->filled('brands') && is_array($request->brands)) {
            $query->whereIn('brand_id', $request->brands);
        }

        // Sorting
        switch ($request->get('sort', 'latest')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
        }

        // Paginate - THIS RETURNS A LengthAwarePaginator (has map() and links())
        $products = $query->paginate(24);

        $user = Auth::user();

        $wishlistedIds = [];
        if ($user) {
            // Get the user's wishlist first, then get product IDs from wishlist_items
            $wishlist = \App\Models\Wishlist::where('user_id', $user->id)->first();
            if ($wishlist) {
                $wishlistedIds = \App\Models\WishlistItem::where('wishlist_id', $wishlist->id)
                    ->pluck('product_id')
                    ->toArray();
            }
        } else {
            // Guest — fetch from session
            $sessionWishlist = session()->get('wishlist', []);
            $wishlistedIds = array_keys($sessionWishlist);
        }

        $productsData = $products->map(function ($product) use ($pricingService, $user, $wishlistedIds) {
            $basePrice  = (float) $product->price;
            $finalPrice = (float) $pricingService->getCustomerPrice($product, $user);

            $discountPercentage = 0;
            if ($basePrice > 0 && $finalPrice < $basePrice) {
                $discountPercentage = round((($basePrice - $finalPrice) / $basePrice) * 100);
            }

            return [
                'id'                  => $product->id,
                'name'                => $product->name,
                'slug'                => $product->slug,
                'base_price'          => number_format($basePrice, 2, '.', ''),
                'price'               => number_format($finalPrice, 2, '.', ''),
                'mrp'                 => $product->mrp ? number_format((float)$product->mrp, 2, '.', '') : null,
                'discount_percentage' => $discountPercentage,
                'unit'                => $product->unit,
                'stock'               => $product->stock ?? 0,
                'image_url'           => $product->image_url,
                'is_weight_based'     => (bool) $product->is_weight_based,
                'is_wishlisted'       => in_array($product->id, $wishlistedIds),
                'average_rating' => round((float) $product->reviews()->avg('rating'), 1),
                'reviews_count'  => $product->reviews()->count(),
                'category' => $product->category ? [
                    'id'   => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                ] : null,
                'brand' => $product->brand ? [
                    'id'   => $product->brand->id,
                    'name' => $product->brand->name,
                    'slug' => $product->brand->slug,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'products' => $productsData,
            'total' => $products->total(),
            'from' => $products->firstItem() ?? 0,
            'to' => $products->lastItem() ?? 0,
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'pagination' => $products->links('vendor.pagination.bootstrap-4')->render()
        ]);
    }

    /**
     * Display single product details
     */
    public function show($slug, PricingService $pricingService)
    {
        $product = Product::with(['category', 'brand', 'images', 'reviews.user'])
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        $user = Auth::user();

        $product->base_price = (float) $product->price;
        $product->final_price = (float) $pricingService->getCustomerPrice($product, $user);
        $product->discount_percentage_calc = ($product->base_price > 0 && $product->final_price < $product->base_price)
            ? round((($product->base_price - $product->final_price) / $product->base_price) * 100)
            : 0;

        // ✅ Fetch offers for this user's group
        $offers = collect();
        if ($user) {
            $groupIds = $user->groups->pluck('id');
            if ($groupIds->isNotEmpty()) {
                $offers = \App\Models\GroupProductOffer::with(['customerGroup'])
                    ->whereIn('customer_group_id', $groupIds)
                    ->where(function($q) use ($product) {
                        $q->where(fn($q) => $q->where('offer_type', 'product')->where('product_id', $product->id))
                        ->orWhere(fn($q) => $q->where('offer_type', 'category')->where('category_id', $product->category_id))
                        ->orWhere(fn($q) => $q->where('offer_type', 'brand')->where('brand_id', $product->brand_id));
                    })
                    ->active()
                    ->get();
            }
        }

        // ✅ Check if user has purchased this product (for review eligibility)
        $hasPurchased = false;
        $hasReviewed  = false;
        if ($user) {
            $hasPurchased = \App\Models\Order::where('user_id', $user->id)
                ->whereIn('status', ['delivered'])
                ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
                ->exists();
            $hasReviewed = \App\Models\ProductReview::where('product_id', $product->id)
                ->where('user_id', $user->id)
                ->exists();
        }

        $relatedProducts = Product::with(['category', 'brand', 'primaryImage'])
            ->where('is_active', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(8)->get();

        $relatedProducts->transform(function ($p) use ($pricingService, $user) {
            $p->base_price = (float) $p->price;
            $p->final_price = (float) $pricingService->getCustomerPrice($p, $user);
            $p->discount_percentage_calc = ($p->base_price > 0 && $p->final_price < $p->base_price)
                ? round((($p->base_price - $p->final_price) / $p->base_price) * 100) : 0;
            return $p;
        });

        $product->reviews_count   = $product->reviews()->count();
        $product->average_rating  = round($product->reviews()->avg('rating'), 1) ?: 0;

        return view('frontend.show', compact('product', 'relatedProducts', 'offers', 'hasPurchased', 'hasReviewed'));
    }

    /**
     * AJAX Search for products
     */
    public function search(Request $request, PricingService $pricingService)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'products' => [],
                'categories' => [],
                'total' => 0
            ]);
        }

        $user = Auth::user();

        // Search products
        $products = Product::with(['category', 'brand', 'primaryImage'])
            ->where('is_active', 1)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->orWhere('sku', 'LIKE', "%{$query}%");
            })
            ->limit(8)
            ->get()
            ->map(function ($product) use ($pricingService, $user) {
                $basePrice = (float) $product->price;
                $finalPrice = (float) $pricingService->getCustomerPrice($product, $user);

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => number_format($finalPrice, 2, '.', ''),
                    'base_price' => number_format($basePrice, 2, '.', ''),
                    'image_url' => $product->image_url,
                    'stock' => $product->stock ?? 0,
                    'category' => $product->category ? $product->category->name : null,
                ];
            });

        // Search categories
        $categories = Category::where('is_active', 1)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'image' => $cat->image_url,
                ];
            });

        return response()->json([
            'success' => true,
            'products' => $products,
            'categories' => $categories,
            'total' => $products->count() + $categories->count()
        ]);
    }

}
