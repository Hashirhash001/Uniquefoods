<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

        $statusCounts = Order::selectRaw("
            COUNT(*) as total,
            SUM(status = 'pending')    as pending,
            SUM(status = 'processing') as processing,
            SUM(status = 'shipped')    as shipped,
            SUM(status = 'delivered')  as delivered,
            SUM(status = 'completed')  as completed,
            SUM(status = 'cancelled')  as cancelled,
            SUM(status != 'cancelled') as revenue_count
        ")->first();

        $todayCounts = Order::whereDate('created_at', today())
            ->selectRaw("COUNT(*) as orders, SUM(IF(status != 'cancelled', total, 0)) as revenue")
            ->first();

        $stats = [
            'total_orders'      => $statusCounts->total,
            'pending_orders'    => $statusCounts->pending,
            'processing_orders' => $statusCounts->processing,
            'shipped_orders'    => $statusCounts->shipped,
            'delivered_orders'  => $statusCounts->delivered,
            'completed_orders'  => $statusCounts->completed,
            'cancelled_orders'  => $statusCounts->cancelled,
            'total_revenue'     => Order::where('status', '!=', 'cancelled')->sum('total'),
            'today_orders'      => $todayCounts->orders,
            'today_revenue'     => $todayCounts->revenue ?? 0,
        ];

        return view('admin.orders.index', compact('orders', 'customers', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'items.product.primaryImage',
            'activities.user',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load('user', 'items.product.primaryImage');
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_name'      => 'required|string|max:255',
            'customer_email'     => 'required|email|max:255',
            'customer_phone'     => 'nullable|string|max:30',
            'shipping_address'   => 'required|string|max:500',
            'shipping_city'      => 'nullable|string|max:100',
            'shipping_postcode'  => 'nullable|string|max:20',
            'shipping_country'   => 'nullable|string|max:100',
            'shipping_cost'      => 'nullable|numeric|min:0',
            'tax'                => 'nullable|numeric|min:0',
            'discount'           => 'nullable|numeric|min:0',
            'customer_notes'     => 'nullable|string|max:1000',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|numeric|min:0',
            'items.*.price'      => 'required|numeric|min:0',
            'items.*.vat_rate'   => 'nullable|numeric|min:0|max:100',
            'items.*.weight'     => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $existingItems  = $order->items()->get()->keyBy('product_id');
            $submittedItems = collect($request->items)->keyBy('product_id');

            // 1. Remove items that were deleted
            foreach ($existingItems as $productId => $item) {
                if (!$submittedItems->has($productId)) {
                    if ($item->product && !$item->product->is_weight_based) {
                        $item->product->increment('stock', $item->quantity);
                    }
                    $item->delete();
                }
            }

            // 2. Update existing / add new items
            foreach ($submittedItems as $productId => $itemData) {
                $product   = \App\Models\Product::find($productId);  // move this UP before vatRate
                $vatRate   = (float) ($itemData['vat_rate'] ?? $product?->tax_rate ?? 0);  // ← fallback to product
                $subtotal  = (float) $itemData['quantity'] * (float) $itemData['price'];
                $vatAmount = round($subtotal * ($vatRate / 100), 2);

                if ($existingItems->has($productId)) {
                    // Update existing
                    $existing = $existingItems[$productId];
                    $qtyDiff  = $existing->quantity - $itemData['quantity'];

                    if ($qtyDiff != 0 && $product && !$product->is_weight_based) {
                        $product->increment('stock', $qtyDiff);
                    }

                    $existing->update([
                        'quantity'   => $itemData['quantity'],
                        'price'      => $itemData['price'],
                        'vat_rate'   => $vatRate,       // ← NEW
                        'vat_amount' => $vatAmount,     // ← NEW
                        'weight'     => $itemData['weight'] ?? null,
                        'subtotal'   => $subtotal,
                    ]);
                } else {
                    // New item added
                    if ($product && !$product->is_weight_based) {
                        $product->decrement('stock', $itemData['quantity']);
                    }

                    $order->items()->create([
                        'product_id'   => $productId,
                        'product_name' => $product->name,
                        'product_sku'  => $product->sku ?? null,
                        'quantity'     => $itemData['quantity'],
                        'price'        => $itemData['price'],
                        'vat_rate'     => $vatRate,     // ← NEW
                        'vat_amount'   => $vatAmount,   // ← NEW
                        'weight'       => $itemData['weight'] ?? null,
                        'subtotal'     => $subtotal,
                    ]);
                }
            }

            // 3. Recalculate totals
            $order->refresh();
            $subtotal     = $order->items->sum('subtotal');
            $tax          = $order->items->sum('vat_amount');  // ← recalculate from frozen VAT amounts
            $shippingCost = is_numeric($request->shipping_cost) ? (float)$request->shipping_cost : (float)$order->shipping_cost;
            $discount     = is_numeric($request->discount)      ? (float)$request->discount      : (float)($order->discount ?? 0);
            $total        = $subtotal + $shippingCost + $tax - $discount;

            $order->update([
                'customer_name'     => $request->customer_name,
                'customer_email'    => $request->customer_email,
                'customer_phone'    => $request->customer_phone,
                'shipping_address'  => $request->shipping_address,
                'shipping_city'     => $request->shipping_city,
                'shipping_postcode' => $request->shipping_postcode,
                'shipping_country'  => $request->shipping_country,
                'shipping_cost'     => $shippingCost,
                'tax'               => $tax,
                'discount'          => $discount,
                'subtotal'          => $subtotal,
                'total'             => $total,
                'customer_notes'    => $request->customer_notes,
            ]);

            // 4. Activity log
            $order->activities()->create([
                'user_id'     => auth()->id(),
                'type'        => 'order_edited',
                'title'       => 'Order Edited by Admin',
                'description' => 'Order items and/or details were manually updated.',
                'meta'        => [],
            ]);

            DB::commit();

            // 5. Send mail with updated invoice
            $order->refresh()->load('items.product');
            Mail::to($order->customer_email)->queue(new \App\Mail\OrderUpdated($order));

            return response()->json(['success' => true, 'message' => 'Order updated and customer notified.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Stream PDF invoice in browser (no print page)
     */
    public function invoice(Order $order)
    {
        $order->load(['user', 'items.product']);

        // -----------------------------------------------------------
        // Resolve logo path — works on both local dev and shared host
        // From your debug output, the confirmed live path is the 3rd one.
        // -----------------------------------------------------------
        $logoCandidates = [
            public_path('admin/assets/images/logo/unique-food-logo3.png'),
            base_path('../public_html/admin/assets/images/logo/unique-food-logo3.png'),
            '/home/u117991691/domains/uniquefoodsonline.co.uk/public_html/admin/assets/images/logo/unique-food-logo3.png',
        ];

        $logoData = null;
        foreach ($logoCandidates as $path) {
            if (file_exists($path)) {
                $logoData = 'data:image/png;base64,' . base64_encode(file_get_contents($path));
                break;
            }
        }

        $pdf = Pdf::loadView('admin.orders.invoice-pdf', compact('order', 'logoData'))
                ->setPaper('a4', 'portrait');

        return $pdf->stream('invoice-' . $order->order_number . '.pdf');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'      => 'required|in:pending,processing,shipped,delivered,completed,cancelled',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $order->status;
            $newStatus = $request->status;

            $order->update([
                'status'      => $newStatus,
                'admin_notes' => $request->admin_notes,
            ]);

            // Restore stock on cancellation
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $order->loadMissing('items.product');
                foreach ($order->items as $item) {
                    if ($item->product && !$item->product->is_weight_based && $item->quantity) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }

            $descriptions = [
                'pending'    => 'Order was marked as pending.',
                'processing' => 'Order is now being processed.',
                'shipped'    => 'Order has been shipped.',
                'delivered'  => 'Order has been delivered.',
                'completed'  => 'Order has been completed.',
                'cancelled'  => 'Order was cancelled. Stock restored automatically.',
            ];

            $order->activities()->create([
                'user_id'     => auth()->id(),
                'type'        => 'status_changed',
                'title'       => 'Status Changed to ' . ucfirst($newStatus),
                'description' => $descriptions[$newStatus] ?? "Status changed from {$oldStatus} to {$newStatus}.",
                'meta'        => [
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                ],
            ]);

            if ($request->filled('admin_notes') && $request->admin_notes !== $order->getOriginal('admin_notes')) {
                $order->activities()->create([
                    'user_id'     => auth()->id(),
                    'type'        => 'note_added',
                    'title'       => 'Admin Note Added',
                    'description' => $request->admin_notes,
                    'meta'        => [],
                ]);
            }

            DB::commit();

            $notifiableStatuses = ['shipped', 'delivered', 'cancelled'];

            if (in_array($newStatus, $notifiableStatuses) && $order->customer_email) {
                // Load what the PDF template needs before handing off to the queue
                $order->loadMissing('items.product');

                Mail::to($order->customer_email)
                    ->queue(new OrderStatusUpdated($order, $oldStatus, $newStatus));
            }

            return response()->json([
                'success'    => true,
                'message'    => 'Order status updated successfully',
                'new_status' => $newStatus,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        try {
            $oldPaymentStatus = $order->payment_status;
            $newPaymentStatus = $request->payment_status;

            $order->update([
                'payment_status' => $newPaymentStatus,
                'paid_at'        => $newPaymentStatus === 'paid' ? now() : null,
            ]);

            $descriptions = [
                'pending'  => 'Payment status reset to pending.',
                'paid'     => 'Payment has been confirmed.',
                'failed'   => 'Payment has been marked as failed.',
                'refunded' => 'Payment has been refunded.',
            ];

            $order->activities()->create([
                'user_id'     => auth()->id(),
                'type'        => 'payment_updated',
                'title'       => 'Payment ' . ucfirst($newPaymentStatus),
                'description' => $descriptions[$newPaymentStatus] ?? "Payment status changed to {$newPaymentStatus}.",
                'meta'        => [
                    'old_payment_status' => $oldPaymentStatus,
                    'new_payment_status' => $newPaymentStatus,
                ],
            ]);

            return response()->json(['success' => true, 'message' => 'Payment status updated']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Order $order)
    {
        $order->loadMissing('items.product');

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
        $query = Order::with(['user', 'items'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q
                ->where('order_number', 'like', "%$s%")
                ->orWhere('customer_name', 'like', "%$s%")
                ->orWhere('customer_email', 'like', "%$s%")
            );
        }
        if ($request->filled('status'))    $query->where('status', $request->status);
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('created_at', '<=', $request->date_to);

        $filename = 'orders_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Order Number', 'Customer Name', 'Email', 'Phone',
                'Subtotal', 'Shipping', 'Tax', 'Total',
                'Payment Method', 'Payment Status', 'Order Status', 'Date', 'Items',
            ]);

            $query->chunk(200, function ($orders) use ($file) {
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
            });

            fclose($file);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
