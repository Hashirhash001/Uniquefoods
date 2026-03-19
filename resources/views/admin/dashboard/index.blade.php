@extends('admin.layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .dash-wrap {
        padding: 20px;
        max-width: 1600px;
        margin: 0 auto;
    }

    /* ── Header ── */
    .dash-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }
    .dash-header h1 { font-size: 22px; font-weight: 700; color: #111827; margin: 0; }
    .dash-header p  { font-size: 13px; color: #9ca3af; margin: 3px 0 0; }
    .dash-date {
        font-size: 13px; color: #6b7280;
        background: white; border: 1px solid #e5e7eb;
        border-radius: 8px; padding: 8px 14px;
        display: flex; align-items: center; gap: 6px;
        white-space: nowrap;
    }

    /* ── Date Filter Bar ── */
    .date-pills { display: flex; flex-wrap: wrap; gap: 6px; }
    .dp-btn {
        padding: 7px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;
        border: 1px solid #e5e7eb; background: white; color: #374151;
        cursor: pointer; transition: all 0.2s; white-space: nowrap;
    }
    .dp-btn:hover  { background: #f3f4f6; }
    .dp-btn.active { background: #08437b; color: white; border-color: #08437b; }
    .custom-date-wrap {
        display: none; align-items: center; gap: 8px; flex-wrap: wrap;
    }
    .custom-date-wrap input {
        height: 34px; border: 1px solid #d1d5db; border-radius: 8px;
        padding: 0 10px; font-size: 13px; width: 140px;
    }
    .custom-date-wrap input:focus { outline: none; border-color: #08437b; }

    /* ── Stat Cards ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }
    .stat-card {
        background: white; border: 1px solid #e5e7eb; border-radius: 12px;
        padding: 18px; position: relative; overflow: hidden;
        transition: box-shadow 0.2s, transform 0.2s;
        text-decoration: none; display: block; color: inherit;
    }
    .stat-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.09); transform: translateY(-2px); color: inherit; }
    .stat-card::after {
        content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
    }
    .stat-card.c-blue::after   { background: #3b82f6; }
    .stat-card.c-green::after  { background: #10b981; }
    .stat-card.c-amber::after  { background: #f59e0b; }
    .stat-card.c-purple::after { background: #8b5cf6; }

    .sc-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }
    .sc-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .sc-icon.blue   { background: #dbeafe; color: #2563eb; }
    .sc-icon.green  { background: #d1fae5; color: #059669; }
    .sc-icon.amber  { background: #fef3c7; color: #d97706; }
    .sc-icon.purple { background: #ede9fe; color: #7c3aed; }

    .sc-label { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 5px; }
    .sc-value { font-size: 26px; font-weight: 800; color: #111827; line-height: 1; margin-bottom: 8px; }
    .sc-footer { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #6b7280; flex-wrap: wrap; }
    .sc-hint { font-size: 11px; color: #9ca3af; margin-top: 4px; }

    .growth-up   { background: #d1fae5; color: #059669; padding: 2px 8px; border-radius: 20px; font-weight: 700; font-size: 11px; display:inline-flex; align-items:center; gap:3px; }
    .growth-down { background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 20px; font-weight: 700; font-size: 11px; display:inline-flex; align-items:center; gap:3px; }

    /* ── Alert Strip ── */
    .alert-strip { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
    .alert-pill {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 9px 14px; border-radius: 10px; font-size: 13px; font-weight: 600;
        text-decoration: none; transition: opacity 0.2s; white-space: nowrap;
    }
    .alert-pill:hover { opacity: 0.82; }
    .alert-pill.ap-orange { background: #fef3c7; color: #92400e; }
    .alert-pill.ap-red    { background: #fee2e2; color: #991b1b; }
    .alert-pill.ap-blue   { background: #dbeafe; color: #1e40af; }
    .alert-pill .ap-count { background: rgba(0,0,0,0.1); padding: 1px 8px; border-radius: 20px; font-size: 11px; }

    /* ── Cards ── */
    .d-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; height: 100%; }
    .d-card-header {
        padding: 14px 18px; border-bottom: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
    }
    .d-card-header h2 { font-size: 14px; font-weight: 700; color: #111827; margin: 0; }
    .view-all { font-size: 12px; font-weight: 600; color: #08437b; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
    .view-all:hover { color: #0f508d; }

    /* ── Chart ── */
    .chart-wrap { padding: 16px; }
    #salesChart  { height: 260px !important; }

    /* ── Orders Table ── */
    .d-table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 520px; }
    .d-table th {
        background: #f9fafb; padding: 9px 14px; text-align: left;
        font-size: 11px; font-weight: 700; color: #9ca3af;
        text-transform: uppercase; border-bottom: 1px solid #e5e7eb; white-space: nowrap;
    }
    .d-table td { padding: 11px 14px; border-bottom: 1px solid #f3f4f6; color: #374151; vertical-align: middle; }
    .d-table tr:last-child td { border-bottom: none; }
    .d-table tr:hover td { background: #fafafa; }

    .o-badge { padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: capitalize; white-space: nowrap; }
    .o-completed  { background: #d1fae5; color: #065f46; }
    .o-pending    { background: #fef3c7; color: #92400e; }
    .o-cancelled  { background: #fee2e2; color: #991b1b; }
    .o-processing { background: #dbeafe; color: #1e40af; }
    .o-shipped    { background: #ede9fe; color: #5b21b6; }
    .o-delivered  { background: #d1fae5; color: #065f46; }
    .o-paid       { background: #d1fae5; color: #065f46; }
    .o-unpaid     { background: #fee2e2; color: #991b1b; }

    /* ── Product Items ── */
    .p-item { display: flex; align-items: center; gap: 12px; padding: 11px 18px; border-bottom: 1px solid #f3f4f6; }
    .p-item:last-child { border-bottom: none; }
    .p-img  { width: 42px; height: 42px; border-radius: 8px; object-fit: cover; border: 1px solid #e5e7eb; flex-shrink: 0; }
    .p-name { font-size: 13px; font-weight: 600; color: #111827; }
    .p-meta { font-size: 11px; color: #9ca3af; margin-top: 2px; }
    .stk-low { background: #fef3c7; color: #92400e; padding: 3px 9px; border-radius: 6px; font-size: 11px; font-weight: 700; white-space: nowrap; }
    .stk-out { background: #fee2e2; color: #991b1b; padding: 3px 9px; border-radius: 6px; font-size: 11px; font-weight: 700; white-space: nowrap; }

    /* ── Category Cards ── */
    .cat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; padding: 16px; }
    .cat-card {
        background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px;
        padding: 14px; text-align: center; transition: all 0.2s;
        text-decoration: none; display: block; color: inherit;
    }
    .cat-card:hover { border-color: #08437b; background: #eff6ff; color: inherit; }
    .cat-count { font-size: 22px; font-weight: 800; color: #08437b; }
    .cat-name  { font-size: 12px; font-weight: 600; color: #374151; margin: 4px 0 2px; }
    .cat-stock { font-size: 11px; color: #9ca3af; }

    /* ── Status Donut ── */
    .donut-wrap { display: flex; align-items: center; gap: 18px; padding: 16px; flex-wrap: wrap; }
    #statusChart { max-width: 150px; max-height: 150px; }
    .donut-legend { flex: 1; min-width: 110px; }
    .legend-item  { display: flex; align-items: center; gap: 8px; font-size: 12px; color: #374151; margin-bottom: 9px; }
    .legend-dot   { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

    /* ── Customers ── */
    .cust-item { display: flex; align-items: center; gap: 10px; padding: 10px 18px; border-bottom: 1px solid #f3f4f6; text-decoration: none; }
    .cust-item:last-child { border-bottom: none; }
    .cust-item:hover { background: #fafafa; }
    .cust-av { width: 34px; height: 34px; border-radius: 50%; background: #08437b; color: white; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .cust-name  { font-size: 13px; font-weight: 600; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px; }
    .cust-meta  { font-size: 11px; color: #9ca3af; }
    .cust-spent { font-size: 13px; font-weight: 700; color: #08437b; margin-left: auto; white-space: nowrap; }

    /* ── Banners ── */
    .banner-preview-item { display: flex; align-items: center; gap: 12px; padding: 10px 18px; border-bottom: 1px solid #f3f4f6; text-decoration: none; }
    .banner-preview-item:last-child { border-bottom: none; }
    .banner-preview-item:hover { background: #fafafa; }
    .bp-thumb { width: 58px; height: 38px; border-radius: 6px; object-fit: cover; border: 1px solid #e5e7eb; flex-shrink: 0; background: #f3f4f6; }
    .bp-title { font-size: 13px; font-weight: 600; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px; }
    .bp-sub   { font-size: 11px; color: #9ca3af; }
    .bp-active   { background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 700; white-space: nowrap; }
    .bp-inactive { background: #f3f4f6; color: #6b7280; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 700; white-space: nowrap; }

    /* ── Top Products rank medals ── */
    .rank-badge {
        width: 22px; height: 22px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 800; flex-shrink: 0;
    }

    /* ── Empty ── */
    .d-empty { text-align: center; padding: 28px 16px; color: #9ca3af; font-size: 13px; }
    .d-empty i { font-size: 28px; opacity: 0.2; display: block; margin-bottom: 8px; }

    /* ── Spinner overlay ── */
    .dash-spinner {
        display: none; position: fixed; inset: 0;
        background: rgba(255,255,255,0.6); z-index: 9999;
        align-items: center; justify-content: center;
    }
    .dash-spinner.show { display: flex; }
    .spin-ring {
        width: 44px; height: 44px;
        border: 4px solid #e5e7eb; border-top-color: #08437b;
        border-radius: 50%; animation: spinr 0.7s linear infinite;
    }
    @keyframes spinr { to { transform: rotate(360deg); } }

    /* ── Responsive ── */
    @media (max-width: 1199px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 767px) {
        .dash-wrap  { padding: 12px; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .sc-value   { font-size: 20px; }
        .sc-icon    { width: 36px; height: 36px; font-size: 15px; }
        .stat-card  { padding: 13px; }
        #salesChart { height: 200px !important; }
        .cat-grid   { grid-template-columns: repeat(2, 1fr); }
        .donut-wrap { flex-direction: column; align-items: flex-start; }
        #statusChart { max-width: 130px; }
        .dash-date  { display: none; }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
        .sc-value   { font-size: 17px; }
        .sc-label   { font-size: 10px; }
        .dash-header h1 { font-size: 18px; }
        .dp-btn { font-size: 11px; padding: 6px 11px; }
    }
</style>
@endpush

@section('content')

{{-- Spinner --}}
<div class="dash-spinner" id="dashSpinner">
    <div class="spin-ring"></div>
</div>

<div class="dash-wrap">

    {{-- ── Header ── --}}
    <div class="dash-header">
        <div>
            <h1>Dashboard</h1>
            <p>Welcome back! Here's your store at a glance.</p>
        </div>
        <div class="dash-date">
            <i class="fas fa-calendar-alt" style="color:#08437b;"></i>
            {{ now()->format('l, d M Y') }}
        </div>
    </div>

    {{-- ── Date Filter Bar ── --}}
    <div class="d-flex align-items-center gap-2 flex-wrap mb-3" id="date-filter-bar">
        <div class="date-pills">
            <button class="dp-btn active" data-range="all">All Time</button>
            <button class="dp-btn" data-range="this_year">This Year</button>
            <button class="dp-btn" data-range="6months">6 Months</button>
            <button class="dp-btn" data-range="3months">3 Months</button>
            <button class="dp-btn" data-range="this_month">This Month</button>
            <button class="dp-btn" data-range="this_week">This Week</button>
            <button class="dp-btn" id="custom-range-btn">
                <i class="fas fa-calendar-alt"></i> Custom
            </button>
        </div>
        <div class="custom-date-wrap" id="custom-date-wrap">
            <input type="date" id="date-from">
            <span style="color:#9ca3af;font-size:13px;">to</span>
            <input type="date" id="date-to">
            <button class="dp-btn active" id="apply-custom-date">Apply</button>
        </div>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="stats-grid">

        {{-- Revenue --}}
        <a href="{{ route('admin.orders.index') }}" class="stat-card c-blue">
            <div class="sc-top">
                <div>
                    <div class="sc-label">Total Revenue</div>
                    <div class="sc-value" id="val-revenue">£{{ number_format($totalRevenue, 2) }}</div>
                    <div class="sc-footer" id="val-revenue-growth">
                        @if($revenueGrowth >= 0)
                            <span class="growth-up"><i class="fas fa-arrow-up"></i>{{ abs($revenueGrowth) }}%</span>
                        @else
                            <span class="growth-down"><i class="fas fa-arrow-down"></i>{{ abs($revenueGrowth) }}%</span>
                        @endif
                        vs last period
                    </div>
                </div>
                <div class="sc-icon blue"><i class="fas fa-sterling-sign"></i></div>
            </div>
            <div class="sc-hint">Click to view orders →</div>
        </a>

        {{-- Orders --}}
        <a href="{{ route('admin.orders.index') }}" class="stat-card c-green">
            <div class="sc-top">
                <div>
                    <div class="sc-label">Total Orders</div>
                    <div class="sc-value" id="val-orders">{{ number_format($totalOrders) }}</div>
                    <div class="sc-footer" id="val-orders-growth">
                        @if($ordersGrowth >= 0)
                            <span class="growth-up"><i class="fas fa-arrow-up"></i>{{ abs($ordersGrowth) }}%</span>
                        @else
                            <span class="growth-down"><i class="fas fa-arrow-down"></i>{{ abs($ordersGrowth) }}%</span>
                        @endif
                        vs last period
                    </div>
                </div>
                <div class="sc-icon green"><i class="fas fa-shopping-bag"></i></div>
            </div>
            <div class="sc-hint">Click to view orders →</div>
        </a>

        {{-- Products — out of stock deep link --}}
        <a href="{{ route('admin.products.index') }}?stock_status=out_of_stock" class="stat-card c-amber" id="sc-products">
            <div class="sc-top">
                <div>
                    <div class="sc-label">Total Products</div>
                    <div class="sc-value" id="val-products">{{ number_format($totalProducts) }}</div>
                    <div class="sc-footer" id="val-products-footer">
                        @if($outOfStockCount > 0)
                            <span class="growth-down"><i class="fas fa-exclamation"></i>{{ $outOfStockCount }} out of stock</span>
                        @else
                            <span class="growth-up"><i class="fas fa-check"></i>All in stock</span>
                        @endif
                    </div>
                </div>
                <div class="sc-icon amber"><i class="fas fa-box"></i></div>
            </div>
            <div class="sc-hint">Click to view out-of-stock →</div>
        </a>

        {{-- Customers --}}
        <a href="{{ route('admin.customers.index') }}" class="stat-card c-purple">
            <div class="sc-top">
                <div>
                    <div class="sc-label">Customers</div>
                    <div class="sc-value" id="val-customers">{{ number_format($totalUsers) }}</div>
                    <div class="sc-footer">
                        Avg order: <strong>£{{ number_format($avgOrderValue, 2) }}</strong>
                    </div>
                </div>
                <div class="sc-icon purple"><i class="fas fa-users"></i></div>
            </div>
            <div class="sc-hint">Click to view customers →</div>
        </a>

    </div>

    {{-- ── Alert Strip ── --}}
    <div class="alert-strip" id="alert-strip">
        @if($pendingOrders > 0)
            <a href="{{ route('admin.orders.index') }}?status=pending" class="alert-pill ap-blue">
                <i class="fas fa-clock"></i> Pending Orders
                <span class="ap-count">{{ $pendingOrders }}</span>
            </a>
        @endif
        @if($lowStockProducts->count() > 0)
            <a href="{{ route('admin.products.index') }}?stock_status=low_stock" class="alert-pill ap-orange">
                <i class="fas fa-exclamation-triangle"></i> Low Stock
                <span class="ap-count">{{ $lowStockProducts->count() }}</span>
            </a>
        @endif
        @if($outOfStockCount > 0)
            <a href="{{ route('admin.products.index') }}?stock_status=out_of_stock" class="alert-pill ap-red">
                <i class="fas fa-times-circle"></i> Out of Stock
                <span class="ap-count">{{ $outOfStockCount }}</span>
            </a>
        @endif
    </div>

    {{-- ── Row 1: Revenue Chart + Order Status Donut ── --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="d-card">
                <div class="d-card-header">
                    <h2><i class="fas fa-chart-line" style="color:#08437b;margin-right:6px;"></i>Revenue Overview</h2>
                    <span style="font-size:12px;color:#9ca3af;" id="chart-period-label">All Time</span>
                </div>
                <div class="chart-wrap">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="d-card">
                <div class="d-card-header">
                    <h2><i class="fas fa-chart-pie" style="color:#08437b;margin-right:6px;"></i>Order Status</h2>
                </div>
                <div class="donut-wrap">
                    <canvas id="statusChart"></canvas>
                    <div class="donut-legend" id="donut-legend">
                        @php
                            $statusColors = [
                                'pending'    => '#f59e0b',
                                'processing' => '#3b82f6',
                                'shipped'    => '#8b5cf6',
                                'delivered'  => '#10b981',
                                'completed'  => '#059669',
                                'cancelled'  => '#ef4444',
                            ];
                        @endphp
                        @foreach($ordersByStatus as $status => $count)
                            <div class="legend-item">
                                <div class="legend-dot" style="background:{{ $statusColors[$status] ?? '#9ca3af' }};"></div>
                                <a href="{{ route('admin.orders.index') }}?status={{ $status }}"
                                   style="flex:1;color:#374151;text-decoration:none;font-size:12px;">
                                    {{ ucfirst($status) }}
                                </a>
                                <strong>{{ $count }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Row 2: Recent Orders + Low Stock ── --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="d-card">
                <div class="d-card-header">
                    <h2><i class="fas fa-receipt" style="color:#08437b;margin-right:6px;"></i>Recent Orders</h2>
                    <a href="{{ route('admin.orders.index') }}" class="view-all">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div style="overflow-x:auto;">
                    <table class="d-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="recent-orders-tbody">
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           style="font-weight:700;color:#08437b;text-decoration:none;font-size:12px;">
                                            {{ $order->order_number ?? '#'.$order->id }}
                                        </a>
                                    </td>
                                    <td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        {{ $order->user->name ?? 'Guest' }}
                                    </td>
                                    <td style="font-weight:700;white-space:nowrap;">£{{ number_format($order->total, 2) }}</td>
                                    <td><span class="o-badge o-{{ $order->status }}">{{ $order->status }}</span></td>
                                    <td style="color:#9ca3af;font-size:12px;white-space:nowrap;">
                                        {{ $order->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="d-empty"><i class="fas fa-shopping-bag"></i>No orders yet</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="d-card">
                <div class="d-card-header">
                    <h2><i class="fas fa-exclamation-triangle" style="color:#f59e0b;margin-right:6px;"></i>Low Stock Alert</h2>
                    <a href="{{ route('admin.products.index') }}?stock_status=low_stock"
                       style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;text-decoration:none;">
                        {{ $lowStockProducts->count() }} items
                    </a>
                </div>
                @forelse($lowStockProducts as $product)
                    <a href="{{ route('admin.products.edit', $product) }}" class="p-item" style="text-decoration:none;">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="p-img">
                        <div style="flex:1;min-width:0;">
                            <div class="p-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ Str::limit($product->name, 24) }}
                            </div>
                            <div class="p-meta">{{ $product->category->name ?? '—' }}</div>
                        </div>
                        <span class="{{ $product->stock === 0 ? 'stk-out' : 'stk-low' }}">
                            {{ $product->stock }} left
                        </span>
                    </a>
                @empty
                    <div class="d-empty"><i class="fas fa-check-circle"></i>All products well stocked</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Row 3: Top Selling + Top Customers + Banners ── --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-4">
            <div class="d-card">
                <div class="d-card-header">
                    <h2><i class="fas fa-trophy" style="color:#f59e0b;margin-right:6px;"></i>Top Selling</h2>
                    <a href="{{ route('admin.products.index') }}" class="view-all">
                        All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div id="top-products-wrap">
                    @forelse($topProducts as $i => $product)
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="p-item" style="text-decoration:none;">
                            <div class="rank-badge" style="background:{{ $i===0?'#fbbf24':($i===1?'#9ca3af':($i===2?'#cd7c3b':'#e5e7eb')) }};color:{{ $i<3?'white':'#6b7280' }};">
                                {{ $i+1 }}
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div class="p-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $product->name }}</div>
                                <div class="p-meta">{{ $product->total_sold }} units sold</div>
                            </div>
                            <div style="font-weight:700;font-size:13px;color:#059669;white-space:nowrap;">
                                £{{ number_format($product->total_revenue, 2) }}
                            </div>
                        </a>
                    @empty
                        <div class="d-empty"><i class="fas fa-chart-bar"></i>No sales data</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="d-card">
                <div class="d-card-header">
                    <h2><i class="fas fa-users" style="color:#8b5cf6;margin-right:6px;"></i>Top Customers</h2>
                    <a href="{{ route('admin.customers.index') }}" class="view-all">
                        All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                @php
                    $topCustomers = \App\Models\User::whereHas('orders')
                        ->withSum('orders','total')
                        ->withCount('orders')
                        ->orderByDesc('orders_sum_total')
                        ->limit(5)->get();
                @endphp
                @forelse($topCustomers as $customer)
                    <a href="{{ route('admin.customers.show', $customer) }}" class="cust-item">
                        @if($customer->avatar)
                            <img src="{{ $customer->avatar }}"
                                 style="width:34px;height:34px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                        @else
                            <div class="cust-av">{{ strtoupper(substr($customer->name,0,1)) }}</div>
                        @endif
                        <div style="min-width:0;">
                            <div class="cust-name">{{ $customer->name }}</div>
                            <div class="cust-meta">{{ $customer->orders_count }} orders</div>
                        </div>
                        <div class="cust-spent">£{{ number_format($customer->orders_sum_total, 2) }}</div>
                    </a>
                @empty
                    <div class="d-empty"><i class="fas fa-users"></i>No customers yet</div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <div class="d-card">
                <div class="d-card-header">
                    <h2><i class="fas fa-images" style="color:#3b82f6;margin-right:6px;"></i>Banners</h2>
                    <a href="{{ route('admin.banners.index') }}" class="view-all">
                        Manage <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                @php
                    $dashBanners = \App\Models\Banner::orderBy('sort_order')->limit(5)->get();
                @endphp
                @forelse($dashBanners as $banner)
                    <a href="{{ route('admin.banners.edit', $banner) }}" class="banner-preview-item">
                        <img src="{{ $banner->image_url }}" class="bp-thumb" alt="{{ $banner->title }}">
                        <div style="flex:1;min-width:0;">
                            <div class="bp-title">{{ $banner->title }}</div>
                            <div class="bp-sub">Order {{ $banner->sort_order }}</div>
                        </div>
                        <span class="{{ $banner->is_active ? 'bp-active' : 'bp-inactive' }}">
                            {{ $banner->is_active ? 'Live' : 'Off' }}
                        </span>
                    </a>
                @empty
                    <div class="d-empty"><i class="fas fa-image"></i>No banners yet</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Row 4: Category Performance ── --}}
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="d-card">
                <div class="d-card-header">
                    <h2><i class="fas fa-layer-group" style="color:#08437b;margin-right:6px;"></i>Category Performance</h2>
                    <a href="{{ route('admin.categories.index') }}" class="view-all">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="cat-grid">
                    @foreach($categoryStats as $category)
                        <a href="{{ route('admin.products.index') }}?category_id={{ $category->id }}"
                           class="cat-card">
                            <div class="cat-count">{{ $category->product_count }}</div>
                            <div class="cat-name">{{ $category->name }}</div>
                            <div class="cat-stock">{{ number_format($category->total_stock) }} in stock</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ─── Initial data from PHP ────────────────────────────────────────────────
const initialSalesData    = @json($salesChartData);
const initialStatusData   = @json($ordersByStatus);
const routeStats          = "{{ route('admin.dashboard.stats') }}";
const routeOrders         = "{{ route('admin.orders.index') }}";
const routeProducts       = "{{ route('admin.products.index') }}";

const STATUS_COLORS = {
    pending:'#f59e0b', processing:'#3b82f6', shipped:'#8b5cf6',
    delivered:'#10b981', completed:'#059669', cancelled:'#ef4444'
};

// ─── Charts ──────────────────────────────────────────────────────────────
let salesChart = new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels:   initialSalesData.map(d => d.month),
        datasets: [{
            label: 'Revenue',
            data:  initialSalesData.map(d => d.revenue),
            borderColor: '#08437b',
            backgroundColor: 'rgba(8,67,123,0.07)',
            borderWidth: 2.5, fill: true, tension: 0.4,
            pointRadius: 3, pointHoverRadius: 6,
            pointBackgroundColor: '#08437b',
            pointBorderColor: '#fff', pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#111827', padding: 12,
                callbacks: { label: ctx => '£' + ctx.parsed.y.toLocaleString('en-GB', {minimumFractionDigits:2}) }
            }
        },
        scales: {
            y: { beginAtZero: true, grid: { color:'#f3f4f6' }, ticks: { callback: v => '£'+v.toLocaleString(), font:{size:11} } },
            x: { grid: { display: false }, ticks: { font:{size:11} } }
        }
    }
});

let statusChart = new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels:   Object.keys(initialStatusData),
        datasets: [{
            data: Object.values(initialStatusData),
            backgroundColor: Object.keys(initialStatusData).map(s => STATUS_COLORS[s] ?? '#9ca3af'),
            borderWidth: 2, borderColor: '#fff'
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: true, cutout: '68%',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed}` } }
        }
    }
});

// ─── Date filter state ────────────────────────────────────────────────────
let currentRange = 'all';
let customFrom   = '';
let customTo     = '';

const periodLabels = {
    all: 'All Time', this_year: 'This Year', '6months': 'Last 6 Months',
    '3months': 'Last 3 Months', this_month: 'This Month', this_week: 'This Week'
};

// Custom date toggle
$('#custom-range-btn').on('click', function() {
    const w = $('#custom-date-wrap');
    w.css('display', w.is(':visible') ? 'none' : 'flex');
});

$('#apply-custom-date').on('click', function() {
    customFrom = $('#date-from').val();
    customTo   = $('#date-to').val();
    if (!customFrom || !customTo) { alert('Please select both dates.'); return; }
    currentRange = 'custom';
    $('.dp-btn').removeClass('active');
    $('#custom-range-btn').addClass('active');
    loadDashboard();
});

// Pill buttons
$(document).on('click', '.dp-btn[data-range]', function() {
    currentRange = $(this).data('range');
    $('.dp-btn').removeClass('active');
    $(this).addClass('active');
    $('#custom-date-wrap').hide();
    customFrom = '';
    customTo   = '';
    loadDashboard();
});

// ─── AJAX loader ──────────────────────────────────────────────────────────
function loadDashboard() {
    $('#dashSpinner').addClass('show');

    $.ajax({
        url: routeStats,
        type: 'GET',
        data: { range: currentRange, date_from: customFrom, date_to: customTo },
        success: function(d) {

            // ── Stat cards ──
            $('#val-revenue').text('£' + d.totalRevenue);
            $('#val-orders').text(d.totalOrders);
            $('#val-customers').text(d.totalUsers);
            $('#val-products').text(d.totalProducts);

            // Revenue growth
            const rg = parseFloat(d.revenueGrowth);
            $('#val-revenue-growth').html(
                (rg >= 0
                    ? `<span class="growth-up"><i class="fas fa-arrow-up"></i>${Math.abs(rg)}%</span>`
                    : `<span class="growth-down"><i class="fas fa-arrow-down"></i>${Math.abs(rg)}%</span>`)
                + ' vs last period'
            );

            // Orders growth
            const og = parseFloat(d.ordersGrowth);
            $('#val-orders-growth').html(
                (og >= 0
                    ? `<span class="growth-up"><i class="fas fa-arrow-up"></i>${Math.abs(og)}%</span>`
                    : `<span class="growth-down"><i class="fas fa-arrow-down"></i>${Math.abs(og)}%</span>`)
                + ' vs last period'
            );

            // Products footer
            $('#val-products-footer').html(
                d.outOfStockCount > 0
                    ? `<span class="growth-down"><i class="fas fa-exclamation"></i>${d.outOfStockCount} out of stock</span>`
                    : `<span class="growth-up"><i class="fas fa-check"></i>All in stock</span>`
            );

            // ── Alert strip ──
            let alerts = '';
            if (d.pendingOrders > 0)
                alerts += `<a href="${routeOrders}?status=pending" class="alert-pill ap-blue"><i class="fas fa-clock"></i> Pending Orders <span class="ap-count">${d.pendingOrders}</span></a>`;
            if (d.lowStockCount > 0)
                alerts += `<a href="${routeProducts}?stock_status=low_stock" class="alert-pill ap-orange"><i class="fas fa-exclamation-triangle"></i> Low Stock <span class="ap-count">${d.lowStockCount}</span></a>`;
            if (d.outOfStockCount > 0)
                alerts += `<a href="${routeProducts}?stock_status=out_of_stock" class="alert-pill ap-red"><i class="fas fa-times-circle"></i> Out of Stock <span class="ap-count">${d.outOfStockCount}</span></a>`;
            $('#alert-strip').html(alerts);

            // ── Revenue chart ──
            salesChart.data.labels = d.salesChartData.map(x => x.month);
            salesChart.data.datasets[0].data = d.salesChartData.map(x => x.revenue);
            salesChart.update();
            $('#chart-period-label').text(periodLabels[currentRange] ?? 'Custom Range');

            // ── Status donut ──
            statusChart.data.labels = Object.keys(d.ordersByStatus);
            statusChart.data.datasets[0].data = Object.values(d.ordersByStatus);
            statusChart.data.datasets[0].backgroundColor = Object.keys(d.ordersByStatus).map(s => STATUS_COLORS[s] ?? '#9ca3af');
            statusChart.update();

            // ── Donut legend ──
            let legend = '';
            Object.entries(d.ordersByStatus).forEach(([status, count]) => {
                const color = STATUS_COLORS[status] ?? '#9ca3af';
                legend += `
                    <div class="legend-item">
                        <div class="legend-dot" style="background:${color};"></div>
                        <a href="${routeOrders}?status=${status}" style="flex:1;color:#374151;text-decoration:none;font-size:12px;">
                            ${status.charAt(0).toUpperCase()+status.slice(1)}
                        </a>
                        <strong>${count}</strong>
                    </div>`;
            });
            $('#donut-legend').html(legend);

            // ── Recent orders table ──
            let rows = '';
            if (d.recentOrders.length) {
                d.recentOrders.forEach(o => {
                    rows += `
                    <tr>
                        <td><a href="/admin/orders/${o.id}" style="font-weight:700;color:#08437b;text-decoration:none;font-size:12px;">${o.order_number}</a></td>
                        <td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${o.user_name}</td>
                        <td style="font-weight:700;white-space:nowrap;">£${parseFloat(o.total).toFixed(2)}</td>
                        <td><span class="o-badge o-${o.status}">${o.status}</span></td>
                        <td style="color:#9ca3af;font-size:12px;white-space:nowrap;">${o.created_at_formatted}</td>
                    </tr>`;
                });
            } else {
                rows = `<tr><td colspan="5"><div class="d-empty"><i class="fas fa-shopping-bag"></i>No orders yet</div></td></tr>`;
            }
            $('#recent-orders-tbody').html(rows);

            // ── Top products ──
            const medals = ['#fbbf24','#9ca3af','#cd7c3b','#e5e7eb'];
            let prods = '';
            if (d.topProducts.length) {
                d.topProducts.forEach((p, i) => {
                    const bg    = medals[i] ?? '#e5e7eb';
                    const color = i < 3 ? 'white' : '#6b7280';
                    prods += `
                    <a href="/admin/products/${p.id}/edit" class="p-item" style="text-decoration:none;">
                        <div class="rank-badge" style="background:${bg};color:${color};">${i+1}</div>
                        <div style="flex:1;min-width:0;">
                            <div class="p-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${p.name}</div>
                            <div class="p-meta">${p.total_sold} units sold</div>
                        </div>
                        <div style="font-weight:700;font-size:13px;color:#059669;white-space:nowrap;">£${parseFloat(p.total_revenue).toFixed(2)}</div>
                    </a>`;
                });
            } else {
                prods = `<div class="d-empty"><i class="fas fa-chart-bar"></i>No sales data</div>`;
            }
            $('#top-products-wrap').html(prods);

            $('#dashSpinner').removeClass('show');
        },
        error: function() {
            $('#dashSpinner').removeClass('show');
        }
    });
}
</script>
@endpush
