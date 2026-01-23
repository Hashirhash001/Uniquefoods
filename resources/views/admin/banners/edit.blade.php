@extends('admin.layouts.app')

@section('title', 'Edit Banner')

@push('styles')
<style>
    /* Reuse ALL styles from create page */
    * {
        box-sizing: border-box;
    }

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
    }

    .card-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #111827;
    }

    .card-body {
        padding: 1.5rem;
    }

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
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #22c55e;
        display: inline-block;
    }

    .form-label {
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-label .text-danger {
        color: #ef4444;
    }

    .form-control,
    .form-select {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 0.625rem 0.875rem;
        font-size: 14px;
        color: #111827;
        transition: all 0.2s;
        width: 100%;
        background: white;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        outline: none;
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    small.text-danger {
        color: #ef4444;
        font-size: 13px;
        margin-top: 0.25rem;
        display: block;
    }

    .form-text {
        font-size: 13px;
        color: #6b7280;
        margin-top: 0.25rem;
        display: block;
    }

    .input-group {
        display: flex;
    }

    .input-group-text {
        background: #f9fafb;
        border: 1px solid #d1d5db;
        border-right: none;
        color: #6b7280;
        font-weight: 500;
        font-size: 14px;
        padding: 0.625rem 0.875rem;
        border-radius: 6px 0 0 6px;
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 6px 6px 0;
    }

    /* Image Upload */
    .image-upload-box {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 2.5rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #fafafa;
        position: relative;
    }

    .image-upload-box:hover {
        border-color: #22c55e;
        background: #f0fdf4;
    }

    .upload-icon {
        font-size: 3rem;
        margin-bottom: 0.75rem;
        opacity: 0.4;
    }

    .upload-placeholder p {
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        margin: 0 0 0.25rem 0;
    }

    .upload-placeholder small {
        color: #6b7280;
        font-size: 13px;
        line-height: 1.6;
    }

    .image-preview {
        position: relative;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.5rem;
        background: white;
        display: none;
    }

    .image-preview.show {
        display: block;
    }

    .image-preview img {
        width: 100%;
        height: auto;
        max-height: 300px;
        object-fit: contain;
        border-radius: 6px;
        display: block;
    }

    .remove-image {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #ef4444;
        color: white;
        border: 2px solid white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        line-height: 1;
        transition: all 0.2s;
        z-index: 10;
    }

    .remove-image:hover {
        background: #dc2626;
        transform: scale(1.1);
    }

    /* Color Picker */
    .color-input-group {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    input[type="color"] {
        width: 50px;
        height: 42px;
        border: 2px solid #d1d5db;
        border-radius: 6px;
        cursor: pointer;
        padding: 2px;
    }

    input[type="color"]::-webkit-color-swatch-wrapper {
        padding: 0;
    }

    input[type="color"]::-webkit-color-swatch {
        border: none;
        border-radius: 4px;
    }

    .color-input-group .form-control {
        flex: 1;
    }

    /* Checkboxes */
    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: #f9fafb;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }

    .checkbox-wrapper input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #22c55e;
    }

    .checkbox-label {
        font-weight: 500;
        color: #111827;
        margin: 0;
        cursor: pointer;
    }

    /* Footer Buttons */
    .form-footer {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }

    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s;
        min-width: 100px;
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
        border: 1px solid #d1d5db;
        color: #374151;
    }

    .btn-outline-secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        color: #111827;
    }

    .btn-primary {
        background: #22c55e;
        color: white;
    }

    .btn-primary:hover {
        background: #16a34a;
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.25);
    }

    /* Row Spacing */
    .row.g-4 {
        --bs-gutter-x: 1.5rem;
        --bs-gutter-y: 1rem;
    }

    @media (max-width: 768px) {
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
                <li class="breadcrumb-item"><a href="{{ route('admin.banners.index') }}">Banners</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="card product-card">
        <div class="card-header">
            <h4>Edit Banner</h4>
        </div>

        <div class="card-body">
            <form id="bannerForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- BANNER CONTENT -->
                <div class="form-section">
                    <h6 class="section-title">Banner Content</h6>

                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label">Main Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ $banner->title }}" required>
                            <small class="form-text">Use &lt;br&gt; for line breaks</small>
                            <small class="text-danger error-title"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ $banner->sort_order }}" min="0">
                            <small class="form-text">Lower numbers appear first</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Subtitle / Promo Text</label>
                            <input type="text" name="subtitle" class="form-control" value="{{ $banner->subtitle }}">
                            <small class="text-danger error-subtitle"></small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $banner->description }}</textarea>
                            <small class="form-text">Max 500 characters</small>
                            <small class="text-danger error-description"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="button_text" class="form-control" value="{{ $banner->button_text }}">
                            <small class="text-danger error-button_text"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Button Link</label>
                            <input type="text" name="button_link" class="form-control" value="{{ $banner->button_link }}">
                            <small class="form-text">Leave empty to hide button</small>
                            <small class="text-danger error-button_link"></small>
                        </div>
                    </div>
                </div>

                <!-- DESIGN & COLORS -->
                <div class="form-section">
                    <h6 class="section-title">Design & Colors</h6>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Background Color</label>
                            <div class="color-input-group">
                                <input type="color" id="bgColor" value="{{ $banner->background_color }}">
                                <input type="text" name="background_color" id="bgColorText" class="form-control" value="{{ $banner->background_color }}">
                            </div>
                            <small class="form-text">Used if no background image is uploaded</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Text Color</label>
                            <div class="color-input-group">
                                <input type="color" id="textColor" value="{{ $banner->text_color }}">
                                <input type="text" name="text_color" id="textColorText" class="form-control" value="{{ $banner->text_color }}">
                            </div>
                            <small class="form-text">Color for title, subtitle and description</small>
                        </div>
                    </div>
                </div>

                <!-- BANNER IMAGE -->
                <div class="form-section">
                    <h6 class="section-title">Banner Image</h6>

                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label">Upload New Image (Optional)</label>
                            <div class="image-upload-box" id="imageUploadBox">
                                <input type="file" id="imageInput" name="image" accept="image/*" hidden>
                                <div class="upload-placeholder" id="uploadPlaceholder" style="display: {{ $banner->image ? 'none' : 'block' }};">
                                    <div class="upload-icon">📷</div>
                                    <p>Click or drag image here</p>
                                    <small>Recommended: 1920x600px • Max size: 2MB</small>
                                </div>
                            </div>
                            <div class="image-preview {{ $banner->image ? 'show' : '' }}" id="imagePreview">
                                <img id="previewImg" src="{{ $banner->image_url }}" alt="Preview">
                                <button type="button" class="remove-image" id="removeImage">×</button>
                            </div>
                            <small class="text-danger error-image"></small>
                        </div>

                        <div class="col-md-12">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" name="is_active" id="isActive" value="1" {{ $banner->is_active ? 'checked' : '' }}>
                                <label class="checkbox-label" for="isActive">
                                    <i class="fas fa-check-circle" style="color: #22c55e;"></i> Publish this banner
                                </label>
                            </div>
                            <small class="form-text">Only active banners are displayed on the website</small>
                        </div>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="form-footer">
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Banner
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Color picker sync
    $('#bgColor').on('input', function() {
        $('#bgColorText').val($(this).val());
    });
    $('#bgColorText').on('input', function() {
        $('#bgColor').val($(this).val());
    });

    $('#textColor').on('input', function() {
        $('#textColorText').val($(this).val());
    });
    $('#textColorText').on('input', function() {
        $('#textColor').val($(this).val());
    });

    // ✅ Image upload - FIXED (vanilla JS to avoid infinite loop)
    const imageUploadBox = document.getElementById('imageUploadBox');
    const imageInput = document.getElementById('imageInput');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeImageBtn = document.getElementById('removeImage');

    // Click on box triggers input
    imageUploadBox.addEventListener('click', function(e) {
        // Prevent click if clicking on remove button
        if (e.target.id === 'removeImage' || e.target.closest('#removeImage')) {
            return;
        }
        imageInput.click();
    });

    // Handle file selection
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file size
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Image size must be less than 2MB',
                confirmButtonColor: '#22c55e',
                width: '30em',
                padding: '2rem'
            });
            imageInput.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            uploadPlaceholder.style.display = 'none';
            imagePreview.classList.add('show');
        };
        reader.readAsDataURL(file);
    });

    // Remove image
    removeImageBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        imageInput.value = '';
        uploadPlaceholder.style.display = 'block';
        imagePreview.classList.remove('show');
    });

    // Drag and drop
    imageUploadBox.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#22c55e';
        this.style.background = '#f0fdf4';
    });

    imageUploadBox.addEventListener('dragleave', function() {
        this.style.borderColor = '#d1d5db';
        this.style.background = '#fafafa';
    });

    imageUploadBox.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#d1d5db';
        this.style.background = '#fafafa';

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            // Manually trigger change event
            const event = new Event('change', { bubbles: true });
            imageInput.dispatchEvent(event);
        }
    });

    // Form submit
    $('#bannerForm').submit(function(e) {
        e.preventDefault();

        // Clear errors
        $('small.text-danger').text('');
        $('.form-control').removeClass('is-invalid');

        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();

        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

        $.ajax({
            url: '{{ route("admin.banners.update", $banner) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    confirmButtonColor: '#22c55e',
                    width: '30em',
                    padding: '2rem'
                }).then(() => {
                    window.location.href = '{{ route("admin.banners.index") }}';
                });
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalText);

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $(`.error-${field}`).text(messages[0]);
                        $(`[name="${field}"]`).addClass('is-invalid');
                    });

                    const firstError = $('.text-danger').filter(function() {
                        return $(this).text().length > 0;
                    }).first();

                    if (firstError.length) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 500);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Something went wrong',
                        confirmButtonColor: '#22c55e',
                        width: '30em',
                        padding: '2rem'
                    });
                }
            }
        });
    });

    // Remove error on input
    $('.form-control').on('input change', function() {
        $(this).removeClass('is-invalid');
        const fieldName = $(this).attr('name');
        $(`.error-${fieldName}`).text('');
    });
});
</script>
@endpush
@endsection
