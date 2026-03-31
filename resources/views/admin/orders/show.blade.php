@extends('admin.layouts.app')
@section('title', 'Order ' . $order->order_number)

@push('styles')
<style>
    .od-wrap { padding: 20px; max-width: 1400px; margin: 0 auto; }

    /* ── Back bar ── */
    .od-back {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 13px; font-weight: 600; color: #6b7280;
        text-decoration: none; margin-bottom: 16px;
        transition: color 0.2s;
    }
    .od-back:hover { color: #08437b; }

    /* ── Header card ── */
    .od-header {
        background: white; border: 1px solid #e5e7eb; border-radius: 12px;
        padding: 20px 24px; margin-bottom: 20px;
        display: flex; align-items: flex-start; justify-content: space-between;
        flex-wrap: wrap; gap: 16px;
    }
    .od-header-left h1 { font-size: 20px; font-weight: 800; color: #111827; margin: 0 0 8px; }
    .od-meta { display: flex; flex-wrap: wrap; gap: 14px; align-items: center; }
    .od-meta-item { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280; }
    .od-meta-item i { color: #9ca3af; font-size: 12px; }
    .od-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

    /* ── Buttons ── */
    .ob {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;
        border: none; cursor: pointer; transition: all 0.2s; text-decoration: none;
        white-space: nowrap;
    }
    .ob:hover { transform: translateY(-1px); opacity: 0.88; }
    .ob-ghost  { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
    .ob-ghost:hover { background: #e5e7eb; opacity: 1; transform: none; color: #374151; }
    .ob-pdf { background: #fff1f2; color: #be123c; border: 1px solid #fda4af; }
    .ob-pdf:hover { background: #fecaca; opacity: 1; transform: none; }
    .ob-del    { background: #fee2e2; color: #991b1b; }
    .ob-blue   { background: #08437b; color: white; }
    .ob-green  { background: #10b981; color: white; }
    .ob-sm { padding: 7px 13px; font-size: 12px; }

    /* ── Status badges ── */
    .sb { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: capitalize; white-space: nowrap; }
    .sb-pending    { background: #fef3c7; color: #92400e; }
    .sb-processing { background: #dbeafe; color: #1e40af; }
    .sb-shipped    { background: #ede9fe; color: #5b21b6; }
    .sb-delivered  { background: #d1fae5; color: #065f46; }
    .sb-completed  { background: #d1fae5; color: #065f46; }
    .sb-cancelled  { background: #fee2e2; color: #991b1b; }
    .sb-paid       { background: #d1fae5; color: #065f46; }
    .sb-unpaid     { background: #fef3c7; color: #92400e; }
    .sb-failed     { background: #fee2e2; color: #991b1b; }
    .sb-refunded   { background: #f3e8ff; color: #6d28d9; }
    .sb-lg { font-size: 13px; padding: 6px 14px; }

    /* ── Layout grid ── */
    .od-grid { display: grid; grid-template-columns: 1fr 360px; gap: 20px; align-items: start; }
    @media(max-width:1100px) { .od-grid { grid-template-columns: 1fr; } }

    /* ── Cards ── */
    .od-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
    .od-card:last-child { margin-bottom: 0; }
    .od-card-head {
        padding: 14px 20px; border-bottom: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: space-between;
        background: #fafafa;
    }
    .od-card-head h2 { font-size: 14px; font-weight: 700; color: #111827; margin: 0; display: flex; align-items: center; gap: 7px; }
    .od-card-head h2 i { color: #08437b; font-size: 13px; }
    .od-card-body { padding: 20px; }
    .od-card-body-0 { padding: 0; }

    /* ── Items table ── */
    .od-table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 480px; }
    .od-table thead th { background: #f9fafb; padding: 10px 14px; font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.04em; border-bottom: 1px solid #e5e7eb; text-align: left; white-space: nowrap; }
    .od-table tbody td { padding: 13px 14px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
    .od-table tbody tr:last-child td { border-bottom: none; }
    .od-table tbody tr:hover td { background: #fafafa; }

    .prod-cell { display: flex; align-items: center; gap: 10px; }
    .prod-img  { width: 46px; height: 46px; border-radius: 8px; object-fit: cover; border: 1px solid #e5e7eb; flex-shrink: 0; }
    .prod-img-placeholder { width: 46px; height: 46px; border-radius: 8px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; border: 1px solid #e5e7eb; flex-shrink: 0; }
    .prod-name { font-weight: 600; color: #111827; font-size: 13px; }
    .prod-sku  { font-size: 11px; color: #9ca3af; margin-top: 2px; }

    /* ── Summary ── */
    .sum-row { display: flex; justify-content: space-between; padding: 9px 0; font-size: 13px; border-bottom: 1px solid #f3f4f6; }
    .sum-row:last-child { border-bottom: none; }
    .sum-row.sum-total { font-size: 16px; font-weight: 800; color: #111827; border-top: 2px solid #e5e7eb; padding-top: 12px; margin-top: 4px; border-bottom: none; }
    .sum-label { color: #6b7280; }
    .sum-val   { font-weight: 600; color: #111827; }

    /* ── Info rows ── */
    .ir { display: flex; justify-content: space-between; align-items: flex-start; padding: 8px 0; border-bottom: 1px solid #f3f4f6; font-size: 13px; }
    .ir:last-child { border-bottom: none; }
    .ir-label { color: #6b7280; font-weight: 500; flex-shrink: 0; margin-right: 12px; }
    .ir-val   { color: #111827; text-align: right; }

    /* ── Address block ── */
    .addr-block { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px; font-size: 13px; line-height: 1.7; color: #374151; }

    /* ── Section divider ── */
    .od-section-title { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; margin: 16px 0 8px; }

    /* ── Form controls ── */
    .od-select, .od-textarea {
        width: 100%; border: 1px solid #d1d5db; border-radius: 8px;
        padding: 9px 12px; font-size: 13px; background: white;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .od-select:focus, .od-textarea:focus {
        border-color: #08437b; box-shadow: 0 0 0 3px rgba(8,67,123,0.08); outline: none;
    }
    .od-textarea { resize: vertical; min-height: 90px; }
    .od-label { font-size: 12px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 5px; display: block; }

    /* ── Timeline ── */
    .timeline { position: relative; padding-left: 28px; }
    .timeline::before { content:''; position:absolute; left:7px; top:8px; bottom:8px; width:2px; background:#e5e7eb; }
    .tl-item { position: relative; padding-bottom: 20px; }
    .tl-item:last-child { padding-bottom: 0; }
    .tl-dot { position: absolute; left: -25px; top: 3px; width: 14px; height: 14px; border-radius: 50%; background: white; border: 3px solid #e5e7eb; }
    .tl-dot.d-blue   { border-color: #08437b; }
    .tl-dot.d-green  { border-color: #10b981; }
    .tl-dot.d-amber  { border-color: #f59e0b; }
    .tl-dot.d-purple { border-color: #8b5cf6; }
    .tl-dot.d-red    { border-color: #ef4444; }
    .tl-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px; }
    .tl-title { font-size: 13px; font-weight: 700; color: #111827; margin-bottom: 3px; }
    .tl-time  { font-size: 11px; color: #9ca3af; }

    /* ── Loading spinner ── */
    .ld-spin { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.3); border-top-color: white; border-radius: 50%; animation: spn 0.6s linear infinite; display: inline-block; vertical-align: middle; }
    @keyframes spn { to { transform: rotate(360deg); } }

    /* ── Responsive ── */
    @media(max-width:767px) {
        .od-wrap { padding: 12px; }
        .od-header { padding: 16px; }
        .od-header-left h1 { font-size: 17px; }
        .od-actions { width: 100%; }
        .od-card-body { padding: 14px; }
        .prod-cell { flex-wrap: wrap; }
    }

    .sb-delivery-pending    { background: #fef3c7; color: #92400e; }
    .sb-delivery-processing { background: #dbeafe; color: #1e40af; }
    .sb-delivery-shipped    { background: #ede9fe; color: #5b21b6; }
    .sb-delivery-delivered  { background: #d1fae5; color: #065f46; }
    .sb-delivery-cancelled  { background: #fee2e2; color: #991b1b; }

    .sb-payment-pending     { background: #fef3c7; color: #92400e; }
    .sb-payment-paid        { background: #d1fae5; color: #065f46; }
    .sb-payment-failed      { background: #fee2e2; color: #991b1b; }
    .sb-payment-refunded    { background: #f3e8ff; color: #6d28d9; }
</style>
@endpush

@section('content')
<div class="od-wrap">

    {{-- Back link --}}
    <a href="{{ route('admin.orders.index') }}" class="od-back">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>

    {{-- ── Header ── --}}
    <div class="od-header">
        <div class="od-header-left">
            <h1>Order {{ $order->order_number }}</h1>
            <div class="od-meta">
                <div class="od-meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $order->created_at->format('d M Y, h:i A') }}
                </div>
                <div class="od-meta-item">
                    <i class="fas fa-user"></i>
                    {{ $order->customer_name }}
                </div>
                <div class="od-meta-item">
                    <i class="fas fa-truck"></i>
                    <span class="sb sb-lg sb-delivery-{{ $order->status }}">
                        Delivery: {{ ucfirst($order->status) }}
                    </span>
                </div>

                <div class="od-meta-item">
                    <i class="fas fa-credit-card"></i>
                    <span class="sb sb-lg sb-payment-{{ $order->payment_status }}">
                        Payment: {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="od-actions">
            {{-- Opens PDF in new tab --}}
            <a href="{{ route('admin.orders.invoice', $order) }}"
               class="ob ob-pdf" target="_blank">
                <i class="fas fa-file-pdf"></i> Invoice PDF
            </a>
            <button class="ob ob-del" id="deleteOrderBtn">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>

    {{-- ── Main grid ── --}}
    <div class="od-grid">

        {{-- ════ LEFT COLUMN ════ --}}
        <div>

            {{-- Order Items --}}
            <div class="od-card">
                <div class="od-card-head">
                    <h2><i class="fas fa-shopping-bag"></i> Order Items</h2>
                    <span style="font-size:12px;color:#9ca3af;">{{ $order->items->count() }} item(s)</span>
                </div>
                <div class="od-card-body-0">
                    <div style="overflow-x:auto;">
                        <table class="od-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Unit Price</th>
                                    <th>Qty</th>
                                    @if($order->items->whereNotNull('weight')->count() > 0)
                                        <th>Weight</th>
                                    @endif
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="prod-cell">
                                            @if($item->product && $item->product->primaryImage)
                                                <img src="{{ $item->product->image_url }}"
                                                     alt="{{ $item->product_name }}"
                                                     class="prod-img">
                                            @else
                                                <div class="prod-img-placeholder">
                                                    <i class="fas fa-image" style="color:#d1d5db;"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="prod-name">{{ $item->product_name }}</div>
                                                @if($item->product_sku)
                                                    <div class="prod-sku">SKU: {{ $item->product_sku }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td style="white-space:nowrap;">£{{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    @if($order->items->whereNotNull('weight')->count() > 0)
                                        <td>{{ $item->weight ? number_format($item->weight, 3).' kg' : '—' }}</td>
                                    @endif
                                    <td style="font-weight:700;white-space:nowrap;">
                                        £{{ number_format($item->quantity * $item->price, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Summary inside the same card --}}
                    <div style="padding:16px 20px;border-top:1px solid #f3f4f6;max-width:340px;margin-left:auto;">
                        <div class="sum-row">
                            <span class="sum-label">Subtotal</span>
                            <span class="sum-val">£{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="sum-row">
                            <span class="sum-label">Shipping</span>
                            <span class="sum-val">
                                {{ $order->shipping_cost > 0 ? '£'.number_format($order->shipping_cost,2) : 'Free' }}
                            </span>
                        </div>
                        @if($order->tax > 0)
                        <div class="sum-row">
                            <span class="sum-label">Tax</span>
                            <span class="sum-val">£{{ number_format($order->tax, 2) }}</span>
                        </div>
                        @endif
                        @if($order->discount > 0)
                        <div class="sum-row">
                            <span class="sum-label">Discount</span>
                            <span class="sum-val" style="color:#059669;">−£{{ number_format($order->discount, 2) }}</span>
                        </div>
                        @endif
                        <div class="sum-row sum-total">
                            <span>Total</span>
                            <span style="color:#08437b;">£{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer + Shipping in two columns on wide screens --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;" class="cust-ship-grid">

                {{-- Customer --}}
                <div class="od-card">
                    <div class="od-card-head">
                        <h2><i class="fas fa-user"></i> Customer</h2>
                        @if($order->user)
                            <a href="{{ route('admin.customers.show', $order->user) }}"
                               style="font-size:12px;color:#08437b;text-decoration:none;font-weight:600;">
                                View Profile →
                            </a>
                        @endif
                    </div>
                    <div class="od-card-body">
                        <div class="ir">
                            <span class="ir-label">Name</span>
                            <span class="ir-val">{{ $order->customer_name }}</span>
                        </div>
                        <div class="ir">
                            <span class="ir-label">Email</span>
                            <span class="ir-val" style="word-break:break-all;">{{ $order->customer_email }}</span>
                        </div>
                        <div class="ir">
                            <span class="ir-label">Phone</span>
                            <span class="ir-val">{{ $order->customer_phone ?? '—' }}</span>
                        </div>
                        <div class="ir">
                            <span class="ir-label">Account</span>
                            <span class="ir-val">
                                @if($order->user)
                                    <span class="sb sb-completed" style="font-size:11px;">Registered</span>
                                @else
                                    <span class="sb sb-pending" style="font-size:11px;">Guest</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Shipping --}}
                <div class="od-card">
                    <div class="od-card-head">
                        <h2><i class="fas fa-truck"></i> Shipping</h2>
                    </div>
                    <div class="od-card-body">
                        <div class="addr-block">
                            <strong>{{ $order->customer_name }}</strong><br>
                            {{ $order->shipping_address }}<br>
                            @if($order->shipping_city) {{ $order->shipping_city }}, @endif
                            {{ $order->shipping_postcode }}<br>
                            {{ $order->shipping_country }}
                        </div>
                        @if($order->customer_notes)
                            <div class="od-section-title" style="margin-top:14px;">Customer Notes</div>
                            <div class="addr-block" style="background:#fffbeb;border-color:#fde68a;">
                                {{ $order->customer_notes }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- ════ RIGHT COLUMN ════ --}}
        <div>

            {{-- Update Order Status --}}
            <div class="od-card">
                <div class="od-card-head">
                    <h2><i class="fas fa-pen"></i> Update Status</h2>
                </div>
                <div class="od-card-body">
                    <form id="updateStatusForm">
                        @csrf
                        @method('PUT')
                        <div style="margin-bottom:14px;">
                            <label class="od-label">Order Status</label>
                            <select name="status" class="od-select" required>
                                @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div style="margin-bottom:14px;">
                            <label class="od-label">Admin Notes</label>
                            <textarea name="admin_notes" class="od-textarea"
                                      placeholder="Internal notes (not visible to customer)…">{{ $order->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="ob ob-blue" id="updateStatusBtn"
                                style="width:100%;justify-content:center;">
                            <i class="fas fa-save"></i> Save Order Status
                        </button>
                    </form>
                </div>
            </div>

            {{-- Payment Info + Update --}}
            <div class="od-card">
                <div class="od-card-head">
                    <h2><i class="fas fa-credit-card"></i> Payment</h2>
                </div>
                <div class="od-card-body">
                    <div class="ir">
                        <span class="ir-label">Method</span>
                        <span class="ir-val">{{ ucfirst(str_replace('_',' ',$order->payment_method)) }}</span>
                    </div>
                    <div class="ir">
                        <span class="ir-label">COD Delivery</span>
                        <span class="ir-val">{{ ucfirst(str_replace('_',' ',$order->cod_delivery_method)) }}</span>
                    </div>
                    <div class="ir">
                        <span class="ir-label">Status</span>
                        <span class="ir-val">
                            <span class="sb sb-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
                        </span>
                    </div>
                    @if($order->paid_at)
                    <div class="ir">
                        <span class="ir-label">Paid At</span>
                        <span class="ir-val">{{ $order->paid_at->format('d M Y h:i A') }}</span>
                    </div>
                    @endif
                    @if($order->stripe_payment_intent_id)
                    <div class="ir">
                        <span class="ir-label">Payment ID</span>
                        <span class="ir-val" style="font-size:11px;word-break:break-all;max-width:160px;">
                            {{ $order->stripe_payment_intent_id }}
                        </span>
                    </div>
                    @endif

                    <div style="border-top:1px solid #f3f4f6;margin:14px 0;padding-top:14px;">
                        <form id="updatePaymentForm">
                            @csrf
                            @method('PUT')
                            <label class="od-label">Update Payment Status</label>
                            <select name="payment_status" class="od-select" style="margin-bottom:10px;" required>
                                @foreach(['pending','paid','failed','refunded'] as $ps)
                                    <option value="{{ $ps }}" {{ $order->payment_status === $ps ? 'selected' : '' }}>
                                        {{ ucfirst($ps) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="ob ob-green" id="updatePayBtn"
                                    style="width:100%;justify-content:center;">
                                <i class="fas fa-check"></i> Update Payment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="od-card">
                <div class="od-card-head">
                    <h2><i class="fas fa-stream"></i> Order Activity</h2>
                    <span style="font-size:12px;color:#9ca3af;">{{ $order->activities->count() }} event(s)</span>
                </div>
                <div class="od-card-body">
                    @if($order->activities->isEmpty())
                        <p style="color:#9ca3af;font-size:13px;text-align:center;padding:20px 0;">
                            No activity recorded yet.
                        </p>
                    @else
                        <div class="timeline">
                            @foreach($order->activities as $activity)
                            <div class="tl-item">
                                <div class="tl-dot {{ $activity->dotClass() }}"></div>
                                <div class="tl-box">
                                    <div class="tl-title">{{ $activity->title }}</div>
                                    <div class="tl-time">
                                        {{ $activity->created_at->format('d M Y, h:i A') }}
                                        @if($activity->user)
                                            &middot; <span style="color:#6b7280;">by {{ $activity->user->name }}</span>
                                        @endif
                                    </div>
                                    @if($activity->description)
                                        <div style="font-size:12px;color:#6b7280;margin-top:5px;font-style:italic;">
                                            {{ $activity->description }}
                                        </div>
                                    @endif

                                    {{-- Show old → new status badge transition --}}
                                    @if($activity->type === 'status_changed' && isset($activity->meta['old_status']))
                                        <div style="margin-top:7px;display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                            <span class="sb sb-{{ $activity->meta['old_status'] }}" style="font-size:11px;">
                                                {{ ucfirst($activity->meta['old_status']) }}
                                            </span>
                                            <i class="fas fa-arrow-right" style="color:#9ca3af;font-size:10px;"></i>
                                            <span class="sb sb-{{ $activity->meta['new_status'] }}" style="font-size:11px;">
                                                {{ ucfirst($activity->meta['new_status']) }}
                                            </span>
                                        </div>
                                    @endif

                                    @if($activity->type === 'payment_updated' && isset($activity->meta['old_payment_status']))
                                        <div style="margin-top:7px;display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                            <span class="sb sb-{{ $activity->meta['old_payment_status'] }}" style="font-size:11px;">
                                                {{ ucfirst($activity->meta['old_payment_status']) }}
                                            </span>
                                            <i class="fas fa-arrow-right" style="color:#9ca3af;font-size:10px;"></i>
                                            <span class="sb sb-{{ $activity->meta['new_payment_status'] }}" style="font-size:11px;">
                                                {{ ucfirst($activity->meta['new_payment_status']) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(function() {

    // ── Update order status ──────────────────────────────────────────────
    $('#updateStatusForm').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('#updateStatusBtn');
        $btn.prop('disabled', true).html('<span class="ld-spin"></span> Saving…');

        $.ajax({
            url:  '{{ route("admin.orders.update-status", $order) }}',
            type: 'PUT',
            data: $(this).serialize(),
            success: function(r) {
                if (r.success) {
                    Swal.fire({
                        icon: 'success', title: 'Updated!', text: r.message,
                        confirmButtonColor: '#08437b', timer: 1600, showConfirmButton: false
                    }).then(() => location.reload());
                }
            },
            error: function(xhr) {
                Swal.fire({ icon:'error', title:'Error', text: xhr.responseJSON?.message ?? 'Failed to update', confirmButtonColor:'#08437b' });
                $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Save Order Status');
            }
        });
    });

    // ── Update payment status ────────────────────────────────────────────
    $('#updatePaymentForm').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('#updatePayBtn');
        $btn.prop('disabled', true).html('<span class="ld-spin"></span> Saving…');

        $.ajax({
            url:  '{{ route("admin.orders.update-payment-status", $order) }}',
            type: 'PUT',
            data: $(this).serialize(),
            success: function(r) {
                if (r.success) {
                    Swal.fire({
                        icon: 'success', title: 'Updated!', text: r.message,
                        confirmButtonColor: '#08437b', timer: 1600, showConfirmButton: false
                    }).then(() => location.reload());
                }
            },
            error: function(xhr) {
                Swal.fire({ icon:'error', title:'Error', text: xhr.responseJSON?.message ?? 'Failed to update', confirmButtonColor:'#08437b' });
                $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Update Payment');
            }
        });
    });

    // ── Delete — requires typing DELETE ─────────────────────────────────
    $('#deleteOrderBtn').on('click', function() {
        Swal.fire({
            title: 'Delete Order {{ $order->order_number }}?',
            html: `<p style="color:#6b7280;font-size:14px;margin-bottom:12px;">
                       This is irreversible. Stock will be restored automatically for non-cancelled orders.
                   </p>
                   <p style="font-size:13px;font-weight:600;">
                       Type <span style="color:#ef4444;font-family:monospace;">DELETE</span> to confirm:
                   </p>`,
            input: 'text',
            inputPlaceholder: 'Type DELETE',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-trash"></i> Delete Order',
            preConfirm: (val) => {
                if (val !== 'DELETE')
                    Swal.showValidationMessage('You must type DELETE exactly to confirm');
            }
        }).then(result => {
            if (!result.isConfirmed) return;

            $.ajax({
                url:  '{{ route("admin.orders.destroy", $order) }}',
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(r) {
                    if (r.success) {
                        Swal.fire({
                            icon: 'success', title: 'Deleted', text: r.message,
                            confirmButtonColor: '#08437b', timer: 1600, showConfirmButton: false
                        }).then(() => window.location.href = '{{ route("admin.orders.index") }}');
                    }
                },
                error: function(xhr) {
                    Swal.fire({ icon:'error', title:'Error', text: xhr.responseJSON?.message ?? 'Delete failed', confirmButtonColor:'#08437b' });
                }
            });
        });
    });

});
</script>

{{-- Responsive: stack customer/shipping on mobile --}}
<style>
    @media(max-width:640px) {
        .cust-ship-grid { grid-template-columns: 1fr !important; }
    }
</style>
@endpush
