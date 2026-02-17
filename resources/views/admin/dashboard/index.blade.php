@extends('admin.layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    :root {
        --primary-color: #2563eb;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --info-color: #3b82f6;
        --bg-primary: #ffffff;
        --bg-secondary: #f8fafc;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --border-color: #e2e8f0;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background: var(--bg-secondary);
        color: var(--text-primary);
    }

    .dashboard-container {
        padding: 24px;
        max-width: 1600px;
        margin: 0 auto;
    }

    .dashboard-header {
        margin-bottom: 32px;
    }

    .dashboard-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .dashboard-header p {
        color: var(--text-secondary);
        font-size: 14px;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: var(--bg-primary);
        border-radius: 12px;
        padding: 24px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-color);
    }

    .stat-card.success::before { background: var(--success-color); }
    .stat-card.danger::before { background: var(--danger-color); }
    .stat-card.warning::before { background: var(--warning-color); }
    .stat-card.info::before { background: var(--info-color); }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-icon.primary { background: #dbeafe; color: var(--primary-color); }
    .stat-icon.success { background: #d1fae5; color: var(--success-color); }
    .stat-icon.danger { background: #fee2e2; color: var(--danger-color); }
    .stat-icon.warning { background: #fef3c7; color: var(--warning-color); }

    .stat-content h3 {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 8px;
        line-height: 1;
    }

    .stat-footer {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }

    .growth-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
    }

    .growth-badge.positive {
        background: #d1fae5;
        color: #059669;
    }

    .growth-badge.negative {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    @media (max-width: 1200px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    .card {
        background: var(--bg-primary);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h2 {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .card-body {
        padding: 24px;
    }

    /* Sales Chart */
    #salesChart {
        width: 100%;
        height: 350px;
    }

    /* Table Styles */
    .table-container {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        text-align: left;
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--text-secondary);
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--border-color);
        background: var(--bg-secondary);
    }

    .data-table tbody td {
        padding: 16px;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
    }

    .data-table tbody tr:hover {
        background: var(--bg-secondary);
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .badge.success { background: #d1fae5; color: #059669; }
    .badge.warning { background: #fef3c7; color: #d97706; }
    .badge.danger { background: #fee2e2; color: #dc2626; }
    .badge.info { background: #dbeafe; color: #2563eb; }

    /* Product List */
    .product-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .product-item:last-child {
        border-bottom: none;
    }

    .product-img {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid var(--border-color);
    }

    .product-info {
        flex: 1;
    }

    .product-name {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 14px;
        margin-bottom: 4px;
    }

    .product-meta {
        font-size: 12px;
        color: var(--text-secondary);
    }

    .stock-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .stock-badge.low {
        background: #fef3c7;
        color: #d97706;
    }

    .stock-badge.out {
        background: #fee2e2;
        color: #dc2626;
    }

    /* View All Link */
    .view-all-link {
        color: var(--primary-color);
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: gap 0.2s;
    }

    .view-all-link:hover {
        gap: 8px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: var(--text-secondary);
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.3;
    }

    /* Category Grid */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 16px;
    }

    .category-card {
        background: var(--bg-secondary);
        padding: 16px;
        border-radius: 8px;
        text-align: center;
        border: 1px solid var(--border-color);
    }

    .category-card h4 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-primary);
    }

    .category-card p {
        font-size: 12px;
        color: var(--text-secondary);
    }

    .category-card .count {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-color);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 16px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-value {
            font-size: 24px;
        }

        .content-grid {
            grid-template-columns: 1fr;
        }

        .category-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>Dashboard Overview</h1>
        <p>Welcome back! Here's what's happening with your store today.</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <!-- Revenue Card -->
        <div class="stat-card primary">
            <div class="stat-header">
                <div class="stat-content">
                    <h3>Total Revenue</h3>
                    <div class="stat-value">£{{ number_format($totalRevenue, 2) }}</div>
                    <div class="stat-footer">
                        @if($revenueGrowth >= 0)
                            <span class="growth-badge positive">
                                <i class="fas fa-arrow-up"></i>
                                {{ abs($revenueGrowth) }}%
                            </span>
                        @else
                            <span class="growth-badge negative">
                                <i class="fas fa-arrow-down"></i>
                                {{ abs($revenueGrowth) }}%
                            </span>
                        @endif
                        <span style="color: var(--text-secondary);">vs last month</span>
                    </div>
                </div>
                <div class="stat-icon primary">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-content">
                    <h3>Total Orders</h3>
                    <div class="stat-value">{{ number_format($totalOrders) }}</div>
                    <div class="stat-footer">
                        @if($ordersGrowth >= 0)
                            <span class="growth-badge positive">
                                <i class="fas fa-arrow-up"></i>
                                {{ abs($ordersGrowth) }}%
                            </span>
                        @else
                            <span class="growth-badge negative">
                                <i class="fas fa-arrow-down"></i>
                                {{ abs($ordersGrowth) }}%
                            </span>
                        @endif
                        <span style="color: var(--text-secondary);">vs last month</span>
                    </div>
                </div>
                <div class="stat-icon success">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>

        <!-- Products Card -->
        <div class="stat-card warning">
            <div class="stat-header">
                <div class="stat-content">
                    <h3>Total Products</h3>
                    <div class="stat-value">{{ number_format($totalProducts) }}</div>
                    <div class="stat-footer">
                        <span style="color: var(--text-secondary);">
                            @if($outOfStockCount > 0)
                                <span class="badge danger">{{ $outOfStockCount }} out of stock</span>
                            @else
                                <span class="badge success">All in stock</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="stat-icon warning">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="stat-card info">
            <div class="stat-header">
                <div class="stat-content">
                    <h3>Total Customers</h3>
                    <div class="stat-value">{{ number_format($totalUsers) }}</div>
                    <div class="stat-footer">
                        <span style="color: var(--text-secondary);">
                            Avg Order: ${{ number_format($avgOrderValue, 2) }}
                        </span>
                    </div>
                </div>
                <div class="stat-icon primary">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Sales Chart -->
        <div class="card">
            <div class="card-header">
                <h2>Revenue Overview</h2>
                <span style="color: var(--text-secondary); font-size: 14px;">Last 12 months</span>
            </div>
            <div class="card-body">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Top Products -->
        <div class="card">
            <div class="card-header">
                <h2>Top Selling Products</h2>
                <a href="{{ route('admin.products.index') }}" class="view-all-link">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="card-body" style="padding: 0;">
                @forelse($topProducts as $product)
                    <div class="product-item" style="padding: 16px 24px;">
                        <div class="product-info">
                            <div class="product-name">{{ $product->name }}</div>
                            <div class="product-meta">
                                {{ $product->total_sold }} units sold
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 600; color: var(--success-color);">
                                £{{ number_format($product->total_revenue, 2) }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">📦</div>
                        <p>No sales data available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Secondary Grid -->
    <div class="content-grid">
        <!-- Recent Orders -->
        <div class="card">
            <div class="card-header">
                <h2>Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="view-all-link">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                                    <td>£{{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span class="badge {{
                                            $order->status === 'completed' ? 'success' :
                                            ($order->status === 'pending' ? 'warning' :
                                            ($order->status === 'cancelled' ? 'danger' : 'info'))
                                        }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        <div class="empty-state-icon">🛒</div>
                                        <p>No recent orders</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="card">
            <div class="card-header">
                <h2>Low Stock Alert</h2>
                <span class="badge warning">{{ $lowStockProducts->count() }} items</span>
            </div>
            <div class="card-body" style="padding: 0;">
                @forelse($lowStockProducts as $product)
                    <div class="product-item" style="padding: 16px 24px;">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-img">
                        <div class="product-info">
                            <div class="product-name">{{ Str::limit($product->name, 25) }}</div>
                            <div class="product-meta">{{ $product->category->name ?? 'N/A' }}</div>
                        </div>
                        <span class="stock-badge low">{{ $product->stock }} left</span>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">✅</div>
                        <p>All products are well stocked</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Category Performance -->
    <div class="card">
        <div class="card-header">
            <h2>Category Performance</h2>
        </div>
        <div class="card-body">
            <div class="category-grid">
                @foreach($categoryStats as $category)
                    <div class="category-card">
                        <h4>{{ $category->name }}</h4>
                        <div class="count">{{ $category->product_count }}</div>
                        <p>{{ $category->total_stock }} in stock</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Sales Chart
    const ctx = document.getElementById('salesChart');
    const salesData = @json($salesChartData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesData.map(d => d.month),
            datasets: [{
                label: 'Revenue',
                data: salesData.map(d => d.revenue),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#334155',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return '£' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e2e8f0'
                    },
                    ticks: {
                        callback: function(value) {
                            return '£' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush
