@extends('admin.layouts.app')

@section('title', 'Product Prices - ' . $group->name)

@push('styles')
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

    .select2-container .select2-selection--single{
        height: 46px !important;
        border: 2px solid #e5e7eb !important;
        border-radius: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 42px !important;
        padding-left: 14px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        height: 44px !important;
        right: 10px !important;
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
                <li class="breadcrumb-item active">Product Prices</li>
            </ol>
        </nav>
    </div>

    <!-- Group Info Header -->
    <div class="group-info-card">
        <div class="group-info">
            <h3>
                <i class="fas fa-tags"></i>
                {{ $group->name }} - Product Prices
            </h3>
            <div class="group-meta">
                <span>
                    <i class="fas fa-users"></i>
                    {{ $group->users->count() }} Members
                </span>
                <span>
                    <i class="fas fa-box"></i>
                    {{ $groupPrices->count() }} Custom Prices
                </span>
            </div>
        </div>
        <a href="{{ route('admin.customer-groups.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Groups
        </a>
    </div>

    <!-- Add Product Price Form -->
    <div class="discount-card">
        <div class="card-header">
            <h4>
                <i class="fas fa-plus-circle"></i>
                Set Product-Specific Price
            </h4>
        </div>

        <div class="card-body">
            <form id="productPriceForm">
                @csrf

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Select Product <span class="text-danger">*</span></label>
                        <select name="product_id" class="form-select" id="productSelect">
                            <option value="">Choose a product...</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->name }} - ₹{{ number_format($product->price, 2) }}
                            </option>
                            @endforeach
                        </select>
                        <small class="form-text">Search and select product to set custom price</small>
                        <small class="text-danger error-product_id"></small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Regular Price</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="text" id="regularPrice" class="form-control"
                                   placeholder="0.00" readonly style="background: #f9fafb;">
                        </div>
                        <small class="form-text">Current product price</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Group Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" name="price" class="form-control"
                                   placeholder="0.00" min="0">
                        </div>
                        <small class="form-text">Custom price for this group</small>
                        <small class="text-danger error-price"></small>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-tag"></i>
                            Set Custom Price
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Existing Product Prices -->
    <div class="discount-card">
        <div class="card-header">
            <h4>
                <i class="fas fa-list"></i>
                Custom Product Prices
            </h4>
        </div>

        <div class="card-body">
            @if($groupPrices->count() > 0)
            <table class="discounts-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Regular Price</th>
                        <th>Group Price</th>
                        <th>Discount</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="pricesTableBody">
                    @foreach($groupPrices as $groupPrice)
                    <tr data-price-id="{{ $groupPrice->id }}">
                        <td>
                            <strong>{{ $groupPrice->product->name }}</strong>
                            <br>
                            <small style="color: #6b7280;">SKU: {{ $groupPrice->product->sku }}</small>
                        </td>
                        <td>
                            <span style="text-decoration: line-through; color: #9ca3af;">
                                ₹{{ number_format($groupPrice->product->price, 2) }}
                            </span>
                        </td>
                        <td>
                            <strong style="font-size: 16px; color: #22c55e;">
                                ₹{{ number_format($groupPrice->price, 2) }}
                            </strong>
                        </td>
                        <td>
                            @php
                                $discount = (($groupPrice->product->price - $groupPrice->price) / $groupPrice->product->price) * 100;
                            @endphp
                            <span class="badge badge-success">
                                {{ number_format($discount, 1) }}% OFF
                            </span>
                        </td>
                        <td>{{ $groupPrice->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-icon btn-danger delete-price"
                                        data-id="{{ $groupPrice->id }}"
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
                <i class="fas fa-tags"></i>
                <h5>No Custom Prices Set</h5>
                <p>Set product-specific prices for this customer group</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {

    $('#productSelect').select2({
        placeholder: 'Search product...',
        allowClear: true,
        width: '100%'
    });

    // Update regular price when product selected
    $('#productSelect').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const price = selectedOption.data('price');

        if (price) {
            $('#regularPrice').val(parseFloat(price).toFixed(2));
        } else {
            $('#regularPrice').val('');
        }
    });

    // Submit product price form
    $('#productPriceForm').on('submit', function(e) {
        e.preventDefault();

        $('.text-danger').text('');
        $('.form-control, .form-select').removeClass('is-invalid');

        Swal.fire({
            title: 'Setting Product Price...',
            html: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: "{{ route('admin.customer-groups.product-prices.store', $group) }}",
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    confirmButtonColor: '#22c55e',
                    timer: 2000
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.close();

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.entries(errors).forEach(([field, messages]) => {
                        $(`.error-${field}`).text(messages[0]);
                        $(`[name="${field}"]`).addClass('is-invalid');
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please check the form and try again',
                        confirmButtonColor: '#22c55e'
                    });
                }
            }
        });
    });

    // Delete price
    $(document).on('click', '.delete-price', function() {
        const priceId = $(this).data('id');

        Swal.fire({
            title: 'Remove Custom Price?',
            text: "Product will revert to regular pricing",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Remove',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/product-group-prices/${priceId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Removed!',
                            text: response.message,
                            confirmButtonColor: '#22c55e',
                            timer: 2000
                        });

                        $(`tr[data-price-id="${priceId}"]`).fadeOut(300, function() {
                            $(this).remove();

                            if ($('#pricesTableBody tr').length === 0) {
                                location.reload();
                            }
                        });
                    }
                });
            }
        });
    });

});
</script>
@endpush
