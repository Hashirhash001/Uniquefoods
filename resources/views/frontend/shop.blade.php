@extends('frontend.layouts.app')

@section('title', 'Shop - All Products')

@push('styles')
<!-- noUiSlider CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
<style>
/* Brand Color Variables */
:root {
    --color-primary: #629D23;
    --color-primary-hover: #518219;
    --text-dark: #1a1a1a;
    --text-light: #666;
    --border-color: #e0e0e0;
    --bg-light: #f8f9fa;
}

input[type=checkbox]~label::before{
    position: unset;
    opacity: 0 !important;
}

input[type=checkbox]~label::after{
    position: unset;
    opacity: 0 !important;
}

input[type=checkbox]~label, input[type=radio]~label{
    padding-left: 5px !important;
}

/* Filter Sidebar */
.filter-sidebar {
    background: #fff;
    border-radius: 8px;
    padding: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.filter-header {
    padding: 20px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filter-header h5 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-dark);
}

.filter-header i {
    margin-right: 8px;
    color: var(--color-primary);
}

.clear-all-btn {
    color: var(--color-primary);
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: color 0.2s;
    border: none;
    background: none;
    padding: 0;
}

.clear-all-btn:hover {
    color: var(--color-primary-hover);
}

.filter-section {
    border-bottom: 1px solid var(--border-color);
    padding: 18px 20px;
}

.filter-section:last-child {
    border-bottom: none;
}

.filter-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    user-select: none;
}

.filter-title i {
    font-size: 12px;
    color: #999;
    transition: transform 0.3s;
}

.filter-title.collapsed i {
    transform: rotate(-90deg);
}

.filter-options {
    max-height: 300px;
    overflow-y: auto;
    transition: max-height 0.3s ease;
}

.filter-options.collapsed {
    max-height: 0;
    overflow: hidden;
}

.filter-options::-webkit-scrollbar {
    width: 6px;
}

.filter-options::-webkit-scrollbar-thumb {
    background: #ddd;
    border-radius: 3px;
}

/* Custom Checkbox Design */
.filter-option {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    cursor: pointer;
    position: relative;
}

.filter-option input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    width: 0;
    height: 0;
}

.checkbox-custom {
    width: 18px;
    height: 18px;
    border: 2px solid var(--border-color);
    border-radius: 4px;
    margin-right: 12px;
    cursor: pointer;
    position: relative;
    flex-shrink: 0;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.filter-option:hover .checkbox-custom {
    border-color: var(--color-primary);
}

.filter-option input[type="checkbox"]:checked ~ .checkbox-custom {
    background-color: var(--color-primary);
    border-color: var(--color-primary);
}

.filter-option input[type="checkbox"]:checked ~ .checkbox-custom::after {
    content: "✓";
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.filter-option label {
    font-size: 14px;
    color: var(--text-light);
    cursor: pointer;
    flex: 1;
    margin: 0;
    user-select: none;
}

.filter-option:hover label {
    color: var(--text-dark);
}

/* Price Slider */
.price-slider-wrapper {
    padding: 20px 10px 10px;
}

#priceSlider {
    margin-bottom: 20px;
}

.noUi-connect {
    background: var(--color-primary);
}

.noUi-handle {
    border: 2px solid var(--color-primary);
    background: white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.noUi-handle:before,
.noUi-handle:after {
    display: none;
}

.price-values {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.price-input-box {
    flex: 1;
}

.price-input-box label {
    font-size: 12px;
    color: var(--text-light);
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
}

.price-input-box input {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid var(--border-color);
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.price-input-box input:focus {
    outline: none;
    border-color: var(--color-primary);
}

/* Active Filters */
.active-filters-bar {
    background: var(--bg-light);
    padding: 15px 20px;
    border-radius: 8px;
    margin: 0 20px 15px;
    display: none;
}

.active-filters-bar.show {
    display: block;
}

.active-filter-tag {
    display: inline-flex;
    align-items: center;
    background: #fff;
    border: 1px solid var(--border-color);
    padding: 6px 12px;
    border-radius: 20px;
    margin: 4px;
    font-size: 13px;
    color: var(--text-dark);
}

.active-filter-tag i {
    margin-left: 8px;
    cursor: pointer;
    color: #999;
    transition: color 0.2s;
}

.active-filter-tag i:hover {
    color: var(--color-primary-hover);
}

/* Product Grid Header */
.product-grid-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 20px;
}

.result-count {
    font-size: 14px;
    color: var(--text-light);
    font-weight: 500;
}

.sort-dropdown-wrapper {
    position: relative;
}

.sort-dropdown {
    padding: 10px 40px 10px 15px;
    border: 2px solid var(--border-color);
    border-radius: 6px;
    font-size: 14px;
    background: #fff;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    min-width: 200px;
    transition: border-color 0.2s;
    color: var(--text-dark);
    font-weight: 500;
    line-height: 1.5;
}

.sort-dropdown:focus {
    outline: none;
    border-color: var(--color-primary);
}

.sort-dropdown option {
    padding: 10px;
    color: var(--text-dark);
}

/* Wishlist Button */
.action-share-option {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
}

.single-shopping-card-one:hover .action-share-option {
    opacity: 1;
    transform: translateY(0);
}

.single-action {
    width: 40px;
    height: 40px;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.single-action:hover {
    background: var(--color-primary);
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(98,157,35,0.3);
    border-color: var(--color-primary);
}

.single-action i {
    font-size: 18px;
    color: var(--text-dark);
    transition: color 0.3s;
}

.single-action:hover i {
    color: #fff;
}

.single-action.active {
    background: var(--color-primary);
    border-color: var(--color-primary);
}

.single-action.active i {
    color: #fff;
}

/* Mobile */
.mobile-filter-btn {
    display: none;
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: var(--color-primary);
    color: #fff;
    padding: 14px 28px;
    border-radius: 30px;
    box-shadow: 0 4px 12px rgba(98,157,35,0.4);
    z-index: 999;
    border: none;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.3s;
}

.mobile-filter-btn:hover {
    background: var(--color-primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(98,157,35,0.5);
}

.mobile-filter-btn i {
    margin-right: 8px;
}

@media (max-width: 991px) {
    .mobile-filter-btn {
        display: block;
    }

    .filter-sidebar-wrapper {
        position: fixed;
        top: 0;
        left: -100%;
        width: 320px;
        height: 100vh;
        background: #fff;
        z-index: 1000;
        transition: left 0.3s;
        overflow-y: auto;
    }

    .filter-sidebar-wrapper.show {
        left: 0;
    }

    .filter-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(0,0,0,0.5);
        z-index: 999;
    }

    .filter-overlay.show {
        display: block;
    }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 64px;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-state h4 {
    color: var(--text-dark);
    margin-bottom: 10px;
}

.empty-state p {
    color: var(--text-light);
    margin-bottom: 20px;
}
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="rts-navigation-area-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="navigator-breadcrumb-wrapper">
                    <a href="{{ route('home') }}">Home</a>
                    <i class="fa-regular fa-chevron-right"></i>
                    <a class="current" href="{{ route('shop') }}">Shop</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section-seperator">
    <div class="container">
        <hr class="section-seperator">
    </div>
</div>

{{-- Main Shop Area --}}
<div class="shop-grid-sidebar-area rts-section-gap">
    <div class="container">
        <div class="row">

            {{-- Filter Sidebar --}}
            <div class="col-lg-3 col-md-12 mb-4">
                <div class="filter-overlay" id="filterOverlay"></div>
                <div class="filter-sidebar-wrapper" id="filterSidebarWrapper">
                    <div class="filter-sidebar">

                        {{-- Filter Header --}}
                        <div class="filter-header">
                            <h5><i class="fa-regular fa-filter"></i>Filters</h5>
                            <button type="button" class="clear-all-btn" id="clearAllFilters">Clear All</button>
                        </div>

                        {{-- Active Filters Bar --}}
                        <div class="active-filters-bar" id="activeFiltersBar">
                            <div id="activeFilterTags"></div>
                        </div>

                        {{-- Price Filter with Slider --}}
                        <div class="filter-section">
                            <div class="filter-title" data-toggle="priceFilter">
                                <span>Price Range</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="filter-options" id="priceFilter">
                                <div class="price-slider-wrapper">
                                    <div id="priceSlider"></div>
                                    <div class="price-values">
                                        <div class="price-input-box">
                                            <label>Min</label>
                                            <input type="number" id="minPrice" readonly value="0">
                                        </div>
                                        <span style="margin: 0 10px; padding-top: 30px;">-</span>
                                        <div class="price-input-box">
                                            <label>Max</label>
                                            <input type="number" id="maxPrice" readonly value="10000">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Categories Filter --}}
                        <div class="filter-section">
                            <div class="filter-title" data-toggle="categoryFilter">
                                <span>Categories</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="filter-options" id="categoryFilter">
                                @php
                                    $categories = \App\Models\Category::where('is_active', 1)
                                        ->whereNull('parent_id')
                                        ->orderBy('name')
                                        ->get();
                                @endphp

                                @foreach($categories as $category)
                                <div class="filter-option">
                                    <input type="checkbox"
                                           id="cat{{ $category->id }}"
                                           class="category-filter"
                                           value="{{ $category->id }}"
                                           data-name="{{ $category->name }}">
                                    <span class="checkbox-custom"></span>
                                    <label for="cat{{ $category->id }}">{{ $category->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Brands Filter --}}
                        <div class="filter-section">
                            <div class="filter-title" data-toggle="brandFilter">
                                <span>Brands</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="filter-options" id="brandFilter">
                                @php
                                    $brands = \App\Models\Brand::where('is_active', 1)
                                        ->orderBy('name')
                                        ->get();
                                @endphp

                                @foreach($brands as $brand)
                                <div class="filter-option">
                                    <input type="checkbox"
                                           id="brand{{ $brand->id }}"
                                           class="brand-filter"
                                           value="{{ $brand->id }}"
                                           data-name="{{ $brand->name }}">
                                    <span class="checkbox-custom"></span>
                                    <label for="brand{{ $brand->id }}">{{ $brand->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Products Grid --}}
            <div class="col-lg-9 col-md-12">

                {{-- Product Grid Header --}}
                <div class="product-grid-header">
                    <div class="result-count">
                        <span id="resultCount">Loading...</span>
                    </div>
                    <div class="sort-dropdown-wrapper">
                        <select id="sortBy" class="sort-dropdown">
                            <option value="latest">Latest First</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="name_asc">Name: A to Z</option>
                            <option value="name_desc">Name: Z to A</option>
                        </select>
                    </div>
                </div>

                {{-- Products Container --}}
                <div class="row g-4" id="productsContainer">
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-3">Loading products...</p>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="row mt-4">
                    <div class="col-12">
                        <div id="paginationContainer"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Mobile Filter Button --}}
<button class="mobile-filter-btn" id="mobileFilterBtn">
    <i class="fa-regular fa-filter"></i> Filters
</button>

@endsection

@push('scripts')
<!-- noUiSlider JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
<script>
$(document).ready(function() {
    let currentPage = 1;
    let activeFilters = {
        categories: [],
        brands: [],
        minPrice: 0,
        maxPrice: 10000
    };

    // Initialize Price Slider
    const priceSlider = document.getElementById('priceSlider');
    noUiSlider.create(priceSlider, {
        start: [0, 10000],
        connect: true,
        step: 10,
        range: {
            'min': 0,
            'max': 10000
        },
        format: {
            to: function(value) {
                return Math.round(value);
            },
            from: function(value) {
                return Math.round(value);
            }
        }
    });

    // Update inputs when slider moves
    priceSlider.noUiSlider.on('update', function(values) {
        $('#minPrice').val(values[0]);
        $('#maxPrice').val(values[1]);
    });

    // Apply price filter when slider changes
    priceSlider.noUiSlider.on('change', function(values) {
        activeFilters.minPrice = values[0];
        activeFilters.maxPrice = values[1];
        currentPage = 1;
        updateActiveFilters();
        loadProducts();
    });

    // Initialize
    loadProducts();

    // Collapsible filter sections
    $('.filter-title').on('click', function() {
        const targetId = $(this).data('toggle');
        const $target = $('#' + targetId);

        $(this).toggleClass('collapsed');
        $target.toggleClass('collapsed');
    });

    // Mobile filter toggle
    $('#mobileFilterBtn').on('click', function() {
        $('#filterSidebarWrapper').addClass('show');
        $('#filterOverlay').addClass('show');
    });

    $('#filterOverlay').on('click', function() {
        $('#filterSidebarWrapper').removeClass('show');
        $('#filterOverlay').removeClass('show');
    });

    // Category filter
    $(document).on('change', '.category-filter', function() {
        console.log('Category changed:', $(this).val(), $(this).is(':checked'));
        updateFilterArray('categories');
        currentPage = 1;
        updateActiveFilters();
        loadProducts();
    });

    // Brand filter
    $(document).on('change', '.brand-filter', function() {
        console.log('Brand changed:', $(this).val(), $(this).is(':checked'));
        updateFilterArray('brands');
        currentPage = 1;
        updateActiveFilters();
        loadProducts();
    });

    // Sort
    $('#sortBy').on('change', function() {
        currentPage = 1;
        loadProducts();
    });

    // Clear all filters
    $('#clearAllFilters').on('click', function(e) {
        e.preventDefault();
        clearAllFilters();
    });

    // Remove individual filter
    $(document).on('click', '.remove-filter', function() {
        const type = $(this).data('type');
        const value = $(this).data('value');

        if (type === 'category') {
            $(`.category-filter[value="${value}"]`).prop('checked', false);
            updateFilterArray('categories');
        } else if (type === 'brand') {
            $(`.brand-filter[value="${value}"]`).prop('checked', false);
            updateFilterArray('brands');
        } else if (type === 'price') {
            priceSlider.noUiSlider.set([0, 10000]);
            activeFilters.minPrice = 0;
            activeFilters.maxPrice = 10000;
        }

        currentPage = 1;
        updateActiveFilters();
        loadProducts();
    });

    // Pagination
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            const urlObj = new URL(url);
            const page = urlObj.searchParams.get('page');
            if (page) {
                currentPage = page;
                loadProducts();
                $('html, body').animate({ scrollTop: 400 }, 'smooth');
            }
        }
    });

    // Helper functions
    function updateFilterArray(type) {
        activeFilters[type] = [];
        $(`.${type.slice(0, -1)}-filter:checked`).each(function() {
            activeFilters[type].push($(this).val());
        });
        console.log('Updated filters:', activeFilters);
    }

    function clearAllFilters() {
        $('.category-filter, .brand-filter').prop('checked', false);
        priceSlider.noUiSlider.set([0, 10000]);
        $('#sortBy').val('latest');
        activeFilters = { categories: [], brands: [], minPrice: 0, maxPrice: 10000 };
        currentPage = 1;
        updateActiveFilters();
        loadProducts();
    }

    function updateActiveFilters() {
        let html = '';
        const hasFilters = activeFilters.categories.length > 0 ||
                          activeFilters.brands.length > 0 ||
                          activeFilters.minPrice > 0 ||
                          activeFilters.maxPrice < 10000;

        if (hasFilters) {
            if (activeFilters.minPrice > 0 || activeFilters.maxPrice < 10000) {
                html += `<span class="active-filter-tag">
                    ₹${activeFilters.minPrice} - ₹${activeFilters.maxPrice}
                    <i class="fa-solid fa-xmark remove-filter" data-type="price"></i>
                </span>`;
            }

            activeFilters.categories.forEach(id => {
                const name = $(`.category-filter[value="${id}"]`).data('name');
                html += `<span class="active-filter-tag">
                    ${name}
                    <i class="fa-solid fa-xmark remove-filter" data-type="category" data-value="${id}"></i>
                </span>`;
            });

            activeFilters.brands.forEach(id => {
                const name = $(`.brand-filter[value="${id}"]`).data('name');
                html += `<span class="active-filter-tag">
                    ${name}
                    <i class="fa-solid fa-xmark remove-filter" data-type="brand" data-value="${id}"></i>
                </span>`;
            });

            $('#activeFiltersBar').addClass('show');
        } else {
            $('#activeFiltersBar').removeClass('show');
        }

        $('#activeFilterTags').html(html);
    }

    function loadProducts() {
        const data = {
            page: currentPage,
            min_price: activeFilters.minPrice,
            max_price: activeFilters.maxPrice,
            categories: activeFilters.categories,
            brands: activeFilters.brands,
            sort: $('#sortBy').val() || 'latest'
        };

        console.log('Loading products with data:', data);

        $('#productsContainer').html(`
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3">Loading products...</p>
            </div>
        `);

        $.ajax({
            url: '{{ route("shop.filter") }}',
            type: 'GET',
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response);
                if (response.success) {
                    displayProducts(response.products);
                    displayPagination(response.pagination);
                    updateProductCount(response.total, response.from, response.to);

                    $('#filterSidebarWrapper').removeClass('show');
                    $('#filterOverlay').removeClass('show');
                } else {
                    showError('Failed to load products');
                }
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr);
                showError('Error loading products');
            }
        });
    }

    function displayProducts(products) {
        if (products.length === 0) {
            $('#productsContainer').html(`
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fa-regular fa-box-open"></i>
                        <h4>No products found</h4>
                        <p>Try adjusting your filters</p>
                        <button class="rts-btn btn-primary" onclick="$('#clearAllFilters').click()">Clear Filters</button>
                    </div>
                </div>
            `);
            return;
        }

        let html = '';
        products.forEach(product => {
            const discount = product.discount_percentage > 0 ?
                `<div class="badge"><span>${product.discount_percentage}% <br> Off</span><i class="fa-solid fa-bookmark"></i></div>` : '';

            const mrp = product.mrp > product.price ?
                `<div class="previous">₹${parseFloat(product.mrp).toFixed(2)}</div>` : '';

            html += `
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    <div class="single-shopping-card-one">
                        <div class="image-and-action-area-wrapper">
                            <a href="/product/${product.slug}" class="thumbnail-preview">
                                ${discount}
                                <img src="${product.image_url}" alt="${product.name}" onerror="this.src='/frontend/assets/images/grocery/01.jpg'">
                            </a>
                            <div class="action-share-option">
                                <div class="single-action wishlist-btn" title="Add to Wishlist" data-id="${product.id}">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="body-content">
                            <a href="/product/${product.slug}">
                                <h4 class="title">${product.name}</h4>
                            </a>
                            ${product.unit ? `<span class="availability">${product.unit}</span>` : ''}
                            <div class="price-area">
                                <span class="current">₹${parseFloat(product.price).toFixed(2)}</span>
                                ${mrp}
                            </div>
                            <div class="cart-counter-action">
                                <a href="/product/${product.slug}" class="rts-btn btn-primary radious-sm with-icon">
                                    <div class="btn-text">Add to Cart</div>
                                    <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        $('#productsContainer').html(html);
    }

    // Wishlist toggle
    $(document).on('click', '.wishlist-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).toggleClass('active');
        const icon = $(this).find('i');
        if ($(this).hasClass('active')) {
            icon.removeClass('fa-regular').addClass('fa-solid');
        } else {
            icon.removeClass('fa-solid').addClass('fa-regular');
        }
    });

    function displayPagination(pagination) {
        $('#paginationContainer').html(pagination);
    }

    function updateProductCount(total, from, to) {
        $('#resultCount').text(`Showing ${from}–${to} of ${total} products`);
    }

    function showError(message) {
        $('#productsContainer').html(`
            <div class="col-12">
                <div class="empty-state">
                    <i class="fa-regular fa-triangle-exclamation"></i>
                    <h4>${message}</h4>
                    <button class="rts-btn btn-primary" onclick="location.reload()">Reload Page</button>
                </div>
            </div>
        `);
    }
});
</script>
@endpush
