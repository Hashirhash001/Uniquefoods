<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\PricingService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user) {
            $user->load('groups');
        }

        // Get ALL parent category IDs that have visible products directly
        $directParentIds = Category::whereNull('parent_id')
            ->where('is_active', 1)
            ->whereHas('products', fn($q) => $q->where('is_active', 1)->visibleTo($user))
            ->pluck('id');

        // Get parent category IDs whose CHILDREN have visible products
        $viaChildIds = Category::whereNull('parent_id')
            ->where('is_active', 1)
            ->whereHas('children', function ($q) use ($user) {
                $q->where('is_active', 1)
                ->whereHas('products', fn($p) => $p->where('is_active', 1)->visibleTo($user));
            })
            ->pluck('id');

        // Merge both sets
        $allParentIds = $directParentIds->merge($viaChildIds)->unique();

        // Fetch those parents with only the children that have visible products
        $categories = Category::with(['children' => function ($q) use ($user) {
                $q->where('is_active', 1)
                ->whereHas('products', fn($p) => $p->where('is_active', 1)->visibleTo($user))
                ->orderBy('name');
            }])
            ->whereIn('id', $allParentIds)
            ->orderBy('name')
            ->get();

        $brands = \App\Models\Brand::where('is_active', 1)
            ->whereHas('products', fn($q) => $q->where('is_active', 1)->visibleTo($user))
            ->orderBy('name')
            ->get();

        return view('frontend.shop', compact('categories', 'brands'));
    }

    // ================================================
    // FILTER (shop page AJAX)
    // ================================================
    public function filter(Request $request, PricingService $pricingService)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $user->loadMissing('groups');
        }

        $query = Product::with(['category', 'brand', 'images', 'reviews'])
            ->where('is_active', 1)
            ->visibleTo($user);

        // ── Price ──
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // ── Categories (include children) ──
        if ($request->filled('categories') && is_array($request->categories)) {
            $allCategoryIds = [];
            foreach ($request->categories as $catId) {
                $allCategoryIds[] = $catId;
                $subcategories = Category::where('parent_id', $catId)
                    ->where('is_active', 1)
                    ->pluck('id')
                    ->toArray();
                $allCategoryIds = array_merge($allCategoryIds, $subcategories);
            }
            $query->whereIn('category_id', array_unique($allCategoryIds));
        }

        // ── Brands ──
        if ($request->filled('brands') && is_array($request->brands)) {
            $query->whereIn('brand_id', $request->brands);
        }

        // ── Search ──
        if ($request->filled('q')) {
            $q = trim($request->get('q'));
            $ql = strtolower($q);

            // Split into meaningful words (2+ chars)
            $words = array_values(array_filter(
                explode(' ', preg_replace('/\s+/', ' ', $q)),
                fn($w) => strlen(trim($w)) >= 2
            ));

            // Compound: "water proof" → "waterproof"
            $compound = count($words) > 1 ? implode('', $words) : null;

            $query->where(function ($sub) use ($q, $words, $compound) {
                // Full phrase in name or SKU
                $sub->where('name', 'LIKE', "%{$q}%")
                    ->orWhere('sku',  'LIKE', "%{$q}%");

                // Multi-word: ALL words must appear in name (AND logic)
                // Catches "HARROGATE- SPRING WATER" when searching "spring water"
                if (count($words) > 1) {
                    $sub->orWhere(function ($and) use ($words) {
                        foreach ($words as $word) {
                            $and->where('name', 'LIKE', "%{$word}%");
                        }
                    });
                }

                // Compound word: "water proof" → also try "waterproof"
                if ($compound) {
                    $sub->orWhere('name', 'LIKE', "%{$compound}%");
                }

                // Category / brand full-phrase match
                $sub->orWhereHas('brand',    fn($b) => $b->where('name', 'LIKE', "%{$q}%"))
                    ->orWhereHas('category', fn($c) => $c->where('name', 'LIKE', "%{$q}%"));

                // Description — only for phrases 5+ chars to avoid noise
                if (strlen($q) >= 5) {
                    $sub->orWhere('description', 'LIKE', "%{$q}%");
                }
            });

            // ── Require name relevance: at least one word must be in name
            //    or category/brand name — blocks description-only matches
            //    like Volvic Water matching "oil"
            $query->where(function ($must) use ($q, $words, $compound) {
                $must->where('name', 'LIKE', "%{$q}%");

                if (count($words) > 1) {
                    $must->orWhere(function ($and) use ($words) {
                        foreach ($words as $word) {
                            $and->where('name', 'LIKE', "%{$word}%");
                        }
                    });
                }

                if ($compound) {
                    $must->orWhere('name', 'LIKE', "%{$compound}%");
                }

                $must->orWhereHas('category', fn($c) => $c->where('name', 'LIKE', "%{$q}%"))
                     ->orWhereHas('brand',    fn($b) => $b->where('name', 'LIKE', "%{$q}%"));
            });

            // ── Word-boundary guard for short single-word queries ──
            // Prevents "foil","oily","boiled","toilet" matching when searching "oil"
            if (count($words) === 1 && strlen($q) <= 5) {
                $pattern = '(^|[[:space:][:punct:]])' . preg_quote($ql, '/') . '([[:space:][:punct:]]|$)';
                $query->where(function ($wb) use ($pattern, $q) {
                    $wb->whereRaw('LOWER(name) REGEXP ?', [$pattern])
                    ->orWhereHas('category', fn($c) =>
                            $c->whereRaw('LOWER(name) REGEXP ?', [$pattern])
                    )
                    ->orWhereHas('brand', fn($b) =>
                            $b->whereRaw('LOWER(name) REGEXP ?', [$pattern])
                    );
                });
            }
        }

        // ── Sort ──
        $sort = $request->get('sort', 'latest');

        if ($request->filled('q') && $sort === 'latest') {
            $q  = trim($request->get('q'));
            $ql = strtolower($q);

            $words    = array_values(array_filter(
                explode(' ', preg_replace('/\s+/', ' ', $q)),
                fn($w) => strlen(trim($w)) >= 2
            ));
            $compound = count($words) > 1 ? implode('', $words) : $ql;

            // Build dynamic Tier 4 (all words present) condition
            $tier4Conditions = implode(' AND ', array_fill(0, max(count($words), 1), 'LOWER(name) LIKE ?'));
            $tier4Bindings   = array_map(fn($w) => '%' . strtolower($w) . '%', $words ?: [$ql]);

            $query->orderByRaw("
                CASE
                    WHEN LOWER(name) = ?                    THEN 0
                    WHEN LOWER(name) LIKE ?                 THEN 1
                    WHEN LOWER(name) LIKE ?                 THEN 2
                    WHEN LOWER(name) LIKE ?
                     AND (LOWER(name) LIKE ?
                       OR LOWER(name) LIKE ?
                       OR LOWER(name) LIKE ?)               THEN 3
                    WHEN {$tier4Conditions}                 THEN 4
                    WHEN LOWER(name) LIKE ?                 THEN 5
                    ELSE 6
                END ASC,
                name ASC
            ", array_merge(
                [
                    $ql,                           // Tier 0: exact
                    $ql . '%',                     // Tier 1: starts with
                    '%' . $compound . '%',         // Tier 2: compound word
                    '%' . $ql . '%',               // Tier 3: contains (base)
                    '% ' . $ql . '%',              // Tier 3: after space
                    '%-' . $ql . '%',              // Tier 3: after hyphen
                    $ql . ' %',                    // Tier 3: at word start
                ],
                $tier4Bindings,                    // Tier 4: all words in name
                ['%' . $ql . '%']                  // Tier 5: contains anywhere
            ));

        } else {
            switch ($sort) {
                case 'price_low':  $query->orderBy('price', 'asc');  break;
                case 'price_high': $query->orderBy('price', 'desc'); break;
                case 'name_asc':   $query->orderBy('name', 'asc');   break;
                case 'name_desc':  $query->orderBy('name', 'desc');  break;
                default:           $query->latest();
            }
        }

        $products = $query->paginate(24);

        // ── Wishlist IDs ──
        $wishlistedIds = [];
        if ($user) {
            $wishlist = \App\Models\Wishlist::where('user_id', $user->id)->first();
            if ($wishlist) {
                $wishlistedIds = \App\Models\WishlistItem::where('wishlist_id', $wishlist->id)
                    ->pluck('product_id')->toArray();
            }
        } else {
            $wishlistedIds = array_keys(session()->get('wishlist', []));
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
                'average_rating'      => round((float) $product->reviews->avg('rating'), 1),
                'reviews_count'       => $product->reviews->count(),
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
            'success'      => true,
            'products'     => $productsData,
            'total'        => $products->total(),
            'from'         => $products->firstItem() ?? 0,
            'to'           => $products->lastItem() ?? 0,
            'current_page' => $products->currentPage(),
            'last_page'    => $products->lastPage(),
            'pagination'   => $products->links('vendor.pagination.bootstrap-4')->render(),
        ]);
    }

    public function show($slug, PricingService $pricingService)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $user->loadMissing('groups');
        }

        // Product must be active AND visible to user's group
        $product = Product::with(['category', 'brand', 'images', 'reviews.user'])
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->visibleTo($user)
            ->firstOrFail();

        $product->base_price  = (float) $product->price;
        $product->final_price = (float) $pricingService->getCustomerPrice($product, $user);
        $product->discount_percentage_calc = (
            $product->base_price > 0 && $product->final_price < $product->base_price
        ) ? round((($product->base_price - $product->final_price) / $product->base_price) * 100) : 0;

        $offers = collect();
        if ($user && $user->groups->isNotEmpty()) {
            $groupIds = $user->groups->pluck('id');
            $offers = \App\Models\GroupProductOffer::with(['customerGroup'])
                ->whereIn('customer_group_id', $groupIds)
                ->where(function ($q) use ($product) {
                    $q->where(fn($q) => $q->where('offer_type', 'product')->where('product_id', $product->id))
                      ->orWhere(fn($q) => $q->where('offer_type', 'category')->where('category_id', $product->category_id))
                      ->orWhere(fn($q) => $q->where('offer_type', 'brand')->where('brand_id', $product->brand_id));
                })
                ->active()
                ->get();
        }

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

        // Related products also scoped to user's group
        $relatedProducts = Product::with(['category', 'brand', 'primaryImage', 'reviews'])
            ->where('is_active', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->visibleTo($user)
            ->limit(8)
            ->get()
            ->transform(function ($p) use ($pricingService, $user) {
                $p->base_price  = (float) $p->price;
                $p->final_price = (float) $pricingService->getCustomerPrice($p, $user);
                $p->discount_percentage_calc = (
                    $p->base_price > 0 && $p->final_price < $p->base_price
                ) ? round((($p->base_price - $p->final_price) / $p->base_price) * 100) : 0;
                return $p;
            });

        $product->reviews_count  = $product->reviews->count();
        $product->average_rating = round($product->reviews->avg('rating'), 1) ?: 0;

        return view('frontend.show', compact('product', 'relatedProducts', 'offers', 'hasPurchased', 'hasReviewed'));
    }

    // ================================================
    // SEARCH (header dropdown AJAX)
    // ================================================
    public function search(Request $request, PricingService $pricingService)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['success' => true, 'products' => [], 'categories' => [], 'total' => 0]);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $user->loadMissing('groups');
        }

        $ql       = strtolower($q);
        $words    = array_values(array_filter(
            explode(' ', preg_replace('/\s+/', ' ', $q)),
            fn($w) => strlen(trim($w)) >= 2
        ));
        $compound = count($words) > 1 ? implode('', $words) : null;

        $tier4Conditions = implode(' AND ', array_fill(0, max(count($words), 1), 'LOWER(name) LIKE ?'));
        $tier4Bindings   = array_map(fn($w) => '%' . strtolower($w) . '%', $words ?: [$ql]);

        $productQuery = Product::with(['category', 'primaryImage'])
            ->where('is_active', 1)
            ->visibleTo($user)
            ->where(function ($sub) use ($q, $words, $compound) {
                $sub->where('name', 'LIKE', "%{$q}%")
                    ->orWhere('sku',  'LIKE', "%{$q}%");

                if (count($words) > 1) {
                    $sub->orWhere(function ($and) use ($words) {
                        foreach ($words as $word) {
                            $and->where('name', 'LIKE', "%{$word}%");
                        }
                    });
                }

                if ($compound) {
                    $sub->orWhere('name', 'LIKE', "%{$compound}%");
                }

                if (strlen($q) >= 5) {
                    $sub->orWhere('description', 'LIKE', "%{$q}%");
                }
            });

        // ── Word-boundary guard for short single-word queries ──
        if (count($words) === 1 && strlen($q) <= 5) {
            $pattern = '(^|[[:space:][:punct:]])' . preg_quote($ql, '/') . '([[:space:][:punct:]]|$)';
            $productQuery->where(function ($wb) use ($pattern) {
                $wb->whereRaw('LOWER(name) REGEXP ?', [$pattern])
                ->orWhereHas('category', fn($c) =>
                        $c->whereRaw('LOWER(name) REGEXP ?', [$pattern])
                )
                ->orWhereHas('brand', fn($b) =>
                        $b->whereRaw('LOWER(name) REGEXP ?', [$pattern])
                );
            });
        }

        $products = $productQuery
            ->orderByRaw("
                CASE
                    WHEN LOWER(name) = ?                    THEN 0
                    WHEN LOWER(name) LIKE ?                 THEN 1
                    WHEN LOWER(name) LIKE ?                 THEN 2
                    WHEN LOWER(name) LIKE ?
                    AND (LOWER(name) LIKE ?
                    OR LOWER(name) LIKE ?
                    OR LOWER(name) LIKE ?)               THEN 3
                    WHEN {$tier4Conditions}                 THEN 4
                    WHEN LOWER(name) LIKE ?                 THEN 5
                    ELSE 6
                END ASC,
                name ASC
            ", array_merge(
                [
                    $ql,
                    $ql . '%',
                    '%' . ($compound ?? $ql) . '%',
                    '%' . $ql . '%',
                    '% ' . $ql . '%',
                    '%-' . $ql . '%',
                    $ql . ' %',
                ],
                $tier4Bindings,
                ['%' . $ql . '%']
            ))
            ->limit(8)
            ->get()
            ->map(function ($product) use ($pricingService, $user) {
                return [
                    'id'         => $product->id,
                    'name'       => $product->name,
                    'slug'       => $product->slug,
                    'price'      => number_format((float) $pricingService->getCustomerPrice($product, $user), 2, '.', ''),
                    'base_price' => number_format((float) $product->price, 2, '.', ''),
                    'image_url'  => $product->image_url,
                    'stock'      => $product->stock ?? 0,
                    'category'   => $product->category?->name,
                ];
            });

        // ── Categories ──
        $categories = Category::where('is_active', 1)
            ->where('name', 'LIKE', "%{$q}%")
            ->orderByRaw("
                CASE
                    WHEN LOWER(name) = ?    THEN 0
                    WHEN LOWER(name) LIKE ? THEN 1
                    ELSE 2
                END ASC
            ", [$ql, $ql . '%'])
            ->limit(3)
            ->get()
            ->map(fn($cat) => [
                'id'    => $cat->id,
                'name'  => $cat->name,
                'slug'  => $cat->slug,
                'image' => $cat->image_url,
            ]);

        return response()->json([
            'success'    => true,
            'products'   => $products,
            'categories' => $categories,
            'total'      => $products->count() + $categories->count(),
        ]);
    }

    /**
     * Category page — group-scoped
     */
    public function category($slug, PricingService $pricingService)
    {
        $category = Category::where('slug', $slug)->where('is_active', 1)->firstOrFail();

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $user->loadMissing('groups');
        }

        $products = Product::with(['category', 'brand', 'primaryImage', 'reviews'])
            ->where('is_active', 1)
            ->where('category_id', $category->id)
            ->visibleTo($user)
            ->latest()
            ->paginate(24);

        return view('frontend.category', compact('category', 'products'));
    }
}
