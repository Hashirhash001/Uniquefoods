@extends('admin.layouts.app')

@section('title', 'Group Discounts - ' . $group->name)

@push('styles')
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
                <li class="breadcrumb-item active">Group Discounts</li>
            </ol>
        </nav>
    </div>

    <!-- Group Info Header -->
    <div class="group-info-card">
        <div class="group-info">
            <h3>
                <i class="fas fa-percent"></i>
                {{ $group->name }} - Group Discounts
            </h3>
            <div class="group-meta">
                <span>
                    <i class="fas fa-users"></i>
                    {{ $group->users->count() }} Members
                </span>
                <span>
                    <i class="fas fa-tag"></i>
                    {{ $discounts->count() }} Active Discounts
                </span>
            </div>
        </div>
        <a href="{{ route('admin.customer-groups.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Groups
        </a>
    </div>

    <!-- Create Discount Form -->
    <div class="discount-card">
        <div class="card-header">
            <h4>
                <i class="fas fa-plus-circle"></i>
                Create New Discount
            </h4>
        </div>

        <div class="card-body">
            <form id="discountForm">
                @csrf

                <div class="row g-4">
                    <div class="col-md-3">
                        <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" id="discountType">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount (₹)</option>
                        </select>
                        <small class="form-text">Choose discount calculation method</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="discountSymbol">%</span>
                            <input type="number" step="0.01" name="value" class="form-control"
                                   placeholder="10" min="0">
                        </div>
                        <small class="text-danger error-value"></small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Minimum Order Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" name="min_order_amount"
                                   class="form-control" placeholder="500" min="0">
                        </div>
                        <small class="form-text">Optional: Leave empty for no minimum</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Add Discount Rule
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Existing Discounts -->
    <div class="discount-card">
        <div class="card-header">
            <h4>
                <i class="fas fa-list"></i>
                Active Discount Rules
            </h4>
        </div>

        <div class="card-body">
            @if($discounts->count() > 0)
            <table class="discounts-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Min Order Amount</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="discountsTableBody">
                    @foreach($discounts as $discount)
                    <tr data-discount-id="{{ $discount->id }}">
                        <td>
                            <span class="badge {{ $discount->type == 'percentage' ? 'badge-primary' : 'badge-warning' }}">
                                {{ ucfirst($discount->type) }}
                            </span>
                        </td>
                        <td>
                            <strong style="font-size: 16px;">
                                @if($discount->type == 'percentage')
                                    {{ $discount->value }}%
                                @else
                                    ₹{{ number_format($discount->value, 2) }}
                                @endif
                            </strong>
                        </td>
                        <td>
                            @if($discount->min_order_amount)
                                ₹{{ number_format($discount->min_order_amount, 2) }}
                            @else
                                <span style="color: #9ca3af;">No minimum</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $discount->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $discount->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $discount->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-icon toggle-discount"
                                        data-id="{{ $discount->id }}"
                                        title="Toggle Status">
                                    <i class="fas fa-toggle-{{ $discount->is_active ? 'on' : 'off' }}"></i>
                                </button>

                                <button class="btn-icon btn-danger delete-discount"
                                        data-id="{{ $discount->id }}"
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
                <i class="fas fa-percent"></i>
                <h5>No Discount Rules Yet</h5>
                <p>Create your first discount rule using the form above</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // Change symbol based on discount type
    $('#discountType').on('change', function() {
        const type = $(this).val();
        $('#discountSymbol').text(type === 'percentage' ? '%' : '₹');

        const input = $('input[name="value"]');
        if (type === 'percentage') {
            input.attr('max', '100');
            input.attr('placeholder', '10');
        } else {
            input.removeAttr('max');
            input.attr('placeholder', '100');
        }
    });

    // Submit discount form
    $('#discountForm').on('submit', function(e) {
        e.preventDefault();

        $('.text-danger').text('');
        $('.form-control').removeClass('is-invalid');

        Swal.fire({
            title: 'Creating Discount...',
            html: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: "{{ route('admin.customer-groups.discounts.store', $group) }}",
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

    // Toggle discount status with confirmation
    $(document).on('click', '.toggle-discount', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const btn = $(this);
        const discountId = btn.data('id');
        const row = btn.closest('tr');
        const badge = row.find('.badge-success, .badge-danger');
        const isCurrentlyActive = badge.hasClass('badge-success');
        const newStatus = isCurrentlyActive ? 'Deactivate' : 'Activate';

        Swal.fire({
            title: `${newStatus} Discount?`,
            text: `Are you sure you want to ${newStatus.toLowerCase()} this discount rule?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: isCurrentlyActive ? '#ef4444' : '#22c55e',
            cancelButtonColor: '#6b7280',
            confirmButtonText: `Yes, ${newStatus}`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.prop('disabled', true);

                $.ajax({
                    url: `/admin/group-discounts/${discountId}/toggle`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            const icon = btn.find('i');

                            if (response.is_active) {
                                badge.removeClass('badge-danger').addClass('badge-success').text('Active');
                                icon.removeClass('fa-toggle-off').addClass('fa-toggle-on');
                            } else {
                                badge.removeClass('badge-success').addClass('badge-danger').text('Inactive');
                                icon.removeClass('fa-toggle-on').addClass('fa-toggle-off');
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Status Updated',
                                text: 'Discount status changed successfully',
                                timer: 2000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to update status',
                            confirmButtonColor: '#22c55e'
                        });
                    },
                    complete: function() {
                        btn.prop('disabled', false);
                    }
                });
            }
        });
    });

    // Delete discount
    $(document).on('click', '.delete-discount', function() {
        const discountId = $(this).data('id');

        Swal.fire({
            title: 'Delete Discount Rule?',
            text: "This action cannot be undone",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/group-discounts/${discountId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            confirmButtonColor: '#22c55e',
                            timer: 2000
                        });

                        $(`tr[data-discount-id="${discountId}"]`).fadeOut(300, function() {
                            $(this).remove();

                            if ($('#discountsTableBody tr').length === 0) {
                                location.reload();
                            }
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to delete discount',
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
