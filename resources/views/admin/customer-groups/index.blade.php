@extends('admin.layouts.app')

@section('title', 'Customer Groups')

@push('styles')
<style>
    .page-header-section {
        margin-bottom: 1.5rem;
    }

    .title-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .title-area h3.title {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .title-area h3.title i {
        color: #22c55e;
        font-size: 32px;
    }

    .page-subtitle {
        font-size: 14px;
        color: #6b7280;
        margin-top: 0.375rem;
    }

    .rts-btn.btn-primary {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3);
    }

    .rts-btn.btn-primary:hover {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        box-shadow: 0 6px 8px -1px rgba(34, 197, 94, 0.4);
        transform: translateY(-1px);
    }

    .filters-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .form-control,
    .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.625rem 1rem;
        font-size: 14px;
        transition: all 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
        outline: none;
    }

    .table-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f9fafb;
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
    }

    .groups-table {
        width: 100%;
        border-collapse: collapse;
    }

    .groups-table thead {
        background: #f9fafb;
    }

    .groups-table th {
        padding: 1rem;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        text-align: left;
        border-bottom: 2px solid #e5e7eb;
    }

    .groups-table td {
        padding: 1rem;
        font-size: 14px;
        color: #111827;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .groups-table tbody tr {
        transition: all 0.2s;
    }

    .groups-table tbody tr:hover {
        background: #f9fafb;
    }

    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    .action-btns {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
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
        text-decoration: none;
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

    .btn-icon.btn-success:hover {
        background: #d1fae5;
        color: #065f46;
        border-color: #86efac;
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

    .card-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e5e7eb;
        background: #fafafa;
    }

    @media (max-width: 768px) {
        .title-wrapper {
            flex-direction: column;
            align-items: flex-start;
        }

        .rts-btn.btn-primary {
            width: 100%;
            justify-content: center;
        }

        .action-btns {
            justify-content: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="transection">
    <!-- Page Header -->
    <div class="page-header-section">
        <div class="title-wrapper">
            <div class="title-area">
                <h3 class="title">
                    <i class="fas fa-users"></i>
                    Customer Groups
                </h3>
                <p class="page-subtitle">Manage customer collections and special pricing</p>
            </div>
            <a href="{{ route('admin.customer-groups.create') }}" class="rts-btn btn-primary">
                <i class="fas fa-plus"></i> Create New Group
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <div class="row g-3">
            <div class="col-md-8">
                <input type="text" id="search" class="form-control" placeholder="🔍 Search by group name...">
            </div>
            <div class="col-md-4">
                <select id="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card table-card">
                <div class="table-header">
                    <h4 class="table-title">All Customer Groups</h4>
                    <span class="results-count">
                        <strong id="groupCount">{{ $groups->total() }}</strong> total
                    </span>
                </div>

                <div class="table-responsive" id="groupsTableContainer">
                    @include('admin.customer-groups.partials.table-rows')
                </div>

                <div class="card-footer">
                    <div id="paginationContainer">
                        {{ $groups->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Filter functionality
    let searchTimeout;

    $('#search, #status').on('input change', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadGroups, 300);
    });

    function loadGroups(page = 1) {
        $.ajax({
            url: "{{ route('admin.customer-groups.index') }}",
            data: {
                search: $('#search').val(),
                status: $('#status').val(),
                page: page
            },
            success: function(response) {
                $('#groupsTableContainer').html(response.html);
                $('#paginationContainer').html(response.pagination);
                $('#groupCount').text(response.total);
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load groups',
                    confirmButtonColor: '#22c55e'
                });
            }
        });
    }

    // Pagination click
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        if (page) {
            loadGroups(page);
        }
    });

    // Toggle status with confirmation
    $(document).on('click', '.toggle-status', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const btn = $(this);
        const groupId = btn.data('id');
        const badge = btn.closest('tr').find('.status-badge');
        const isCurrentlyActive = badge.hasClass('badge-success');
        const newStatus = isCurrentlyActive ? 'Deactivate' : 'Activate';

        Swal.fire({
            title: `${newStatus} Customer Group?`,
            text: `Are you sure you want to ${newStatus.toLowerCase()} this customer group?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: isCurrentlyActive ? '#ef4444' : '#22c55e',
            cancelButtonColor: '#6b7280',
            confirmButtonText: `Yes, ${newStatus}`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Disable button during request
                btn.prop('disabled', true);

                $.ajax({
                    url: `/admin/customer-groups/${groupId}/toggle-status`,
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
                                text: response.message || 'Status changed successfully',
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

    // Delete group
    $(document).on('click', '.delete-group', function(e) {
        e.preventDefault();
        let groupId = $(this).data('id');

        Swal.fire({
            title: 'Delete Customer Group?',
            html: '<p>This will remove:</p><ul style="text-align: left; margin-top: 10px;"><li>All customer assignments</li><li>All pricing rules</li><li>All discounts</li></ul>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/customer-groups/${groupId}`,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            loadGroups();
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete group',
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
