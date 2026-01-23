@extends('admin.layouts.app')

@section('title', 'Banners')

@push('styles')
<style>
    /* Match your product form styles */
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

    .product-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .card-header {
        background: white;
        border-bottom: 1px solid #e5e7eb;
        padding: 1.25rem 1.5rem;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #111827;
    }

    .card-body {
        padding: 0;
    }

    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary {
        background: #22c55e;
        color: white;
    }

    .btn-primary:hover {
        background: #16a34a;
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.25);
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 13px;
        min-width: auto;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    /* Table Styles */
    .table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
    }

    .table thead th {
        background: #f9fafb;
        padding: 1rem 1.5rem;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        border-bottom: 1px solid #e5e7eb;
    }

    .table tbody td {
        padding: 1rem 1.5rem;
        font-size: 14px;
        color: #111827;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f9fafb;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Banner Preview */
    .banner-thumb {
        width: 80px;
        height: 80px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9fafb;
    }

    .banner-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .banner-info {
        max-width: 400px;
    }

    .banner-title {
        font-size: 15px;
        font-weight: 600;
        color: #111827;
        margin: 0 0 0.375rem 0;
        line-height: 1.4;
    }

    .banner-subtitle {
        font-size: 13px;
        color: #22c55e;
        margin: 0 0 0.375rem 0;
        font-weight: 500;
    }

    .banner-desc {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
        line-height: 1.5;
    }

    .color-dots {
        display: flex;
        gap: 6px;
        margin-top: 0.5rem;
    }

    .color-dot {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        border: 1px solid #d1d5db;
        cursor: help;
    }

    .button-preview {
        background: #f9fafb;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        display: inline-block;
    }

    .button-preview-text {
        font-size: 13px;
        font-weight: 600;
        color: #111827;
        margin: 0 0 0.25rem 0;
    }

    .button-preview-link {
        font-size: 11px;
        color: #6b7280;
        margin: 0;
    }

    /* Badge Status */
    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .badge-success {
        background: #dcfce7;
        color: #166534;
    }

    .badge-success:hover {
        background: #bbf7d0;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-danger:hover {
        background: #fecaca;
    }

    .badge i {
        font-size: 8px;
    }

    .badge-light {
        background: #f3f4f6;
        color: #374151;
        cursor: default;
    }

    /* Button Group */
    .btn-group {
        display: flex;
        gap: 0.5rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        margin: 0 0 0.5rem 0;
    }

    .empty-state p {
        font-size: 14px;
        color: #6b7280;
        margin: 0 0 1.5rem 0;
    }

    /* Pagination */
    .card-footer {
        padding: 1rem 1.5rem;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        border-radius: 0 0 8px 8px;
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
                <li class="breadcrumb-item active">Banners</li>
            </ol>
        </nav>
    </div>

    <div class="card product-card">
        <div class="card-header">
            <h4>Banners Management</h4>
            <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Banner
            </a>
        </div>

        <div class="card-body">
            @if($banners->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th width="100">Preview</th>
                            <th>Title & Content</th>
                            <th width="140">Button</th>
                            <th width="80" class="text-center">Order</th>
                            <th width="120" class="text-center">Status</th>
                            <th width="140" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($banners as $banner)
                            <tr id="banner-{{ $banner->id }}">
                                <td>
                                    <div class="banner-thumb">
                                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="banner-info">
                                        <h5 class="banner-title">{{ $banner->title }}</h5>
                                        @if($banner->subtitle)
                                            <p class="banner-subtitle">{{ $banner->subtitle }}</p>
                                        @endif
                                        @if($banner->description)
                                            <p class="banner-desc">{{ Str::limit($banner->description, 80) }}</p>
                                        @endif
                                        <div class="color-dots">
                                            <span class="color-dot" style="background: {{ $banner->background_color }}" title="Background: {{ $banner->background_color }}"></span>
                                            <span class="color-dot" style="background: {{ $banner->text_color }}" title="Text: {{ $banner->text_color }}"></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($banner->button_text)
                                        <div class="button-preview">
                                            <div class="button-preview-text">{{ $banner->button_text }}</div>
                                            @if($banner->button_link)
                                                <div class="button-preview-link">{{ Str::limit($banner->button_link, 20) }}</div>
                                            @endif
                                        </div>
                                    @else
                                        <span style="color: #9ca3af;">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-light">{{ $banner->sort_order }}</span>
                                </td>
                                <td class="text-center">
                                    <button class="badge {{ $banner->is_active ? 'badge-success' : 'badge-danger' }} toggle-status"
                                            data-id="{{ $banner->id }}"
                                            title="Click to toggle">
                                        <i class="fas fa-circle"></i>
                                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.banners.edit', $banner) }}"
                                           class="btn btn-primary btn-sm"
                                           title="Edit Banner">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $banner->id }}"
                                                title="Delete Banner">
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
                    <div class="empty-icon">📷</div>
                    <h3>No Banners Found</h3>
                    <p>Create your first banner to display on the home page</p>
                    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Banner
                    </a>
                </div>
            @endif
        </div>

        @if($banners->hasPages())
            <div class="card-footer">
                {{ $banners->links() }}
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle status
    $('.toggle-status').click(function() {
        const id = $(this).data('id');
        const btn = $(this);

        $.ajax({
            url: `/admin/banners/${id}/toggle-status`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.is_active) {
                    btn.removeClass('badge-danger').addClass('badge-success')
                       .html('<i class="fas fa-circle"></i> Active');
                } else {
                    btn.removeClass('badge-success').addClass('badge-danger')
                       .html('<i class="fas fa-circle"></i> Inactive');
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    confirmButtonColor: '#22c55e',
                    timer: 2000,
                    width: '30em',
                    padding: '2rem'
                });
            }
        });
    });

    // Delete
    $('.delete-btn').click(function() {
        const id = $(this).data('id');
        const row = $(`#banner-${id}`);

        Swal.fire({
            title: 'Delete Banner?',
            text: 'This action cannot be undone',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            width: '30em',
            padding: '2rem'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/banners/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        row.fadeOut(300, function() {
                            $(this).remove();

                            if ($('tbody tr').length === 0) {
                                location.reload();
                            }
                        });

                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            confirmButtonColor: '#22c55e',
                            timer: 2000,
                            width: '30em',
                            padding: '2rem'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
@endsection
