<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::active()
            ->orderBy('sort_order')
            ->get();

        $featuredCategories = Category::with('activeChildren')
            ->whereNull('parent_id')
            ->where('is_active', 1)
            ->whereNotNull('image')
            ->orderBy('sort_order')
            ->limit(10)
            ->get();

        $products = Product::with(['category', 'brand'])
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->latest()
            ->take(10)
            ->get();

        // Popular Products
        $popularProducts = Product::with(['category', 'brand'])
            ->where('is_active', 1)
            ->where('is_popular', 1) // Add this field to products table
            ->latest()
            ->take(20)
            ->get();

        // Popular Categories for tabs
        $popularCategories = Category::whereIn('id', $popularProducts->pluck('category_id')->unique())
            ->where('is_active', 1)
            ->take(4)
            ->get();

        return view('frontend.home', compact('banners', 'featuredCategories', 'products', 'popularProducts', 'popularCategories'));
    }

}
