<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::withCount('products');

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Apply status filter
        if ($request->filled('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $brands = $query->latest()->paginate(10);

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.brands.partials.table-rows', compact('brands'))->render(),
                'pagination' => $brands->appends($request->except('page'))->links('pagination::bootstrap-5')->render(),
                'total' => $brands->total()
            ]);
        }

        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'is_active' => 'boolean'
        ]);

        // Generate slug from name
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        // Ensure is_active is set
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        Brand::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Brand created successfully'
        ]);
    }

    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'is_active' => 'boolean'
        ]);

        // Generate slug from name if name changed
        if ($data['name'] !== $brand->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $brand->id);
        }

        // Ensure is_active is set
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $brand->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Brand updated successfully'
        ]);
    }

    public function toggleStatus(Brand $brand)
    {
        $brand->update([
            'is_active' => !$brand->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete brand with products. Please reassign products first.'
            ], 422);
        }

        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully'
        ]);
    }

    private function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Brand::where('slug', $slug);

            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
