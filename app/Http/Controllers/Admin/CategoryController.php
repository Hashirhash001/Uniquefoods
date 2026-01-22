<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display paginated categories with relationships
     */
    public function index(Request $request)
    {
        $query = Category::with(['parent', 'children'])
            ->withCount(['products', 'children']);

        // Apply filters if present
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        if ($request->filled('parent_filter')) {
            if ($request->parent_filter === 'main') {
                $query->whereNull('parent_id');
            } elseif ($request->parent_filter === 'sub') {
                $query->whereNotNull('parent_id');
            } elseif (is_numeric($request->parent_filter)) {
                $query->where('parent_id', $request->parent_filter);
            }
        }

        $categories = $query->latest()->paginate(10);

        $parentCategories = Category::whereNull('parent_id')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        // Load all categories for tree view
        $allCategories = Category::with(['children'])
            ->withCount('products')
            ->get();

        // If AJAX request, return JSON with HTML
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.categories.partials.table-rows', compact('categories'))->render(),
                'pagination' => $categories->appends($request->except('page'))->links('pagination::bootstrap-5')->render(),
                'total' => $categories->total()
            ]);
        }

        return view('admin.categories.index', compact('categories', 'parentCategories', 'allCategories'));
    }

    /**
     * Store a new category
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'nullable|in:0,1'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($data['name']) . '.' . $image->getClientOriginalExtension();
            $data['image'] = $image->storeAs('categories', $imageName, 'public');
        }

        // Generate slug from name
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        // Ensure is_active is set
        $data['is_active'] = $request->input('is_active', '1') == '1' ? 1 : 0;

        Category::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully'
        ]);
    }

    /**
     * Update existing category
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_image' => 'nullable|boolean',
            'is_active' => 'nullable|in:0,1'
        ]);

        // Prevent category from being its own parent
        if ($request->parent_id == $category->id) {
            return response()->json([
                'success' => false,
                'message' => 'A category cannot be its own parent'
            ], 422);
        }

        // Prevent circular reference
        if ($request->parent_id && $this->wouldCreateCircularReference($category->id, $request->parent_id)) {
            return response()->json([
                'success' => false,
                'message' => 'This would create a circular reference'
            ], 422);
        }

        // Handle image removal
        if ($request->remove_image && $category->image) {
            Storage::disk('public')->delete($category->image);
            $data['image'] = null;
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($data['name']) . '.' . $image->getClientOriginalExtension();
            $data['image'] = $image->storeAs('categories', $imageName, 'public');
        }

        // Generate slug from name (only if name changed)
        if ($data['name'] !== $category->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $category->id);
        }

        // Ensure is_active is set
        $data['is_active'] = $request->input('is_active', '1') == '1' ? 1 : 0;

        $category->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully'
        ]);
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    /**
     * Delete category
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with products. Please reassign products first.'
            ], 422);
        }

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // Delete all child categories and their images
        foreach ($category->children as $child) {
            if ($child->image) {
                Storage::disk('public')->delete($child->image);
            }
        }
        $category->children()->delete();

        // Delete the category
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category and its subcategories deleted successfully'
        ]);
    }

    /**
     * Check if setting parent would create circular reference
     */
    private function wouldCreateCircularReference($categoryId, $parentId)
    {
        $parent = Category::find($parentId);

        while ($parent) {
            if ($parent->id == $categoryId) {
                return true;
            }
            $parent = $parent->parent;
        }

        return false;
    }

    /**
     * Generate unique slug for category
     */
    private function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Category::where('slug', $slug);

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
