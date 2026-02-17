<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Overview Stats
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::where('is_admin', 0)->count();

        // Growth Metrics (compared to last month)
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $currentMonthRevenue = Order::where('created_at', '>=', $currentMonth)
            ->where('status', '!=', 'cancelled')
            ->sum('total');
        $lastMonthRevenue = Order::whereBetween('created_at', [$lastMonth, $currentMonth])
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        $revenueGrowth = $lastMonthRevenue > 0
            ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        $currentMonthOrders = Order::where('created_at', '>=', $currentMonth)->count();
        $lastMonthOrders = Order::whereBetween('created_at', [$lastMonth, $currentMonth])->count();

        $ordersGrowth = $lastMonthOrders > 0
            ? round((($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1)
            : 0;

        // Recent Orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Low Stock Products
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->where('stock', '>', 0)
            ->with(['category', 'primaryImage'])
            ->orderBy('stock', 'asc')
            ->take(8)
            ->get();

        // Out of Stock
        $outOfStockCount = Product::where('stock', 0)->count();

        // Top Selling Products (last 30 days)
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->where('orders.created_at', '>=', Carbon::now()->subDays(30))
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // Sales Chart Data (last 12 months)
        $salesChartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthRevenue = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', '!=', 'cancelled')
                ->sum('total');

            $salesChartData[] = [
                'month' => $month->format('M Y'),
                'revenue' => $monthRevenue
            ];
        }

        // Order Status Distribution
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Category Performance
        $categoryStats = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('COUNT(products.id) as product_count'),
                DB::raw('SUM(products.stock) as total_stock')
            )
            ->where('products.is_active', 1)
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('product_count', 'desc')
            ->take(6)
            ->get();

        // Recent User Registrations
        $recentUsers = User::where('is_admin', 0)
            ->latest()
            ->take(5)
            ->get();

        // Average Order Value
        $avgOrderValue = Order::where('status', '!=', 'cancelled')
            ->avg('total');

        // Pending Orders
        $pendingOrders = Order::where('status', 'pending')->count();

        return view('admin.dashboard.index', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalUsers',
            'revenueGrowth',
            'ordersGrowth',
            'recentOrders',
            'lowStockProducts',
            'outOfStockCount',
            'topProducts',
            'salesChartData',
            'ordersByStatus',
            'categoryStats',
            'recentUsers',
            'avgOrderValue',
            'pendingOrders'
        ));
    }
}
