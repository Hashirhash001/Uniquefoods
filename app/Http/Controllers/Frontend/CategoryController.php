<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function show($slug, PricingService $pricingService)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $user->loadMissing('groups');
        }

        $products = Product::with(['category', 'brand', 'primaryImage', 'reviews'])
            ->where('is_active', 1)
            ->where('category_id', $category->id)
            ->visibleTo($user)   // ← GROUP FILTER
            ->latest()
            ->paginate(20);

        // Apply pricing to each product
        $products->transform(function ($p) use ($pricingService, $user) {
            $p->base_price  = (float) $p->price;
            $p->final_price = (float) $pricingService->getCustomerPrice($p, $user);
            $p->discount_percentage_calc = ($p->base_price > 0 && $p->final_price < $p->base_price)
                ? round((($p->base_price - $p->final_price) / $p->base_price) * 100)
                : 0;
            $p->average_rating = round((float) $p->reviews->avg('rating'), 1);
            $p->reviews_count  = $p->reviews->count();
            return $p;
        });

        return view('frontend.category-show', compact('category', 'products'));
    }
}
