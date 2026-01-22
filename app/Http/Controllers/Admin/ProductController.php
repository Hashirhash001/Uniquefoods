<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /* ================= INDEX ================= */
    public function index(Request $request)
    {
        $query = Product::with(['primaryImage', 'category', 'brand']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Price Range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Category Filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Brand Filter
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Stock Filter
        if ($request->filled('stock_status')) {
            if ($request->stock_status == 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status == 'out_of_stock') {
                $query->where('stock', '<=', 0);
            } elseif ($request->stock_status == 'low_stock') {
                $query->where('stock', '>', 0)->where('stock', '<=', 10);
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $products = $query->paginate(15);

        // AJAX Request - UPDATED
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.products.partials.table-rows', compact('products'))->render(),
                'pagination' => $products->appends($request->except('page'))->links('pagination::bootstrap-5')->render(),
                'total' => $products->total()
            ]);
        }

        // Get categories and brands for filters
        $categories = \App\Models\Category::where('is_active', 1)->orderBy('name')->get();
        $brands = \App\Models\Brand::where('is_active', 1)->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    /* ================= CREATE ================= */
    public function create()
    {
        $categories = Category::where('is_active', 1)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        try {
            DB::beginTransaction();

            // Generate unique SKU
            $validated['sku'] = $this->generateUniqueSKU();

            // Create product
            $product = Product::create($validated);

            // Handle images
            if ($request->hasFile('images')) {
                $this->handleImageUpload($request->file('images'), $product);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'product_id' => $product->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create product: ' . $e->getMessage()
            ], 500);
        }
    }

    /* ================= SHOW ================= */
    public function show(Product $product)
    {
        $product->load(['images', 'category', 'brand']);
        return view('admin.products.show', compact('product'));
    }

    /* ================= EDIT ================= */
    public function edit(Product $product)
    {
        $product->load('images');

        $categories = Category::where('is_active', 1)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, Product $product)
    {
        $validated = $this->validateData($request, $product->id);

        try {
            DB::beginTransaction();

            // Update product
            $product->update($validated);

            // Handle deleted images
            if ($request->has('deleted_images')) {
                foreach ($request->deleted_images as $imageId) {
                    $image = ProductImage::find($imageId);
                    if ($image && $image->product_id == $product->id) {
                        // Delete from storage
                        if (Storage::disk('public')->exists($image->image_path)) {
                            Storage::disk('public')->delete($image->image_path);
                        }
                        $image->delete();
                    }
                }
            }

            // Handle new images
            if ($request->hasFile('images')) {
                $existingCount = $product->images()->count();
                $this->handleImageUpload($request->file('images'), $product, $existingCount);
            }

            // Ensure we have a primary image
            if ($product->images()->count() > 0 && !$product->images()->where('is_primary', true)->exists()) {
                $product->images()->first()->update(['is_primary' => true]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage()
            ], 500);
        }
    }

    /* ================= UPDATED HANDLE IMAGE UPLOAD ================= */
    private function handleImageUpload($images, $product, $startIndex = 0)
    {
        foreach ($images as $index => $image) {
            $filename = time() . '_' . ($startIndex + $index) . '.' . $image->extension();
            $path = $image->storeAs('products', $filename, 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => ($startIndex + $index) === 0 && !$product->images()->where('is_primary', true)->exists(),
                'sort_order' => $startIndex + $index,
            ]);
        }
    }

    /* ================= DELETE ================= */
    public function destroy(Product $product)
    {
        try {
            // Delete images from storage
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage()
            ], 500);
        }
    }

    /* ================= VALIDATION ================= */
    private function validateData(Request $request, $productId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0|gte:price',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|in:pcs,kg,g,l,ml,dozen',
            'is_active' => 'boolean',
            'is_weight_based' => 'boolean',
            'is_featured' => 'boolean',
            'price_per_kg' => 'nullable|required_if:is_weight_based,1|numeric|min:0',
            'min_weight' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0|gte:min_weight',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'barcode' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:100' // 100KB = 100 kilobytes
        ];

        $messages = [
            'mrp.gte' => 'MRP must be greater than or equal to selling price',
            'max_weight.gte' => 'Maximum weight must be greater than minimum weight',
            'price_per_kg.required_if' => 'Price per KG is required for weight-based products',
            'images.*.max' => 'Each image must not exceed 100 KB',
            'images.*.mimes' => 'Images must be in JPEG, PNG, JPG or WEBP format',
            'images.*.image' => 'File must be a valid image',
            'images.max' => 'You can upload maximum 5 images'
        ];

        return $request->validate($rules, $messages);
    }

    /* ================= GENERATE SKU ================= */
    private function generateUniqueSKU()
    {
        do {
            $sku = 'UF-' . strtoupper(Str::random(6));
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }

}
