<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display orders list with filters
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])
            ->withCount('items');

        // Search by order number, customer name, email, or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by price range
        if ($request->filled('min_amount')) {
            $query->where('total', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('total', '<=', $request->max_amount);
        }

        // Filter by customer
        if ($request->filled('customer_id')) {
            $query->where('user_id', $request->customer_id);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $orders = $query->paginate(20);

        // AJAX Request
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.orders.partials.table-rows', compact('orders'))->render(),
                'pagination' => $orders->appends($request->except('page'))->links('pagination::bootstrap-5')->render(),
                'total' => $orders->total(),
                'showing' => [
                    'from' => $orders->firstItem(),
                    'to' => $orders->lastItem(),
                    'total' => $orders->total()
                ]
            ]);
        }

        // Get customers for filter dropdown
        $customers = User::where('is_admin', 0)
            ->whereHas('orders')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        // Statistics for dashboard cards
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'customers', 'stats'));
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product.primaryImage']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,refunded',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        try {
            $oldStatus = $order->status;

            $order->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes
            ]);

            // If order is cancelled, restore product stock
            if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product && !$item->product->is_weight_based) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'new_status' => $request->status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        try {
            $order->update([
                'payment_status' => $request->payment_status,
                'paid_at' => $request->payment_status === 'paid' ? now() : null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete order
     */
    public function destroy(Order $order)
    {
        try {
            // Restore stock if order is not cancelled
            if ($order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product && !$item->product->is_weight_based) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }

            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete orders
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);

        try {
            DB::beginTransaction();

            $orders = Order::whereIn('id', $request->order_ids)->get();

            foreach ($orders as $order) {
                // Restore stock if not cancelled
                if ($order->status !== 'cancelled') {
                    foreach ($order->items as $item) {
                        if ($item->product && !$item->product->is_weight_based) {
                            $item->product->increment('stock', $item->quantity);
                        }
                    }
                }
            }

            Order::whereIn('id', $request->order_ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($request->order_ids) . ' orders deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'items']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $filename = 'orders_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'Order Number', 'Customer Name', 'Email', 'Phone',
                'Subtotal', 'Shipping', 'Tax', 'Total',
                'Payment Method', 'Payment Status', 'Order Status',
                'Date', 'Items Count'
            ]);

            // Data rows
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone,
                    number_format($order->subtotal, 2),
                    number_format($order->shipping_cost, 2),
                    number_format($order->tax, 2),
                    number_format($order->total, 2),
                    $order->payment_method,
                    $order->payment_status,
                    $order->status,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->items->count()
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print invoice
     */
    public function invoice(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.invoice', compact('order'));
    }
}
