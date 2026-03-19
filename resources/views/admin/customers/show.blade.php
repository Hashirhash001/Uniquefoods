@extends('admin.layouts.app')
@section('title', $user->name . ' — Customer Detail')

@push('styles')
<style>
    .detail-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        margin-bottom: 1.25rem;
        overflow: hidden;
    }
    .dc-header {
        padding: 0.9rem 1.1rem;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dc-body { padding: 1.1rem; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1rem;
    }
    .info-label { font-size: 11px; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
    .info-value { font-size: 14px; font-weight: 600; color: #111827; margin-top: 3px; word-break: break-all; }

    .stat-pill {
        background: #f3f4f6;
        border-radius: 10px;
        padding: 0.9rem;
        text-align: center;
    }
    .sp-value { font-size: 20px; font-weight: 800; color: #08437b; }
    .sp-label { font-size: 11px; color: #6b7280; margin-top: 3px; }

    .orders-table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 560px; }
    .orders-table th {
        background: #f9fafb;
        padding: 9px 12px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }
    .orders-table td {
        padding: 11px 12px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: top;
    }
    .orders-table tr:last-child td { border-bottom: none; }

    .order-status {
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .status-pending    { background: #fef3c7; color: #92400e; }
    .status-processing { background: #dbeafe; color: #1e40af; }
    .status-completed  { background: #d1fae5; color: #065f46; }
    .status-cancelled  { background: #fee2e2; color: #991b1b; }
    .status-shipped    { background: #ede9fe; color: #5b21b6; }
    .status-delivered  { background: #d1fae5; color: #065f46; }
    .status-paid       { background: #d1fae5; color: #065f46; }
    .status-unpaid     { background: #fee2e2; color: #991b1b; }

    .fav-product {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .fav-product:last-child { border-bottom: none; }
    .fav-rank {
        width: 26px; height: 26px; border-radius: 50%;
        background: #08437b; color: white;
        font-size: 11px; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .status-breakdown-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f3f4f6;
        font-size: 13px;
    }
    .status-breakdown-item:last-child { border-bottom: none; }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #6b7280;
        font-size: 13px;
        text-decoration: none;
        margin-bottom: 1.1rem;
    }
    .back-btn:hover { color: #08437b; }

    .monthly-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .monthly-table th {
        padding: 8px 12px;
        background: #f9fafb;
        color: #9ca3af;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        text-transform: uppercase;
    }
    .monthly-table td {
        padding: 9px 12px;
        border-bottom: 1px solid #f3f4f6;
    }
    .monthly-table tr:last-child td { border-bottom: none; }

    /* Pagination for orders */
    #orders-pagination .pagination {
        display: flex; flex-wrap: wrap; gap: 4px;
        list-style: none; padding: 0; margin: 0;
    }
    #orders-pagination .page-item .page-link {
        padding: 6px 12px; border-radius: 6px;
        font-size: 12px; font-weight: 500;
        border: 1px solid #e5e7eb;
        color: #374151; background: white;
        text-decoration: none; cursor: pointer;
    }
    #orders-pagination .page-item.active .page-link {
        background: #08437b; color: white; border-color: #08437b;
    }
    #orders-pagination .page-item.disabled .page-link { opacity: 0.4; cursor: not-allowed; }

    @media (max-width: 767px) {
        .sp-value { font-size: 16px; }
        .dc-body { padding: 0.9rem; }
        .info-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 480px) {
        .info-grid { grid-template-columns: 1fr; }
        .stat-pill { padding: 0.7rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <a href="{{ route('admin.customers.index') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Customers
    </a>

    {{-- Profile Header --}}
    <div class="detail-card">
        <div class="dc-body">
            <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" style="width:60px;height:60px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                @else
                    <div style="width:60px;height:60px;border-radius:50%;background:#08437b;color:white;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;flex-shrink:0;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div style="min-width:0;">
                    <h4 style="margin:0;font-size:18px;font-weight:700;color:#111827;">{{ $user->name }}</h4>
                    <div style="font-size:12px;color:#9ca3af;">Customer since {{ $user->created_at->format('d M Y') }}</div>
                </div>
            </div>

            {{-- Key Stats --}}
            <div class="row g-2 mb-4">
                <div class="col-6 col-sm-3">
                    <div class="stat-pill">
                        <div class="sp-value">{{ $user->orders_count }}</div>
                        <div class="sp-label">Total Orders</div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="stat-pill">
                        <div class="sp-value" style="font-size:16px;">£{{ number_format($user->orders_sum_total, 2) }}</div>
                        <div class="sp-label">Total Spent</div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="stat-pill">
                        <div class="sp-value" style="font-size:16px;">
                            £{{ $user->orders_count > 0 ? number_format($user->orders_sum_total / $user->orders_count, 2) : '0.00' }}
                        </div>
                        <div class="sp-label">Avg Order</div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="stat-pill">
                        <div class="sp-value">
                            {{ $orders->whereIn('status', ['completed','delivered'])->count() }}
                        </div>
                        <div class="sp-label">Completed</div>
                    </div>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value" style="font-size:13px;">{{ $user->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone</div>
                    <div class="info-value">{{ $user->mobile ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email Verified</div>
                    <div class="info-value" style="font-size:12px;">
                        {{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Not verified' }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Login Via</div>
                    <div class="info-value">{{ $user->provider ? ucfirst($user->provider) : 'Email' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Last Order</div>
                    <div class="info-value" style="font-size:12px;">{{ $orders->first()?->created_at->format('d M Y') ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">

            {{-- Order History --}}
            <div class="detail-card">
                <div class="dc-header"><i class="fas fa-shopping-bag" style="color:#08437b;"></i> Order History</div>
                <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                    <table class="orders-table" id="orders-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody id="orders-tbody">
                            @forelse($orders->take(10) as $order)
                                @include('admin.customers._order_row', ['order' => $order])
                            @empty
                                <tr><td colspan="6" class="text-center py-4" style="color:#9ca3af;">No orders</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div id="orders-pagination" class="p-3 d-flex justify-content-center">
                    @if($orders->count() > 10)
                        <ul class="pagination">
                            @foreach(range(1, ceil($orders->count() / 10)) as $p)
                                <li class="page-item {{ $p === 1 ? 'active' : '' }}">
                                    <a class="page-link orders-page-btn" data-page="{{ $p }}" href="#">{{ $p }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Favourite Products --}}
            <div class="detail-card">
                <div class="dc-header"><i class="fas fa-star" style="color:#f59e0b;"></i> Favourite Products</div>
                <div class="dc-body">
                    @forelse($favoriteProducts as $i => $product)
                        <div class="fav-product">
                            <div class="fav-rank">{{ $i + 1 }}</div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:600;color:#111827;font-size:13px;">{{ $product->name }}</div>
                                <div style="font-size:11px;color:#9ca3af;">Ordered {{ $product->times_ordered }}x · {{ $product->total_qty }} units</div>
                            </div>
                            <div style="text-align:right;flex-shrink:0;">
                                <div style="font-weight:700;color:#08437b;font-size:13px;">£{{ number_format($product->total_spent, 2) }}</div>
                                <div style="font-size:11px;color:#9ca3af;">total spent</div>
                            </div>
                        </div>
                    @empty
                        <p style="color:#9ca3af;font-size:13px;margin:0;">No product data available</p>
                    @endforelse
                </div>
            </div>

        </div>

        <div class="col-lg-4">

            {{-- Order Status Breakdown --}}
            <div class="detail-card">
                <div class="dc-header"><i class="fas fa-chart-pie" style="color:#08437b;"></i> Status Breakdown</div>
                <div class="dc-body">
                    @forelse($statusBreakdown as $status => $count)
                        <div class="status-breakdown-item">
                            <span class="order-status status-{{ $status }}">{{ ucfirst($status) }}</span>
                            <strong>{{ $count }} order{{ $count > 1 ? 's' : '' }}</strong>
                        </div>
                    @empty
                        <p style="color:#9ca3af;font-size:13px;">No data</p>
                    @endforelse
                </div>
            </div>

            {{-- Monthly Spend --}}
            <div class="detail-card">
                <div class="dc-header"><i class="fas fa-chart-bar" style="color:#08437b;"></i> Monthly Spend</div>
                <div style="overflow-x:auto;">
                    <table class="monthly-table">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th style="text-align:right;">Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlySpend as $month => $total)
                                <tr>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y') }}</td>
                                    <td style="text-align:right;font-weight:700;color:#08437b;">£{{ number_format($total, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" style="text-align:center;padding:16px;color:#9ca3af;">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Client-side order pagination (orders already loaded)
const allOrders = @json($orders->values());
const perPage = 10;
let currentOrderPage = 1;

function renderOrderPage(page) {
    currentOrderPage = page;
    const start = (page - 1) * perPage;
    const slice = allOrders.slice(start, start + perPage);

    if (slice.length === 0) {
        $('#orders-tbody').html('<tr><td colspan="6" class="text-center py-4" style="color:#9ca3af;">No orders</td></tr>');
        return;
    }

    let html = '';
    slice.forEach(order => {
        const items = order.items || [];
        const shown = items.slice(0, 2).map(i => `<div style="font-size:11px;color:#374151;">${i.product_name} × ${i.quantity}</div>`).join('');
        const more  = items.length > 2 ? `<div style="font-size:10px;color:#9ca3af;">+${items.length - 2} more</div>` : '';
        html += `
        <tr>
            <td><a href="/admin/orders/${order.id}" style="font-weight:700;color:#08437b;text-decoration:none;font-size:12px;">${order.order_number}</a></td>
            <td style="color:#9ca3af;font-size:11px;white-space:nowrap;">${new Date(order.created_at).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'})}</td>
            <td>${shown}${more}</td>
            <td style="font-weight:700;white-space:nowrap;">£${parseFloat(order.total).toFixed(2)}</td>
            <td><span class="order-status status-${order.status}">${order.status}</span></td>
            <td><span class="order-status status-${order.payment_status}">${order.payment_status}</span></td>
        </tr>`;
    });

    $('#orders-tbody').html(html);

    // Update pagination active
    $('#orders-pagination .page-item').removeClass('active');
    $(`#orders-pagination .orders-page-btn[data-page="${page}"]`).parent().addClass('active');
}

$(document).ready(function() {
    if (allOrders.length > 0) renderOrderPage(1);

    $(document).on('click', '.orders-page-btn', function(e) {
        e.preventDefault();
        renderOrderPage(parseInt($(this).data('page')));
    });
});
</script>
@endpush
