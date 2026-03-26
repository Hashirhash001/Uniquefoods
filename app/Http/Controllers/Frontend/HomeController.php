<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\CustomerGroup;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Services\PricingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(PricingService $pricingService)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            $user->loadMissing('groups');
        }

        $banners = Cache::remember('active_banners', 1800, function () {
            return Banner::active()->orderBy('sort_order')->get();
        });

        $featuredCategories = Cache::remember('featured_categories', 3600, function () {
            return Category::where('is_active', 1)
                ->whereNotNull('image')
                ->orderBy('sort_order')
                ->limit(10)
                ->get();
        });

        $products = Product::with(['category', 'brand', 'primaryImage', 'images', 'reviews'])
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->visibleTo($user)   // ← GROUP FILTER
            ->latest()
            ->take(10)
            ->get();

        $popularProducts = Product::with(['category', 'brand', 'primaryImage', 'images', 'reviews'])
            ->where('is_active', 1)
            ->where('is_popular', 1)
            ->visibleTo($user)   // ← GROUP FILTER
            ->latest()
            ->take(20)
            ->get();

        $popularCategories = Category::whereIn('id', $popularProducts->pluck('category_id')->unique())
            ->where('is_active', 1)
            ->take(4)
            ->get();

        $wishlistedIds = [];
        if ($user) {
            $wishlist = Wishlist::where('user_id', $user->id)->first();
            if ($wishlist) {
                $wishlistedIds = WishlistItem::where('wishlist_id', $wishlist->id)
                    ->pluck('product_id')
                    ->toArray();
            }
        } else {
            $wishlistedIds = array_keys(session()->get('wishlist', []));
        }

        $applyPricing = function ($p) use ($pricingService, $user, $wishlistedIds) {
            $p->base_price  = (float) $p->price;
            $p->final_price = (float) $pricingService->getCustomerPrice($p, $user);
            $p->discount_percentage_calc = ($p->base_price > 0 && $p->final_price < $p->base_price)
                ? round((($p->base_price - $p->final_price) / $p->base_price) * 100)
                : 0;
            $p->is_wishlisted  = in_array($p->id, $wishlistedIds);
            $p->average_rating = round((float) $p->reviews->avg('rating'), 1);
            $p->reviews_count  = $p->reviews->count();
            return $p;
        };

        $products->transform($applyPricing);
        $popularProducts->transform($applyPricing);

        return view('frontend.home', compact(
            'banners',
            'featuredCategories',
            'products',
            'popularProducts',
            'popularCategories'
        ));
    }
}
