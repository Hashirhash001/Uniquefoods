@extends('admin.layouts.app')

@section('title', 'Orders Management')

@push('styles')
<style>
    :root {
        --primary: #2563eb;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
        --secondary: #6b7280;
    }

    .orders-container {
        padding: 24px;
        max-width: 1800px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .stat-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
    }

    .stat-card.primary .stat-value { color: var(--primary); }
    .stat-card.success .stat-value { color: var(--success); }
    .stat-card.warning .stat-value { color: var(--warning); }
    .stat-card.danger .stat-value { color: var(--danger); }

    /* Filter Section */
    .filters-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 24px;
    }

    .filters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .filters-header h3 {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 500;
        color: #475569;
        margin-bottom: 6px;
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

    .filter-actions {
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

    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    .btn-success {
        background: var(--success);
        color: white;
    }

    .btn-success:hover {
        background: #059669;
    }

    .btn-danger {
        background: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }

    /* Table Section */
    .table-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .table-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .table-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
    }

    .bulk-actions {
        display: none;
        gap: 12px;
        align-items: center;
    }

    .bulk-actions.active {
        display: flex;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table thead th {
        background: #f8fafc;
        padding: 14px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .orders-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 14px;
        color: #334155;
    }

    .orders-table tbody tr:hover {
        background: #f8fafc;
    }

    .order-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .order-number {
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
    }

    .order-number:hover {
        text-decoration: underline;
    }

    .customer-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .customer-name {
        font-weight: 500;
        color: #1e293b;
    }

    .customer-email {
        font-size: 12px;
        color: #64748b;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
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

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
        color: #64748b;
        text-decoration: none;
    }

    .action-btn:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
        color: #334155;
    }

    .action-btn.view:hover {
        background: #dbeafe;
        border-color: var(--primary);
        color: var(--primary);
    }

    .action-btn.delete:hover {
        background: #fee2e2;
        border-color: var(--danger);
        color: var(--danger);
    }

    /* Pagination */
    .table-footer {
        padding: 20px 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .showing-info {
        font-size: 14px;
        color: #64748b;
    }

    /* Loading Overlay */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .loading-overlay.active {
        display: flex;
    }

    .spinner {
        border: 3px solid #f1f5f9;
        border-top: 3px solid var(--primary);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 24px;
    }

    .empty-icon {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.3;
    }

    .empty-title {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .empty-text {
        font-size: 14px;
        color: #64748b;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .orders-container {
            padding: 16px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .orders-table {
            font-size: 13px;
        }

        .orders-table thead th,
        .orders-table tbody td {
            padding: 12px 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="orders-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1>Orders Management</h1>
        <div class="header-actions">
            <button class="btn btn-success" id="exportBtn">
                <i class="fas fa-download"></i>
                Export CSV
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ number_format($stats['pending_orders']) }}</div>
        </div>
        <div class="stat-card info">
            <div class="stat-label">Processing</div>
            <div class="stat-value">{{ number_format($stats['processing_orders']) }}</div>
        </div>
        <div class="stat-card success">
            <div class="stat-label">Delivered</div>  {{-- was "Completed" --}}
            <div class="stat-value">{{ number_format($stats['completed_orders']) }}</div>
        </div>
        <div class="stat-card danger">
            <div class="stat-label">Cancelled</div>
            <div class="stat-value">{{ number_format($stats['cancelled_orders']) }}</div>
        </div>
        <div class="stat-card primary">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">£{{ number_format($stats['total_revenue'], 2) }}</div>
        </div>
        <div class="stat-card success">
            <div class="stat-label">Today Orders</div>
            <div class="stat-value">{{ number_format($stats['today_orders']) }}</div>
        </div>
        <div class="stat-card success">
            <div class="stat-label">Today Revenue</div>
            <div class="stat-value">£{{ number_format($stats['today_revenue'], 2) }}</div>  {{-- was $ --}}
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <div class="filters-header">
            <h3><i class="fas fa-filter"></i> Filters</h3>
            <button class="btn btn-secondary btn-sm" id="clearFilters">
                <i class="fas fa-times"></i> Clear All
            </button>
        </div>

        <form id="filterForm">
            <div class="filter-grid">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Order #, Name, Email, Phone...">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Payment Status</label>
                    <select name="payment_status" class="form-control">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-control">
                        <option value="">All Methods</option>
                        <option value="cashondelivery">Cash on Delivery</option>
                        <option value="stripe">Stripe</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date From</label>
                    <input type="date" name="date_from" class="form-control">
                </div>

                <div class="form-group">
                    <label>Date To</label>
                    <input type="date" name="date_to" class="form-control">
                </div>

                <div class="form-group">
                    <label>Min Amount</label>
                    <input type="number" name="min_amount" class="form-control" placeholder="0.00" step="0.01">
                </div>

                <div class="form-group">
                    <label>Max Amount</label>
                    <input type="number" name="max_amount" class="form-control" placeholder="0.00" step="0.01">
                </div>

                <div class="form-group">
                    <label>Customer</label>
                    <select name="customer_id" class="form-control">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at">Date</option>
                        <option value="order_number">Order Number</option>
                        <option value="customer_name">Customer Name</option>
                        <option value="total">Amount</option>
                        <option value="status">Status</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Order</label>
                    <select name="sort_order" class="form-control">
                        <option value="desc">Newest First</option>
                        <option value="asc">Oldest First</option>
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="table-card" style="position: relative;">
        <div class="loading-overlay" id="loadingOverlay">
            <div class="spinner"></div>
        </div>

        <div class="table-header">
            <div class="table-title">
                <span id="showingInfo">Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders</span>
            </div>
            <div class="bulk-actions" id="bulkActions">
                <span id="selectedCount">0 selected</span>
                <button class="btn btn-danger btn-sm" id="bulkDeleteBtn">
                    <i class="fas fa-trash"></i> Delete Selected
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th width="40">
                            <input type="checkbox" class="order-checkbox" id="selectAll">
                        </th>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    @include('admin.orders.partials.table-rows', ['orders' => $orders])
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div class="showing-info" id="paginationInfo">
                Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} results
            </div>
            <div id="paginationLinks">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let selectedOrders = new Set();

    // Filter Form Submit
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        loadOrders(1);
    });

    // Clear Filters
    $('#clearFilters').on('click', function() {
        $('#filterForm')[0].reset();
        loadOrders(1);
    });

    // Load Orders with AJAX
    function loadOrders(page = 1) {
        $('#loadingOverlay').addClass('active');

        const formData = $('#filterForm').serialize();
        const url = `{{ route('admin.orders.index') }}?page=${page}&${formData}`;

        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#ordersTableBody').html(response.html);
                $('#paginationLinks').html(response.pagination);

                const showing = response.showing;
                $('#showingInfo').text(`Showing ${showing.from} to ${showing.to} of ${showing.total} orders`);
                $('#paginationInfo').text(`Showing ${showing.from} to ${showing.to} of ${showing.total} results`);

                selectedOrders.clear();
                updateBulkActions();
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to load orders', 'error');
            },
            complete: function() {
                $('#loadingOverlay').removeClass('active');
            }
        });
    }

    // Pagination Click
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const page = new URL(url).searchParams.get('page');
        loadOrders(page);
    });

    // Select All Checkbox
    $('#selectAll').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.order-checkbox-item').prop('checked', isChecked);

        selectedOrders.clear();
        if (isChecked) {
            $('.order-checkbox-item').each(function() {
                selectedOrders.add($(this).data('order-id'));
            });
        }
        updateBulkActions();
    });

    // Individual Checkbox
    $(document).on('change', '.order-checkbox-item', function() {
        const orderId = $(this).data('order-id');
        if ($(this).is(':checked')) {
            selectedOrders.add(orderId);
        } else {
            selectedOrders.delete(orderId);
            $('#selectAll').prop('checked', false);
        }
        updateBulkActions();
    });

    // Update Bulk Actions Visibility
    function updateBulkActions() {
        const count = selectedOrders.size;
        if (count > 0) {
            $('#bulkActions').addClass('active');
            $('#selectedCount').text(`${count} selected`);
        } else {
            $('#bulkActions').removeClass('active');
        }
    }

    // Bulk Delete
    $('#bulkDeleteBtn').on('click', function() {
        if (selectedOrders.size === 0) return;

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${selectedOrders.size} order(s)`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete them!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.orders.bulk-delete") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order_ids: Array.from(selectedOrders)
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success');
                            selectedOrders.clear();
                            loadOrders(1);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to delete orders', 'error');
                    }
                });
            }
        });
    });

    // Delete Single Order
    $(document).on('click', '.delete-order', function(e) {
        e.preventDefault();
        const orderId = $(this).data('order-id');
        const orderNumber = $(this).data('order-number');

        Swal.fire({
            title: 'Are you sure?',
            text: `Delete order ${orderNumber}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/orders/${orderId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success');
                            loadOrders(1);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to delete order', 'error');
                    }
                });
            }
        });
    });

    // Export Orders
    $('#exportBtn').on('click', function() {
        const formData = $('#filterForm').serialize();
        window.location.href = `{{ route('admin.orders.export') }}?${formData}`;
    });
});
</script>
@endpush
