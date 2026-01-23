<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

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
    public function filter(Request $request)
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

        // Categories filter (using IDs)
        if ($request->filled('categories') && is_array($request->categories)) {
            $query->whereIn('category_id', $request->categories);
        }

        // Brands filter (using IDs)
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

        // Paginate
        $products = $query->paginate(12);

        // Format response
        $productsData = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'mrp' => $product->mrp,
                'unit' => $product->unit,
                'image_url' => $product->image_url,
                'discount_percentage' => $product->discount_percentage,
                'category' => $product->category ? $product->category->name : null,
                'brand' => $product->brand ? $product->brand->name : null,
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
     * Display products by category
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->with('activeChildren')
            ->firstOrFail();

        $categoryIds = [$category->id];

        if ($category->activeChildren->count() > 0) {
            $categoryIds = array_merge(
                $categoryIds,
                $category->activeChildren->pluck('id')->toArray()
            );
        }

        $products = Product::with(['category', 'brand', 'primaryImage', 'images'])
            ->where('is_active', 1)
            ->whereIn('category_id', $categoryIds)
            ->latest()
            ->paginate(12);

        return view('frontend.category', compact('category', 'products'));
    }

    /**
     * Display single product details
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'brand', 'images'])
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        // Related products from same category
        $relatedProducts = Product::with(['category', 'brand', 'primaryImage'])
            ->where('is_active', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(8)
            ->get();

        return view('frontend.product', compact('product', 'relatedProducts'));
    }
}
