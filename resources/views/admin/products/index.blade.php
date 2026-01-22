@extends('admin.layouts.app')

@section('title','Products')

@push('styles')
<style>
/* Form Check Fixes */
.form-check-input::before,
.form-check-input::after {
    display: none !important;
}

.form-check-label::before,
.form-check-label::after {
    display: none !important;
}

/* Header */
.page-header-section {
    margin-bottom: 1.5rem;
}

.title-right-actioin-btn-wrapper-product-list {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.title-right-actioin-btn-wrapper-product-list h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
    color: #111827;
}

.page-subtitle {
    font-size: 14px;
    color: #6b7280;
    margin-top: 0.25rem;
}

.rts-btn.btn-primary {
    background: #22c55e;
    color: white;
    padding: 0.625rem 1.25rem;
    border-radius: 6px;
    font-weight: 500;
    font-size: 14px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
    border: none;
    white-space: nowrap;
}

.rts-btn.btn-primary:hover {
    background: #16a34a;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.25);
    color: white;
}

.rts-btn.btn-primary i {
    font-size: 14px;
}

/* Improved Filters Card */
.filters-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.filters-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.filters-title-wrapper {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.filters-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filters-title i {
    color: #22c55e;
    font-size: 18px;
}

.active-filters-count {
    background: #22c55e;
    color: white;
    padding: 0.25rem 0.625rem;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    min-width: 24px;
    text-align: center;
}

.clear-filters {
    font-size: 13px;
    color: #ef4444;
    text-decoration: none;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-weight: 500;
}

.clear-filters:hover {
    color: white;
    background: #ef4444;
}

.clear-filters i {
    font-size: 13px;
}

/* Filter Groups */
.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group-label {
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.filter-group-label i {
    font-size: 11px;
    color: #9ca3af;
}

.form-control, .form-select {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 0.625rem 0.875rem;
    font-size: 14px;
    height: 42px;
    transition: all 0.2s;
    background: white;
}

.form-control:focus, .form-select:focus {
    border-color: #22c55e;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    outline: none;
    background: white;
}

.form-control::placeholder {
    color: #9ca3af;
    font-size: 13px;
}

.input-icon {
    position: relative;
}

.input-icon i {
    position: absolute;
    left: 0.875rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 14px;
    pointer-events: none;
}

.input-icon .form-control {
    padding-left: 2.5rem;
}

/* Price Range Inputs */
.price-range-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.price-range-group .form-control {
    flex: 1;
}

.price-separator {
    color: #9ca3af;
    font-weight: 500;
    font-size: 14px;
}

/* Table Card */
.table-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.table-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    background: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.results-count {
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
}

.table-responsive {
    overflow-x: auto;
}

.products-table {
    width: 100%;
    margin: 0;
    border-collapse: collapse;
}

.products-table thead {
    background: #f9fafb;
}

.products-table th {
    padding: 0.875rem 1rem;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    border-bottom: 2px solid #e5e7eb;
}

.products-table th.sortable {
    cursor: pointer;
    user-select: none;
    transition: all 0.2s;
    position: relative;
    padding-right: 2rem;
}

.products-table th.sortable:hover {
    color: #22c55e;
    background: #f0fdf4;
}

.products-table th.sortable .sort-icon {
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    flex-direction: column;
    gap: 0;
    opacity: 0.3;
}

.products-table th.sortable .sort-icon i {
    font-size: 8px;
    line-height: 1;
}

.products-table th.sortable.active {
    color: #22c55e;
    background: #f0fdf4;
}

.products-table th.sortable.active .sort-icon {
    opacity: 1;
}

.products-table th.sortable.asc .sort-icon .fa-caret-up {
    color: #22c55e;
}

.products-table th.sortable.desc .sort-icon .fa-caret-down {
    color: #22c55e;
}

.products-table tbody tr {
    transition: background 0.2s;
}

.products-table tbody tr:hover {
    background: #f9fafb;
}

.products-table td {
    padding: 0.875rem 1rem;
    font-size: 14px;
    color: #111827;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: middle;
}

.product-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.no-image {
    width: 50px;
    height: 50px;
    background: #f3f4f6;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #9ca3af;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.product-details {
    flex: 1;
}

.product-name {
    font-weight: 500;
    color: #111827;
    margin-bottom: 0.25rem;
    line-height: 1.3;
}

.product-sku {
    font-size: 12px;
    color: #6b7280;
}

.badge {
    padding: 0.25rem 0.625rem;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.badge-success {
    background: #d1fae5;
    color: #065f46;
}

.badge-danger {
    background: #fee2e2;
    color: #991b1b;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
}

.action-btns {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.btn-icon {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    background: white;
    color: #6b7280;
    transition: all 0.2s;
    cursor: pointer;
    font-size: 14px;
}

.btn-icon:hover {
    background: #dbeafe;
    color: #2563eb;
    border-color: #3b82f6;
    transform: translateY(-1px);
}

.btn-icon.btn-danger:hover {
    background: #fee2e2;
    color: #991b1b;
    border-color: #fecaca;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6b7280;
}

.empty-state i {
    font-size: 4rem;
    opacity: 0.2;
    margin-bottom: 1rem;
}

.empty-state h4 {
    font-size: 18px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.empty-state p {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
}

/* Green Pagination */
.pagination .page-link {
    color: #374151;
    border: 1px solid #e5e7eb;
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border-radius: 6px;
    font-size: 14px;
}

.pagination .page-link:hover {
    background: #f9fafb;
    color: #111827;
    border-color: #d1d5db;
}

.pagination .page-item.active .page-link {
    background: #22c55e;
    border-color: #22c55e;
    color: white;
    font-weight: 600;
}

.pagination .page-item.disabled .page-link {
    color: #9ca3af;
    background: #f9fafb;
    border-color: #e5e7eb;
}

/* Loading Overlay */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10;
    backdrop-filter: blur(2px);
}

.loading-overlay.active {
    display: flex;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #f3f4f6;
    border-top-color: #22c55e;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Mobile Responsive */
@media (max-width: 992px) {
    .products-table th,
    .products-table td {
        padding: 0.75rem 0.5rem;
        font-size: 13px;
    }

    .product-name {
        font-size: 13px;
    }

    .action-btns {
        flex-direction: column;
        gap: 0.25rem;
    }
}

@media (max-width: 768px) {
    .title-right-actioin-btn-wrapper-product-list {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .rts-btn.btn-primary {
        width: 100%;
        justify-content: center;
    }

    .filters-card {
        padding: 1rem;
    }

    .filters-header {
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .table-header {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }

    /* Mobile Table */
    .products-table thead {
        display: none;
    }

    .products-table tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        background: white;
    }

    .products-table td {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border: none;
        align-items: center;
    }

    .products-table td:before {
        content: attr(data-label);
        font-weight: 600;
        color: #6b7280;
        font-size: 12px;
        text-transform: uppercase;
    }

    .product-info {
        flex-direction: column;
        align-items: flex-start;
    }

    .action-btns {
        flex-direction: row;
        justify-content: flex-end;
        width: 100%;
    }

    .price-range-group {
        flex-direction: column;
        align-items: stretch;
    }

    .price-separator {
        text-align: center;
    }
}

@media (max-width: 576px) {
    .filters-title-wrapper {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .clear-filters {
        font-size: 12px;
    }

    .filter-group-label {
        font-size: 11px;
    }
}

</style>
@endpush

@section('content')
<div class="transection">
    <!-- Page Header -->
    <div class="page-header-section">
        <div class="title-right-actioin-btn-wrapper-product-list">
            <div>
                <h3 class="title">Product Management</h3>
                <p class="page-subtitle">Manage your product inventory</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="rts-btn btn-primary">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <div class="filters-header">
            <div class="filters-title-wrapper">
                <h5 class="filters-title">
                    <i class="fas fa-filter"></i> Filters
                </h5>
                <span class="active-filters-count" id="activeFiltersCount" style="display: none;">0</span>
            </div>
            <a href="#" class="clear-filters" id="clearFilters">
                <i class="fas fa-times-circle"></i> Clear All
            </a>
        </div>

        <div class="row g-3">
            <!-- Search -->
            <div class="col-lg-4 col-md-6">
                <div class="filter-group">
                    <label class="filter-group-label">
                        <i class="fas fa-search"></i> Search
                    </label>
                    <div class="input-icon">
                        <i class="fas fa-search"></i>
                        <input type="text" id="search" class="form-control"
                               placeholder="Product name, SKU, or barcode...">
                    </div>
                </div>
            </div>

            <!-- Category -->
            <div class="col-lg-2 col-md-6">
                <div class="filter-group">
                    <label class="filter-group-label">
                        <i class="fas fa-folder"></i> Category
                    </label>
                    <select id="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Brand -->
            <div class="col-lg-2 col-md-6">
                <div class="filter-group">
                    <label class="filter-group-label">
                        <i class="fas fa-tag"></i> Brand
                    </label>
                    <select id="brand_id" class="form-select">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Status -->
            <div class="col-lg-2 col-md-6">
                <div class="filter-group">
                    <label class="filter-group-label">
                        <i class="fas fa-toggle-on"></i> Status
                    </label>
                    <select id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Stock Status -->
            <div class="col-lg-2 col-md-6">
                <div class="filter-group">
                    <label class="filter-group-label">
                        <i class="fas fa-boxes"></i> Stock
                    </label>
                    <select id="stock_status" class="form-select">
                        <option value="">All Stock</option>
                        <option value="in_stock">In Stock</option>
                        <option value="low_stock">Low Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
            </div>

            <!-- Price Range -->
            <div class="col-lg-4 col-md-12">
                <div class="filter-group">
                    <label class="filter-group-label">
                        <i class="fas fa-rupee-sign"></i> Price Range
                    </label>
                    <div class="price-range-group">
                        <input type="number" id="min_price" class="form-control" placeholder="Min ₹">
                        <span class="price-separator">—</span>
                        <input type="number" id="max_price" class="form-control" placeholder="Max ₹">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card table-card" style="position: relative;">
                <div class="loading-overlay" id="loadingOverlay">
                    <div class="spinner"></div>
                </div>

                <div class="table-header">
                    <h4 class="table-title">Product List</h4>
                    <span class="results-count" id="resultsCount">
                        (<span id="productCount">{{ $products->total() }}</span> total)
                    </span>
                </div>

                <div id="productsTableContainer">
                    @include('admin.products.partials.table-rows')
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    <div id="paginationContainer">
                        {{ $products->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentSortBy = 'created_at';
let currentSortOrder = 'desc';

// AJAX Pagination
$(document).on('click', '#paginationContainer .pagination a', function(e) {
    e.preventDefault();

    let url = $(this).attr('href');
    if (!url || url === '#') return;

    let page = new URL(url, window.location.origin).searchParams.get('page');
    if (page) {
        loadProducts(page);

        // Scroll to top of table
        $('html, body').animate({
            scrollTop: $('.table-card').offset().top - 20
        }, 300);
    }
});

function loadProducts(page = 1) {
    $('#loadingOverlay').addClass('active');

    $.ajax({
        url: "{{ route('admin.products.index') }}",
        data: {
            page: page,
            search: $('#search').val(),
            category_id: $('#category_id').val(),
            brand_id: $('#brand_id').val(),
            status: $('#status').val(),
            stock_status: $('#stock_status').val(),
            min_price: $('#min_price').val(),
            max_price: $('#max_price').val(),
            sort_by: currentSortBy,
            sort_order: currentSortOrder
        },
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function (response) {
            // Update table
            $('#productsTableContainer').html(response.html);

            // Update pagination
            $('#paginationContainer').html(response.pagination);

            // Update count
            $('#productCount').text(response.total);

            $('#loadingOverlay').removeClass('active');
            updateSortIndicators();
            updateActiveFiltersCount();
        },
        error: function() {
            $('#loadingOverlay').removeClass('active');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load products',
                confirmButtonColor: '#22c55e'
            });
        }
    });
}

function updateSortIndicators() {
    $('.sortable').removeClass('active asc desc');
    $(`.sortable[data-sort="${currentSortBy}"]`)
        .addClass('active')
        .addClass(currentSortOrder);
}

function updateActiveFiltersCount() {
    let count = 0;

    if ($('#search').val()) count++;
    if ($('#category_id').val()) count++;
    if ($('#brand_id').val()) count++;
    if ($('#status').val()) count++;
    if ($('#stock_status').val()) count++;
    if ($('#min_price').val()) count++;
    if ($('#max_price').val()) count++;

    if (count > 0) {
        $('#activeFiltersCount').text(count).show();
    } else {
        $('#activeFiltersCount').hide();
    }
}

// Filter changes
$(document).on('keyup', '#search', debounce(function () {
    loadProducts(1);
}, 500));

$(document).on('change', '#category_id, #brand_id, #status, #stock_status', function () {
    loadProducts(1);
});

$(document).on('change', '#min_price, #max_price', debounce(function () {
    loadProducts(1);
}, 800));

// Sorting
$(document).on('click', '.sortable', function () {
    const sortBy = $(this).data('sort');

    if (currentSortBy === sortBy) {
        currentSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
    } else {
        currentSortBy = sortBy;
        currentSortOrder = 'asc';
    }

    loadProducts(1);
});

// Clear Filters
$('#clearFilters').click(function(e) {
    e.preventDefault();
    $('#search').val('');
    $('#category_id').val('');
    $('#brand_id').val('');
    $('#status').val('');
    $('#stock_status').val('');
    $('#min_price').val('');
    $('#max_price').val('');
    currentSortBy = 'created_at';
    currentSortOrder = 'desc';
    loadProducts(1);
});

// Delete Product
$(document).on('click', '.delete-product', function () {
    const productId = $(this).data('id');
    const productName = $(this).data('name');

    Swal.fire({
        title: 'Delete Product?',
        html: `Are you sure you want to delete <strong>${productName}</strong>?<br>This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        width: '30em',
        padding: '2rem'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/products/${productId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message,
                        confirmButtonColor: '#22c55e',
                        timer: 2000
                    });
                    loadProducts($('#paginationContainer .pagination .active .page-link').text() || 1);
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Failed to delete product',
                        confirmButtonColor: '#22c55e'
                    });
                }
            });
        }
    });
});

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Initial state
updateActiveFiltersCount();
</script>
@endpush
