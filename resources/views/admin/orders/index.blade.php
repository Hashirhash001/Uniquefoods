@extends('admin.layouts.app')
@section('title', 'Orders Management')

@push('styles')
<style>
    .ord-wrap { padding: 20px; max-width: 1800px; margin: 0 auto; }

    /* ── Header ── */
    .ord-header { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:20px; }
    .ord-header h1 { font-size:22px; font-weight:700; color:#111827; margin:0; }
    .ord-header p  { font-size:13px; color:#9ca3af; margin:3px 0 0; }

    /* ── Stat Cards ── */
    .o-stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:12px; margin-bottom:20px; }
    .o-stat {
        background:white; border:1px solid #e5e7eb; border-radius:10px;
        padding:14px 16px; cursor:pointer; transition:all 0.2s;
        text-decoration:none; display:block; position:relative; overflow:hidden;
    }
    .o-stat:hover { box-shadow:0 6px 20px rgba(0,0,0,0.08); transform:translateY(-2px); }
    .o-stat.active-filter { border-color:#08437b; box-shadow:0 0 0 2px rgba(8,67,123,0.15); }
    .o-stat::after { content:''; position:absolute; bottom:0; left:0; right:0; height:3px; background:var(--oc,#e5e7eb); }
    .o-stat-label { font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:5px; }
    .o-stat-val   { font-size:22px; font-weight:800; color:#111827; line-height:1; }
    .o-stat-sub   { font-size:11px; color:#9ca3af; margin-top:4px; }

    /* ── Filters ── */
    .o-filters { background:white; border:1px solid #e5e7eb; border-radius:10px; padding:18px; margin-bottom:20px; }
    .o-filter-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; }
    .o-filter-head h3 { font-size:14px; font-weight:700; color:#111827; margin:0; display:flex; align-items:center; gap:6px; }
    .o-filter-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:12px; }
    .o-fg { display:flex; flex-direction:column; gap:4px; }
    .o-fg label { font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em; }
    .o-fg .form-control, .o-fg .form-select {
        height:38px; border:1px solid #d1d5db; border-radius:7px;
        padding:0 10px; font-size:13px; background:white;
    }
    .o-fg .form-control:focus, .o-fg .form-select:focus {
        border-color:#08437b; box-shadow:0 0 0 3px rgba(8,67,123,0.08); outline:none;
    }

    /* ── Buttons ── */
    .o-btn {
        display:inline-flex; align-items:center; gap:6px;
        padding:8px 16px; border-radius:7px; font-size:13px; font-weight:600;
        border:none; cursor:pointer; transition:all 0.2s; text-decoration:none; white-space:nowrap;
    }
    .o-btn:hover { opacity:0.88; transform:translateY(-1px); }
    .o-btn-primary  { background:#08437b; color:white; }
    .o-btn-green    { background:#10b981; color:white; }
    .o-btn-red      { background:#ef4444; color:white; }
    .o-btn-ghost    { background:#f3f4f6; color:#374151; border:1px solid #e5e7eb; }
    .o-btn-ghost:hover { background:#e5e7eb; opacity:1; transform:none; }
    .o-btn-sm { padding:6px 12px; font-size:12px; }

    /* ── Table Card ── */
    .o-card { background:white; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; position:relative; }
    .o-card-head {
        padding:14px 18px; border-bottom:1px solid #e5e7eb;
        display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;
    }
    .o-card-head-left  { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .o-card-head-right { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .showing-txt { font-size:13px; color:#6b7280; }
    .bulk-bar { display:none; align-items:center; gap:8px; }
    .bulk-bar.show { display:flex; }

    /* ── Table ── */
    .o-table { width:100%; border-collapse:collapse; font-size:13px; min-width:700px; }
    .o-table thead th {
        background:#f9fafb; padding:10px 14px;
        font-size:11px; font-weight:700; color:#9ca3af;
        text-transform:uppercase; letter-spacing:0.04em;
        border-bottom:1px solid #e5e7eb; white-space:nowrap; text-align:left;
    }
    .o-table tbody td { padding:12px 14px; border-bottom:1px solid #f3f4f6; vertical-align:middle; }
    .o-table tbody tr:last-child td { border-bottom:none; }
    .o-table tbody tr:hover td { background:#fafafa; }

    /* ── Badges ── */
    .ob { padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; text-transform:capitalize; white-space:nowrap; }
    .ob-pending    { background:#fef3c7; color:#92400e; }
    .ob-processing { background:#dbeafe; color:#1e40af; }
    .ob-shipped    { background:#ede9fe; color:#5b21b6; }
    .ob-delivered  { background:#d1fae5; color:#065f46; }
    .ob-completed  { background:#d1fae5; color:#065f46; }
    .ob-cancelled  { background:#fee2e2; color:#991b1b; }
    .ob-paid       { background:#d1fae5; color:#065f46; }
    .ob-unpaid     { background:#fef3c7; color:#92400e; }
    .ob-failed     { background:#fee2e2; color:#991b1b; }
    .ob-refunded   { background:#f3e8ff; color:#6d28d9; }

    /* ── Action buttons ── */
    .ab { width:30px; height:30px; border-radius:6px; border:1px solid #e5e7eb; background:white; display:inline-flex; align-items:center; justify-content:center; color:#6b7280; cursor:pointer; transition:all 0.15s; text-decoration:none; font-size:13px; }
    .ab:hover        { border-color:#08437b; color:#08437b; background:#eff6ff; }
    .ab.ab-red:hover { border-color:#ef4444; color:#ef4444; background:#fee2e2; }
    .ab.ab-grn:hover { border-color:#10b981; color:#10b981; background:#d1fae5; }

    /* ── Pagination ── */
    .o-card-foot {
        padding:14px 18px; border-top:1px solid #e5e7eb;
        display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;
    }
    .pagination .page-link { border-radius:6px; font-size:13px; color:#374151; border:1px solid #e5e7eb; padding:5px 10px; margin:0 2px; }
    .pagination .page-item.active .page-link { background:#08437b; border-color:#08437b; color:white; }

    /* ── Spinner ── */
    .o-spinner { position:absolute; inset:0; background:rgba(255,255,255,0.75); display:none; align-items:center; justify-content:center; z-index:10; border-radius:10px; }
    .o-spinner.show { display:flex; }
    .spin-r { width:38px; height:38px; border:3px solid #e5e7eb; border-top-color:#08437b; border-radius:50%; animation:spn 0.7s linear infinite; }
    @keyframes spn { to { transform:rotate(360deg); } }

    /* ── Empty ── */
    .o-empty { text-align:center; padding:48px 16px; color:#9ca3af; }
    .o-empty i { font-size:36px; opacity:0.15; display:block; margin-bottom:10px; }
    .o-empty h4 { font-size:15px; font-weight:600; color:#374151; margin-bottom:4px; }

    /* ── Active filters bar ── */
    .active-filters-bar { display:flex; flex-wrap:wrap; gap:6px; margin-bottom:14px; }
    .af-tag { display:inline-flex; align-items:center; gap:5px; background:#eff6ff; border:1px solid #bfdbfe; color:#1e40af; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600; }
    .af-tag button { background:none; border:none; cursor:pointer; color:#1e40af; padding:0; font-size:12px; line-height:1; }

    /* ── Responsive ── */
    @media(max-width:991px) { .o-stats-grid { grid-template-columns:repeat(3,1fr); } }
    @media(max-width:767px) {
        .ord-wrap { padding:12px; }
        .o-stats-grid { grid-template-columns:repeat(2,1fr); }
        .o-filter-grid { grid-template-columns:1fr 1fr; }
        .o-stat-val { font-size:18px; }
        .ord-header h1 { font-size:18px; }
    }
    @media(max-width:480px) {
        .o-stats-grid { grid-template-columns:1fr 1fr; gap:8px; }
        .o-filter-grid { grid-template-columns:1fr; }
    }
</style>
@endpush

@section('content')
<div class="ord-wrap">

    {{-- Header --}}
    <div class="ord-header">
        <div>
            <h1>Orders</h1>
            <p>Manage and track all customer orders</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <button class="o-btn o-btn-green" id="exportBtn">
                <i class="fas fa-file-csv"></i> Export CSV
            </button>
        </div>
    </div>

    {{-- ── Stat Cards (clickable → filter) ── --}}
    <div class="o-stats-grid">
        <a href="javascript:void(0)" class="o-stat" style="--oc:#3b82f6;" data-filter-status="" title="All orders">
            <div class="o-stat-label">All Orders</div>
            <div class="o-stat-val">{{ number_format($stats['total_orders']) }}</div>
        </a>
        <a href="javascript:void(0)" class="o-stat" style="--oc:#f59e0b;" data-filter-status="pending">
            <div class="o-stat-label">Pending</div>
            <div class="o-stat-val">{{ number_format($stats['pending_orders']) }}</div>
        </a>
        <a href="javascript:void(0)" class="o-stat" style="--oc:#3b82f6;" data-filter-status="processing">
            <div class="o-stat-label">Processing</div>
            <div class="o-stat-val">{{ number_format($stats['processing_orders']) }}</div>
        </a>
        <a href="javascript:void(0)" class="o-stat" style="--oc:#8b5cf6;" data-filter-status="shipped">
            <div class="o-stat-label">Shipped</div>
            <div class="o-stat-val">{{ number_format($stats['shipped_orders']) }}</div>
        </a>
        <a href="javascript:void(0)" class="o-stat" style="--oc:#10b981;" data-filter-status="delivered">
            <div class="o-stat-label">Delivered</div>
            <div class="o-stat-val">{{ number_format($stats['delivered_orders']) }}</div>
        </a>
        <a href="javascript:void(0)" class="o-stat" style="--oc:#ef4444;" data-filter-status="cancelled">
            <div class="o-stat-label">Cancelled</div>
            <div class="o-stat-val">{{ number_format($stats['cancelled_orders']) }}</div>
        </a>
        <div class="o-stat" style="--oc:#08437b;cursor:default;">
            <div class="o-stat-label">Total Revenue</div>
            <div class="o-stat-val" style="font-size:18px;">£{{ number_format($stats['total_revenue'],2) }}</div>
        </div>
        <div class="o-stat" style="--oc:#059669;cursor:default;">
            <div class="o-stat-label">Today Revenue</div>
            <div class="o-stat-val" style="font-size:18px;">£{{ number_format($stats['today_revenue'],2) }}</div>
            <div class="o-stat-sub">{{ $stats['today_orders'] }} orders today</div>
        </div>
    </div>

    {{-- ── Filters ── --}}
    <div class="o-filters">
        <div class="o-filter-head">
            <h3><i class="fas fa-filter" style="color:#08437b;"></i> Filters
                <span id="activeFilterCount" style="display:none;background:#08437b;color:white;padding:1px 8px;border-radius:20px;font-size:11px;"></span>
            </h3>
            <button class="o-btn o-btn-ghost o-btn-sm" id="clearFilters">
                <i class="fas fa-times"></i> Clear All
            </button>
        </div>

        {{-- Active filter tags --}}
        <div class="active-filters-bar" id="activeFilterTags"></div>

        <div class="o-filter-grid">
            <div class="o-fg">
                <label><i class="fas fa-search" style="color:#9ca3af;"></i> Search</label>
                <input type="text" id="f-search" class="form-control" placeholder="Order #, name, email...">
            </div>
            <div class="o-fg">
                <label>Delivery Status</label>
                <select id="f-status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="o-fg">
                <label>Payment Status</label>
                <select id="f-payment_status" class="form-select">
                    <option value="">All</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="failed">Failed</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
            <div class="o-fg">
                <label>Payment Method</label>
                <select id="f-payment_method" class="form-select">
                    <option value="">All Methods</option>
                    <option value="cashondelivery">Cash on Delivery</option>
                    <option value="stripe">Stripe</option>
                </select>
            </div>
            <div class="o-fg">
                <label>Date From</label>
                <input type="date" id="f-date_from" class="form-control">
            </div>
            <div class="o-fg">
                <label>Date To</label>
                <input type="date" id="f-date_to" class="form-control">
            </div>
            <div class="o-fg">
                <label>Min Amount</label>
                <input type="number" id="f-min_amount" class="form-control" placeholder="0.00" step="0.01">
            </div>
            <div class="o-fg">
                <label>Max Amount</label>
                <input type="number" id="f-max_amount" class="form-control" placeholder="0.00" step="0.01">
            </div>
            <div class="o-fg">
                <label>Customer</label>
                <select id="f-customer_id" class="form-select">
                    <option value="">All Customers</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="o-fg">
                <label>Sort By</label>
                <select id="f-sort_by" class="form-select">
                    <option value="created_at">Date</option>
                    <option value="order_number">Order Number</option>
                    <option value="total">Amount</option>
                    <option value="status">Status</option>
                </select>
            </div>
            <div class="o-fg">
                <label>Direction</label>
                <select id="f-sort_order" class="form-select">
                    <option value="desc">Newest First</option>
                    <option value="asc">Oldest First</option>
                </select>
            </div>
        </div>
    </div>

    {{-- ── Table ── --}}
    <div class="o-card">
        <div class="o-spinner" id="ordSpinner"><div class="spin-r"></div></div>

        <div class="o-card-head">
            <div class="o-card-head-left">
                {{-- Select All --}}
                <div style="display:flex;align-items:center;gap:10px;">
                    <input type="checkbox" id="selectAll" title="Select all"
                        style="width:16px;height:16px;cursor:pointer;accent-color:#08437b;">
                    <label for="selectAll"
                        style="font-size:12px;color:#9ca3af;cursor:pointer;margin:0;">
                        Select all
                    </label>
                </div>

                {{-- Bulk delete (shown when items selected) --}}
                <div class="bulk-bar" id="bulkBar">
                    <span id="bulkCount" style="font-size:13px;color:#6b7280;"></span>
                    <button class="o-btn o-btn-red o-btn-sm" id="bulkDeleteBtn">
                        <i class="fas fa-trash"></i> Delete Selected
                    </button>
                </div>

                {{-- Showing info --}}
                <span class="showing-txt" id="showingTxt">
                    Showing {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders
                </span>
            </div>

            {{-- Right side empty or reserved for future actions --}}
            <div class="o-card-head-right"></div>
        </div>

        <div style="overflow-x:auto;">
            <table class="o-table">
                <thead>
                    <tr>
                        <th width="36"></th>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Delivery</th>
                        <th>Date</th>
                        <th width="110">Actions</th>
                    </tr>
                </thead>
                <tbody id="ordersBody">
                    @include('admin.orders.partials.table-rows', ['orders' => $orders])
                </tbody>
            </table>
        </div>

        <div class="o-card-foot">
            <span class="showing-txt" id="footTxt">
                {{ $orders->total() }} total orders
            </span>
            <div id="paginationWrap">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(function() {
    let selectedOrders = new Set();

    // ─── Auto-apply URL filters (from dashboard deep links) ─────────────
    (function() {
        const p = new URLSearchParams(window.location.search);
        if (p.get('status'))         { $('#f-status').val(p.get('status')); }
        if (p.get('payment_status')) { $('#f-payment_status').val(p.get('payment_status')); }
        if (p.get('payment_method')) { $('#f-payment_method').val(p.get('payment_method')); }
        if (p.get('search'))         { $('#f-search').val(p.get('search')); }
        if (p.get('date_from'))      { $('#f-date_from').val(p.get('date_from')); }
        if (p.get('date_to'))        { $('#f-date_to').val(p.get('date_to')); }
        if (p.get('customer_id'))    { $('#f-customer_id').val(p.get('customer_id')); }

        // Highlight matching stat card
        const st = p.get('status');
        if (st !== null) {
            $(`[data-filter-status="${st}"]`).addClass('active-filter');
        }

        if ([...p.keys()].some(k => k !== 'page')) loadOrders(1);
        else updateFilterTags();
    })();

    // ─── Stat card click → set status filter ────────────────────────────
    $(document).on('click', '[data-filter-status]', function() {
        const status = $(this).data('filter-status');
        $('#f-status').val(status);
        $('[data-filter-status]').removeClass('active-filter');
        $(this).addClass('active-filter');
        loadOrders(1);
        $('html,body').animate({ scrollTop: $('.o-card').offset().top - 20 }, 300);
    });

    // ─── Filter triggers ────────────────────────────────────────────────
    let searchTimer;
    $('#f-search').on('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => loadOrders(1), 450);
    });

    $('#f-status, #f-payment_status, #f-payment_method, #f-customer_id, #f-sort_by, #f-sort_order').on('change', () => loadOrders(1));
    $('#f-date_from, #f-date_to, #f-min_amount, #f-max_amount').on('change', () => loadOrders(1));

    // ─── Clear filters ───────────────────────────────────────────────────
    $('#clearFilters').on('click', function() {
        $('#f-search').val('');
        $('#f-status, #f-payment_status, #f-payment_method, #f-customer_id').val('');
        $('#f-date_from, #f-date_to, #f-min_amount, #f-max_amount').val('');
        $('#f-sort_by').val('created_at');
        $('#f-sort_order').val('desc');
        $('[data-filter-status]').removeClass('active-filter');
        loadOrders(1);
    });

    // ─── Load orders ─────────────────────────────────────────────────────
    function loadOrders(page = 1) {
        $('#ordSpinner').addClass('show');
        updateFilterTags();

        $.ajax({
            url: '{{ route("admin.orders.index") }}',
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            data: {
                page,
                search:         $('#f-search').val(),
                status:         $('#f-status').val(),
                payment_status: $('#f-payment_status').val(),
                payment_method: $('#f-payment_method').val(),
                date_from:      $('#f-date_from').val(),
                date_to:        $('#f-date_to').val(),
                min_amount:     $('#f-min_amount').val(),
                max_amount:     $('#f-max_amount').val(),
                customer_id:    $('#f-customer_id').val(),
                sort_by:        $('#f-sort_by').val(),
                sort_order:     $('#f-sort_order').val(),
            },
            success: function(r) {
                $('#ordersBody').html(r.html);
                $('#paginationWrap').html(r.pagination);
                const s = r.showing;
                $('#showingTxt').text(`Showing ${s.from}–${s.to} of ${s.total} orders`);
                $('#footTxt').text(`${s.total} total orders`);
                selectedOrders.clear();
                updateBulkBar();
                $('#selectAll').prop('checked', false);
            },
            error: function() {
                Swal.fire({ icon:'error', title:'Error', text:'Failed to load orders', confirmButtonColor:'#08437b' });
            },
            complete: function() { $('#ordSpinner').removeClass('show'); }
        });
    }

    // ─── Pagination ───────────────────────────────────────────────────────
    $(document).on('click', '#paginationWrap .pagination a', function(e) {
        e.preventDefault();
        const page = new URL($(this).attr('href'), location.origin).searchParams.get('page') || 1;
        loadOrders(page);
        $('html,body').animate({ scrollTop: $('.o-card').offset().top - 20 }, 200);
    });

    // ─── Checkboxes ───────────────────────────────────────────────────────
    $('#selectAll').on('change', function() {
        const checked = $(this).is(':checked');
        $('.ord-cb').prop('checked', checked);
        selectedOrders.clear();
        if (checked) $('.ord-cb').each(function() { selectedOrders.add(+$(this).val()); });
        updateBulkBar();
    });

    $(document).on('change', '.ord-cb', function() {
        const id = +$(this).val();
        $(this).is(':checked') ? selectedOrders.add(id) : selectedOrders.delete(id);
        $('#selectAll').prop('checked', $('.ord-cb').length === selectedOrders.size && selectedOrders.size > 0);
        updateBulkBar();
    });

    function updateBulkBar() {
        const n = selectedOrders.size;
        if (n > 0) { $('#bulkBar').addClass('show'); $('#bulkCount').text(n + ' selected'); }
        else       { $('#bulkBar').removeClass('show'); }
    }

    // ─── Active filter tags ───────────────────────────────────────────────
    function updateFilterTags() {
        const filters = [
            { id:'f-search',         label:'Search' },
            { id:'f-status',         label:'Status' },
            { id:'f-payment_status', label:'Payment Status' },
            { id:'f-payment_method', label:'Method' },
            { id:'f-date_from',      label:'From' },
            { id:'f-date_to',        label:'To' },
            { id:'f-min_amount',     label:'Min £' },
            { id:'f-max_amount',     label:'Max £' },
        ];

        let html = '';
        let count = 0;
        filters.forEach(f => {
            const v = $('#' + f.id).val();
            if (v) {
                count++;
                html += `<span class="af-tag">${f.label}: <strong>${v}</strong>
                    <button data-clear="${f.id}" title="Remove">×</button></span>`;
            }
        });
        $('#activeFilterTags').html(html);
        if (count > 0) $('#activeFilterCount').text(count).show();
        else           $('#activeFilterCount').hide();
    }

    $(document).on('click', '.af-tag button', function() {
        const fid = $(this).data('clear');
        $('#' + fid).val('');
        if (fid === 'f-status') $('[data-filter-status]').removeClass('active-filter');
        loadOrders(1);
    });

    // ─── Bulk delete ──────────────────────────────────────────────────────
    $('#bulkDeleteBtn').on('click', function() {
        if (!selectedOrders.size) return;

        Swal.fire({
            title: `Delete ${selectedOrders.size} order(s)?`,
            html: `<p style="color:#6b7280;font-size:14px;">This will permanently delete the selected orders and restore product stock where applicable.</p>
                   <p style="margin-top:10px;"><strong>Type <span style="color:#ef4444;">DELETE</span> to confirm:</strong></p>`,
            input: 'text',
            inputPlaceholder: 'Type DELETE',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Delete Orders',
            preConfirm: (val) => {
                if (val !== 'DELETE') { Swal.showValidationMessage('Please type DELETE to confirm'); }
            }
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.orders.bulk-delete") }}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}', order_ids: [...selectedOrders] },
                    success: function(r) {
                        if (r.success) {
                            Swal.fire({ icon:'success', title:'Deleted', text:r.message, confirmButtonColor:'#08437b', timer:2000 });
                            selectedOrders.clear();
                            loadOrders(1);
                        }
                    },
                    error: () => Swal.fire({ icon:'error', title:'Error', text:'Failed to delete', confirmButtonColor:'#08437b' })
                });
            }
        });
    });

    // ─── Single delete ────────────────────────────────────────────────────
    $(document).on('click', '.del-order', function() {
        const id  = $(this).data('id');
        const num = $(this).data('num');

        Swal.fire({
            title: `Delete Order ${num}?`,
            html: `<p style="color:#6b7280;font-size:14px;">This cannot be undone. Stock will be restored automatically.</p>
                   <p style="margin-top:10px;"><strong>Type <span style="color:#ef4444;">DELETE</span> to confirm:</strong></p>`,
            input: 'text',
            inputPlaceholder: 'Type DELETE',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete It',
            preConfirm: (val) => {
                if (val !== 'DELETE') Swal.showValidationMessage('Please type DELETE to confirm');
            }
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/orders/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(r) {
                        if (r.success) {
                            Swal.fire({ icon:'success', title:'Deleted!', text:r.message, confirmButtonColor:'#08437b', timer:1800 });
                            loadOrders(1);
                        }
                    },
                    error: () => Swal.fire({ icon:'error', title:'Error', text:'Delete failed', confirmButtonColor:'#08437b' })
                });
            }
        });
    });

    // ─── Export ───────────────────────────────────────────────────────────
    $('#exportBtn').on('click', function() {
        const params = new URLSearchParams({
            search:         $('#f-search').val(),
            status:         $('#f-status').val(),
            payment_status: $('#f-payment_status').val(),
            date_from:      $('#f-date_from').val(),
            date_to:        $('#f-date_to').val(),
        });
        window.location.href = '{{ route("admin.orders.export") }}?' + params.toString();
    });
});
</script>
@endpush
