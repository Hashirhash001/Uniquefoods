<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Services\PricingService;
use App\Http\Controllers\Controller;
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
        $products = Product::with(['category', 'brand'])
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->latest()
            ->take(10)
            ->get();

        // Popular Products
        $popularProducts = Product::with(['category', 'brand'])
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

        // ✅ Apply pricing logic to featured products
        $user = Auth::user();

        $products->transform(function ($p) use ($pricingService, $user) {
            $p->base_price = (float) $p->price;
            $p->final_price = (float) $pricingService->getCustomerPrice($p, $user);
            $p->discount_percentage_calc = ($p->base_price > 0 && $p->final_price < $p->base_price)
                ? round((($p->base_price - $p->final_price) / $p->base_price) * 100)
                : 0;
            return $p;
        });

        // ✅ Apply pricing logic to popular products
        $popularProducts->transform(function ($p) use ($pricingService, $user) {
            $p->base_price = (float) $p->price;
            $p->final_price = (float) $pricingService->getCustomerPrice($p, $user);
            $p->discount_percentage_calc = ($p->base_price > 0 && $p->final_price < $p->base_price)
                ? round((($p->base_price - $p->final_price) / $p->base_price) * 100)
                : 0;
            return $p;
        });

        return view('frontend.home', compact('banners', 'featuredCategories', 'products', 'popularProducts', 'popularCategories'));
    }
}
