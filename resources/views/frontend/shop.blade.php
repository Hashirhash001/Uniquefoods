@extends('frontend.layouts.app')

@section('title', 'Shop - All Products')

@push('styles')
<!-- noUiSlider CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/shop.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/cart-wishlist.css') }}">
@endpush

@section('content')
<!-- Breadcrumb -->
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
    <div class="container"><hr class="section-seperator"></div>
</div>

<!-- Main Shop Area -->
<div class="shop-grid-sidebar-area rts-section-gap">
    <div class="container">
        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-lg-3 col-md-12 mb-4">
                <div class="shop-filter-overlay" id="shopFilterOverlay"></div>
                <div class="shop-filter-sidebar-wrapper" id="shopFilterSidebarWrapper">
                    <div class="shop-filter-sidebar">
                        <!-- Filter Header -->
                        <div class="shop-filter-header">
                            <h5><i class="fa-solid fa-sliders"></i>Filters</h5>
                            <button type="button" class="shop-clear-all-btn" id="shopClearAllFilters">Clear All</button>
                        </div>

                        <!-- Active Filters Bar -->
                        <div class="shop-active-filters-bar" id="shopActiveFiltersBar">
                            <div id="shopActiveFilterTags"></div>
                        </div>

                        <!-- Price Filter with Slider -->
                        <div class="shop-filter-section">
                            <div class="shop-filter-title" data-toggle="shopPriceFilter">
                                <span>Price Range</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="shop-filter-options" id="shopPriceFilter">
                                <div class="shop-price-slider-wrapper">
                                    <div id="shopPriceSlider"></div>
                                    <div class="shop-price-values">
                                        <div class="shop-price-input-box">
                                            <label>Min Price</label>
                                            <input type="number" id="shopMinPrice" readonly value="0">
                                        </div>
                                        <span class="shop-price-separator">-</span>
                                        <div class="shop-price-input-box">
                                            <label>Max Price</label>
                                            <input type="number" id="shopMaxPrice" readonly value="10000">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Categories Filter -->
                        <div class="shop-filter-section">
                            <div class="shop-filter-title" data-toggle="shopCategoryFilter">
                                <span>Categories</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="shop-filter-options" id="shopCategoryFilter">
                                @php
                                    $parentCategories = App\Models\Category::where('is_active', 1)
                                        ->whereNull('parent_id')
                                        ->with(['children' => function($query) {
                                            $query->where('is_active', 1)->orderBy('name');
                                        }])
                                        ->orderBy('name')
                                        ->get();
                                @endphp

                                @foreach($parentCategories as $parent)
                                    <div class="shop-filter-option shop-parent-category">
                                        <input type="checkbox"
                                               id="shopCat{{ $parent->id }}"
                                               class="shop-category-filter shop-parent-filter"
                                               value="{{ $parent->id }}"
                                               data-name="{{ $parent->name }}"
                                               data-has-children="{{ $parent->children->count() > 0 ? 'true' : 'false' }}">
                                        <span class="shop-checkbox-custom"></span>
                                        <label for="shopCat{{ $parent->id }}">
                                            {{ $parent->name }}
                                            @if($parent->children->count() > 0)
                                                <span class="category-count">({{ $parent->children->count() }})</span>
                                            @endif
                                        </label>
                                    </div>

                                    @if($parent->children->count() > 0)
                                        <div class="shop-subcategory-group" data-parent-id="{{ $parent->id }}">
                                            @foreach($parent->children as $child)
                                                <div class="shop-filter-option shop-child-category">
                                                    <input type="checkbox"
                                                           id="shopCat{{ $child->id }}"
                                                           class="shop-category-filter shop-child-filter"
                                                           value="{{ $child->id }}"
                                                           data-name="{{ $child->name }}"
                                                           data-parent-id="{{ $parent->id }}"
                                                           data-parent-name="{{ $parent->name }}">
                                                    <span class="shop-checkbox-custom"></span>
                                                    <label for="shopCat{{ $child->id }}">{{ $child->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Brands Filter -->
                        <div class="shop-filter-section">
                            <div class="shop-filter-title" data-toggle="shopBrandFilter">
                                <span>Brands</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="shop-filter-options" id="shopBrandFilter">
                                @php
                                    $brands = App\Models\Brand::where('is_active', 1)->orderBy('name')->get();
                                @endphp
                                @foreach($brands as $brand)
                                    <div class="shop-filter-option">
                                        <input type="checkbox"
                                               id="shopBrand{{ $brand->id }}"
                                               class="shop-brand-filter"
                                               value="{{ $brand->id }}"
                                               data-name="{{ $brand->name }}">
                                        <span class="shop-checkbox-custom"></span>
                                        <label for="shopBrand{{ $brand->id }}">{{ $brand->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9 col-md-12">
                <!-- Product Grid Header -->
                <div class="shop-product-grid-header">
                    <div class="shop-result-count">
                        <span id="shopResultCount">Loading...</span>
                    </div>
                    <div class="shop-sort-dropdown-wrapper">
                        <select id="shopSortBy" class="shop-sort-dropdown">
                            <option value="latest">Latest First</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="name_asc">Name: A to Z</option>
                            <option value="name_desc">Name: Z to A</option>
                        </select>
                    </div>
                </div>

                <!-- Products Container (Infinite Scroll) -->
                <div class="row g-4" id="shopProductsContainer">
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-3">Loading products...</p>
                    </div>
                </div>

                <!-- NO PAGINATION - Using Infinite Scroll Instead -->
            </div>
        </div>
    </div>
</div>

<!-- Mobile Filter Button -->
<button class="shop-mobile-filter-btn" id="shopMobileFilterBtn">
    <i class="fa-solid fa-sliders"></i> Filters
</button>
@endsection

@push('scripts')
<!-- noUiSlider JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
<script src="{{ asset('frontend/assets/js/cart-wishlist.js') }}"></script>
<script src="{{ asset('frontend/assets/js/infinite-scroll.js') }}"></script>
<script>
$(document).ready(function() {
    showLoader('Loading Products...');

    let currentPage = 1;
    let lastPage = 1;
    let activeFilters = {
        categories: [],
        brands: [],
        minPrice: 0,
        maxPrice: 10000
    };

    // Make activeFilters global for infinite scroll
    window.activeFilters = activeFilters;

    // Initialize Price Slider
    const priceSlider = document.getElementById('shopPriceSlider');
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
        $('#shopMinPrice').val(values[0]);
        $('#shopMaxPrice').val(values[1]);
    });

    // Apply price filter when slider changes
    priceSlider.noUiSlider.on('change', function(values) {
        activeFilters.minPrice = values[0];
        activeFilters.maxPrice = values[1];
        currentPage = 1;
        updateActiveFilters();
        loadProducts(true); // true = reset infinite scroll
    });

    // Initialize
    loadProducts(true);

    // Collapsible filter sections
    $('.shop-filter-title').on('click', function() {
        const targetId = $(this).data('toggle');
        const target = $('#' + targetId);
        $(this).toggleClass('collapsed');
        target.toggleClass('collapsed');
    });

    // Mobile filter toggle
    $('#shopMobileFilterBtn').on('click', function() {
        $('#shopFilterSidebarWrapper').addClass('show');
        $('#shopFilterOverlay').addClass('show');
    });

    $('#shopFilterOverlay').on('click', function() {
        $('#shopFilterSidebarWrapper').removeClass('show');
        $('#shopFilterOverlay').removeClass('show');
    });

    // ========================================
    // FIX: Category filter with proper event handling
    // ========================================
    $(document).on('change', '.shop-category-filter', function() {
        const checkbox = $(this);
        const categoryId = checkbox.val();
        const isParent = checkbox.hasClass('shop-parent-filter');
        const isChecked = checkbox.is(':checked');

        if (isParent) {
            const childCheckboxes = $(`.shop-child-filter[data-parent-id="${categoryId}"]`);
            childCheckboxes.prop('checked', isChecked);
        } else {
            const parentId = checkbox.data('parent-id');
            const parentCheckbox = $(`.shop-parent-filter[value="${parentId}"]`);
            const siblingCheckboxes = $(`.shop-child-filter[data-parent-id="${parentId}"]`);
            const checkedSiblings = siblingCheckboxes.filter(':checked').length;
            const totalSiblings = siblingCheckboxes.length;

            if (checkedSiblings === totalSiblings) {
                parentCheckbox.prop('checked', true);
            } else {
                parentCheckbox.prop('checked', false);
            }

            if (checkedSiblings > 0) {
                parentCheckbox.closest('.shop-parent-category').addClass('has-selected-child');
            } else {
                parentCheckbox.closest('.shop-parent-category').removeClass('has-selected-child');
            }
        }

        updateCategoryFilters();
        currentPage = 1;
        updateActiveFilters();
        loadProducts(true); // Reset infinite scroll
    });

    function updateCategoryFilters() {
        activeFilters.categories = [];
        $('.shop-category-filter:checked').each(function() {
            activeFilters.categories.push($(this).val());
        });
    }

    // Brand filter
    $(document).on('change', '.shop-brand-filter', function() {
        activeFilters.brands = [];
        $('.shop-brand-filter:checked').each(function() {
            activeFilters.brands.push($(this).val());
        });
        currentPage = 1;
        updateActiveFilters();
        loadProducts(true); // Reset infinite scroll
    });

    // Sort
    $('#shopSortBy').on('change', function() {
        currentPage = 1;
        loadProducts(true); // Reset infinite scroll
    });

    // Clear all filters
    $('#shopClearAllFilters').on('click', function(e) {
        e.preventDefault();
        clearAllFilters();
    });

    // Remove individual filter
    $(document).on('click', '.shop-remove-filter', function() {
        const type = $(this).data('type');
        const value = $(this).data('value');

        if (type === 'category') {
            const checkbox = $(`.shop-category-filter[value="${value}"]`);
            checkbox.prop('checked', false).trigger('change');
        } else if (type === 'brand') {
            $(`.shop-brand-filter[value="${value}"]`).prop('checked', false);
            activeFilters.brands = [];
            $('.shop-brand-filter:checked').each(function() {
                activeFilters.brands.push($(this).val());
            });
        } else if (type === 'price') {
            priceSlider.noUiSlider.set([0, 10000]);
            activeFilters.minPrice = 0;
            activeFilters.maxPrice = 10000;
        }

        currentPage = 1;
        updateActiveFilters();
        loadProducts(true); // Reset infinite scroll
    });

    function clearAllFilters() {
        $('.shop-category-filter, .shop-brand-filter').prop('checked', false);
        priceSlider.noUiSlider.set([0, 10000]);
        $('#shopSortBy').val('latest');
        activeFilters = {
            categories: [],
            brands: [],
            minPrice: 0,
            maxPrice: 10000
        };
        window.activeFilters = activeFilters;
        currentPage = 1;
        updateActiveFilters();
        loadProducts(true); // Reset infinite scroll
    }

    function updateActiveFilters() {
        let html = '';
        const hasFilters = activeFilters.categories.length > 0 ||
                          activeFilters.brands.length > 0 ||
                          activeFilters.minPrice > 0 ||
                          activeFilters.maxPrice < 10000;

        if (hasFilters) {
            if (activeFilters.minPrice > 0 || activeFilters.maxPrice < 10000) {
                html += `<span class="shop-active-filter-tag">₹${activeFilters.minPrice} - ₹${activeFilters.maxPrice} <i class="fa-solid fa-xmark shop-remove-filter" data-type="price"></i></span>`;
            }

            activeFilters.categories.forEach(id => {
                const checkbox = $(`.shop-category-filter[value="${id}"]`);
                const name = checkbox.data('name');
                const isChild = checkbox.hasClass('shop-child-filter');
                const parentName = isChild ? checkbox.data('parent-name') : '';
                const displayName = isChild ? `${parentName} > ${name}` : name;
                html += `<span class="shop-active-filter-tag">${displayName} <i class="fa-solid fa-xmark shop-remove-filter" data-type="category" data-value="${id}"></i></span>`;
            });

            activeFilters.brands.forEach(id => {
                const name = $(`.shop-brand-filter[value="${id}"]`).data('name');
                html += `<span class="shop-active-filter-tag">${name} <i class="fa-solid fa-xmark shop-remove-filter" data-type="brand" data-value="${id}"></i></span>`;
            });

            $('#shopActiveFiltersBar').addClass('show');
        } else {
            $('#shopActiveFiltersBar').removeClass('show');
        }

        $('#shopActiveFilterTags').html(html);
    }

    // MODIFIED loadProducts for infinite scroll
    function loadProducts(reset = false) {
        // If reset, clear container and reset infinite scroll
        if (reset) {
            currentPage = 1;
            $('#shopProductsContainer').empty();

            // Reset infinite scroll
            if (window.InfiniteScroll) {
                window.InfiniteScroll.reset();
                window.InfiniteScroll.currentPage = 1;
            }

            // Trigger event for infinite scroll
            $(document).trigger('shopFiltersChanged');
        }

        const data = {
            page: currentPage,
            min_price: activeFilters.minPrice,
            max_price: activeFilters.maxPrice,
            categories: activeFilters.categories,
            brands: activeFilters.brands,
            sort: $('#shopSortBy').val() || 'latest'
        };

        // Only show spinner if resetting (not on infinite scroll)
        if (reset) {
            $('#shopProductsContainer').html(`
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3">Loading products...</p>
                </div>
            `);
        }

        $.ajax({
            url: '{{ route("shop.filter") }}',
            type: 'GET',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayProducts(response.products, reset);
                    updateProductCount(response.total, response.from, response.to);

                    // Update infinite scroll state
                    if (window.InfiniteScroll) {
                        window.InfiniteScroll.lastPage = response.last_page;
                        window.InfiniteScroll.currentPage = response.current_page;
                    }

                    $('#shopFilterSidebarWrapper').removeClass('show');
                    $('#shopFilterOverlay').removeClass('show');

                    // Initialize wishlist states
                    if (typeof window.initializeWishlistStates === 'function') {
                        window.initializeWishlistStates();
                    }

                    // Sync cart
                    if (typeof window.Cart !== 'undefined') {
                        window.Cart.syncAllProductCards();
                    }
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

    // Make loadProducts available to infinite scroll
    window.loadProducts = loadProducts;

    function displayProducts(products, reset = false) {
        const container = $('#shopProductsContainer');

        if (reset) {
            container.empty();
        }

        if (products.length === 0 && reset) {
            container.html(`
                <div class="col-12">
                    <div class="shop-empty-state">
                        <i class="fa-regular fa-box-open"></i>
                        <h4>No products found</h4>
                        <p>Try adjusting your filters</p>
                        <button class="rts-btn btn-primary" onclick="$('#shopClearAllFilters').click()">Clear Filters</button>
                    </div>
                </div>
            `);
            return;
        }

        let html = '';
        products.forEach(product => {
            html += `
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    <div class="shop-product-card">
                        <div class="product-image-wrapper">
                            <a href="/product/${product.slug}" class="product-image-link">
                                ${product.discount_percentage > 0 ? `
                                    <div class="product-badge-discount">
                                        <span>${product.discount_percentage}% OFF</span>
                                    </div>
                                ` : ''}
                                ${product.stock <= 5 && product.stock > 0 ? `
                                    <div class="product-badge-stock">
                                        <span>Only ${product.stock} left</span>
                                    </div>
                                ` : ''}
                                <img src="${product.image_url}" alt="${product.name}" class="product-main-image" onerror="this.src='/frontend/assets/images/grocery/01.jpg'">
                            </a>
                            <div class="product-quick-actions">
                                <button class="quick-action-btn wishlist-toggle-btn"
                                        title="Add to Wishlist"
                                        data-product-id="${product.id}">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                                <button class="quick-action-btn shop-quick-view-btn" title="Quick View" data-id="${product.id}">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-meta">
                                <span class="product-category">${product.category?.name || 'Uncategorized'}</span>
                                ${product.brand ? `
                                    <span class="meta-separator">•</span>
                                    <span class="product-brand">${product.brand.name}</span>
                                ` : ''}
                            </div>
                            <a href="/product/${product.slug}" class="product-name-link">
                                <h4 class="product-name">${product.name}</h4>
                            </a>
                            <div class="product-rating">
                                <div class="stars">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-regular fa-star"></i>
                                </div>
                                <span class="rating-count">(4.0)</span>
                            </div>
                            <div class="product-price">
                                <span class="price-current">₹${parseFloat(product.price).toFixed(2)}</span>
                                ${product.mrp && product.mrp > product.price ? `
                                    <span class="price-original">₹${parseFloat(product.mrp).toFixed(2)}</span>
                                    <span class="price-save">Save ₹${(product.mrp - product.price).toFixed(2)}</span>
                                ` : ''}
                            </div>
                            ${product.stock > 0 ? `
                                <div class="product-stock in-stock">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>In Stock</span>
                                </div>
                            ` : `
                                <div class="product-stock out-of-stock">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    <span>Out of Stock</span>
                                </div>
                            `}
                            <button class="product-add-to-cart add-to-cart-btn ${product.stock === 0 ? 'disabled' : ''}"
                                    ${product.stock === 0 ? 'disabled' : ''}
                                    data-product-id="${product.id}">
                                <i class="fa-regular fa-cart-shopping"></i>
                                <span>${product.stock > 0 ? 'Add to Cart' : 'Out of Stock'}</span>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        container.append(html);
    }

    function updateProductCount(total, from, to) {
        $('#shopResultCount').text(`Showing ${from}-${to} of ${total} products`);
    }

    function showError(message) {
        $('#shopProductsContainer').html(`
            <div class="col-12">
                <div class="shop-empty-state">
                    <i class="fa-regular fa-triangle-exclamation"></i>
                    <h4>${message}</h4>
                    <button class="rts-btn btn-primary" onclick="location.reload()">Reload Page</button>
                </div>
            </div>
        `);
    }
});

// JAVASCRIPT ALTERNATIVE: Make entire row clickable
$(document).on('click', '.shop-filter-option', function(e) {
    // Don't trigger if clicking directly on checkbox or label (let native behavior work)
    if (!$(e.target).is('input, label')) {
        const checkbox = $(this).find('input[type="checkbox"]');
        checkbox.prop('checked', !checkbox.is(':checked')).trigger('change');
    }
});
</script>
@endpush
