<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerGroup;
use App\Models\GroupDiscount;
use App\Models\GroupProductOffer;
use App\Models\ProductGroupPrice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
                'html'       => view('admin.customer-groups.partials.table-rows', compact('groups'))->render(),
                'pagination' => $groups->links('pagination::bootstrap-5')->render(),
                'total'      => $groups->total()
            ]);
        }

        return view('admin.customer-groups.index', compact('groups'));
    }

    public function create()
    {
        $customers = User::where('is_admin', 0)->orderBy('name')->get();
        return view('admin.customer-groups.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:customer_groups,name',
            'description' => 'nullable|string|max:1000',
            'is_active'   => 'boolean',
            'customers'   => 'nullable|array',
            'customers.*' => 'exists:users,id'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

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

        $customers = User::where('is_admin', 0)->orderBy('name')->get();

        return view('admin.customer-groups.edit', compact('customerGroup', 'customers'));
    }

    public function update(Request $request, CustomerGroup $customerGroup)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('customer_groups')->ignore($customerGroup->id)],
            'description' => 'nullable|string|max:1000',
            'is_active'   => 'boolean',
            'customers'   => 'nullable|array',
            'customers.*' => 'exists:users,id'
        ]);

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
            'success'   => true,
            'is_active' => $customerGroup->is_active
        ]);
    }

    public function overview(CustomerGroup $customerGroup, Request $request)
    {
        $productsQuery = $customerGroup->products()
            ->with('primaryImage', 'category', 'brand')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('sku',  'like', '%'.$request->search.'%');
                });
            })
            ->when($request->filled('category_id'), fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('status'),      fn($q) => $q->where('is_active',   $request->status))
            ->when($request->filled('stock_status'), function ($q) use ($request) {
                match($request->stock_status) {
                    'in_stock'     => $q->where('stock', '>', 10),
                    'low_stock'    => $q->whereBetween('stock', [1, 10]),
                    'out_of_stock' => $q->where('stock', '<=', 0),
                    default        => null,
                };
            })
            ->orderBy('name');

        $products   = $productsQuery->paginate(15, ['*'], 'products_page')->withQueryString();
        $categories = \App\Models\Category::orderBy('name')->get();

        $offersQuery = $customerGroup->groupProductOffers()
            ->with(['product', 'category', 'brand'])
            ->when($request->filled('offer_search'), function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->whereHas('product',   fn($p) => $p->where('name', 'like', '%'.$request->offer_search.'%'))
                    ->orWhereHas('category', fn($c) => $c->where('name', 'like', '%'.$request->offer_search.'%'))
                    ->orWhereHas('brand',    fn($b) => $b->where('name', 'like', '%'.$request->offer_search.'%'));
                });
            })
            ->when($request->filled('offer_status'), function ($q) use ($request) {
                $now = now();
                if ($request->offer_status === '1') {
                    $q->where('starts_at', '<=', $now)->where('ends_at', '>=', $now);
                } else {
                    $q->where('ends_at', '<', $now)->orWhere('starts_at', '>', $now);
                }
            })
            ->orderBy('created_at', 'desc');

        $offers = $offersQuery->paginate(10, ['*'], 'offers_page')->withQueryString();

        // ── AJAX response ──
        if ($request->ajax()) {
            return response()->json([
                'products_html'  => view('admin.customer-groups.partials.overview-products',
                                        compact('products', 'customerGroup'))->render(),
                'products_total' => $products->total(),
                'offers_html'    => view('admin.customer-groups.partials.overview-offers',
                                        compact('offers', 'customerGroup'))->render(),
                'offers_total'   => $offers->total(),
            ]);
        }

        return view('admin.customer-groups.overview', compact(
            'customerGroup', 'products', 'categories', 'offers'
        ));
    }

    // ✅ Fixed — initialise before the closure, Intelephense knows the type
    public function duplicate(Request $request, CustomerGroup $customerGroup)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('customer_groups', 'name')->whereNull('deleted_at'),
            ],
        ]);

        /** @var CustomerGroup $newGroup */
        $newGroup = null;

        DB::transaction(function () use ($request, $customerGroup, &$newGroup) {

            $newGroup = CustomerGroup::create([
                'name'        => $request->name,
                'slug'        => $this->uniqueSlug(Str::slug($request->name)),
                'description' => $customerGroup->description,
                'is_active'   => $customerGroup->is_active,
            ]);

            // Copy pivot products (customer_group_product table)
            $productIds = $customerGroup->products()->pluck('products.id')->toArray();
            if (!empty($productIds)) {
                $newGroup->products()->sync($productIds);
            }

            // Copy product-specific prices
            $customerGroup->productGroupPrices()->each(function ($price) use ($newGroup) {
                ProductGroupPrice::create([
                    'customer_group_id' => $newGroup->id,
                    'product_id'        => $price->product_id,
                    'price'             => $price->price,
                ]);
            });

            // Copy group discounts
            $customerGroup->groupDiscounts()->each(function ($discount) use ($newGroup) {
                GroupDiscount::create([
                    'customer_group_id' => $newGroup->id,
                    'type'              => $discount->type,
                    'value'             => $discount->value,
                    'min_order_amount'  => $discount->min_order_amount,
                    'is_active'         => $discount->is_active,
                ]);
            });

            // Copy product offers
            $customerGroup->groupProductOffers()->each(function ($offer) use ($newGroup) {
                GroupProductOffer::create([
                    'customer_group_id' => $newGroup->id,
                    'offer_type'        => $offer->offer_type,
                    'product_id'        => $offer->product_id,
                    'category_id'       => $offer->category_id,
                    'brand_id'          => $offer->brand_id,
                    'offer_price'       => $offer->offer_price,
                    'discount_type'     => $offer->discount_type,
                    'discount_value'    => $offer->discount_value,
                    'starts_at'         => now(),
                    'ends_at'           => $offer->ends_at,
                ]);
            });
        });

        return response()->json([
            'success'        => true,
            'message'        => 'Group duplicated successfully.',
            'new_group_name' => $newGroup->name,
        ]);
    }

    private function uniqueSlug(string $slug): string
    {
        $original = $slug;
        $counter  = 1;

        while (
            CustomerGroup::withTrashed()
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }

}
