<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerGroup;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $sort      = $request->get('sort', 'latest');
        $direction = $request->get('direction', 'desc');

        $query = User::where('is_admin', 0)
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->with(['orders' => fn($q) => $q->latest()->limit(1), 'groups'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where(fn($q2) => $q2
                    ->where('name',   'like', "%$s%")
                    ->orWhere('email',  'like', "%$s%")
                    ->orWhere('mobile', 'like', "%$s%")
                );
            })
            ->when($request->filled('group'), fn($q) =>
                $q->whereHas('groups', fn($g) =>
                    $g->where('customer_groups.id', $request->group)
                )
            );

        match($sort) {
            'name'        => $query->orderBy('name', $direction),
            'most_orders' => $query->orderBy('orders_count', $direction),
            'most_spent'  => $query->orderBy('orders_sum_total', $direction),
            default       => $query->orderBy('created_at', $direction),
        };

        $customers      = $query->paginate(20)->withQueryString();
        $totalCustomers = User::where('is_admin', 0)->count();
        $totalRevenue   = Order::where('status', '!=', 'cancelled')->sum('total');
        $avgOrderValue  = Order::avg('total');
        $groups         = CustomerGroup::where('is_active', 1)->orderBy('name')->get();

        // Top spender for badge
        $topSpenderId = User::where('is_admin', 0)
            ->withSum('orders', 'total')
            ->orderByDesc('orders_sum_total')
            ->value('id');

        if ($request->ajax()) {
            return response()->json([
                'html'       => view('admin.customers._table_rows',
                                    compact('customers', 'sort', 'direction', 'topSpenderId'))->render(),
                'pagination' => $customers->links('pagination::bootstrap-5')->render(),
                'total'      => $customers->total(),
                'from'       => $customers->firstItem() ?? 0,
                'to'         => $customers->lastItem()  ?? 0,
            ]);
        }

        return view('admin.customers.index', compact(
            'customers', 'sort', 'direction',
            'totalCustomers', 'totalRevenue', 'avgOrderValue',
            'groups', 'topSpenderId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->withoutTrashed()],
            'mobile'                => 'nullable|string|max:20',
            'password'              => 'required|string|min:6|confirmed',
            'groups'                => 'nullable|array',
            'groups.*'              => 'exists:customer_groups,id',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'mobile'            => $request->mobile,
            'password'          => Hash::make($request->password),
            'is_verified'       => true,
            'email_verified_at' => now(),
        ]);

        if ($request->filled('groups')) {
            $user->groups()->sync($request->groups);
        } else {
            $hd = CustomerGroup::where('slug', 'home-delivery')->first();
            if ($hd) $user->groups()->attach($hd->id);
        }

        return response()->json(['success' => true, 'message' => 'Customer created successfully']);
    }

    public function edit(User $user)
    {
        $user->load('groups');
        $groups = CustomerGroup::where('is_active', 1)->orderBy('name')->get();

        return response()->json([
            'success'        => true,
            'user'           => $user,
            'groups'         => $groups,
            'user_group_ids' => $user->groups->pluck('id'),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)->withoutTrashed()],
            'mobile'   => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'groups'   => 'nullable|array',
            'groups.*' => 'exists:customer_groups,id',
        ]);

        $data = ['name' => $request->name, 'email' => $request->email, 'mobile' => $request->mobile];
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);

        $user->update($data);
        $user->groups()->sync($request->groups ?? []);

        return response()->json(['success' => true, 'message' => 'Customer updated successfully']);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true, 'message' => 'Customer deleted successfully']);
    }

    public function show(User $user)
    {
        $user->loadCount('orders')->loadSum('orders', 'total');

        $orders = $user->orders()->with(['items.product'])->latest()->get();

        $favoriteProducts = $user->orders()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->selectRaw('products.id, products.name, products.slug,
                SUM(order_items.quantity) as total_qty,
                SUM(order_items.subtotal) as total_spent,
                COUNT(order_items.id) as times_ordered')
            ->groupBy('products.id', 'products.name', 'products.slug')
            ->orderByDesc('total_qty')->limit(5)->get();

        $statusBreakdown = $user->orders()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')->pluck('count', 'status');

        $monthlySpend = $user->orders()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as total")
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')->orderBy('month')->pluck('total', 'month');

        return view('admin.customers.show', compact(
            'user', 'orders', 'favoriteProducts', 'statusBreakdown', 'monthlySpend'
        ));
    }
}
