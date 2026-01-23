<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', 1)->firstOrFail();

        $products = $category->products()
            ->where('is_active', 1)
            ->latest()
            ->paginate(20);

        return view('frontend.category-show', compact('category', 'products'));
    }
}

