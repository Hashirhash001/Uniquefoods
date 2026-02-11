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

        $productsData = $products->map(function ($product) use ($pricingService, $user) {
            $basePrice  = (float) $product->price;
            $finalPrice = (float) $pricingService->getCustomerPrice($product, $user); // your service logic [file:8]

            // If you also want to show "You save" and % off:
            $discountPercentage = 0;
            if ($basePrice > 0 && $finalPrice < $basePrice) {
                $discountPercentage = round((($basePrice - $finalPrice) / $basePrice) * 100);
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,

                // Keep both:
                'base_price' => number_format($basePrice, 2, '.', ''),
                'price'      => number_format($finalPrice, 2, '.', ''),

                // Optional: if you already return mrp:
                'mrp' => $product->mrp ? number_format((float)$product->mrp, 2, '.', '') : null,

                // Make badge based on final vs base:
                'discount_percentage' => $discountPercentage,

                'unit' => $product->unit,
                'stock' => $product->stock ?? 0,
                'image_url' => $product->image_url,

                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                ] : null,

                'brand' => $product->brand ? [
                    'id' => $product->brand->id,
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
    public function show($slug, PricingService $pricingService)
    {
        $product = Product::with(['category', 'brand', 'images'])
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        $user = Auth::user();

        // Main product computed pricing
        $product->base_price = (float) $product->price;
        $product->final_price = (float) $pricingService->getCustomerPrice($product, $user);
        $product->discount_percentage_calc = ($product->base_price > 0 && $product->final_price < $product->base_price)
            ? round((($product->base_price - $product->final_price) / $product->base_price) * 100)
            : 0;

        // Related products
        $relatedProducts = Product::with(['category', 'brand', 'primaryImage'])
            ->where('is_active', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(8)
            ->get();

        $relatedProducts->transform(function ($p) use ($pricingService, $user) {
            $p->base_price = (float) $p->price;
            $p->final_price = (float) $pricingService->getCustomerPrice($p, $user);
            $p->discount_percentage_calc = ($p->base_price > 0 && $p->final_price < $p->base_price)
                ? round((($p->base_price - $p->final_price) / $p->base_price) * 100)
                : 0;
            return $p;
        });

        return view('frontend.show', compact('product', 'relatedProducts'));
    }
}
