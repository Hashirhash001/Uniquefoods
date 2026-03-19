@extends('admin.layouts.app')

@section('title', 'Banners')

@push('styles')
<style>
    .page-header { margin-bottom: 1.25rem; }

    .breadcrumb { background: transparent; padding: 0; margin: 0; font-size: 14px; }
    .breadcrumb-item { color: #6b7280; }
    .breadcrumb-item a { color: #6b7280; text-decoration: none; transition: color 0.2s; }
    .breadcrumb-item a:hover { color: #08437b; }
    .breadcrumb-item.active { color: #111827; font-weight: 500; }
    .breadcrumb-item + .breadcrumb-item::before { color: #d1d5db; content: "/"; }

    .product-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    .card-header {
        background: white;
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem 1.25rem;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .card-header h4 { margin: 0; font-size: 17px; font-weight: 600; color: #111827; }

    .btn {
        padding: 0.55rem 1.1rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        text-decoration: none;
    }
    .btn-primary { background: #08437b; color: white; }
    .btn-primary:hover { background: #0f508d; color: white; }
    .btn-sm { padding: 0.35rem 0.7rem; font-size: 12px; }
    .btn-danger { background: #ef4444; color: white; }
    .btn-danger:hover { background: #dc2626; color: white; }

    /* Table */
    .table { width: 100%; margin: 0; border-collapse: collapse; }
    .table thead th {
        background: #f9fafb;
        padding: 0.875rem 1rem;
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }
    .table tbody td {
        padding: 0.875rem 1rem;
        font-size: 14px;
        color: #111827;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .table tbody tr:hover { background: #f9fafb; }
    .table tbody tr:last-child td { border-bottom: none; }

    /* Banner thumb */
    .banner-thumb {
        width: 72px; height: 56px;
        border-radius: 6px; overflow: hidden;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .banner-thumb img { width: 100%; height: 100%; object-fit: cover; }

    .banner-title { font-size: 14px; font-weight: 600; color: #111827; margin: 0 0 3px 0; line-height: 1.4; }
    .banner-subtitle { font-size: 12px; color: #08437b; margin: 0 0 3px 0; font-weight: 500; }
    .banner-desc { font-size: 12px; color: #6b7280; margin: 0; line-height: 1.5; }

    .color-dots { display: flex; gap: 5px; margin-top: 6px; flex-wrap: wrap; }
    .color-dot {
        width: 18px; height: 18px; border-radius: 4px;
        border: 1px solid #d1d5db; cursor: help; flex-shrink: 0;
    }

    .button-preview {
        background: #f9fafb; padding: 6px 10px;
        border-radius: 6px; border: 1px solid #e5e7eb;
        display: inline-block; max-width: 120px;
    }
    .button-preview-text { font-size: 12px; font-weight: 600; color: #111827; margin: 0 0 2px 0; }
    .button-preview-link { font-size: 11px; color: #6b7280; margin: 0; word-break: break-all; }

    /* Badges */
    .badge {
        padding: 4px 10px; border-radius: 6px;
        font-size: 11px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.025em;
        border: none; cursor: pointer; transition: all 0.2s;
        display: inline-flex; align-items: center; gap: 4px;
        white-space: nowrap;
    }
    .badge i { font-size: 7px; }
    .badge-success  { background: #dcfce7; color: #166534; }
    .badge-success:hover { background: #bbf7d0; }
    .badge-danger   { background: #fee2e2; color: #991b1b; }
    .badge-danger:hover { background: #fecaca; }
    .badge-light    { background: #f3f4f6; color: #374151; cursor: default; }

    .btn-group { display: flex; gap: 6px; justify-content: center; }

    /* Empty */
    .empty-state { text-align: center; padding: 4rem 2rem; }
    .empty-icon   { font-size: 4rem; color: #d1d5db; margin-bottom: 1rem; }
    .empty-state h3 { font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 0.5rem 0; }
    .empty-state p  { font-size: 14px; color: #6b7280; margin: 0 0 1.5rem 0; }

    /* Pagination */
    .card-footer {
        padding: 1rem 1.25rem;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        border-radius: 0 0 8px 8px;
    }

    /* ============================================
       MOBILE CARD VIEW — replaces table on small screens
       ============================================ */
    .banner-mobile-card {
        display: none;
        border-bottom: 1px solid #f3f4f6;
        padding: 1rem;
        gap: 0.75rem;
    }
    .banner-mobile-card:last-of-type { border-bottom: none; }

    .bmc-top {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
    }
    .bmc-info { flex: 1; min-width: 0; }
    .bmc-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 8px;
    }
    .bmc-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
    }

    @media (max-width: 767px) {
        /* Hide desktop table */
        .desktop-table { display: none !important; }
        /* Show mobile cards */
        .banner-mobile-card { display: flex; flex-direction: column; }

        .card-header h4 { font-size: 15px; }
        .btn { font-size: 13px; padding: 0.5rem 0.9rem; }
    }

    @media (min-width: 768px) {
        /* On desktop, hide mobile cards */
        .banner-mobile-card { display: none !important; }
        /* Show table wrapped in table-responsive */
        .desktop-table { display: block !important; }
    }

    @media (max-width: 575px) {
        .container-fluid { padding: 0.75rem !important; }
        .card-header { padding: 0.875rem 1rem; }
        .empty-state { padding: 2.5rem 1rem; }
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
                <i class="fas fa-plus"></i> Add Banner
            </a>
        </div>

        <div class="card-body">
            @if($banners->count() > 0)

                {{-- ✅ DESKTOP TABLE --}}
                <div class="desktop-table">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="90">Preview</th>
                                    <th>Title & Content</th>
                                    <th width="130">Button</th>
                                    <th width="70" class="text-center">Order</th>
                                    <th width="110" class="text-center">Status</th>
                                    <th width="130" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($banners as $banner)
                                    <tr id="banner-row-{{ $banner->id }}">
                                        <td>
                                            <div class="banner-thumb">
                                                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div style="max-width:380px;">
                                                <h5 class="banner-title">{{ $banner->title }}</h5>
                                                @if($banner->subtitle)
                                                    <p class="banner-subtitle">{{ $banner->subtitle }}</p>
                                                @endif
                                                @if($banner->description)
                                                    <p class="banner-desc">{{ Str::limit($banner->description, 80) }}</p>
                                                @endif
                                                <div class="color-dots">
                                                    <span class="color-dot" style="background:{{ $banner->background_color }}" title="BG: {{ $banner->background_color }}"></span>
                                                    <span class="color-dot" style="background:{{ $banner->text_color }}" title="Text: {{ $banner->text_color }}"></span>
                                                    @if($banner->title_color)
                                                        <span class="color-dot" style="background:{{ $banner->title_color }}" title="Title: {{ $banner->title_color }}"></span>
                                                    @endif
                                                    @if($banner->subtitle_color)
                                                        <span class="color-dot" style="background:{{ $banner->subtitle_color }}" title="Subtitle: {{ $banner->subtitle_color }}"></span>
                                                    @endif
                                                    @if($banner->subtitle_bg_color)
                                                        <span class="color-dot" style="background:{{ $banner->subtitle_bg_color }}" title="Subtitle BG: {{ $banner->subtitle_bg_color }}"></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($banner->button_text)
                                                <div class="button-preview">
                                                    <div class="button-preview-text">{{ $banner->button_text }}</div>
                                                    @if($banner->button_link)
                                                        <div class="button-preview-link">{{ Str::limit($banner->button_link, 22) }}</div>
                                                    @endif
                                                </div>
                                            @else
                                                <span style="color:#9ca3af;">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-light">{{ $banner->sort_order }}</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="badge {{ $banner->is_active ? 'badge-success' : 'badge-danger' }} toggle-status"
                                                    data-id="{{ $banner->id }}">
                                                <i class="fas fa-circle"></i>
                                                {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-primary btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $banner->id }}" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ✅ MOBILE CARDS --}}
                @foreach($banners as $banner)
                    <div class="banner-mobile-card" id="banner-card-{{ $banner->id }}">
                        <div class="bmc-top">
                            <div class="banner-thumb">
                                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}">
                            </div>
                            <div class="bmc-info">
                                <div class="banner-title">{{ $banner->title }}</div>
                                @if($banner->subtitle)
                                    <div class="banner-subtitle">{{ $banner->subtitle }}</div>
                                @endif
                                @if($banner->description)
                                    <div class="banner-desc">{{ Str::limit($banner->description, 60) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="bmc-meta">
                            <div class="color-dots">
                                <span class="color-dot" style="background:{{ $banner->background_color }}" title="BG"></span>
                                <span class="color-dot" style="background:{{ $banner->text_color }}" title="Text"></span>
                                @if($banner->title_color)
                                    <span class="color-dot" style="background:{{ $banner->title_color }}" title="Title"></span>
                                @endif
                                @if($banner->subtitle_bg_color)
                                    <span class="color-dot" style="background:{{ $banner->subtitle_bg_color }}" title="Subtitle BG"></span>
                                @endif
                            </div>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <span class="badge badge-light" style="font-size:11px;">Order: {{ $banner->sort_order }}</span>
                                @if($banner->button_text)
                                    <span style="font-size:11px;color:#6b7280;background:#f3f4f6;padding:3px 8px;border-radius:5px;">
                                        {{ $banner->button_text }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="bmc-actions">
                            <button class="badge {{ $banner->is_active ? 'badge-success' : 'badge-danger' }} toggle-status"
                                    data-id="{{ $banner->id }}">
                                <i class="fas fa-circle"></i>
                                {{ $banner->is_active ? 'Active' : 'Inactive' }}
                            </button>
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $banner->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach

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

    // Toggle status — updates BOTH desktop row and mobile card
    $(document).on('click', '.toggle-status', function() {
        const id   = $(this).data('id');
        const btns = $(`.toggle-status[data-id="${id}"]`); // selects both desktop + mobile

        $.ajax({
            url: `/admin/banners/${id}/toggle-status`,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                btns.each(function() {
                    if (response.is_active) {
                        $(this).removeClass('badge-danger').addClass('badge-success')
                               .html('<i class="fas fa-circle"></i> Active');
                    } else {
                        $(this).removeClass('badge-success').addClass('badge-danger')
                               .html('<i class="fas fa-circle"></i> Inactive');
                    }
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Updated',
                    text: response.message,
                    confirmButtonColor: '#08437b',
                    timer: 2000,
                    showConfirmButton: false,
                    width: '30em',
                    padding: '2rem'
                });
            }
        });
    });

    // Delete — removes BOTH desktop row and mobile card
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');

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
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        // Remove both desktop row and mobile card
                        $(`#banner-row-${id}`).fadeOut(300, function() { $(this).remove(); });
                        $(`#banner-card-${id}`).fadeOut(300, function() {
                            $(this).remove();
                            // Reload if nothing left
                            if ($('.delete-btn').length === 0) location.reload();
                        });

                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            confirmButtonColor: '#08437b',
                            timer: 2000,
                            showConfirmButton: false,
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
