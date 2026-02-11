<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class CustomerGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerGroup::withCount('users');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $groups = $query->orderBy('created_at', 'desc')->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.customer-groups.partials.table-rows', compact('groups'))->render(),
                'pagination' => $groups->links('pagination::bootstrap-5')->render(),
                'total' => $groups->total()
            ]);
        }

        return view('admin.customer-groups.index', compact('groups'));
    }

    public function create()
    {
        $customers = User::where('is_admin', 0)
            ->orderBy('name')
            ->get();

        return view('admin.customer-groups.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:customer_groups,name',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'customers' => 'nullable|array',
            'customers.*' => 'exists:users,id'
        ]);

        // Auto-generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $count = 1;
        while (CustomerGroup::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count;
            $count++;
        }

        $group = CustomerGroup::create($validated);

        if (!empty($validated['customers'])) {
            $group->users()->attach($validated['customers']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer group created successfully'
        ]);
    }

    public function edit(CustomerGroup $customerGroup)
    {
        $customerGroup->load('users');

        $customers = User::where('is_admin', 0)
            ->orderBy('name')
            ->get();

        return view('admin.customer-groups.edit', compact('customerGroup', 'customers'));
    }

    public function update(Request $request, CustomerGroup $customerGroup)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('customer_groups')->ignore($customerGroup->id)],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'customers' => 'nullable|array',
            'customers.*' => 'exists:users,id'
        ]);

        // Auto-update slug if name changed
        if ($validated['name'] !== $customerGroup->name) {
            $validated['slug'] = Str::slug($validated['name']);

            $originalSlug = $validated['slug'];
            $count = 1;
            while (CustomerGroup::where('slug', $validated['slug'])
                ->where('id', '!=', $customerGroup->id)
                ->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count;
                $count++;
            }
        }

        $customerGroup->update($validated);

        if (isset($validated['customers'])) {
            $customerGroup->users()->sync($validated['customers']);
        } else {
            $customerGroup->users()->detach();
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer group updated successfully'
        ]);
    }

    public function destroy(CustomerGroup $customerGroup)
    {
        $customerGroup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer group deleted successfully'
        ]);
    }

    public function toggleStatus(CustomerGroup $customerGroup)
    {
        $customerGroup->update(['is_active' => !$customerGroup->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $customerGroup->is_active
        ]);
    }
}
