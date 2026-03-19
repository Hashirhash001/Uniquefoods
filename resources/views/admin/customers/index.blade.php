@extends('admin.layouts.app')

@section('title', 'Customers')

@push('styles')
<style>
    .stat-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.25rem;
    }
    .stat-card .stat-value { font-size: 24px; font-weight: 700; color: #111827; }
    .stat-card .stat-label { font-size: 12px; color: #6b7280; margin-top: 4px; }
    .stat-card .stat-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }

    .customers-table-wrap {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }

    .customers-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    .table-scroll-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .customers-table th {
        background: #f9fafb;
        padding: 11px 14px;
        font-size: 12px;
        font-weight: 700;
        color: #6b7280;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .customers-table td {
        padding: 13px 14px;
        font-size: 13px;
        color: #374151;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .customers-table tr:last-child td { border-bottom: none; }
    .customers-table tr:hover td { background: #fafafa; }

    .avatar-circle {
        width: 38px; height: 38px; border-radius: 50%;
        background: #08437b; color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px; flex-shrink: 0;
    }

    .customer-name { font-weight: 600; color: #111827; font-size: 13px; }
    .customer-phone { font-size: 11px; color: #9ca3af; }
    .customer-email {
        color: #374151;
        word-break: break-all;
        font-size: 12px;
    }

    .badge-status { padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap; }
    .badge-status.verified   { background: #d1fae5; color: #065f46; }
    .badge-status.unverified { background: #f3f4f6; color: #6b7280; }

    .sort-bar { display: flex; gap: 6px; flex-wrap: wrap; }
    .sort-btn {
        padding: 7px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #e5e7eb;
        background: white;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .sort-btn:hover, .sort-btn.active {
        background: #08437b;
        color: white;
        border-color: #08437b;
    }

    .view-btn {
        padding: 5px 13px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        background: #08437b;
        color: white;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .view-btn:hover { background: #0f508d; color: white; }

    /* Pagination */
    #pagination-wrap .pagination {
        display: flex; flex-wrap: wrap; gap: 4px; justify-content: center;
        list-style: none; padding: 0; margin: 0;
    }
    #pagination-wrap .page-item .page-link {
        padding: 7px 13px; border-radius: 6px;
        font-size: 13px; font-weight: 500;
        border: 1px solid #e5e7eb;
        color: #374151; background: white;
        text-decoration: none; cursor: pointer;
    }
    #pagination-wrap .page-item.active .page-link {
        background: #08437b; color: white; border-color: #08437b;
    }
    #pagination-wrap .page-item.disabled .page-link {
        opacity: 0.4; cursor: not-allowed;
    }

    /* Loading overlay */
    #table-loading {
        display: none;
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.7);
        z-index: 10;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }
    .table-container { position: relative; }

    @media (max-width: 767px) {
        .stat-card .stat-value { font-size: 20px; }
        .page-header-row { flex-direction: column; align-items: flex-start !important; gap: 4px; }
        .customers-table th, .customers-table td { padding: 10px 10px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row gap-2 mb-4 page-header-row">
        <div>
            <h4 style="font-size:20px;font-weight:700;color:#111827;margin:0;">Customers</h4>
            <p style="color:#6b7280;font-size:13px;margin:4px 0 0;">Users who have placed at least one order</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#dbeafe;"><i class="fas fa-users" style="color:#1d4ed8;"></i></div>
                <div>
                    <div class="stat-value">{{ number_format($totalCustomers) }}</div>
                    <div class="stat-label">Total Customers</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#d1fae5;"><i class="fas fa-sterling-sign" style="color:#065f46;"></i></div>
                <div>
                    <div class="stat-value">£{{ number_format($totalRevenue, 2) }}</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fef3c7;"><i class="fas fa-receipt" style="color:#92400e;"></i></div>
                <div>
                    <div class="stat-value">£{{ number_format($avgOrderValue, 2) }}</div>
                    <div class="stat-label">Avg Order Value</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sort + Count Bar --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div class="sort-bar" id="sort-bar">
            <span style="font-size:12px;font-weight:600;color:#9ca3af;align-self:center;">Sort:</span>
            <button class="sort-btn active" data-sort="latest">Newest</button>
            <button class="sort-btn" data-sort="most_orders">Most Orders</button>
            <button class="sort-btn" data-sort="most_spent">Most Spent</button>
            <button class="sort-btn" data-sort="name">Name A–Z</button>
        </div>
        <span id="result-count" style="font-size:12px;color:#9ca3af;"></span>
    </div>

    {{-- Table --}}
    <div class="table-container customers-table-wrap">
        <div id="table-loading" style="display:none;position:absolute;inset:0;background:rgba(255,255,255,0.75);z-index:10;align-items:center;justify-content:center;border-radius:10px;">
            <div class="spinner-border text-primary" style="color:#08437b!important;"></div>
        </div>
        <div class="table-scroll-wrap">
            <table class="customers-table" id="customers-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th>Orders</th>
                        <th>Total Spent</th>
                        <th>Last Order</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="customers-tbody">
                    <tr><td colspan="8" class="text-center py-4"><div class="spinner-border spinner-border-sm" style="color:#08437b;"></div></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div id="pagination-wrap" class="mt-3 d-flex justify-content-center"></div>

</div>
@endsection

@push('scripts')
<script>
let currentSort = 'latest';
let currentPage = 1;

function loadCustomers(sort, page) {
    currentSort = sort;
    currentPage = page;

    // Update sort button active states
    $('#sort-bar .sort-btn').removeClass('active');
    $(`#sort-bar .sort-btn[data-sort="${sort}"]`).addClass('active');

    // Show loading
    document.getElementById('table-loading').style.display = 'flex';

    $.ajax({
        url: '{{ route("admin.customers.index") }}',
        type: 'GET',
        data: { sort: sort, page: page, ajax: 1 },
        success: function(res) {
            $('#customers-tbody').html(res.html);
            $('#pagination-wrap').html(res.pagination);
            $('#result-count').text(res.total + ' customers found');
            document.getElementById('table-loading').style.display = 'none';
        },
        error: function() {
            document.getElementById('table-loading').style.display = 'none';
            $('#customers-tbody').html('<tr><td colspan="8" class="text-center py-4 text-danger">Failed to load. Please try again.</td></tr>');
        }
    });
}

$(document).ready(function() {
    // Initial load
    loadCustomers('latest', 1);

    // Sort buttons
    $(document).on('click', '#sort-bar .sort-btn', function() {
        loadCustomers($(this).data('sort'), 1);
    });

    // Pagination clicks
    $(document).on('click', '#pagination-wrap .page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) loadCustomers(currentSort, page);
    });
});
</script>
@endpush
