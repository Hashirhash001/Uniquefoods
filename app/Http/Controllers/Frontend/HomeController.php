<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Services\PricingService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(PricingService $pricingService)
    {
        $banners = Banner::active()
            ->orderBy('sort_order')
            ->get();

        $featuredCategories = Category::where('is_active', 1)
            ->whereNotNull('image')
            ->orderBy('sort_order')
            ->limit(10)
            ->get();

        // Featured Products
        $products = Product::with(['category', 'brand', 'primaryImage', 'images'])
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->latest()
            ->take(10)
            ->get();

        // Popular Products
        $popularProducts = Product::with(['category', 'brand', 'primaryImage', 'images'])
            ->where('is_active', 1)
            ->where('is_popular', 1)
            ->latest()
            ->take(20)
            ->get();

        // Popular Categories for tabs
        $popularCategories = Category::whereIn('id', $popularProducts->pluck('category_id')->unique())
            ->where('is_active', 1)
            ->take(4)
            ->get();

        $user = Auth::user();

        // ✅ Fetch wishlisted product IDs ONCE for both collections
        $wishlistedIds = [];
        if ($user) {
            $wishlist = Wishlist::where('user_id', $user->id)->first();
            if ($wishlist) {
                $wishlistedIds = WishlistItem::where('wishlist_id', $wishlist->id)
                    ->pluck('product_id')
                    ->toArray();
            }
        } else {
            // Guest — session based
            $sessionWishlist = session()->get('wishlist', []);
            $wishlistedIds = array_keys($sessionWishlist);
        }

        // ✅ Apply pricing + wishlist state to featured products
        $products->transform(function ($p) use ($pricingService, $user, $wishlistedIds) {
            $p->base_price  = (float) $p->price;
            $p->final_price = (float) $pricingService->getCustomerPrice($p, $user);
            $p->discount_percentage_calc = ($p->base_price > 0 && $p->final_price < $p->base_price)
                ? round((($p->base_price - $p->final_price) / $p->base_price) * 100)
                : 0;
            $p->is_wishlisted = in_array($p->id, $wishlistedIds); // ✅
            return $p;
        });

        // ✅ Apply pricing + wishlist state to popular products
        $popularProducts->transform(function ($p) use ($pricingService, $user, $wishlistedIds) {
            $p->base_price  = (float) $p->price;
            $p->final_price = (float) $pricingService->getCustomerPrice($p, $user);
            $p->discount_percentage_calc = ($p->base_price > 0 && $p->final_price < $p->base_price)
                ? round((($p->base_price - $p->final_price) / $p->base_price) * 100)
                : 0;
            $p->is_wishlisted = in_array($p->id, $wishlistedIds); // ✅
            return $p;
        });

        return view('frontend.home', compact(
            'banners',
            'featuredCategories',
            'products',
            'popularProducts',
            'popularCategories'
        ));
    }
}
