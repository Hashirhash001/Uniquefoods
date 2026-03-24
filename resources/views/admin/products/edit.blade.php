@extends('admin.layouts.app')

@section('title','Edit Product')

@push('styles')
<style>
    * { box-sizing: border-box; }
    .page-header { margin-bottom: 1.25rem; }
    .breadcrumb { background: transparent; padding: 0; margin: 0; font-size: 14px; }
    .breadcrumb-item { color: #6b7280; }
    .breadcrumb-item a { color: #6b7280; text-decoration: none; transition: color 0.2s; }
    .breadcrumb-item a:hover { color: #08437b; }
    .breadcrumb-item.active { color: #111827; font-weight: 500; }
    .breadcrumb-item + .breadcrumb-item::before { color: #d1d5db; content: "/"; }
    .product-card { background: white; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); margin-bottom: 2rem; }
    .card-header { background: white; border-bottom: 1px solid #e5e7eb; padding: 1.25rem 1.5rem; border-radius: 8px 8px 0 0; }
    .card-header h4 { margin: 0; font-size: 18px; font-weight: 600; color: #111827; }
    .card-body { padding: 1.5rem; }
    .form-section { margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid #e5e7eb; }
    .form-section:last-of-type { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .section-title { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 1.25rem; padding-bottom: 0.5rem; border-bottom: 2px solid #08437b; display: inline-block; }
    .form-label { font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 0.5rem; display: block; }
    .form-label .text-danger { color: #ef4444; }
    .form-control, .form-select { border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem; font-size: 14px; color: #111827; transition: all 0.2s; height: 42px; background: white; width: 100%; }
    .form-control:focus, .form-select:focus { border-color: #08437b; box-shadow: 0 0 0 3px rgba(8,67,123,0.1); outline: none; }
    .form-control::placeholder { color: #9ca3af; }
    textarea.form-control { min-height: 100px; height: auto; resize: vertical; }
    .form-control.is-invalid, .form-select.is-invalid { border-color: #ef4444; }
    small.text-danger { color: #ef4444; font-size: 13px; margin-top: 0.25rem; display: block; }
    .form-text { font-size: 13px; color: #6b7280; margin-top: 0.25rem; display: block; }
    .input-group { display: flex; width: 100%; }
    .input-group-text { background: #f9fafb; border: 1px solid #d1d5db; border-right: none; color: #6b7280; font-weight: 500; font-size: 14px; padding: 0.625rem 0.875rem; display: flex; align-items: center; border-radius: 6px 0 0 6px; }
    .input-group .form-control { border-left: none; border-radius: 0 6px 6px 0; }
    .input-group .form-control:focus { border-left: 1px solid #08437b; }
    .searchable-select-wrapper { position: relative; }
    .select-trigger { width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem 0.625rem 0.875rem; font-size: 14px; color: #111827; background: white; cursor: pointer; height: 42px; display: flex; align-items: center; justify-content: space-between; transition: border-color 0.2s; user-select: none; }
    .select-trigger:hover { border-color: #9ca3af; }
    .select-trigger.open { border-color: #08437b; border-radius: 6px 6px 0 0; }
    .select-trigger .trigger-text { flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .select-trigger .trigger-arrow { font-size: 11px; color: #6b7280; transition: transform 0.2s; flex-shrink: 0; margin-left: 8px; }
    .select-trigger.open .trigger-arrow { transform: rotate(180deg); }
    .select-dropdown { position: absolute; top: 100%; left: 0; right: 0; z-index: 999; background: white; border: 1px solid #08437b; border-top: none; border-radius: 0 0 6px 6px; max-height: 240px; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.07); display: none; }
    .select-dropdown.open { display: block; }
    .select-search { padding: 0.5rem; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; background: white; }
    .select-search input { width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 0.375rem 0.625rem; font-size: 13px; outline: none; }
    .select-search input:focus { border-color: #08437b; }
    .select-option { padding: 0.5rem 0.875rem; font-size: 14px; color: #111827; cursor: pointer; transition: background 0.15s; }
    .select-option:hover { background: #f3f4f6; }
    .select-option.selected { background: #eff6ff; color: #1e40af; font-weight: 500; }
    .select-option.placeholder { color: #9ca3af; }
    .select-optgroup-label { padding: 0.375rem 0.875rem; font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; background: #f9fafb; border-top: 1px solid #e5e7eb; }
    .select-option.grouped { padding-left: 1.5rem; }
    .select-no-results { padding: 0.75rem; font-size: 13px; color: #9ca3af; text-align: center; }
    .image-upload-box { border: 2px dashed #d1d5db; border-radius: 8px; padding: 2.5rem 1.5rem; text-align: center; cursor: pointer; transition: all 0.2s; background: #fafafa; }
    .image-upload-box:hover { border-color: #08437b; background: #f0fdf4; }
    .upload-icon { font-size: 3rem; margin-bottom: 0.75rem; opacity: 0.4; }
    .upload-placeholder p { font-size: 14px; font-weight: 500; color: #374151; margin: 0 0 0.25rem; }
    .upload-placeholder small { color: #6b7280; font-size: 13px; }
    .preview-card { position: relative; border: 1px solid #e5e7eb; border-radius: 6px; padding: 0.375rem; background: white; overflow: hidden; }
    .preview-card img { width: 100%; height: 140px; object-fit: cover; border-radius: 4px; display: block; }
    .preview-remove { position: absolute; top: 0.5rem; right: 0.5rem; background: #ef4444; color: white; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 18px; line-height: 1; font-weight: 600; z-index: 10; transition: all 0.2s; border: 2px solid white; }
    .preview-remove:hover { background: #dc2626; transform: scale(1.1); }
    .preview-card .badge { position: absolute; bottom: 0.5rem; left: 0.5rem; background: #08437b; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
    .existing-image-card { position: relative; border: 1px solid #e5e7eb; border-radius: 6px; padding: 0.375rem; background: white; overflow: hidden; }
    .existing-image-card img { width: 100%; height: 140px; object-fit: cover; border-radius: 4px; display: block; }
    .existing-image-card.marked-delete { opacity: 0.35; }
    .existing-image-card.marked-delete::after { content: 'Will be deleted'; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); background: rgba(239,68,68,0.9); color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 12px; font-weight: 600; white-space: nowrap; }

    /* Group Visibility */
    .group-check-card {
        border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem 1.25rem;
        display: flex; align-items: center; gap: 0.75rem; cursor: pointer;
        transition: all 0.2s; background: white; user-select: none;
    }
    .group-check-card:hover { border-color: #08437b; background: #f0f9ff; }
    .group-check-card input[type="checkbox"] { width: 18px; height: 18px; accent-color: #08437b; cursor: pointer; flex-shrink: 0; }
    .group-check-card.checked { border-color: #08437b; background: #eff6ff; }
    .group-check-card .group-name { font-size: 14px; font-weight: 600; color: #111827; }
    .group-check-card .group-desc { font-size: 12px; color: #6b7280; margin-top: 2px; }
    .group-default-badge { margin-left: auto; background: #dbeafe; color: #1e40af; font-size: 10px; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: 4px; text-transform: uppercase; flex-shrink: 0; }

    .form-footer { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; gap: 0.75rem; justify-content: flex-end; }
    .btn { padding: 0.625rem 1.25rem; border-radius: 6px; font-weight: 500; font-size: 14px; transition: all 0.2s; min-width: 100px; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; gap: 0.5rem; }
    .btn-outline-secondary { background: white; border: 1px solid #d1d5db; color: #374151; }
    .btn-outline-secondary:hover { background: #f9fafb; border-color: #9ca3af; color: #111827; }
    .btn-primary { background: #08437b; color: white; }
    .btn-primary:hover { background: #0f508d; }
    .sku-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; padding: 0.625rem 0.875rem; font-size: 14px; color: #374151; font-family: monospace; }
    @media (max-width: 768px) { .card-body { padding: 1.25rem; } .form-footer { flex-direction: column-reverse; } .form-footer .btn { width: 100%; } .preview-card img, .existing-image-card img { height: 120px; } }
    .swal2-popup { font-size: 14px !important; border-radius: 8px !important; }
    .swal2-confirm, .swal2-cancel { font-size: 14px !important; padding: 0.625rem 1.25rem !important; border-radius: 6px !important; font-weight: 500 !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                <li class="breadcrumb-item active">Edit: {{ Str::limit($product->name, 40) }}</li>
            </ol>
        </nav>
    </div>

    <div class="card product-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-edit me-2" style="color:#08437b;"></i>Edit Product</h4>
            <span class="sku-badge"><i class="fas fa-tag"></i> {{ $product->sku }}</span>
        </div>

        <div class="card-body">
            <form id="productForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ── BASIC INFO ── --}}
                <div class="form-section">
                    <h6 class="section-title">Basic Information</h6>
                    <div class="row g-4">

                        <div class="col-md-8">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $product->name) }}"
                                   placeholder="e.g., TRS Basmati Rice 5kg">
                            <small class="text-danger error-name"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" @selected(old('is_active', $product->is_active) == 1)>Active</option>
                                <option value="0" @selected(old('is_active', $product->is_active) == 0)>Inactive</option>
                            </select>
                        </div>

                        {{-- Category searchable --}}
                        <div class="col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <div class="searchable-select-wrapper" id="categorySelectWrapper">
                                <div class="select-trigger" id="categoryTrigger">
                                    <span class="trigger-text {{ $product->category ? '' : 'text-muted' }}">
                                        {{ $product->category ? $product->category->name : 'Select Category' }}
                                    </span>
                                    <span class="trigger-arrow">▼</span>
                                </div>
                                <div class="select-dropdown" id="categoryDropdown">
                                    <div class="select-search">
                                        <input type="text" id="categorySearch" placeholder="Search categories...">
                                    </div>
                                    <div id="categoryOptions">
                                        <div class="select-option placeholder" data-value="">Select Category</div>
                                        @foreach($categories as $parent)
                                            @if($parent->children->isEmpty())
                                                <div class="select-option {{ $product->category_id == $parent->id ? 'selected' : '' }}"
                                                     data-value="{{ $parent->id }}">
                                                    {{ $parent->name }}
                                                </div>
                                            @else
                                                <div class="select-optgroup-label">{{ $parent->name }}</div>
                                                @foreach($parent->children as $child)
                                                    <div class="select-option grouped {{ $product->category_id == $child->id ? 'selected' : '' }}"
                                                         data-value="{{ $child->id }}">
                                                        {{ $child->name }}
                                                    </div>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="category_id" id="categoryId"
                                   value="{{ old('category_id', $product->category_id) }}">
                            <small class="text-danger error-category_id"></small>
                        </div>

                        {{-- Brand searchable --}}
                        <div class="col-md-6">
                            <label class="form-label">Brand</label>
                            <div class="searchable-select-wrapper" id="brandSelectWrapper">
                                <div class="select-trigger" id="brandTrigger">
                                    <span class="trigger-text">
                                        {{ $product->brand ? $product->brand->name : 'No Brand / Generic' }}
                                    </span>
                                    <span class="trigger-arrow">▼</span>
                                </div>
                                <div class="select-dropdown" id="brandDropdown">
                                    <div class="select-search">
                                        <input type="text" id="brandSearch" placeholder="Search brands...">
                                    </div>
                                    <div id="brandOptions">
                                        <div class="select-option placeholder {{ !$product->brand_id ? 'selected' : '' }}"
                                             data-value="">No Brand / Generic</div>
                                        @foreach($brands as $brand)
                                            <div class="select-option {{ $product->brand_id == $brand->id ? 'selected' : '' }}"
                                                 data-value="{{ $brand->id }}">
                                                {{ $brand->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="brand_id" id="brandId"
                                   value="{{ old('brand_id', $product->brand_id) }}">
                            <small class="text-danger error-brand_id"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Unit Type <span class="text-danger">*</span></label>
                            <select name="unit" class="form-select">
                                <optgroup label="Weight">
                                    <option value="kg"  @selected(old('unit',$product->unit)=='kg')>Kilogram (kg)</option>
                                    <option value="g"   @selected(old('unit',$product->unit)=='g')>Gram (g)</option>
                                </optgroup>
                                <optgroup label="Volume">
                                    <option value="ml"  @selected(old('unit',$product->unit)=='ml')>Milliliter (ml)</option>
                                    <option value="l"   @selected(old('unit',$product->unit)=='l')>Liter (l)</option>
                                </optgroup>
                                <optgroup label="Count">
                                    <option value="pcs" @selected(old('unit',$product->unit)=='pcs')>Pieces (pcs)</option>
                                    <option value="nos" @selected(old('unit',$product->unit)=='nos')>Numbers (nos)</option>
                                    <option value="doz" @selected(old('unit',$product->unit)=='doz')>Dozen (doz)</option>
                                </optgroup>
                                <optgroup label="Packaging">
                                    <option value="box" @selected(old('unit',$product->unit)=='box')>Box</option>
                                    <option value="pkt" @selected(old('unit',$product->unit)=='pkt')>Packet (pkt)</option>
                                    <option value="rol" @selected(old('unit',$product->unit)=='rol')>Roll (rol)</option>
                                    <option value="drm" @selected(old('unit',$product->unit)=='drm')>Drum (drm)</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Featured?</label>
                            <select name="is_featured" class="form-select">
                                <option value="0" @selected(!old('is_featured',$product->is_featured))>No</option>
                                <option value="1" @selected(old('is_featured',$product->is_featured)==1)>Yes — Show on homepage</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Popular?</label>
                            <select name="is_popular" class="form-select">
                                <option value="0" @selected(!old('is_popular',$product->is_popular))>No</option>
                                <option value="1" @selected(old('is_popular',$product->is_popular)==1)>Yes — Show in popular section</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Product description (optional)">{{ old('description', $product->description) }}</textarea>
                            <small class="text-danger error-description"></small>
                        </div>

                    </div>
                </div>

                {{-- ── PRICING & STOCK ── --}}
                <div class="form-section">
                    <h6 class="section-title">Pricing & Stock</h6>
                    <div class="row g-4">

                        <div class="col-md-3">
                            <label class="form-label">Selling Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">£</span>
                                <input type="number" step="0.01" name="price" class="form-control"
                                       value="{{ old('price', $product->price) }}" placeholder="0.00">
                            </div>
                            <small class="text-danger error-price"></small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">MRP <span class="form-text d-inline">(optional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text">£</span>
                                <input type="number" step="0.01" name="mrp" class="form-control"
                                       value="{{ old('mrp', $product->mrp) }}" placeholder="0.00">
                            </div>
                            <small class="text-danger error-mrp"></small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tax Rate (%)</label>
                            <input type="number" step="0.01" name="tax_rate" class="form-control"
                                   value="{{ old('tax_rate', $product->tax_rate) }}" placeholder="0.00">
                            <small class="text-danger error-tax_rate"></small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control"
                                   value="{{ old('stock', $product->stock) }}" placeholder="0">
                            <small class="text-danger error-stock"></small>
                        </div>

                    </div>
                </div>

                {{-- ── ADDITIONAL INFO ── --}}
                <div class="form-section">
                    <h6 class="section-title">Additional Information</h6>
                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="form-label">Barcode / EAN</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                <input type="text" name="barcode" class="form-control"
                                       value="{{ old('barcode', $product->barcode) }}"
                                       placeholder="Scan or enter barcode">
                            </div>
                            <small class="text-danger error-barcode"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control"
                                   value="{{ old('slug', $product->slug) }}">
                            <small class="form-text">Leave blank to auto-regenerate from name</small>
                            <small class="text-danger error-slug"></small>
                        </div>

                    </div>
                </div>

                {{-- ── CUSTOMER GROUP VISIBILITY ── --}}
                @php
                    $assignedGroupIds = $product->customerGroups->pluck('id')->toArray();
                @endphp
                <div class="form-section">
                    <h6 class="section-title">Visible To (Customer Groups)</h6>
                    <p class="form-text mb-3">
                        <i class="fas fa-info-circle"></i>
                        Select which customer groups can see and purchase this product.
                        If none selected, it will be hidden from all groups.
                    </p>
                    <div class="row g-3">
                        @foreach($customerGroups as $group)
                            @php $isAssigned = in_array($group->id, $assignedGroupIds); @endphp
                            <div class="col-md-4">
                                <label class="group-check-card {{ $isAssigned ? 'checked' : '' }}"
                                       id="groupCard_{{ $group->id }}">
                                    <input type="checkbox"
                                           name="group_ids[]"
                                           value="{{ $group->id }}"
                                           {{ $isAssigned ? 'checked' : '' }}
                                           onchange="toggleGroupCard(this)">
                                    <div>
                                        <div class="group-name">{{ $group->name }}</div>
                                        @if($group->description)
                                            <div class="group-desc">{{ Str::limit($group->description, 60) }}</div>
                                        @endif
                                    </div>
                                    @if($group->slug === 'home-delivery')
                                        <span class="group-default-badge">Default</span>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <small class="text-danger error-group_ids mt-2"></small>
                </div>

                {{-- ── IMAGES ── --}}
                <div class="form-section">
                    <h6 class="section-title">Product Images</h6>

                    {{-- Existing images --}}
                    @if($product->images->count())
                        <p class="form-text mb-2">Existing images — click × to remove</p>
                        <div class="row g-3 mb-4" id="existingImagesRow">
                            @foreach($product->images as $img)
                                <div class="col-md-2 col-6" id="existingImg{{ $img->id }}">
                                    <div class="existing-image-card" id="existingCard{{ $img->id }}">
                                        <span class="preview-remove"
                                              onclick="markDeleteImage({{ $img->id }})">×</span>
                                        <img src="{{ asset('storage/'.$img->image_path) }}"
                                             alt="Product image">
                                        @if($img->is_primary)
                                            <div class="badge">Primary</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- New image upload --}}
                    <div class="image-upload-box" id="imageUploadBox">
                        <input type="file" id="imageInput" accept="image/*" multiple hidden>
                        <div class="upload-placeholder">
                            <div class="upload-icon">📷</div>
                            <p>Click or drag to add more images</p>
                            <small>Maximum 5 total &bull; Max 100 KB each</small>
                        </div>
                    </div>
                    <small class="text-danger error-images" style="display:block; margin-top:0.5rem;"></small>
                    <div class="row g-3 mt-2" id="imagePreview"></div>
                </div>

                {{-- ── FOOTER ── --}}
                <div class="form-footer">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const PRODUCT_UPDATE_URL = "{{ route('admin.products.update', $product->id) }}";
const PRODUCTS_INDEX_URL = "{{ route('admin.products.index') }}";

/* ── GROUP CARD TOGGLE ── */
function toggleGroupCard(checkbox) {
    checkbox.closest('.group-check-card').classList.toggle('checked', checkbox.checked);
}

/* ── SEARCHABLE SELECT ── */
function initSearchableSelect(wrapperId, triggerEl, dropdownEl, searchEl, optionsContainerId, hiddenInputId) {
    const trigger   = document.getElementById(triggerEl);
    const dropdown  = document.getElementById(dropdownEl);
    const search    = document.getElementById(searchEl);
    const container = document.getElementById(optionsContainerId);
    const hidden    = document.getElementById(hiddenInputId);

    trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        const isOpen = dropdown.classList.contains('open');
        closeAllDropdowns();
        if (!isOpen) {
            dropdown.classList.add('open');
            trigger.classList.add('open');
            search.focus();
        }
    });

    search.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        container.querySelectorAll('.select-option').forEach(opt => {
            opt.style.display = opt.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
        container.querySelectorAll('.select-optgroup-label').forEach(g => {
            let next = g.nextElementSibling;
            let visible = false;
            while (next && !next.classList.contains('select-optgroup-label')) {
                if (next.style.display !== 'none') visible = true;
                next = next.nextElementSibling;
            }
            g.style.display = visible ? '' : 'none';
        });
    });

    container.addEventListener('click', function(e) {
        const opt = e.target.closest('.select-option');
        if (!opt) return;
        hidden.value = opt.dataset.value;
        trigger.querySelector('.trigger-text').textContent = opt.textContent.trim();
        trigger.querySelector('.trigger-text').classList.toggle('text-muted', opt.dataset.value === '');
        container.querySelectorAll('.select-option').forEach(o => o.classList.remove('selected'));
        opt.classList.add('selected');
        dropdown.classList.remove('open');
        trigger.classList.remove('open');
        search.value = '';
        container.querySelectorAll('.select-option, .select-optgroup-label').forEach(el => el.style.display = '');
    });
}

function closeAllDropdowns() {
    document.querySelectorAll('.select-dropdown.open').forEach(d => d.classList.remove('open'));
    document.querySelectorAll('.select-trigger.open').forEach(t => t.classList.remove('open'));
}
document.addEventListener('click', closeAllDropdowns);

initSearchableSelect('categorySelectWrapper','categoryTrigger','categoryDropdown','categorySearch','categoryOptions','categoryId');
initSearchableSelect('brandSelectWrapper','brandTrigger','brandDropdown','brandSearch','brandOptions','brandId');


/* ── DELETE EXISTING IMAGES ── */
let deletedImageIds = [];

function markDeleteImage(id) {
    const card = document.getElementById(`existingCard${id}`);
    if (deletedImageIds.includes(id)) {
        deletedImageIds = deletedImageIds.filter(i => i !== id);
        card.classList.remove('marked-delete');
    } else {
        deletedImageIds.push(id);
        card.classList.add('marked-delete');
    }
}


/* ── NEW IMAGE UPLOAD ── */
const MAX_FILE_SIZE = 100 * 1024;
const MAX_FILES     = 5;
let selectedFiles   = [];

const imageInput   = document.getElementById('imageInput');
const imagePreview = document.getElementById('imagePreview');
const uploadBox    = document.getElementById('imageUploadBox');

uploadBox.onclick    = () => imageInput.click();
uploadBox.ondragover = (e) => { e.preventDefault(); uploadBox.style.borderColor = '#08437b'; };
uploadBox.ondragleave= () =>  { uploadBox.style.borderColor = '#d1d5db'; };
uploadBox.ondrop     = (e) => { e.preventDefault(); handleFiles(Array.from(e.dataTransfer.files).filter(f=>f.type.startsWith('image/'))); };
imageInput.onchange  = () => handleFiles(Array.from(imageInput.files));

function handleFiles(files) {
    $('.error-images').text('');
    const existingKept = document.querySelectorAll('.existing-image-card:not(.marked-delete)').length;
    const total = existingKept + selectedFiles.length + files.length;
    if (total > MAX_FILES) {
        showImageError(`Maximum ${MAX_FILES} images allowed. You have ${existingKept} existing + ${selectedFiles.length} new.`);
        imageInput.value = '';
        return;
    }
    const invalid = [], valid = [];
    files.forEach(f => f.size > MAX_FILE_SIZE
        ? invalid.push(`${f.name} is ${(f.size/1024).toFixed(1)} KB (max 100 KB)`)
        : valid.push(f)
    );
    if (invalid.length) { showImageError(invalid.join('<br>')); imageInput.value = ''; return; }
    selectedFiles.push(...valid);
    renderPreviews();
    imageInput.value = '';
}

function renderPreviews() {
    imagePreview.innerHTML = '';
    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            const col = document.createElement('div');
            col.className = 'col-md-2 col-6';
            col.innerHTML = `
                <div class="preview-card">
                    <span class="preview-remove" onclick="removeImage(${index})">×</span>
                    <img src="${e.target.result}" alt="New">
                    <div class="badge" style="background:#6b7280;">New</div>
                    <div style="position:absolute;bottom:0.5rem;right:0.5rem;background:rgba(0,0,0,0.7);
                         color:white;padding:0.125rem 0.375rem;border-radius:3px;font-size:10px;">
                        ${(file.size/1024).toFixed(1)} KB
                    </div>
                </div>`;
            imagePreview.appendChild(col);
        };
        reader.readAsDataURL(file);
    });
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    renderPreviews();
}

function showImageError(msg) {
    $('.error-images').html(msg);
    Swal.fire({ icon: 'error', title: 'Image Error', html: msg, confirmButtonColor: '#08437b' });
}


/* ── FORM SUBMIT ── */
$('#productForm').submit(function(e) {
    e.preventDefault();
    $('small.text-danger').text('');
    $('.form-control, .form-select').removeClass('is-invalid');

    if (!$('#categoryId').val()) {
        $('#categoryId').closest('.col-md-6').find('.text-danger').text('Please select a category');
        Swal.fire({ icon: 'warning', title: 'Category Required', text: 'Please select a category.', confirmButtonColor: '#08437b' });
        return;
    }

    // Validate at least one group is checked
    if ($('input[name="group_ids[]"]:checked').length === 0) {
        $('.error-group_ids').text('Please select at least one customer group.');
        Swal.fire({ icon: 'warning', title: 'Group Required', text: 'Please select at least one customer group.', confirmButtonColor: '#08437b' });
        return;
    }

    const formData = new FormData(this);
    formData.delete('images[]');
    selectedFiles.forEach(file => formData.append('images[]', file));
    deletedImageIds.forEach(id => formData.append('deleted_images[]', id));

    Swal.fire({
        title: 'Updating Product...', html: 'Please wait',
        allowOutsideClick: false, allowEscapeKey: false,
        didOpen: () => Swal.showLoading()
    });

    $.ajax({
        url: PRODUCT_UPDATE_URL,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            Swal.fire({
                icon: 'success', title: 'Updated!',
                text: response.message || 'Product updated successfully',
                confirmButtonColor: '#08437b', confirmButtonText: 'Back to Products'
            }).then(() => window.location.href = PRODUCTS_INDEX_URL);
        },
        error: function(xhr) {
            Swal.close();
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let msgs = [];
                Object.entries(errors).forEach(([field, messages]) => {
                    const clean = field.replace(/\.\d+$/, '');
                    $(`.error-${clean}`).text(messages[0]);
                    $(`[name="${field}"], [name="${clean}"]`).addClass('is-invalid');
                    msgs.push(...messages);
                });
                Swal.fire({
                    icon: 'error', title: 'Validation Errors',
                    html: `<div style="text-align:left;font-size:14px;line-height:1.6">${msgs.map(m=>`• ${m}`).join('<br>')}</div>`,
                    confirmButtonColor: '#08437b'
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Something went wrong.', confirmButtonColor: '#08437b' });
            }
        }
    });
});

$('.form-control, .form-select').on('input change', function() {
    $(this).removeClass('is-invalid');
    $(`.error-${$(this).attr('name')}`).text('');
});
</script>
@endpush
