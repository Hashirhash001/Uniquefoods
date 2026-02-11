@extends('admin.layouts.app')

@section('title','Create Product')

@push('styles')
<style>
    /* Reset & Base */
    * {
        box-sizing: border-box;
    }

    .error-images {
        display: block !important;
        min-height: 18px;
        line-height: 1.4;
    }

    /* Page Header */
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
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #22c55e;
        display: inline-block;
    }

    /* Form Labels */
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

    /* Form Controls */
    .form-control,
    .form-select {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 0.625rem 0.875rem;
        font-size: 14px;
        color: #111827;
        transition: all 0.2s;
        height: 42px;
        background: white;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        outline: none;
    }

    .form-control::placeholder {
        color: #9ca3af;
    }

    textarea.form-control {
        min-height: 100px;
        height: auto;
        resize: vertical;
        padding: 0.625rem 0.875rem;
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #ef4444;
    }

    /* Error Messages */
    small.text-danger {
        color: #ef4444;
        font-size: 13px;
        margin-top: 0.25rem;
        display: block;
        font-weight: 400;
    }

    /* Helper Text */
    .form-text {
        font-size: 13px;
        color: #6b7280;
        margin-top: 0.25rem;
        display: block;
    }

    /* Input Groups */
    .input-group-text {
        background: #f9fafb;
        border: 1px solid #d1d5db;
        border-right: none;
        color: #6b7280;
        font-weight: 500;
        font-size: 14px;
        padding: 0.625rem 0.875rem;
    }

    .input-group .form-control {
        border-left: none;
    }

    .input-group .form-control:focus {
        border-left: 1px solid #22c55e;
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
        margin-bottom: 0.25rem;
        margin-top: 0;
    }

    .upload-placeholder small {
        color: #6b7280;
        font-size: 13px;
    }

    /* Image Preview */
    .preview-card {
        position: relative;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 0.375rem;
        background: white;
        overflow: hidden;
    }

    .preview-card img {
        width: 100%;
        height: 140px;
        object-fit: cover;
        border-radius: 4px;
        display: block;
    }

    .preview-remove {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: #ef4444;
        color: white;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
        font-weight: 600;
        z-index: 10;
        transition: all 0.2s;
        border: 2px solid white;
    }

    .preview-remove:hover {
        background: #dc2626;
        transform: scale(1.1);
    }

    .preview-card .badge {
        position: absolute;
        bottom: 0.5rem;
        left: 0.5rem;
        background: #22c55e;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
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

    .btn-primary:active {
        transform: translateY(0);
    }

    /* Row Spacing */
    .row.g-4 {
        --bs-gutter-x: 1.5rem;
        --bs-gutter-y: 1rem;
    }

    /* Column Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.25rem;
        }

        .form-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .section-title {
            font-size: 15px;
        }

        .image-upload-box {
            padding: 2rem 1rem;
        }

        .upload-icon {
            font-size: 2.5rem;
        }

        .form-footer {
            flex-direction: column-reverse;
        }

        .form-footer .btn {
            width: 100%;
        }

        .preview-card img {
            height: 120px;
        }
    }

    /* Additional Professional Touches */
    select.form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        padding-right: 2.5rem;
    }

    /* Ensure consistent spacing */
    .col-md-2, .col-md-3, .col-md-4, .col-md-6, .col-md-8, .col-md-12 {
        padding-left: calc(var(--bs-gutter-x) * 0.5);
        padding-right: calc(var(--bs-gutter-x) * 0.5);
    }

    /* SweetAlert2 Consistency */
    .swal2-popup {
        font-size: 14px !important;
        border-radius: 8px !important;
    }

    .swal2-title {
        font-size: 20px !important;
        font-weight: 600 !important;
    }

    .swal2-html-container {
        font-size: 14px !important;
    }

    .swal2-confirm, .swal2-cancel {
        font-size: 14px !important;
        padding: 0.625rem 1.25rem !important;
        border-radius: 6px !important;
        font-weight: 500 !important;
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
                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>

    <div class="card product-card">
        <div class="card-header">
            <h4>Create New Product</h4>
        </div>

        <div class="card-body">
            <form id="productForm" enctype="multipart/form-data">
                @csrf

                <!-- BASIC INFO -->
                <div class="form-section">
                    <h6 class="section-title">Basic Information</h6>

                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., Fresh Tomatoes, Basmati Rice 5kg">
                            <small class="text-danger error-name"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger error-category_id"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" class="form-select">
                                <option value="">No Brand / Generic</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger error-brand_id"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Unit Type <span class="text-danger">*</span></label>
                            <select name="unit" class="form-select">
                                <option value="pcs">Pieces (pcs)</option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="g">Gram (g)</option>
                                <option value="l">Liter (l)</option>
                                <option value="ml">Milliliter (ml)</option>
                                <option value="dozen">Dozen</option>
                            </select>
                            <small class="form-text">How this product is sold</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Weight Based Pricing?</label>
                            <select name="is_weight_based" class="form-select" id="weightToggle">
                                <option value="0">No - Fixed Price</option>
                                <option value="1">Yes - Price per KG</option>
                            </select>
                            <small class="form-text">e.g., vegetables, fruits, meat</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Product description (optional)"></textarea>
                            <small class="text-danger error-description"></small>
                        </div>
                    </div>
                </div>

                <!-- PRICING -->
                <div class="form-section">
                    <h6 class="section-title">Pricing & Stock</h6>

                    <div class="row g-4">
                        <div class="col-md-3">
                            <label class="form-label">Selling Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00">
                            </div>
                            <small class="text-danger error-price"></small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">MRP (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="mrp" class="form-control" placeholder="0.00">
                            </div>
                            <small class="form-text">Maximum Retail Price</small>
                            <small class="text-danger error-mrp"></small>
                        </div>

                        <div class="col-md-3 d-none" id="pricePerKgWrapper">
                            <label class="form-label">Price per KG <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="price_per_kg" class="form-control" placeholder="0.00">
                            </div>
                            <small class="text-danger error-price_per_kg"></small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control" placeholder="0" value="0">
                            <small class="text-danger error-stock"></small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tax Rate (%)</label>
                            <input type="number" step="0.01" name="tax_rate" class="form-control" placeholder="0.00">
                            <small class="form-text">GST/VAT percentage</small>
                            <small class="text-danger error-tax_rate"></small>
                        </div>

                        <div class="col-md-3 d-none" id="minWeightWrapper">
                            <label class="form-label">Min Weight (kg)</label>
                            <input type="number" step="0.001" name="min_weight" class="form-control" placeholder="0.250">
                            <small class="text-danger error-min_weight"></small>
                        </div>

                        <div class="col-md-3 d-none" id="maxWeightWrapper">
                            <label class="form-label">Max Weight (kg)</label>
                            <input type="number" step="0.001" name="max_weight" class="form-control" placeholder="5.000">
                            <small class="text-danger error-max_weight"></small>
                        </div>
                    </div>
                </div>

                <!-- ADDITIONAL INFO -->
                <div class="form-section">
                    <h6 class="section-title">Additional Information</h6>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label">Barcode</label>
                            <input type="text" name="barcode" class="form-control" placeholder="Product barcode">
                            <small class="text-danger error-barcode"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Featured Product?</label>
                            <select name="is_featured" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <small class="form-text">Show on homepage</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Popular Product?</label>
                            <select name="is_popular" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <small class="form-text">Show in popular products section</small>
                        </div>

                    </div>
                </div>

                <!-- IMAGES -->
                <div class="form-section">
                    <h6 class="section-title">Product Images</h6>

                    <div class="image-upload-box" id="imageUploadBox">
                        <input type="file" id="imageInput" accept="image/*" multiple hidden>
                        <div class="upload-placeholder">
                            <div class="upload-icon">📷</div>
                            <p>Click or drag images here</p>
                            <small>Maximum 5 images • Max 100 KB per image • First image will be primary</small>
                        </div>
                    </div>
                    <small class="text-danger error-images" style="display: block; margin-top: 0.5rem;"></small>

                    {{-- <small class="text-danger error-images"></small> --}}
                    <div class="row g-3 mt-3" id="imagePreview"></div>
                </div>

                <!-- FOOTER -->
                <div class="form-footer">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Save Product
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>

/* ================= FRONTEND FILE SIZE VALIDATION ================= */
const MAX_FILE_SIZE = 100 * 1024; // 100KB in bytes
const MAX_FILES = 5;

function validateFileSize(file) {
    if (file.size > MAX_FILE_SIZE) {
        const fileSizeKB = (file.size / 1024).toFixed(2);
        return {
            valid: false,
            message: `${file.name} is ${fileSizeKB} KB. Maximum allowed size is 100 KB`
        };
    }
    return { valid: true };
}

function showImageError(message) {
    $('.error-images').text(message);

    Swal.fire({
        icon: 'error',
        title: 'Image Upload Error',
        html: message,
        confirmButtonColor: '#22c55e',
        confirmButtonText: 'OK',
        width: '35em',
        padding: '2rem'
    });
}

/* ================= IMAGE HANDLING ================= */
let selectedFiles = [];
const imageInput = document.getElementById('imageInput');
const imagePreview = document.getElementById('imagePreview');
const uploadBox = document.getElementById('imageUploadBox');

uploadBox.onclick = () => imageInput.click();

uploadBox.ondragover = (e) => {
    e.preventDefault();
    uploadBox.style.borderColor = '#22c55e';
    uploadBox.style.background = '#f0fdf4';
};

uploadBox.ondragleave = () => {
    uploadBox.style.borderColor = '#d1d5db';
    uploadBox.style.background = '#fafafa';
};

uploadBox.ondrop = (e) => {
    e.preventDefault();
    uploadBox.style.borderColor = '#d1d5db';
    uploadBox.style.background = '#fafafa';

    const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
    handleFiles(files);
};

imageInput.onchange = () => {
    handleFiles(Array.from(imageInput.files));
};

function handleFiles(files) {
    // Clear previous errors
    $('.error-images').text('');

    // Check total count
    const totalImages = selectedFiles.length + files.length;

    if (totalImages > MAX_FILES) {
        showImageError(`Maximum ${MAX_FILES} images allowed. You already have ${selectedFiles.length} image(s).`);
        imageInput.value = ''; // Clear input
        return;
    }

    // Validate each file
    const invalidFiles = [];
    const validFiles = [];

    files.forEach(file => {
        const validation = validateFileSize(file);
        if (!validation.valid) {
            invalidFiles.push(validation.message);
        } else {
            validFiles.push(file);
        }
    });

    // Show errors if any
    if (invalidFiles.length > 0) {
        const errorMessage = invalidFiles.join('<br>');
        showImageError(errorMessage);
        imageInput.value = ''; // Clear input
        return;
    }

    // Add valid files
    selectedFiles.push(...validFiles);
    renderPreviews();
    imageInput.value = ''; // Clear input for next selection
}

function renderPreviews() {
    imagePreview.innerHTML = '';
    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            const fileSizeKB = (file.size / 1024).toFixed(2);
            const col = document.createElement('div');
            col.className = 'col-md-2 col-6';
            col.innerHTML = `
                <div class="preview-card">
                    <span class="preview-remove" onclick="removeImage(${index})">×</span>
                    <img src="${e.target.result}" alt="Preview">
                    ${index === 0 ? '<div class="badge">Primary</div>' : ''}
                    <div style="position:absolute;bottom:0.5rem;right:0.5rem;background:rgba(0,0,0,0.7);color:white;padding:0.125rem 0.375rem;border-radius:3px;font-size:10px;">${fileSizeKB} KB</div>
                </div>`;
            imagePreview.appendChild(col);
        };
        reader.readAsDataURL(file);
    });
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    renderPreviews();
    $('.error-images').text('');
}

/* ================= WEIGHT TOGGLE ================= */
$('#weightToggle').change(function () {
    const isWeightBased = $(this).val() == '1';
    $('#pricePerKgWrapper').toggleClass('d-none', !isWeightBased);
    $('#minWeightWrapper').toggleClass('d-none', !isWeightBased);
    $('#maxWeightWrapper').toggleClass('d-none', !isWeightBased);

    if (isWeightBased) {
        $('input[name="price_per_kg"]').attr('required', true);
    } else {
        $('input[name="price_per_kg"]').removeAttr('required');
    }
});

/* ================= FORM SUBMIT ================= */
$('#productForm').submit(function (e) {
    e.preventDefault();

    // Clear previous errors
    $('small.text-danger').text('');
    $('.form-control, .form-select').removeClass('is-invalid');

    const formData = new FormData(this);

    // Remove images from form data and add selected files
    formData.delete('images[]');
    selectedFiles.forEach(file => formData.append('images[]', file));

    // Show loading
    Swal.fire({
        title: 'Saving Product...',
        html: 'Please wait while we create your product',
        allowOutsideClick: false,
        allowEscapeKey: false,
        width: '30em',
        padding: '2rem',
        didOpen: () => Swal.showLoading()
    });

    $.ajax({
        url: "{{ route('admin.products.store') }}",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message || 'Product created successfully',
                confirmButtonColor: '#22c55e',
                confirmButtonText: 'View Products',
                width: '30em',
                padding: '2rem'
            }).then(() => {
                window.location.href = "{{ route('admin.products.index') }}";
            });
        },
        error: function(xhr) {
            Swal.close();

            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let errorMessages = [];

                Object.entries(errors).forEach(([field, messages]) => {
                    const cleanField = field.replace(/\.\d+$/, '');
                    const errorElement = $(`.error-${cleanField}`);
                    const inputElement = $(`[name="${field}"], [name="${cleanField}"], [name="${cleanField}[]"]`);

                    if (errorElement.length) {
                        if (!errorElement.text()) {
                            errorElement.text(messages[0]);
                        }
                        inputElement.addClass('is-invalid');
                    }

                    errorMessages.push(...messages);
                });

                const firstError = $('.text-danger').filter(function() {
                    return $(this).text().length > 0;
                }).first();

                if (firstError.length) {
                    $('html, body').animate({
                        scrollTop: firstError.offset().top - 100
                    }, 500);
                }

                const errorList = errorMessages.map(msg => `• ${msg}`).join('<br>');

                Swal.fire({
                    icon: 'error',
                    title: 'Validation Errors',
                    html: `<div style="text-align: left; font-size: 14px; line-height: 1.6;">${errorList}</div>`,
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: 'OK',
                    width: '40em',
                    padding: '2rem'
                });
            } else if (xhr.status === 413) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'Total upload size is too large. Please upload smaller files.',
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: 'OK',
                    width: '30em',
                    padding: '2rem'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Something went wrong. Please try again.',
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: 'OK',
                    width: '30em',
                    padding: '2rem'
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

</script>
@endpush
