@extends('admin.layouts.app')

@section('title', 'Create Customer Group')

@push('styles')
<style>
    * { box-sizing: border-box; }

    .page-header {
        margin-bottom: 1.25rem;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 14px;
    }

    .breadcrumb-item {
        color: #6b7280;
    }

    .breadcrumb-item a {
        color: #6b7280;
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb-item a:hover {
        color: #22c55e;
    }

    .breadcrumb-item.active {
        color: #111827;
        font-weight: 500;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: #d1d5db;
        content: "/";
    }

    /* Product Card */
    .product-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-bottom: 1px solid #e5e7eb;
        padding: 1.5rem 2rem;
    }

    .card-header h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header h4 i {
        color: #22c55e;
        font-size: 24px;
    }

    .card-body {
        padding: 2rem;
    }

    /* Form Sections */
    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .form-section:last-of-type {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #22c55e;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: #22c55e;
    }

    /* Form Labels */
    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-label .text-danger {
        color: #ef4444;
    }

    .form-label .badge {
        font-size: 10px;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: 600;
        margin-left: 0.5rem;
    }

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Form Controls */
    .form-control,
    .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 14px;
        color: #111827;
        transition: all 0.2s;
        width: 100%;
        background: #ffffff;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
        outline: none;
        background: #f9fafb;
    }

    .form-control::placeholder {
        color: #9ca3af;
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }

    /* Error Messages */
    small.text-danger {
        color: #ef4444;
        font-size: 13px;
        margin-top: 0.375rem;
        display: block;
        font-weight: 500;
    }

    /* Helper Text */
    .form-text {
        font-size: 13px;
        color: #6b7280;
        margin-top: 0.375rem;
        display: block;
    }

    /* Customer Selection Box */
    .customer-selection-wrapper {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        background: #fafafa;
    }

    .customer-search-header {
        padding: 1rem;
        background: white;
        border-bottom: 2px solid #e5e7eb;
    }

    .customer-search-header input {
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        padding: 0.625rem 1rem;
        padding-left: 2.5rem;
        font-size: 14px;
        width: 100%;
        transition: all 0.2s;
    }

    .customer-search-header input:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        outline: none;
    }

    .search-icon-wrapper {
        position: relative;
    }

    .search-icon-wrapper i {
        position: absolute;
        left: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 14px;
    }

    .customer-stats {
        display: flex;
        gap: 1rem;
        padding: 0.75rem 1rem;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        font-size: 13px;
    }

    .customer-stats span {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        color: #6b7280;
        font-weight: 500;
    }

    .customer-stats span i {
        color: #22c55e;
    }

    .customer-stats .selected-count {
        color: #22c55e;
        font-weight: 600;
    }

    .customer-select {
        max-height: 320px;
        overflow-y: auto;
        background: white;
    }

    .customer-select::-webkit-scrollbar {
        width: 6px;
    }

    .customer-select::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }

    .customer-item {
        padding: 0.875rem 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.2s;
        border-bottom: 1px solid #f3f4f6;
    }

    .customer-item:last-child {
        border-bottom: none;
    }

    .customer-item:hover {
        background: #f0fdf4;
    }

    .customer-item.selected {
        background: #f0fdf4;
        border-left: 3px solid #22c55e;
    }

    .customer-item input[type="checkbox"] {
        cursor: pointer;
        width: 18px;
        height: 18px;
        accent-color: #22c55e;
    }

    .customer-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .customer-name {
        font-weight: 600;
        color: #111827;
        font-size: 14px;
    }

    .customer-email {
        font-size: 12px;
        color: #6b7280;
    }

    .customer-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        flex-shrink: 0;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 48px;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    /* Footer Buttons */
    .form-footer {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 2px solid #e5e7eb;
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        min-width: 120px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-outline-secondary {
        background: white;
        border: 2px solid #d1d5db;
        color: #374151;
    }

    .btn-outline-secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        color: #111827;
    }

    .btn-primary {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        box-shadow: 0 6px 8px -1px rgba(34, 197, 94, 0.4);
        transform: translateY(-1px);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.25rem;
        }

        .form-footer {
            flex-direction: column-reverse;
        }

        .form-footer .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.customer-groups.index') }}">Customer Groups</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>

    <div class="card product-card">
        <div class="card-header">
            <h4>
                <i class="fas fa-users"></i>
                Create New Customer Group
            </h4>
        </div>

        <div class="card-body">
            <form id="groupForm">
                @csrf

                <!-- BASIC INFO -->
                <div class="form-section">
                    <h6 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Basic Information
                    </h6>

                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label">
                                Group Name <span class="text-danger">*</span>
                                <span class="badge badge-info">Required</span>
                            </label>
                            <input type="text" name="name" class="form-control"
                                   placeholder="e.g., Gold Members, Monthly Buyers, Festival Shoppers">
                            <small class="form-text">This name will be visible to customers</small>
                            <small class="text-danger error-name"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <small class="form-text">Only active groups get pricing benefits</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Brief description about this customer group and its benefits..."></textarea>
                            <small class="form-text">Optional: Describe who should be in this group</small>
                            <small class="text-danger error-description"></small>
                        </div>
                    </div>
                </div>

                <!-- CUSTOMER SELECTION -->
                <div class="form-section">
                    <h6 class="section-title">
                        <i class="fas fa-user-plus"></i>
                        Assign Customers
                    </h6>

                    <div class="customer-selection-wrapper">
                        <div class="customer-search-header">
                            <div class="search-icon-wrapper">
                                <i class="fas fa-search"></i>
                                <input type="text" id="customerSearch"
                                       placeholder="Search by name or email..."
                                       autocomplete="off">
                            </div>
                        </div>

                        <div class="customer-stats">
                            <span>
                                <i class="fas fa-users"></i>
                                Total: <strong id="totalCustomers">{{ $customers->count() }}</strong>
                            </span>
                            <span>
                                <i class="fas fa-check-circle"></i>
                                Selected: <strong class="selected-count" id="selectedCount">0</strong>
                            </span>
                        </div>

                        <div class="customer-select" id="customerList">
                            @forelse($customers as $customer)
                            <div class="customer-item" data-customer-id="{{ $customer->id }}">
                                <input type="checkbox" name="customers[]" value="{{ $customer->id }}"
                                       id="customer-{{ $customer->id }}">
                                <div class="customer-avatar">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <label for="customer-{{ $customer->id }}" class="customer-info">
                                    <span class="customer-name">{{ $customer->name }}</span>
                                    <span class="customer-email">{{ $customer->email }}</span>
                                </label>
                            </div>
                            @empty
                            <div class="empty-state">
                                <i class="fas fa-users-slash"></i>
                                <p>No customers found</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                    <small class="form-text mt-2">
                        <i class="fas fa-lightbulb"></i>
                        Tip: You can assign more customers later or set up pricing rules first
                    </small>
                    <small class="text-danger error-customers"></small>
                </div>

                <!-- FOOTER -->
                <div class="form-footer">
                    <a href="{{ route('admin.customer-groups.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Create Group
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // Customer search functionality
    $('#customerSearch').on('input', function() {
        let search = $(this).val().toLowerCase();
        let visibleCount = 0;

        $('.customer-item').each(function() {
            let text = $(this).text().toLowerCase();
            let isVisible = text.includes(search);
            $(this).toggle(isVisible);
            if (isVisible) visibleCount++;
        });

        // Show empty state if no results
        if (visibleCount === 0 && search !== '') {
            if ($('#noResults').length === 0) {
                $('#customerList').append(`
                    <div class="empty-state" id="noResults">
                        <i class="fas fa-search"></i>
                        <p>No customers found matching "${search}"</p>
                    </div>
                `);
            }
        } else {
            $('#noResults').remove();
        }
    });

    // Update selected count
    function updateSelectedCount() {
        let count = $('input[name="customers[]"]:checked').length;
        $('#selectedCount').text(count);

        // Highlight selected items
        $('.customer-item').each(function() {
            let checkbox = $(this).find('input[type="checkbox"]');
            if (checkbox.is(':checked')) {
                $(this).addClass('selected');
            } else {
                $(this).removeClass('selected');
            }
        });
    }

    // Handle checkbox changes
    $(document).on('change', 'input[name="customers[]"]', function() {
        updateSelectedCount();
    });

    // Click on customer item to toggle checkbox
    $(document).on('click', '.customer-item', function(e) {
        if (e.target.tagName !== 'INPUT') {
            let checkbox = $(this).find('input[type="checkbox"]');
            checkbox.prop('checked', !checkbox.prop('checked'));
            updateSelectedCount();
        }
    });

    // Form submit with AJAX
    $('#groupForm').submit(function(e) {
        e.preventDefault();

        // Clear previous errors
        $('small.text-danger').text('');
        $('.form-control, .form-select').removeClass('is-invalid');

        // Show loading
        Swal.fire({
            title: 'Creating Customer Group...',
            html: 'Please wait while we set up your group',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: "{{ route('admin.customer-groups.store') }}",
            method: "POST",
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    html: `
                        <p>${response.message}</p>
                        <p class="text-muted" style="font-size: 14px; margin-top: 10px;">
                            You can now set up pricing rules and discounts for this group
                        </p>
                    `,
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: 'View Groups'
                }).then(() => {
                    window.location.href = "{{ route('admin.customer-groups.index') }}";
                });
            },
            error: function(xhr) {
                Swal.close();

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorList = [];

                    Object.entries(errors).forEach(([field, messages]) => {
                        $(`.error-${field}`).text(messages[0]);
                        $(`[name="${field}"]`).addClass('is-invalid');
                        errorList.push(`• ${messages[0]}`);
                    });

                    // Scroll to first error
                    const firstError = $('.text-danger').filter(function() {
                        return $(this).text().length > 0;
                    }).first();

                    if (firstError.length) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 500);
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Errors',
                        html: `<div style="text-align: left; font-size: 14px;">${errorList.join('<br>')}</div>`,
                        confirmButtonColor: '#22c55e',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Something went wrong. Please try again.',
                        confirmButtonColor: '#22c55e',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    });

    // Remove error styling on input change
    $('.form-control, .form-select').on('input change', function() {
        $(this).removeClass('is-invalid');
        const fieldName = $(this).attr('name');
        $(`.error-${fieldName}`).text('');
    });

});
</script>
@endpush
