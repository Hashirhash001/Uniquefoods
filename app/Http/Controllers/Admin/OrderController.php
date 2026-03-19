<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])->withCount('items');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status'))         $query->where('status', $request->status);
        if ($request->filled('payment_status')) $query->where('payment_status', $request->payment_status);
        if ($request->filled('payment_method')) $query->where('payment_method', $request->payment_method);
        if ($request->filled('date_from'))      $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))        $query->whereDate('created_at', '<=', $request->date_to);
        if ($request->filled('min_amount'))     $query->where('total', '>=', $request->min_amount);
        if ($request->filled('max_amount'))     $query->where('total', '<=', $request->max_amount);
        if ($request->filled('customer_id'))    $query->where('user_id', $request->customer_id);

        $sortBy    = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $orders = $query->paginate(20)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html'       => view('admin.orders.partials.table-rows', compact('orders'))->render(),
                'pagination' => $orders->links('pagination::bootstrap-5')->render(),
                'total'      => $orders->total(),
                'showing'    => [
                    'from'  => $orders->firstItem() ?? 0,
                    'to'    => $orders->lastItem()  ?? 0,
                    'total' => $orders->total(),
                ],
            ]);
        }

        $customers = User::where('is_admin', 0)
            ->whereHas('orders')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        $stats = [
            'total_orders'      => Order::count(),
            'pending_orders'    => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'shipped_orders'    => Order::where('status', 'shipped')->count(),
            'delivered_orders'  => Order::where('status', 'delivered')->count(),
            'completed_orders'  => Order::where('status', 'completed')->count(),
            'cancelled_orders'  => Order::where('status', 'cancelled')->count(),
            'total_revenue'     => Order::where('status', '!=', 'cancelled')->sum('total'),
            'today_orders'      => Order::whereDate('created_at', today())->count(),
            'today_revenue'     => Order::whereDate('created_at', today())
                                        ->where('status', '!=', 'cancelled')->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'customers', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product.primaryImage']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Stream PDF invoice in browser (no print page)
     */
    public function invoice(Order $order)
    {
        $order->load(['user', 'items.product']);

        $pdf = Pdf::loadView('admin.orders.invoice-pdf', compact('order'))
                  ->setPaper('a4', 'portrait');

        // stream = view in browser; download = force download
        return $pdf->stream('invoice-' . $order->order_number . '.pdf');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'      => 'required|in:pending,processing,shipped,delivered,completed,cancelled',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        try {
            $oldStatus = $order->status;
            $order->update([
                'status'      => $request->status,
                'admin_notes' => $request->admin_notes,
            ]);

            if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product && !$item->product->is_weight_based) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }

            return response()->json([
                'success'    => true,
                'message'    => 'Order status updated successfully',
                'new_status' => $request->status,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        try {
            $order->update([
                'payment_status' => $request->payment_status,
                'paid_at'        => $request->payment_status === 'paid' ? now() : null,
            ]);

            return response()->json(['success' => true, 'message' => 'Payment status updated']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Order $order)
    {
        try {
            if ($order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product && !$item->product->is_weight_based) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }
            $order->delete();

            return response()->json(['success' => true, 'message' => 'Order deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'order_ids'   => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ]);

        try {
            DB::beginTransaction();

            $orders = Order::whereIn('id', $request->order_ids)->with('items.product')->get();

            foreach ($orders as $order) {
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
                'message' => count($request->order_ids) . ' orders deleted successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function export(Request $request)
    {
        $query = Order::with(['user', 'items']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('order_number', 'like', "%$s%")
                ->orWhere('customer_name', 'like', "%$s%")
                ->orWhere('customer_email', 'like', "%$s%"));
        }
        if ($request->filled('status'))    $query->where('status', $request->status);
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('created_at', '<=', $request->date_to);

        $orders   = $query->latest()->get();
        $filename = 'orders_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Order Number', 'Customer Name', 'Email', 'Phone',
                'Subtotal', 'Shipping', 'Tax', 'Total',
                'Payment Method', 'Payment Status', 'Order Status', 'Date', 'Items',
            ]);
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
                    $order->items->count(),
                ]);
            }
            fclose($file);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
