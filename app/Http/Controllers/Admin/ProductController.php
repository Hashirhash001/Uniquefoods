<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CustomerGroup;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\ProductImport;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // INDEX
    public function index(Request $request)
    {
        $query = Product::with('primaryImage', 'category', 'brand');

        if ($request->filled('search')) {
            $search = trim($request->search);

            // Split into words — tolerates extra spaces in stored names
            $words = array_values(array_filter(
                explode(' ', preg_replace('/\s+/', ' ', $search)),
                fn($w) => strlen(trim($w)) >= 2
            ));

            $query->where(function ($q) use ($search, $words) {
                // Full phrase match (works if spaces match exactly)
                $q->where('name',    'LIKE', "%{$search}%")
                ->orWhere('sku',    'LIKE', "%{$search}%")
                ->orWhere('barcode','LIKE', "%{$search}%");

                // All-words AND match — tolerates double spaces, hyphens, etc.
                if (count($words) > 1) {
                    $q->orWhere(function ($and) use ($words) {
                        foreach ($words as $word) {
                            $and->where('name', 'LIKE', "%{$word}%");
                        }
                    });
                }
            });
        }

        if ($request->filled('min_price')) $query->where('price', '>=', $request->min_price);
        if ($request->filled('max_price')) $query->where('price', '<=', $request->max_price);
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('brand_id')) $query->where('brand_id', $request->brand_id);
        if ($request->filled('status')) $query->where('is_active', $request->status);

        // ── Stock Status ──
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':        // ← match HTML value
                    $query->where('stock', '>', 10);
                    break;
                case 'low_stock':       // ← match HTML value
                    $query->where('stock', '>=', 1)
                        ->where('stock', '<=', 10);
                    break;
                case 'out_of_stock':    // ← match HTML value
                    $query->where('stock', '<=', 0);
                    break;
            }
        }

        // Temporary debug — remove after confirming
        Log::info('Stock filter received: ' . $request->stock_status);

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
        $q           = trim($request->get('q', ''));
        $forOrder    = $request->boolean('for_order');   // admin order edit context
        $inStockOnly = $request->boolean('in_stock_only');

        $products = Product::query()
            ->where('is_active', 1)
            ->when($inStockOnly && !$forOrder, function ($query) {
                // Only enforce stock filter when NOT in admin order-edit context
                $query->where('stock', '>', 0);
            })
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'sku', 'price', 'tax_rate', 'stock']);

        return response()->json(
            $products->map(function ($p) use ($forOrder) {
                $outOfStock = $p->stock <= 0;
                return [
                    'id'              => $p->id,
                    'name'            => $p->name,
                    'sku'             => $p->sku,
                    'price'           => $p->price,
                    'tax_rate'        => $p->tax_rate,
                    'stock'           => $p->stock,
                    'is_out_of_stock' => $outOfStock,
                    // Admins can always add; non-admin contexts block out-of-stock
                    'can_add_to_order' => $forOrder ? true : !$outOfStock,
                ];
            })
        );
    }

    public function exportCsv(Request $request)
    {
        $query = $this->buildExportQuery($request);
        $filename = 'products_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            if (ob_get_level()) ob_end_clean();

            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'ID', 'Name', 'SKU', 'Barcode', 'Category', 'Brand',
                'Price (£)', 'MRP (£)', 'Cost (£)', 'Stock', 'Unit',
                'Weight Based', 'Price/kg (£)', 'Min Weight', 'Max Weight',
                'Tax Rate (%)', 'Status', 'Featured', 'Popular', 'Created At',
            ]);

            foreach ($query->cursor() as $product) {
                fputcsv($handle, [
                    $product->id,
                    $product->name,
                    $product->sku,
                    $product->barcode ?? '',
                    $product->category->name ?? '',
                    $product->brand->name ?? '',
                    number_format($product->price, 2),
                    $product->mrp ? number_format($product->mrp, 2) : '',
                    $product->cost ? number_format($product->cost, 2) : '',
                    $product->stock,
                    $product->unit,
                    $product->is_weight_based ? 'Yes' : 'No',
                    $product->price_per_kg ? number_format($product->price_per_kg, 2) : '',
                    $product->min_weight ?? '',
                    $product->max_weight ?? '',
                    $product->tax_rate ?? '',
                    $product->is_active ? 'Active' : 'Inactive',
                    $product->is_featured ? 'Yes' : 'No',
                    $product->is_popular ? 'Yes' : 'No',
                    $product->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Encoding'    => 'none',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $products = $this->buildExportQuery($request)->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.products.export-pdf', [
            'products'   => $products,
            'exportedAt' => now()->format('d/m/Y H:i'),
            'filters'    => $this->describeFilters($request),
        ])->setPaper('a4', 'landscape');

        $filename = 'products_' . now()->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }

    private function buildExportQuery(Request $request)
    {
        $query = Product::with('category', 'brand');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name',    'like', "%{$search}%")
                ->orWhere('sku',   'like', "%{$search}%")
                ->orWhere('barcode','like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('brand_id'))    $query->where('brand_id',    $request->brand_id);
        if ($request->filled('status'))      $query->where('is_active',   $request->status);
        if ($request->filled('min_price'))   $query->where('price', '>=', $request->min_price);
        if ($request->filled('max_price'))   $query->where('price', '<=', $request->max_price);

        if ($request->filled('stock_status')) {
            match ($request->stock_status) {
                'in_stock'     => $query->where('stock', '>', 10),
                'low_stock'    => $query->whereBetween('stock', [1, 10]),
                'out_of_stock' => $query->where('stock', '<=', 0),
                default        => null,
            };
        }

        if ($request->filled('group_id')) {
            $query->whereHas('customerGroups', fn($q) =>
                $q->where('customer_groups.id', $request->group_id)
            );
        }

        return $query->orderBy('name');
    }

    private function describeFilters(Request $request): string
    {
        $parts = [];
        if ($request->filled('search'))       $parts[] = 'Search: "' . $request->search . '"';
        if ($request->filled('category_id'))  $parts[] = 'Category ID: ' . $request->category_id;
        if ($request->filled('brand_id'))     $parts[] = 'Brand ID: ' . $request->brand_id;
        if ($request->filled('status'))       $parts[] = 'Status: ' . ($request->status ? 'Active' : 'Inactive');
        if ($request->filled('stock_status')) $parts[] = 'Stock: ' . $request->stock_status;
        if ($request->filled('min_price'))    $parts[] = 'Min Price: £' . $request->min_price;
        if ($request->filled('max_price'))    $parts[] = 'Max Price: £' . $request->max_price;
        return $parts ? implode(' | ', $parts) : 'All products';
    }

    // ── Import page ──
    public function importPage()
    {
        $history = ProductImport::with('importedBy')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.products.import', compact('history'));
    }

    // ── Download template ──
    public function importTemplate()
    {
        $filename = 'product_import_template.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ];

        $columns = [
            'name', 'category', 'brand', 'price', 'mrp', 'cost',
            'stock', 'unit', 'tax_rate', 'barcode', 'description',
            'is_active', 'is_featured', 'is_popular',
            'is_weight_based', 'price_per_kg', 'min_weight', 'max_weight',
            'customer_groups',
        ];

        $example = [
            'Organic Basmati Rice', 'Grains & Rice', 'Tilda', '3.99', '4.99', '2.50',
            '100', 'pcs', '5', '5012345678901', 'Premium long grain basmati rice',
            '1', '0', '0',
            '0', '', '', '',
            'Home Delivery,Wholesale',
        ];

        return response()->streamDownload(function () use ($columns, $example) {
            $h = fopen('php://output', 'w');
            fprintf($h, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($h, $columns);
            fputcsv($h, $example);
            fclose($h);
        }, $filename, $headers);
    }

    // ── Process import ──
    public function importProcess(Request $request)
    {
        $request->validate([
            'csv_file'     => 'required|file|mimes:csv,txt|max:5120',
            'on_duplicate' => 'required|in:skip,update',
        ]);

        define('IMPORT_ROW_LIMIT', 500);

        $file     = $request->file('csv_file');
        $filename = 'import_' . now()->format('YmdHis') . '_' . uniqid() . '.csv';
        $file->storeAs('imports', $filename, 'local');

        $handle = fopen($file->getRealPath(), 'r');

        // Strip BOM if present (Excel adds this)
        $bom = fread($handle, 3);
        if ($bom !== chr(0xEF) . chr(0xBB) . chr(0xBF)) {
            rewind($handle); // Not a BOM, rewind and read normally
        }

        // Auto-detect delimiter by reading first line
        $firstLine = fgets($handle);
        rewind($handle);

        // Re-skip BOM if present
        $bom = fread($handle, 3);
        if ($bom !== chr(0xEF) . chr(0xBB) . chr(0xBF)) {
            rewind($handle);
        }

        $delimiter = ',';
        $tabCount   = substr_count($firstLine, "\t");
        $commaCount = substr_count($firstLine, ',');
        $semicolonCount = substr_count($firstLine, ';');

        if ($tabCount > $commaCount && $tabCount > $semicolonCount) {
            $delimiter = "\t";
        } elseif ($semicolonCount > $commaCount) {
            $delimiter = ';';
        }

        $header = fgetcsv($handle, 0, $delimiter);

        // Normalise header — strip BOM from first cell, lowercase, replace spaces/dashes
        $header = array_map(function ($h) {
            // Remove UTF-8 BOM from individual cells (Excel quirk)
            $h = str_replace("\xEF\xBB\xBF", '', $h);
            return strtolower(trim(str_replace([' ', '-'], '_', $h)));
        }, $header);

        $required = ['name', 'price', 'stock', 'unit', 'category'];
        foreach ($required as $col) {
            if (!in_array($col, $header)) {
                fclose($handle);
                return response()->json([
                    'success' => false,
                    'message' => "Missing required column: \"{$col}\". Please use the template.",
                ], 422);
            }
        }

        // Count rows before processing
        $rowCount = 0;
        while (fgetcsv($handle, 0, $delimiter) !== false) $rowCount++;
        rewind($handle);

        // Re-skip BOM
        $bom = fread($handle, 3);
        if ($bom !== chr(0xEF) . chr(0xBB) . chr(0xBF)) rewind($handle);

        // Re-skip header
        fgetcsv($handle, 0, $delimiter);

        if ($rowCount > 500) {
            fclose($handle);
            return response()->json([
                'success' => false,
                'message' => "Your file contains {$rowCount} rows. Maximum allowed is 500 rows per import. Please split your file and import in batches.",
            ], 422);
        }

        $onDuplicate    = $request->on_duplicate;
        $imported       = [];
        $errors         = [];
        $importedCount  = 0;
        $skippedCount   = 0;
        $failedCount    = 0;
        $rowNum         = 1;

        // Cache lookups
        $categories = Category::pluck('id', 'name');
        $brands     = Brand::pluck('id', 'name');
        $groups     = CustomerGroup::pluck('id', 'name');

        $validUnits = ['pcs','kg','g','l','ml','nos','box','pkt','rol','drm','doz','cs'];

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rowNum++;
                if (count(array_filter($row, fn($v) => trim($v) !== '')) === 0) continue;

                $data = array_combine($header, array_pad($row, count($header), ''));
                $data = array_map('trim', $data);

                // ── Validate row ──
                $rowErrors = [];

                if (empty($data['name']))  $rowErrors[] = 'Name is required';
                if (!is_numeric($data['price'] ?? '')) $rowErrors[] = 'Price must be numeric';
                if (!is_numeric($data['stock'] ?? '')) $rowErrors[] = 'Stock must be numeric';
                if (!in_array($data['unit'] ?? '', $validUnits)) $rowErrors[] = 'Invalid unit (valid: ' . implode(', ', $validUnits) . ')';

                // Category lookup
                $categoryId = null;
                if (!empty($data['category'])) {
                    $categoryId = $categories->get($data['category']);
                    if (!$categoryId) $rowErrors[] = "Category \"{$data['category']}\" not found";
                } else {
                    $rowErrors[] = 'Category is required';
                }

                if (!empty($rowErrors)) {
                    $failedCount++;
                    $errors[] = ['row' => $rowNum, 'name' => $data['name'] ?? '—', 'errors' => $rowErrors];
                    continue;
                }

                // Brand
                $brandId = !empty($data['brand']) ? ($brands->get($data['brand']) ?? null) : null;

                // Check duplicate
                $existing = Product::where('name', $data['name'])
                    ->orWhere(function ($q) use ($data) {
                        if (!empty($data['barcode'])) $q->where('barcode', $data['barcode']);
                    })->first();

                if ($existing) {
                    if ($onDuplicate === 'skip') {
                        $skippedCount++;
                        $errors[] = ['row' => $rowNum, 'name' => $data['name'], 'errors' => ['Skipped — duplicate name/barcode']];
                        continue;
                    }
                    // update
                    $existing->update($this->buildProductData($data, $categoryId, $brandId));
                    $product = $existing;
                } else {
                    $productData = $this->buildProductData($data, $categoryId, $brandId);
                    $productData['sku'] = $this->generateUniqueSKU();
                    $product = Product::create($productData);
                }

                // Customer groups
                if (!empty($data['customer_groups'])) {
                    $groupNames = array_map('trim', explode(',', $data['customer_groups']));
                    $groupIds   = collect($groupNames)
                        ->map(fn($n) => $groups->get($n))
                        ->filter()
                        ->values()
                        ->toArray();
                    if ($groupIds) $product->customerGroups()->sync($groupIds);
                } else {
                    $homeDelivery = CustomerGroup::where('slug', 'home-delivery')->pluck('id');
                    $product->customerGroups()->sync($homeDelivery);
                }

                $imported[] = $product->id;
                $importedCount++;
            }

            fclose($handle);

            $import = ProductImport::create([
                'filename'            => $filename,
                'original_filename'   => $file->getClientOriginalName(),
                'total_rows'          => $rowNum - 1,
                'imported_rows'       => $importedCount,
                'skipped_rows'        => $skippedCount,
                'failed_rows'         => $failedCount,
                'errors'              => $errors ?: null,
                'imported_product_ids'=> $imported,
                'status'              => 'completed',
                'imported_by'         => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success'        => true,
                'message'        => "Import complete: {$importedCount} imported, {$skippedCount} skipped, {$failedCount} failed.",
                'import_id'      => $import->id,
                'imported_count' => $importedCount,
                'skipped_count'  => $skippedCount,
                'failed_count'   => $failedCount,
                'errors'         => $errors,
                'can_rollback'   => $importedCount > 0,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            fclose($handle);
            Log::error('Product import failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Import failed: ' . $e->getMessage()], 500);
        }
    }

    // ── Rollback an import ──
    public function importRollback(ProductImport $import)
    {
        if ($import->status === 'rolled_back') {
            return response()->json(['success' => false, 'message' => 'Already rolled back.'], 422);
        }

        $ids = $import->imported_product_ids ?? [];

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No product IDs recorded for this import.'], 422);
        }

        DB::beginTransaction();
        try {
            // Soft-delete all imported products
            Product::whereIn('id', $ids)->delete();

            $import->update([
                'status'         => 'rolled_back',
                'rolled_back_at' => now(),
                'rolled_back_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' products from this import have been removed.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Rollback failed: ' . $e->getMessage()], 500);
        }
    }

    // ── Import history (AJAX refresh) ──
    public function importHistory()
    {
        $history = ProductImport::with('importedBy', 'rolledBackBy')
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json([
            'html' => view('admin.products.partials.import-history', compact('history'))->render(),
        ]);
    }

    private function buildProductData(array $data, ?int $categoryId, ?int $brandId): array
    {
        $isWeightBased = (int) ($data['is_weight_based'] ?? 0);
        return [
            'name'           => $data['name'],
            'slug'           => Product::generateUniqueSlug($data['name']),
            'category_id'    => $categoryId,
            'brand_id'       => $brandId,
            'price'          => (float) ($data['price'] ?? 0),
            'mrp'            => !empty($data['mrp']) ? (float) $data['mrp'] : null,
            'cost'           => !empty($data['cost']) ? (float) $data['cost'] : null,
            'stock'          => (int) ($data['stock'] ?? 0),
            'unit'           => $data['unit'] ?? 'pcs',
            'tax_rate'       => !empty($data['tax_rate']) ? (float) $data['tax_rate'] : null,
            'barcode'        => $data['barcode'] ?? null,
            'description'    => $data['description'] ?? null,
            'is_active'      => isset($data['is_active'])  ? (int) $data['is_active']  : 1,
            'is_featured'    => isset($data['is_featured']) ? (int) $data['is_featured'] : 0,
            'is_popular'     => isset($data['is_popular'])  ? (int) $data['is_popular']  : 0,
            'is_weight_based'=> $isWeightBased,
            'price_per_kg'   => $isWeightBased && !empty($data['price_per_kg']) ? (float) $data['price_per_kg'] : null,
            'min_weight'     => $isWeightBased && !empty($data['min_weight'])   ? (float) $data['min_weight']   : null,
            'max_weight'     => $isWeightBased && !empty($data['max_weight'])   ? (float) $data['max_weight']   : null,
        ];
    }
}
