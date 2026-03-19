<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'latest');

        $customers = User::whereHas('orders')
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->with(['orders' => fn($q) => $q->latest()->limit(1)])
            ->when($sort === 'most_orders', fn($q) => $q->orderByDesc('orders_count'))
            ->when($sort === 'most_spent',  fn($q) => $q->orderByDesc('orders_sum_total'))
            ->when($sort === 'latest',      fn($q) => $q->orderByDesc('created_at'))
            ->when($sort === 'name',        fn($q) => $q->orderBy('name'))
            ->paginate(20)
            ->withQueryString();

        $totalCustomers = User::whereHas('orders')->count();
        $totalRevenue   = Order::whereIn('user_id', User::whereHas('orders')->pluck('id'))->sum('total');
        $avgOrderValue  = Order::avg('total');

        if ($request->ajax() || $request->get('ajax')) {
            $html = view('admin.customers._table_rows', compact('customers'))->render();

            // Build pagination HTML manually
            $pagination = '';
            if ($customers->hasPages()) {
                $pagination .= '<ul class="pagination">';
                // Previous
                $pagination .= '<li class="page-item ' . ($customers->onFirstPage() ? 'disabled' : '') . '">';
                $pagination .= '<a class="page-link" data-page="' . ($customers->currentPage() - 1) . '" href="#">&laquo;</a></li>';
                // Pages
                foreach (range(1, $customers->lastPage()) as $p) {
                    $active = $p === $customers->currentPage() ? 'active' : '';
                    $pagination .= "<li class='page-item {$active}'><a class='page-link' data-page='{$p}' href='#'>{$p}</a></li>";
                }
                // Next
                $pagination .= '<li class="page-item ' . ($customers->hasMorePages() ? '' : 'disabled') . '">';
                $pagination .= '<a class="page-link" data-page="' . ($customers->currentPage() + 1) . '" href="#">&raquo;</a></li>';
                $pagination .= '</ul>';
            }

            return response()->json([
                'html'       => $html,
                'pagination' => $pagination,
                'total'      => $customers->total(),
            ]);
        }

        return view('admin.customers.index', compact(
            'customers', 'sort', 'totalCustomers', 'totalRevenue', 'avgOrderValue'
        ));
    }

    public function show(User $user)
    {
        abort_unless($user->orders()->exists(), 404);

        $user->loadCount('orders')
             ->loadSum('orders', 'total');

        // All orders with items
        $orders = $user->orders()
            ->with(['items.product'])
            ->latest()
            ->get();

        // Favorite products — most frequently ordered
        $favoriteProducts = $user->orders()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->selectRaw('
                products.id,
                products.name,
                products.slug,
                SUM(order_items.quantity) as total_qty,
                SUM(order_items.subtotal) as total_spent,
                COUNT(order_items.id) as times_ordered
            ')
            ->groupBy('products.id', 'products.name', 'products.slug')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Order status breakdown
        $statusBreakdown = $user->orders()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Monthly spend (last 12 months)
        $monthlySpend = $user->orders()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as total")
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('admin.customers.show', compact(
            'user', 'orders', 'favoriteProducts', 'statusBreakdown', 'monthlySpend'
        ));
    }
}
