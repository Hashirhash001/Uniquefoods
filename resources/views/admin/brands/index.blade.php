@extends('admin.layouts.app')

@section('title', 'Brands Management')

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

        .form-check-switch .form-check-input {
            width: 3rem !important;
            height: 1.5rem !important;
            background-image: none !important;
        }

        .form-check {
            padding-left: 0 !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
        }

        .form-check-input {
            margin: 0 !important;
            flex-shrink: 0 !important;
        }

        .form-check-label {
            margin: 0 !important;
            cursor: pointer !important;
            font-size: 14px;
        }

        /* Page Header */
        .page-header-section {
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .page-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin: 0.25rem 0 0 0;
        }

        .btn-create {
            background: #22c55e;
            border: none;
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-create:hover {
            background: #16a34a;
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.25);
        }

        .btn-create i {
            font-size: 14px;
        }

        /* Card */
        .product-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        /* Table */
        .brand-table {
            margin: 0;
            width: 100%;
        }

        .brand-table thead {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .brand-table thead th {
            padding: 0.875rem 1rem;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .brand-table tbody td {
            padding: 0.875rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            color: #111827;
        }

        .brand-table tbody tr {
            transition: background 0.15s;
        }

        .brand-table tbody tr:hover {
            background: #fafafa;
        }

        .brand-name {
            font-weight: 500;
            color: #111827;
            font-size: 14px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.inactive {
            background: #f3f4f6;
            color: #6b7280;
        }

        /* Action Buttons */
        .action-btn {
            padding: 0;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 0.25rem;
            transition: all 0.2s;
            cursor: pointer;
            color: #6b7280;
            font-size: 14px;
        }

        .action-btn:hover {
            border-color: #d1d5db;
            background: #f9fafb;
        }

        .action-btn.btn-toggle {
            color: #d97706;
        }

        .action-btn.btn-toggle:hover {
            background: #fef3c7;
            border-color: #fbbf24;
        }

        .action-btn.btn-edit {
            color: #2563eb;
        }

        .action-btn.btn-edit:hover {
            background: #dbeafe;
            border-color: #3b82f6;
        }

        .action-btn.btn-delete {
            color: #dc2626;
        }

        .action-btn.btn-delete:hover {
            background: #fee2e2;
            border-color: #ef4444;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 4rem;
            opacity: 0.2;
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: #6b7280;
            font-size: 14px;
        }

        /* Modal */
        .modal-dialog {
            max-width: 500px;
            margin: 1.75rem auto;
        }

        .modal-content {
            border: none;
            border-radius: 8px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid #e5e7eb;
            padding: 1.25rem 1.5rem;
            background: white;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            display: flex;
            align-items: center;
        }

        .modal-title i {
            color: #22c55e !important;
            margin-right: 0.5rem !important;
            font-size: 18px !important;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
        }

        /* Form Elements */
        .form-label {
            font-size: 14px !important;
            font-weight: 500 !important;
            color: #374151 !important;
            margin-bottom: 0.5rem !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.25rem !important;
        }

        .form-control,
        .form-select {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 0.625rem 0.875rem;
            font-size: 14px;
            transition: all 0.2s;
            height: 42px;
            line-height: 1.5;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
            outline: none;
        }

        .form-text {
            font-size: 13px;
            color: #6b7280;
            margin-top: 0.25rem;
            display: block;
        }

        .text-danger {
            color: #ef4444;
            font-size: 13px;
            margin-top: 0.25rem;
            display: block;
            font-weight: 400;
        }

        .form-check-input:checked {
            background-color: #22c55e;
            border-color: #22c55e;
        }

        /* Buttons */
        .btn-secondary {
            background: white;
            border: 1px solid #d1d5db;
            color: #374151;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-primary-modal {
            background: #22c55e;
            border: none;
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-primary-modal:hover {
            background: #16a34a;
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.25);
        }

        /* Filters Section */
        .filters-section {
            background: white;
            padding: 1.25rem;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            margin-bottom: 1.5rem;
        }

        /* Green Pagination Styling */
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

        /* Loading State */
        .table-loading {
            position: relative;
            opacity: 0.6;
            pointer-events: none;
        }

        .table-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 40px;
            height: 40px;
            margin: -20px 0 0 -20px;
            border: 3px solid #f3f4f6;
            border-top-color: #22c55e;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 20px;
            }

            .page-subtitle {
                font-size: 13px;
            }

            .page-header-section .d-flex {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .btn-create {
                width: 100%;
                justify-content: center;
            }

            .filters-section {
                padding: 1rem;
            }

            .brand-table thead th {
                padding: 0.75rem 0.5rem;
                font-size: 11px;
            }

            .brand-table tbody td {
                padding: 0.75rem 0.5rem;
                font-size: 13px;
            }

            .brand-table thead th[width="60"],
            .brand-table tbody td:first-child {
                display: none;
            }

            .action-btn {
                width: 28px;
                height: 28px;
                font-size: 12px;
                margin: 0 0.125rem;
            }

            .status-badge {
                padding: 0.25rem 0.5rem;
                font-size: 10px;
            }

            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }

            .modal-header {
                padding: 1rem 1.25rem;
            }

            .modal-title {
                font-size: 16px;
            }

            .modal-title i {
                font-size: 16px;
            }

            .modal-body {
                padding: 1.25rem;
            }

            .modal-footer {
                padding: 1rem 1.25rem;
                flex-direction: column-reverse;
                gap: 0.5rem;
            }

            .btn-secondary,
            .btn-primary-modal {
                width: 100%;
                margin: 0;
            }

            .empty-state {
                padding: 3rem 1rem;
            }

            .empty-icon {
                font-size: 3rem;
            }

            .empty-title {
                font-size: 16px;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding: 0.75rem;
            }

            .brand-table thead th[width="100"],
            .brand-table tbody td:nth-child(4) {
                display: none;
            }

            .form-control {
                font-size: 14px;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .brand-table thead th {
                padding: 0.875rem 0.875rem;
                font-size: 12px;
            }

            .brand-table tbody td {
                padding: 0.875rem 0.875rem;
            }

            .modal-dialog {
                max-width: 480px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="page-header-section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Brand Management</h4>
                    <p class="page-subtitle">Manage product brands</p>
                </div>
                <button class="btn btn-create" data-bs-toggle="modal" data-bs-target="#brandModal" onclick="openCreateModal()">
                    <i class="fas fa-plus"></i> Add Brand
                </button>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <div class="row g-3">
                <div class="col-md-8">
                    <input type="text" id="searchBrand" class="form-control" placeholder="Search brands...">
                </div>
                <div class="col-md-4">
                    <select id="filterStatus" class="form-select">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Brands Table -->
        <div class="row">
            <div class="col-12">
                <div class="card product-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            Brands List (<span id="brandCount">{{ $brands->total() }}</span> total)
                        </h4>
                    </div>

                    <div class="card-body pt-0 p-0">
                        <div class="table-responsive">
                            <table class="brand-table table mb-0" id="brandTable">
                                <thead>
                                    <tr>
                                        <th width="60">#</th>
                                        <th>Brand Name</th>
                                        <th width="120">Status</th>
                                        <th width="100" class="text-center">Products</th>
                                        <th width="160" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="brandTableBody">
                                    @include('admin.brands.partials.table-rows')
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer">
                        <div id="paginationContainer">
                            {{ $brands->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- CREATE/EDIT MODAL -->
    <div class="modal fade" id="brandModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form id="brandForm" class="modal-content">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="brand_id" id="brandId">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-tag" id="modalIcon"></i>
                        <span id="modalTitleText">Create New Brand</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">
                            Brand Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="brandName" class="form-control"
                            placeholder="Enter brand name" required>
                        <small class="text-danger error-name"></small>
                    </div>

                    <div class="mb-0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="brandStatus" value="1"
                                checked>
                            <label class="form-check-label" for="brandStatus">
                                Active Status
                            </label>
                        </div>
                        <small class="form-text">Enable to make this brand visible</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary-modal">
                        Save Brand
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let isEditMode = false;
        let currentBrandId = null;

        // AJAX Pagination
        $(document).on('click', '#paginationContainer .pagination a', function(e) {
            e.preventDefault();

            let url = $(this).attr('href');
            if (!url || url === '#') return;

            let page = new URL(url, window.location.origin).searchParams.get('page');
            if (page) {
                loadBrands(page);
            }
        });

        function loadBrands(page = 1) {
            const searchText = $('#searchBrand').val();
            const statusFilter = $('#filterStatus').val();

            // Add loading state
            const $tbody = $('#brandTableBody');
            $tbody.addClass('table-loading');

            $.ajax({
                url: '{{ route('admin.brands.index') }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchText,
                    status: statusFilter
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $tbody.removeClass('table-loading');

                    // Update table body
                    $tbody.html(response.html);

                    // Update pagination
                    $('#paginationContainer').html(response.pagination);

                    // Update count
                    $('#brandCount').text(response.total);
                },
                error: function(xhr) {
                    $tbody.removeClass('table-loading');
                    console.error('Error loading brands:', xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load brands',
                        confirmButtonColor: '#22c55e',
                        width: '30em',
                        padding: '2rem'
                    });
                }
            });
        }

        // Filter with AJAX
        let searchTimeout;
        $('#searchBrand').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                loadBrands(1);
            }, 500);
        });

        $('#filterStatus').change(function() {
            loadBrands(1);
        });

        function openCreateModal() {
            isEditMode = false;
            currentBrandId = null;

            $('#brandForm')[0].reset();
            $('#formMethod').val('POST');
            $('#brandId').val('');
            $('#modalTitleText').text('Create New Brand');
            $('#brandStatus').prop('checked', true);
            $('.error-name').text('');

            $('#modalIcon').removeClass('fa-edit').addClass('fa-tag');
        }

        function editBrand(brand) {
            isEditMode = true;
            currentBrandId = brand.id;

            $('#brandForm')[0].reset();
            $('#formMethod').val('PUT');
            $('#brandId').val(brand.id);
            $('#brandName').val(brand.name);
            $('#brandStatus').prop('checked', brand.is_active == 1);
            $('#modalTitleText').text('Edit Brand');
            $('.error-name').text('');

            $('#modalIcon').removeClass('fa-tag').addClass('fa-edit');

            $('#brandModal').modal('show');
        }

        $('#brandForm').submit(function(e) {
            e.preventDefault();

            $('.error-name').text('');

            const formData = new FormData(this);
            const data = {};

            for (let [key, value] of formData.entries()) {
                if (key !== '_method' && key !== 'brand_id') {
                    data[key] = value;
                }
            }

            if (!$('#brandStatus').is(':checked')) {
                data.is_active = 0;
            }

            const url = isEditMode ?
                `/admin/brands/${currentBrandId}` :
                "{{ route('admin.brands.store') }}";

            const method = isEditMode ? 'PUT' : 'POST';

            Swal.fire({
                title: 'Saving...',
                allowOutsideClick: false,
                width: '30em',
                padding: '2rem',
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: url,
                method: method,
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonColor: '#22c55e',
                        confirmButtonText: 'OK',
                        width: '30em',
                        padding: '2rem',
                        timer: 2000
                    }).then(() => {
                        $('#brandModal').modal('hide');
                        loadBrands($('#paginationContainer .pagination .active .page-link').text() || 1);
                    });
                },
                error: function(xhr) {
                    Swal.close();
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        if (errors.name) {
                            $('.error-name').text(errors.name[0]);
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please check the form for errors',
                            confirmButtonColor: '#22c55e',
                            confirmButtonText: 'OK',
                            width: '30em',
                            padding: '2rem'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Something went wrong',
                            confirmButtonColor: '#22c55e',
                            confirmButtonText: 'OK',
                            width: '30em',
                            padding: '2rem'
                        });
                    }
                }
            });
        });

        function toggleStatus(id, currentStatus) {
            const newStatus = currentStatus ? 0 : 1;

            Swal.fire({
                title: 'Toggle Status?',
                text: `Change status to ${newStatus ? 'Active' : 'Inactive'}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, change it!',
                cancelButtonText: 'Cancel',
                width: '30em',
                padding: '2rem'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/brands/${id}/toggle-status`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: 'Status changed successfully',
                                confirmButtonColor: '#22c55e',
                                confirmButtonText: 'OK',
                                timer: 1500,
                                showConfirmButton: false,
                                width: '30em',
                                padding: '2rem'
                            });
                            loadBrands($('#paginationContainer .pagination .active .page-link').text() || 1);
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to update status',
                                confirmButtonColor: '#22c55e',
                                confirmButtonText: 'OK',
                                width: '30em',
                                padding: '2rem'
                            });
                        }
                    });
                }
            });
        }

        function deleteBrand(id) {
            Swal.fire({
                title: 'Delete Brand?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                width: '30em',
                padding: '2rem'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        allowOutsideClick: false,
                        width: '30em',
                        padding: '2rem',
                        didOpen: () => Swal.showLoading()
                    });

                    $.ajax({
                        url: `/admin/brands/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                confirmButtonColor: '#22c55e',
                                confirmButtonText: 'OK',
                                width: '30em',
                                padding: '2rem',
                                timer: 2000
                            });
                            loadBrands(1);
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Failed to delete brand',
                                confirmButtonColor: '#22c55e',
                                confirmButtonText: 'OK',
                                width: '30em',
                                padding: '2rem'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
