<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CustomerGroup;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    // INDEX
    public function index(Request $request)
    {
        $query = Product::with('primaryImage', 'category', 'brand');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('sku', 'like', "%$search%")
                  ->orWhere('barcode', 'like', "%$search%");
            });
        }

        if ($request->filled('min_price')) $query->where('price', '>=', $request->min_price);
        if ($request->filled('max_price')) $query->where('price', '<=', $request->max_price);
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('brand_id')) $query->where('brand_id', $request->brand_id);
        if ($request->filled('status')) $query->where('is_active', $request->status);

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'instock')     $query->where('stock', '>', 0);
            elseif ($request->stock_status === 'outofstock') $query->where('stock', '<=', 0);
            elseif ($request->stock_status === 'lowstock')   $query->where('stock', '>', 0)->where('stock', '<=', 10);
        }

        if ($request->filled('group_id')) {
            $query->whereHas('customerGroups', function ($q) use ($request) {
                $q->where('customer_groups.id', $request->group_id);
            });
        }

        $sortBy    = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'html'       => view('admin.products.partials.table-rows', compact('products'))->render(),
                'pagination' => $products->appends($request->except('page'))->links('pagination::bootstrap-5')->render(),
                'total'      => $products->total(),
            ]);
        }

        $customerGroups = \App\Models\CustomerGroup::where('is_active', 1)->orderBy('name')->get();

        $categories = Category::where('is_active', 1)->orderBy('name')->get();
        $brands     = Brand::where('is_active', 1)->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands', 'customerGroups'));
    }

    // CREATE ── pass $customerGroups to view
    public function create()
    {
        $categories     = Category::with('children')
            ->whereNull('parent_id')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();
        $brands         = Brand::where('is_active', 1)->orderBy('name')->get();
        $customerGroups = CustomerGroup::where('is_active', 1)->orderBy('name')->get();

        return view('admin.products.create', compact('categories', 'brands', 'customerGroups'));
    }

    // STORE ── sync group_ids after creating product
    public function store(Request $request)
    {
        $validated = $this->normaliseData($this->validateData($request));

        try {
            DB::beginTransaction();

            $validated['sku'] = $this->generateUniqueSKU();
            $product = Product::create($validated);

            // ── Sync customer groups ──────────────────────────────────────────
            if ($request->filled('group_ids')) {
                $product->customerGroups()->sync($request->group_ids);
            } else {
                // Default: assign to Home Delivery if none selected
                $homeDelivery = CustomerGroup::where('slug', 'home-delivery')->pluck('id');
                $product->customerGroups()->sync($homeDelivery);
            }

            if ($request->hasFile('images')) {
                $this->handleImageUpload($request->file('images'), $product);
            }

            DB::commit();

            return response()->json([
                'success'    => true,
                'message'    => 'Product created successfully',
                'product_id' => $product->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed: ' . $e->getMessage()], 500);
        }
    }

    // SHOW
    public function show(Product $product)
    {
        $product->load('images', 'category', 'brand');
        return view('admin.products.show', compact('product'));
    }

    // EDIT ── pass $customerGroups + selected group ids to view
    public function edit(Product $product)
    {
        $product->load('images', 'customerGroups');

        $categories     = Category::with('children')
            ->whereNull('parent_id')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();
        $brands         = Brand::where('is_active', 1)->orderBy('name')->get();
        $customerGroups = CustomerGroup::where('is_active', 1)->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'customerGroups'));
    }

    // UPDATE ── sync group_ids
    public function update(Request $request, Product $product)
    {
        $validated = $this->normaliseData($this->validateData($request, $product->id));

        try {
            DB::beginTransaction();

            $product->update($validated);

            // ── Sync customer groups ──────────────────────────────────────────
            if ($request->has('group_ids')) {
                $product->customerGroups()->sync($request->group_ids);
            } else {
                $product->customerGroups()->detach();
            }

            if ($request->has('deleted_images')) {
                foreach ($request->deleted_images as $imageId) {
                    $image = ProductImage::find($imageId);
                    if ($image && $image->product_id === $product->id) {
                        Storage::disk('public')->delete($image->image_path);
                        $image->delete();
                    }
                }
            }

            if ($request->hasFile('images')) {
                $existingCount = $product->images()->count();
                $this->handleImageUpload($request->file('images'), $product, $existingCount);
            }

            if ($product->images()->count() > 0 && !$product->images()->where('is_primary', true)->exists()) {
                $product->images()->first()->update(['is_primary' => true]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Product updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed: ' . $e->getMessage()], 500);
        }
    }

    // DELETE
    public function destroy(Product $product)
    {
        try {
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }
            $product->delete();
            return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete product: ' . $e->getMessage()], 500);
        }
    }

    // VALIDATION
    private function validateData(Request $request, $productId = null)
    {
        $rules = [
            'name'          => 'required|string|max:255',
            'slug'          => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('products', 'slug')->ignore($productId)],
            'category_id'   => 'required|exists:categories,id',
            'brand_id'      => 'nullable|exists:brands,id',
            'price'         => 'required|numeric|min:0',
            'mrp'           => 'nullable|numeric|min:0|gte:price',
            'cost'  => 'nullable|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'unit'          => 'required|string|in:pcs,kg,g,l,ml,nos,box,pkt,rol,drm,doz,cs',
            'is_active'     => 'nullable|in:0,1',
            'is_weight_based'=> 'nullable|in:0,1',
            'is_featured'   => 'nullable|in:0,1',
            'is_popular'    => 'nullable|in:0,1',
            'price_per_kg'  => 'nullable|numeric|min:0',
            'min_weight'    => 'nullable|numeric|min:0',
            'max_weight'    => 'nullable|numeric|min:0|gte:min_weight',
            'tax_rate'      => 'nullable|numeric|min:0|max:100',
            'barcode'       => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'images'        => 'nullable|array|max:5',
            'images.*'      => 'image|mimes:jpeg,png,jpg,webp|max:100',
            'group_ids'     => 'nullable|array',
            'group_ids.*'   => 'exists:customer_groups,id',
        ];

        if ($request->input('is_weight_based') == 1) {
            $rules['price_per_kg'] = 'required|numeric|min:0.01';
        }

        $messages = [
            'mrp.gte'           => 'MRP must be greater than or equal to the selling price',
            'max_weight.gte'    => 'Maximum weight must be greater than minimum weight',
            'price_per_kg.required' => 'Price per KG is required for weight-based products',
            'images.*.max'      => 'Each image must not exceed 100 KB',
            'images.*.mimes'    => 'Images must be JPEG, PNG, JPG or WEBP',
            'images.*.image'    => 'File must be a valid image',
            'images.max'        => 'Maximum 5 images allowed',
        ];

        return $request->validate($rules, $messages);
    }

    private function normaliseData(array $data): array
    {
        $data['is_active']      = isset($data['is_active'])      ? (int)$data['is_active']      : 1;
        $data['is_weight_based']= isset($data['is_weight_based'])? (int)$data['is_weight_based']: 0;
        $data['is_featured']    = isset($data['is_featured'])    ? (int)$data['is_featured']    : 0;
        $data['is_popular']     = isset($data['is_popular'])     ? (int)$data['is_popular']     : 0;

        if (!$data['is_weight_based']) {
            $data['price_per_kg'] = null;
            $data['min_weight']   = null;
            $data['max_weight']   = null;
        }

        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }

        unset($data['images'], $data['group_ids']);
        return $data;
    }

    private function handleImageUpload($images, $product, $startIndex = 0)
    {
        foreach ($images as $index => $image) {
            $filename = time() . '_' . ($startIndex + $index) . '.' . $image->extension();
            $path     = $image->storeAs('products', $filename, 'public');
            ProductImage::create([
                'product_id'  => $product->id,
                'image_path'  => $path,
                'is_primary'  => ($startIndex + $index) === 0 && !$product->images()->where('is_primary', true)->exists(),
                'sort_order'  => $startIndex + $index,
            ]);
        }
    }

    private function generateUniqueSKU(): string
    {
        do {
            $sku = 'UF-' . strtoupper(\Illuminate\Support\Str::random(6));
        } while (Product::where('sku', $sku)->exists());
        return $sku;
    }

    // Quick search for admin order editing
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $products = Product::where('is_active', 1)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('sku',  'like', "%$q%");
            })
            ->select('id', 'name', 'sku', 'price', 'stock')
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}
