@extends('admin.layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@push('styles')
<style>
    :root {
        --primary: #2563eb;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
    }

    .order-details-container {
        padding: 24px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .badge-shipped {
        background: #e0f2fe;
        color: #0369a1;
    }

    .badge-delivered {
        background: #d1fae5;
        color: #059669;
    }


    /* Header */
    .order-header {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .order-header-left h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 8px 0;
    }

    .order-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .order-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #64748b;
    }

    .order-meta-item i {
        color: #94a3b8;
    }

    .order-header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-success {
        background: var(--success);
        color: white;
    }

    .btn-success:hover {
        background: #059669;
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    .btn-danger {
        background: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    /* Content Grid */
    .order-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    @media (max-width: 1024px) {
        .order-content {
            grid-template-columns: 1fr;
        }
    }

    .card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h2 {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .card-body {
        padding: 24px;
    }

    /* Order Items Table */
    .order-items-table {
        width: 100%;
        border-collapse: collapse;
    }

    .order-items-table thead th {
        text-align: left;
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }

    .order-items-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 14px;
        vertical-align: middle;
    }

    .product-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .product-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
    }

    .product-info {
        flex: 1;
    }

    .product-name {
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .product-sku {
        font-size: 12px;
        color: #64748b;
    }

    /* Order Summary */
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        font-size: 14px;
    }

    .summary-row:not(:last-child) {
        border-bottom: 1px solid #f1f5f9;
    }

    .summary-row.total {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        padding-top: 16px;
        margin-top: 8px;
        border-top: 2px solid #e2e8f0;
    }

    .summary-label {
        color: #64748b;
    }

    .summary-value {
        font-weight: 500;
        color: #1e293b;
    }

    /* Info Sections */
    .info-section {
        margin-bottom: 24px;
    }

    .info-section:last-child {
        margin-bottom: 0;
    }

    .info-section h3 {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 14px;
    }

    .info-label {
        color: #64748b;
        font-weight: 500;
    }

    .info-value {
        color: #1e293b;
        text-align: right;
    }

    .address-block {
        background: #f8fafc;
        padding: 16px;
        border-radius: 8px;
        font-size: 14px;
        line-height: 1.6;
        color: #334155;
    }

    /* Status Badges */
    .badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .badge-lg {
        padding: 8px 16px;
        font-size: 14px;
    }

    .badge-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-processing {
        background: #dbeafe;
        color: #2563eb;
    }

    .badge-completed {
        background: #d1fae5;
        color: #059669;
    }

    .badge-cancelled {
        background: #fee2e2;
        color: #dc2626;
    }

    .badge-refunded {
        background: #f3e8ff;
        color: #9333ea;
    }

    .badge-paid {
        background: #d1fae5;
        color: #059669;
    }

    .badge-failed {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Status Update Form */
    .status-form {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 500;
        color: #475569;
    }

    .form-control {
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 32px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: #e2e8f0;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 24px;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: -28px;
        top: 4px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--primary);
    }

    .timeline-dot.success {
        border-color: var(--success);
    }

    .timeline-dot.warning {
        border-color: var(--warning);
    }

    .timeline-dot.danger {
        border-color: var(--danger);
    }

    .timeline-content {
        background: #f8fafc;
        padding: 12px 16px;
        border-radius: 8px;
    }

    .timeline-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
        font-size: 14px;
    }

    .timeline-time {
        font-size: 12px;
        color: #64748b;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 24px;
        color: #64748b;
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 12px;
        opacity: 0.3;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .order-details-container {
            padding: 16px;
        }

        .order-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .order-header-actions {
            width: 100%;
        }

        .order-header-actions .btn {
            flex: 1;
            justify-content: center;
        }

        .product-cell {
            flex-direction: column;
            align-items: flex-start;
        }

        .order-items-table {
            font-size: 13px;
        }

        .order-items-table thead th,
        .order-items-table tbody td {
            padding: 12px 8px;
        }
    }

    /* Loading Spinner */
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="order-details-container">
    <!-- Order Header -->
    <div class="order-header">
        <div class="order-header-left">
            <h1>Order {{ $order->order_number }}</h1>
            <div class="order-meta">
                <div class="order-meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>{{ $order->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <div class="order-meta-item">
                    <i class="fas fa-user"></i>
                    <span>{{ $order->customer_name }}</span>
                </div>
                <div class="order-meta-item">
                    <span class="badge badge-{{ $order->status }} badge-lg">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="order-header-actions">
            <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-secondary" target="_blank">
                <i class="fas fa-print"></i> Print Invoice
            </a>
            <button class="btn btn-danger" id="deleteOrderBtn">
                <i class="fas fa-trash"></i> Delete Order
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="order-content">
        <!-- Left Column -->
        <div class="left-column">
            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-shopping-cart"></i> Order Items ({{ $order->items->count() }})</h2>
                </div>
                <div class="card-body" style="padding: 0;">
                    <div style="overflow-x: auto;">
                        <table class="order-items-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    @if($order->items->where('weight', '!=', null)->count() > 0)
                                        <th>Weight</th>
                                    @endif
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="product-cell">
                                                @if($item->product && $item->product->primaryImage)
                                                    <img src="{{ $item->product->image_url }}"
                                                         alt="{{ $item->product_name }}"
                                                         class="product-image">
                                                @else
                                                    <div class="product-image" style="background: #f1f5f9; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-image" style="color: #cbd5e1;"></i>
                                                    </div>
                                                @endif
                                                <div class="product-info">
                                                    <div class="product-name">{{ $item->product_name }}</div>
                                                    @if($item->product_sku)
                                                        <div class="product-sku">SKU: {{ $item->product_sku }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        @if($order->items->where('weight', '!=', null)->count() > 0)
                                            <td>{{ $item->weight ? number_format($item->weight, 3) . ' kg' : '-' }}</td>
                                        @endif
                                        <td><strong>${{ number_format($item->subtotal, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card" style="margin-top: 24px;">
                <div class="card-header">
                    <h2><i class="fas fa-calculator"></i> Order Summary</h2>
                </div>
                <div class="card-body">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value">£{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Shipping Cost</span>
                        <span class="summary-value">£{{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Tax (20%)</span>
                        <span class="summary-value">£{{ number_format($order->tax, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="summary-row">
                            <span class="summary-label">Discount</span>
                            <span class="summary-value" style="color: var(--success);">
                                -${{ number_format($order->discount, 2) }}
                            </span>
                        </div>
                    @endif
                    <div class="summary-row total">
                        <span class="summary-label">Total</span>
                        <span class="summary-value">£{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card" style="margin-top: 24px;">
                <div class="card-header">
                    <h2><i class="fas fa-user"></i> Customer Information</h2>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $order->customer_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $order->customer_email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $order->customer_phone }}</span>
                    </div>
                    @if($order->user)
                        <div class="info-row">
                            <span class="info-label">Account:</span>
                            <span class="info-value">
                                <span class="badge badge-success">Registered User</span>
                            </span>
                        </div>
                    @else
                        <div class="info-row">
                            <span class="info-label">Account:</span>
                            <span class="info-value">
                                <span class="badge badge-pending">Guest</span>
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="card" style="margin-top: 24px;">
                <div class="card-header">
                    <h2><i class="fas fa-truck"></i> Shipping Information</h2>
                </div>
                <div class="card-body">
                    <h3>Shipping Address</h3>
                    <div class="address-block">
                        <strong>{{ $order->customer_name }}</strong><br>
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_postcode }}<br>
                        {{ $order->shipping_country }}
                    </div>

                    @if($order->customer_notes)
                        <div style="margin-top: 20px;">
                            <h3>Customer Notes</h3>
                            <div class="address-block">
                                {{ $order->customer_notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="right-column">
            <!-- Update Order Status -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-edit"></i> Update Status</h2>
                </div>
                <div class="card-body">
                    <form id="updateStatusForm" class="status-form">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Order Status</label>
                            <select name="status" class="form-control" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Admin Notes (Optional)</label>
                            <textarea name="admin_notes" class="form-control" placeholder="Add internal notes...">{{ $order->admin_notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            <i class="fas fa-save"></i> Update Order Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card" style="margin-top: 24px;">
                <div class="card-header">
                    <h2><i class="fas fa-credit-card"></i> Payment Info</h2>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Method:</span>
                        <span class="info-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="badge badge-{{ $order->payment_status }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </span>
                    </div>
                    @if($order->paid_at)
                        <div class="info-row">
                            <span class="info-label">Paid At:</span>
                            <span class="info-value">{{ $order->paid_at->format('M d, Y h:i A') }}</span>
                        </div>
                    @endif
                    @if($order->stripe_payment_intent_id)
                        <div class="info-row">
                            <span class="info-label">Payment ID:</span>
                            <span class="info-value" style="font-size: 12px; word-break: break-all;">
                                {{ $order->stripe_payment_intent_id }}
                            </span>
                        </div>
                    @endif

                    <div style="margin-top: 20px;">
                        <form id="updatePaymentForm">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>Update Payment Status</label>
                                <select name="payment_status" class="form-control" required>
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success" style="width: 100%; justify-content: center;">
                                <i class="fas fa-check"></i> Update Payment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card" style="margin-top: 24px;">
                <div class="card-header">
                    <h2><i class="fas fa-history"></i> Order Activity</h2>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot success"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">Order Placed</div>
                                <div class="timeline-time">{{ $order->created_at->format('M d, Y h:i A') }}</div>
                            </div>
                        </div>

                        @if($order->payment_status == 'paid' && $order->paid_at)
                            <div class="timeline-item">
                                <div class="timeline-dot success"></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Payment Confirmed</div>
                                    <div class="timeline-time">{{ $order->paid_at->format('M d, Y h:i A') }}</div>
                                </div>
                            </div>
                        @endif

                        @if($order->status == 'processing')
                            <div class="timeline-item">
                                <div class="timeline-dot warning"></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Order Processing</div>
                                    <div class="timeline-time">{{ $order->updated_at->format('M d, Y h:i A') }}</div>
                                </div>
                            </div>
                        @endif

                        @if(in_array($order->status, ['shipped', 'delivered']))
                            <div class="timeline-item">
                                <div class="timeline-dot {{ $order->status == 'delivered' ? 'success' : 'warning' }}"></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Order {{ ucfirst($order->status) }}</div>
                                    <div class="timeline-time">{{ $order->updated_at->format('M d, Y h:i A') }}</div>
                                </div>
                            </div>
                        @endif

                        @if($order->status == 'cancelled')
                            <div class="timeline-item">
                                <div class="timeline-dot danger"></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Order Cancelled</div>
                                    <div class="timeline-time">{{ $order->updated_at->format('M d, Y h:i A') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update Order Status
    $('#updateStatusForm').on('submit', function(e) {
        e.preventDefault();

        const $btn = $(this).find('button[type="submit"]');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<span class="loading-spinner"></span> Updating...');

        $.ajax({
            url: '{{ route("admin.orders.update-status", $order) }}',
            type: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to update order status'
                });
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Update Payment Status
    $('#updatePaymentForm').on('submit', function(e) {
        e.preventDefault();

        const $btn = $(this).find('button[type="submit"]');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<span class="loading-spinner"></span> Updating...');

        $.ajax({
            url: '{{ route("admin.orders.update-payment-status", $order) }}',
            type: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to update payment status'
                });
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Delete Order
    $('#deleteOrderBtn').on('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.orders.destroy", $order) }}',
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = '{{ route("admin.orders.index") }}';
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to delete order'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
