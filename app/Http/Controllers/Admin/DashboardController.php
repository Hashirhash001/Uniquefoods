<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                'categories.id',
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

    public function stats(Request $request)
    {
        $range    = $request->get('range', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        $start = null;
        $end   = Carbon::now();

        switch ($range) {
            case 'this_week':  $start = Carbon::now()->startOfWeek();   break;
            case 'this_month': $start = Carbon::now()->startOfMonth();  break;
            case '3months':    $start = Carbon::now()->subMonths(3);    break;
            case '6months':    $start = Carbon::now()->subMonths(6);    break;
            case 'this_year':  $start = Carbon::now()->startOfYear();   break;
            case 'custom':
                $start = $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : null;
                $end   = $dateTo   ? Carbon::parse($dateTo)->endOfDay()     : Carbon::now();
                break;
            default: $start = null;
        }

        // Stats
        $totalRevenue  = Order::when($start, fn($q) => $q->whereBetween('created_at', [$start, $end]))
                            ->where('status', '!=', 'cancelled')->sum('total');
        $totalOrders   = Order::when($start, fn($q) => $q->whereBetween('created_at', [$start, $end]))->count();
        $totalUsers    = User::where('is_admin', 0)
                            ->when($start, fn($q) => $q->whereBetween('created_at', [$start, $end]))->count();
        $totalProducts = Product::count();
        $avgOrderValue = Order::when($start, fn($q) => $q->whereBetween('created_at', [$start, $end]))
                            ->where('status', '!=', 'cancelled')->avg('total') ?? 0;

        // Growth
        $periodLength = $start ? $start->diffInSeconds($end) : null;
        $prevStart    = $start ? $start->copy()->subSeconds($periodLength) : null;
        $prevEnd      = $start ? $start->copy() : null;

        $prevRevenue = $prevStart
            ? Order::whereBetween('created_at', [$prevStart, $prevEnd])->where('status', '!=', 'cancelled')->sum('total')
            : 0;
        $prevOrders  = $prevStart
            ? Order::whereBetween('created_at', [$prevStart, $prevEnd])->count()
            : 0;

        $revenueGrowth = $prevRevenue > 0
            ? round((($totalRevenue - $prevRevenue) / $prevRevenue) * 100, 1) : 0;
        $ordersGrowth  = $prevOrders > 0
            ? round((($totalOrders - $prevOrders)   / $prevOrders)  * 100, 1) : 0;

        // Alert counts (always global, not date-filtered)
        $pendingOrders   = Order::where('status', 'pending')->count();
        $outOfStockCount = Product::where('stock', 0)->count();
        $lowStockCount   = Product::where('stock', '>', 0)->where('stock', '<=', 10)->count();

        // Sales chart data
        $salesChartData = [];
        if (!$start || $start->diffInDays($end) > 90) {
            $months = $start ? min(12, max(1, (int) $start->diffInMonths($end) + 1)) : 12;
            for ($i = $months - 1; $i >= 0; $i--) {
                $m = Carbon::now()->subMonths($i);
                $salesChartData[] = [
                    'month'   => $m->format('M Y'),
                    'revenue' => Order::whereYear('created_at', $m->year)
                                    ->whereMonth('created_at', $m->month)
                                    ->where('status', '!=', 'cancelled')
                                    ->sum('total'),
                ];
            }
        } else {
            $weeks = (int) $start->diffInWeeks($end) + 1;
            for ($i = $weeks - 1; $i >= 0; $i--) {
                $ws = $start->copy()->addWeeks($weeks - 1 - $i)->startOfWeek();
                $we = $ws->copy()->endOfWeek();
                $salesChartData[] = [
                    'month'   => $ws->format('d M'),
                    'revenue' => Order::whereBetween('created_at', [$ws, $we])
                                    ->where('status', '!=', 'cancelled')
                                    ->sum('total'),
                ];
            }
        }

        // Order status
        $ordersByStatus = Order::when($start, fn($q) => $q->whereBetween('created_at', [$start, $end]))
                            ->select('status', DB::raw('count(*) as count'))
                            ->groupBy('status')
                            ->pluck('count', 'status')
                            ->toArray();

        // Recent orders ✅
        $recentOrders = Order::with('user')
            ->when($start, fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->latest()->take(10)->get()
            ->map(fn($o) => [
                'id'                   => $o->id,
                'order_number'         => $o->order_number ?? '#' . $o->id,
                'user_name'            => $o->user->name ?? 'Guest',
                'total'                => $o->total,
                'status'               => $o->status,
                'created_at_formatted' => $o->created_at->format('d M Y'),
            ])->values();

        // Top products ✅ (was missing entirely!)
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->when($start, fn($q) => $q->whereBetween('orders.created_at', [$start, $end]))
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get()
            ->map(fn($p) => [
                'id'            => $p->id,
                'name'          => $p->name,
                'total_sold'    => $p->total_sold,
                'total_revenue' => $p->total_revenue,
            ])->values();

        return response()->json([
            'totalRevenue'    => number_format($totalRevenue, 2),
            'totalOrders'     => number_format($totalOrders),
            'totalUsers'      => number_format($totalUsers),
            'totalProducts'   => number_format($totalProducts),
            'avgOrderValue'   => number_format($avgOrderValue, 2),
            'revenueGrowth'   => $revenueGrowth,
            'ordersGrowth'    => $ordersGrowth,
            'pendingOrders'   => $pendingOrders,
            'outOfStockCount' => $outOfStockCount,
            'lowStockCount'   => $lowStockCount,
            'salesChartData'  => $salesChartData,
            'ordersByStatus'  => $ordersByStatus,
            'recentOrders'    => $recentOrders,
            'topProducts'     => $topProducts,
        ]);
    }

}
