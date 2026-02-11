@extends('admin.layouts.app')

@section('title', 'Product Offers - ' . $group->name)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    * { box-sizing: border-box; }

    .page-header { margin-bottom: 1.25rem; }

    .breadcrumb {
        background: transparent; padding: 0; margin: 0; font-size: 14px;
    }
    .breadcrumb-item { color: #6b7280; }
    .breadcrumb-item a { color: #6b7280; text-decoration: none; transition: color 0.2s; }
    .breadcrumb-item a:hover { color: #22c55e; }
    .breadcrumb-item.active { color: #111827; font-weight: 500; }
    .breadcrumb-item + .breadcrumb-item::before { color: #d1d5db; content: "/"; }

    /* Header Card */
    .group-info-card {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 1px solid #22c55e;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .group-info h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .group-info h3 i { color: #22c55e; }

    .group-meta {
        display: flex;
        gap: 1.5rem;
        margin-top: 0.5rem;
    }

    .group-meta span {
        font-size: 14px;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .group-meta span i { color: #22c55e; }

    .back-btn {
        background: white;
        border: 2px solid #22c55e;
        color: #22c55e;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .back-btn:hover {
        background: #22c55e;
        color: white;
    }

    /* Main Card */
    .discount-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .card-header {
        background: white;
        border-bottom: 2px solid #e5e7eb;
        padding: 1.5rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-header h4 i { color: #22c55e; }

    .card-body { padding: 1.5rem; }

    /* Form Styles */
    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control, .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 14px;
        color: #111827;
        transition: all 0.2s;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
        outline: none;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #ef4444;
    }

    .input-group {
        display: flex;
        align-items: stretch;
    }

    .input-group-text {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-right: none;
        border-radius: 8px 0 0 8px;
        padding: 0.75rem 1rem;
        color: #6b7280;
        font-weight: 600;
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 8px 8px 0;
    }

    .form-text {
        font-size: 13px;
        color: #6b7280;
        margin-top: 0.375rem;
        display: block;
    }

    small.text-danger {
        color: #ef4444;
        font-size: 13px;
        margin-top: 0.375rem;
        display: block;
        font-weight: 500;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 8px -1px rgba(34, 197, 94, 0.4);
    }

    /* Discounts Table */
    .discounts-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1.5rem;
    }

    .discounts-table thead {
        background: #f9fafb;
    }

    .discounts-table th {
        padding: 0.875rem 1rem;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        text-align: left;
        border-bottom: 2px solid #e5e7eb;
    }

    .discounts-table td {
        padding: 1rem;
        font-size: 14px;
        color: #111827;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .discounts-table tbody tr:hover {
        background: #f9fafb;
    }

    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-primary { background: #dbeafe; color: #1e40af; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-info { background: #e0e7ff; color: #3730a3; }

    .action-btns {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        background: white;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-icon:hover {
        background: #dbeafe;
        color: #2563eb;
        border-color: #3b82f6;
    }

    .btn-icon.btn-danger:hover {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 64px;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        font-size: 18px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .offer-type-selector {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 1rem;
    }

    .offer-type-btn {
        padding: 0.75rem 1.5rem;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 600;
        color: #6b7280;
    }

    .offer-type-btn.active {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-color: #22c55e;
        color: #15803d;
    }

    .offer-type-btn:hover:not(.active) {
        border-color: #9ca3af;
        color: #111827;
    }

    .offer-fields {
        display: none;
    }

    .offer-fields.active {
        display: block;
    }

    /* Select2 Custom Styling */
    .select2-container--default .select2-selection--single {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        height: 46px;
        padding: 0.5rem;
    }

    .select2-container--default .select2-selection--single:focus {
        border-color: #22c55e;
        outline: none;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 30px;
        color: #111827;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
    }

    .select2-dropdown {
        border: 2px solid #22c55e;
        border-radius: 8px;
    }

    .select2-results__option--highlighted {
        background-color: #22c55e !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <!-- Breadcrumb -->
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.customer-groups.index') }}">Customer Groups</a></li>
                <li class="breadcrumb-item active">Product Offers</li>
            </ol>
        </nav>
    </div>

    <!-- Group Info Header -->
    <div class="group-info-card">
        <div class="group-info">
            <h3>
                <i class="fas fa-gift"></i>
                {{ $group->name }} - Time-Limited Offers
            </h3>
            <div class="group-meta">
                <span>
                    <i class="fas fa-users"></i>
                    {{ $group->users->count() }} Members
                </span>
                <span>
                    <i class="fas fa-clock"></i>
                    {{ $offers->count() }} Active Offers
                </span>
            </div>
        </div>
        <a href="{{ route('admin.customer-groups.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Groups
        </a>
    </div>

    <!-- Create Offer Form -->
    <div class="discount-card">
        <div class="card-header">
            <h4>
                <i class="fas fa-plus-circle"></i>
                Create Time-Limited Offer
            </h4>
        </div>

        <div class="card-body">
            <form id="offerForm">
                @csrf

                <!-- Offer Type Selector -->
                <div class="offer-type-selector">
                    <button type="button" class="offer-type-btn active" data-type="product">
                        <i class="fas fa-box"></i> Single Product
                    </button>
                    <button type="button" class="offer-type-btn" data-type="category">
                        <i class="fas fa-layer-group"></i> Entire Category
                    </button>
                    <button type="button" class="offer-type-btn" data-type="brand">
                        <i class="fas fa-tags"></i> All Brand Products
                    </button>
                </div>

                <input type="hidden" name="offer_type" id="offerType" value="product">

                <div class="row g-4">
                    <!-- Product Selection (Default) -->
                    <div class="col-md-12 offer-fields active" id="productFields">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Select Product <span class="text-danger">*</span></label>
                                <select name="product_id" class="form-select select2-product" id="productSelect">
                                    <option value="">Choose a product...</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }} - ₹{{ number_format($product->price, 2) }}
                                    </option>
                                    @endforeach
                                </select>
                                <small class="text-danger error-product_id"></small>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Regular Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="text" id="regularPrice" class="form-control"
                                           placeholder="0.00" readonly style="background: #f9fafb;">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Offer Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="offer_price" class="form-control"
                                           placeholder="0.00" min="0">
                                </div>
                                <small class="text-danger error-offer_price"></small>
                            </div>
                        </div>
                    </div>

                    <!-- Category Selection -->
                    <div class="col-md-12 offer-fields" id="categoryFields">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Select Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select select2-category">
                                    <option value="">Choose a category...</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }} ({{ $category->products->count() }} products)
                                    </option>
                                    @endforeach
                                </select>
                                <small class="form-text">All products in this category will get the discount</small>
                                <small class="text-danger error-category_id"></small>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                                <select name="category_discount_type" id="categoryDiscountType" class="form-select">
                                    <option value="">Select type...</option>
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount (₹)</option>
                                </select>
                                <small class="text-danger error-category_discount_type"></small>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                                <input type="number" step="0.01"
                                    name="category_discount_value"
                                    id="categoryDiscountValue"
                                    class="form-control"
                                    placeholder="Enter value" min="0">
                                <small class="text-danger error-category_discount_value"></small>
                            </div>
                        </div>
                    </div>

                    <!-- Brand Selection -->
                    <div class="col-md-12 offer-fields" id="brandFields">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Select Brand <span class="text-danger">*</span></label>
                                <select name="brand_id" class="form-select select2-brand">
                                    <option value="">Choose a brand...</option>
                                    @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">
                                        {{ $brand->name }} ({{ $brand->products->count() }} products)
                                    </option>
                                    @endforeach
                                </select>
                                <small class="form-text">All products from this brand will get the discount</small>
                                <small class="text-danger error-brand_id"></small>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                                <select name="brand_discount_type" id="brandDiscountType" class="form-select">
                                    <option value="">Select type...</option>
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount (₹)</option>
                                </select>
                                <small class="text-danger error-brand_discount_type"></small>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="brandSymbol">%</span>
                                    <input type="number" step="0.01"
                                        name="brand_discount_value"
                                        id="brandDiscountValue"
                                        class="form-control"
                                        placeholder="Enter value" min="0">
                                </div>
                                <small class="text-danger error-brand_discount_value"></small>
                            </div>
                        </div>
                    </div>

                    <!-- Date Selection (Common for all) -->
                    <div class="col-md-6">
                        <label class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="text" name="starts_at" class="form-control date-picker"
                               placeholder="Select start date">
                        <small class="text-danger error-starts_at"></small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">End Date <span class="text-danger">*</span></label>
                        <input type="text" name="ends_at" class="form-control date-picker"
                               placeholder="Select end date">
                        <small class="text-danger error-ends_at"></small>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-gift"></i>
                            Create Offer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Existing Offers -->
    <div class="discount-card">
        <div class="card-header">
            <h4>
                <i class="fas fa-list"></i>
                Active Offers
            </h4>
        </div>

        <div class="card-body">
            @if($offers->count() > 0)
            <table class="discounts-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Target</th>
                        <th>Discount</th>
                        <th>Valid Period</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="offersTableBody">
                    @foreach($offers as $offer)
                    <tr data-offer-id="{{ $offer->id }}">
                        <td>
                            <span class="badge badge-{{ $offer->offer_type === 'product' ? 'primary' : ($offer->offer_type === 'category' ? 'warning' : 'info') }}">
                                {{ ucfirst($offer->offer_type) }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ $offer->offer_name }}</strong>
                            @if($offer->offer_type === 'product' && $offer->product)
                                <br>
                                <small style="color: #9ca3af;">
                                    Regular: ₹{{ number_format($offer->product->price, 2) }}
                                </small>
                            @endif
                        </td>
                        <td>
                            @if($offer->offer_type === 'product')
                                <strong style="font-size: 16px; color: #ef4444;">
                                    ₹{{ number_format($offer->offer_price, 2) }}
                                </strong>
                                @if($offer->product)
                                    @php
                                        $discount = (($offer->product->price - $offer->offer_price) / $offer->product->price) * 100;
                                    @endphp
                                    <span class="badge badge-success">{{ number_format($discount, 1) }}% OFF</span>
                                @endif
                            @else
                                <strong style="font-size: 16px; color: #ef4444;">
                                    @if($offer->discount_type === 'percentage')
                                        {{ $offer->discount_value }}% OFF
                                    @else
                                        ₹{{ number_format($offer->discount_value, 2) }} OFF
                                    @endif
                                </strong>
                            @endif
                        </td>
                        <td>
                            <small style="color: #6b7280;">
                                {{ $offer->starts_at->format('M d, Y') }}
                                <br>
                                to {{ $offer->ends_at->format('M d, Y') }}
                            </small>
                        </td>
                        <td>
                            @php
                                $now = now();
                                $isActive = $now->between($offer->starts_at, $offer->ends_at);
                                $isPending = $now->lt($offer->starts_at);
                                $isExpired = $now->gt($offer->ends_at);
                            @endphp

                            @if($isActive)
                                <span class="badge badge-success">Active</span>
                            @elseif($isPending)
                                <span class="badge badge-warning">Scheduled</span>
                            @else
                                <span class="badge badge-danger">Expired</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-icon btn-danger delete-offer"
                                        data-id="{{ $offer->id }}"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <i class="fas fa-gift"></i>
                <h5>No Offers Created</h5>
                <p>Create time-limited offers for this customer group</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {

    // Initialize Select2 for searchable dropdowns
    $('.select2-product').select2({
        placeholder: 'Search products...',
        allowClear: true,
        width: '100%'
    });

    $('.select2-category').select2({
        placeholder: 'Search categories...',
        allowClear: true,
        width: '100%'
    });

    $('.select2-brand').select2({
        placeholder: 'Search brands...',
        allowClear: true,
        width: '100%'
    });

    // Initialize date pickers
    flatpickr(".date-picker", {
        dateFormat: "Y-m-d",
        minDate: "today"
    });

    // Offer type switching
    $('.offer-type-btn').on('click', function() {
        const type = $(this).data('type');

        // Update button states
        $('.offer-type-btn').removeClass('active');
        $(this).addClass('active');

        // Update hidden field
        $('#offerType').val(type);

        // Show/hide relevant fields
        $('.offer-fields').removeClass('active');
        $(`#${type}Fields`).addClass('active');

        // Restore name attributes to active fields
        $('.offer-fields.active').find('input[data-original-name], select[data-original-name]').each(function() {
            $(this).attr('name', $(this).attr('data-original-name'));
        });

        // Clear all errors and values
        $('.text-danger').text('');
        $('.form-control, .form-select').removeClass('is-invalid').val('');
        $('.select2-product, .select2-category, .select2-brand').val(null).trigger('change');
        $('#regularPrice').val('');
        $('input[name="starts_at"], input[name="ends_at"]').val('');
    });

    // Update regular price for product selection
    $('#productSelect').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const price = selectedOption.data('price');

        if (price) {
            $('#regularPrice').val(parseFloat(price).toFixed(2));
        } else {
            $('#regularPrice').val('');
        }
    });

    // Update symbol for category discount
    $('#categoryDiscountType').on('change', function() {
        const type = $(this).val();
        $('#categorySymbol').text(type === 'percentage' ? '%' : '₹');

        const placeholder = type === 'percentage' ? '10' : '100';
        $('#categoryDiscountValue').attr('placeholder', placeholder);

        if (type === 'percentage') {
            $('#categoryDiscountValue').attr('max', '100');
        } else {
            $('#categoryDiscountValue').removeAttr('max');
        }
    });

    // Update symbol for brand discount
    $('#brandDiscountType').on('change', function() {
        const type = $(this).val();
        $('#brandSymbol').text(type === 'percentage' ? '%' : '₹');

        const placeholder = type === 'percentage' ? '10' : '100';
        $('#brandDiscountValue').attr('placeholder', placeholder);

        if (type === 'percentage') {
            $('#brandDiscountValue').attr('max', '100');
        } else {
            $('#brandDiscountValue').removeAttr('max');
        }
    });

    // Submit offer form
    $('#offerForm').on('submit', function (e) {
        e.preventDefault();

        const offerType = $('#offerType').val();

        // Base payload always
        const payload = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            offer_type: offerType,
            starts_at: $('input[name="starts_at"]').val(),
            ends_at: $('input[name="ends_at"]').val(),
        };

        if (offerType === 'product') {
            payload.product_id = $('select[name="product_id"]').val();
            payload.offer_price = $('input[name="offer_price"]').val();
        }

        if (offerType === 'category') {
            payload.category_id = $('select[name="category_id"]').val();
            payload.discount_type = $('select[name="category_discount_type"]').val();
            payload.discount_value = $('input[name="category_discount_value"]').val();
        }

        if (offerType === 'brand') {
            payload.brand_id = $('select[name="brand_id"]').val();
            payload.discount_type = $('select[name="brand_discount_type"]').val();
            payload.discount_value = $('input[name="brand_discount_value"]').val();
        }

        Swal.fire({
            title: 'Creating Offer...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: "{{ route('admin.customer-groups.product-offers.store', $group) }}",
            method: "POST",
            data: payload,
            success: function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => location.reload());
            },
            error: function (xhr) {
            Swal.close();

            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors || {};
                Object.keys(errors).forEach((field) => {
                // show error in <small class="error-FIELD">
                $(`.error-${field}`).text(errors[field][0]);
                // highlight
                $(`[name="${field}"]`).addClass('is-invalid');
                });

                Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please check the form',
                confirmButtonColor: '#22c55e'
                });
            }
            }
        });
    });

    // Delete offer
    $(document).on('click', '.delete-offer', function() {
        const offerId = $(this).data('id');

        Swal.fire({
            title: 'Delete Offer?',
            text: "This will remove the time-limited offer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/product-offers/${offerId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            confirmButtonColor: '#22c55e',
                            timer: 2000
                        });

                        $(`tr[data-offer-id="${offerId}"]`).fadeOut(300, function() {
                            $(this).remove();

                            if ($('#offersTableBody tr').length === 0) {
                                location.reload();
                            }
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to delete offer',
                            confirmButtonColor: '#22c55e'
                        });
                    }
                });
            }
        });
    });

});
</script>
@endpush
