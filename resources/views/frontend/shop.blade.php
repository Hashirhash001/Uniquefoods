@extends('frontend.layouts.app')

@section('title', 'Shop - All Products - Unique Foods')
@section('meta_description', 'Browse our full range of fresh groceries, produce, and specialty foods.')
@section('meta_canonical',   route('shop'))
@section('og_title',         'Shop — ' . config('app.name'))
@section('og_url',           route('shop'))

@push('styles')
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

<!-- Main Shop Area -->
<div class="shop-grid-sidebar-area rts-section-gap">
    <div class="container">
        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-lg-3 col-md-12">
                <div class="shop-filter-overlay" id="shopFilterOverlay"></div>
                <div class="shop-filter-sidebar-wrapper" id="shopFilterSidebarWrapper">

                    <!-- Mobile sidebar close button -->
                    <div class="shop-filter-mobile-close d-lg-none">
                        <span style="font-size:16px; font-weight:700; color:#111;">Filters</span>
                        <button id="shopFilterClose" style="background:none;border:none;font-size:22px;cursor:pointer;color:#666;padding:4px 8px;width:unset;">
                            <i class="fa-regular fa-xmark"></i>
                        </button>
                    </div>

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

                        <!-- Price Filter -->
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

                        <!-- Mobile Apply Button -->
                        <div class="d-lg-none" style="padding:16px 24px;">
                            <button id="shopApplyFilters" style="width:100%;padding:14px;background:#0f508d;color:white;border:none;border-radius:8px;font-size:15px;font-weight:700;cursor:pointer;">
                                Apply Filters
                            </button>
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

                    <!-- Desktop Sort Dropdown -->
                    <div class="shop-desktop-sort-dropdown-wrapper" id="shopDesktopSortWrapper">
                        <button class="shop-desktop-sort-trigger" id="shopDesktopSortTrigger">
                            <i class="fa-regular fa-clock" id="shopDesktopSortIcon"></i>
                            <span id="shopDesktopSortLabel">Latest First</span>
                            <i class="fa-regular fa-chevron-down shop-desktop-sort-arrow"></i>
                        </button>
                        <div class="shop-desktop-sort-menu" id="shopDesktopSortMenu">
                            <button class="shop-desktop-sort-option active" data-value="latest" data-label="Latest First" data-icon="fa-regular fa-clock">
                                <i class="fa-regular fa-clock"></i>
                                <span>Latest First</span>
                                <i class="fa-solid fa-check shop-sort-check"></i>
                            </button>
                            <button class="shop-desktop-sort-option" data-value="price_low" data-label="Price: Low to High" data-icon="fa-regular fa-arrow-up">
                                <i class="fa-regular fa-arrow-up"></i>
                                <span>Price: Low to High</span>
                                <i class="fa-solid fa-check shop-sort-check"></i>
                            </button>
                            <button class="shop-desktop-sort-option" data-value="price_high" data-label="Price: High to Low" data-icon="fa-regular fa-arrow-down">
                                <i class="fa-regular fa-arrow-down"></i>
                                <span>Price: High to Low</span>
                                <i class="fa-solid fa-check shop-sort-check"></i>
                            </button>
                            <button class="shop-desktop-sort-option" data-value="name_asc" data-label="Name: A to Z" data-icon="fa-regular fa-arrow-down-a-z">
                                <i class="fa-regular fa-arrow-down-a-z"></i>
                                <span>Name: A to Z</span>
                                <i class="fa-solid fa-check shop-sort-check"></i>
                            </button>
                            <button class="shop-desktop-sort-option" data-value="name_desc" data-label="Name: Z to A" data-icon="fa-regular fa-arrow-up-a-z">
                                <i class="fa-regular fa-arrow-up-a-z"></i>
                                <span>Name: Z to A</span>
                                <i class="fa-solid fa-check shop-sort-check"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Products Container -->
                <div class="row g-4" id="shopProductsContainer">
                    @for($i = 0; $i < 8; $i++)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-6 skeleton-col">
                            <div class="shop-product-card skeleton-card">
                                <div class="skeleton skeleton-image"></div>
                                <div class="product-info" style="padding: 12px;">
                                    <div class="skeleton skeleton-text short"></div>
                                    <div class="skeleton skeleton-text"></div>
                                    <div class="skeleton skeleton-text medium"></div>
                                    <div class="skeleton skeleton-price"></div>
                                    <div class="skeleton skeleton-btn"></div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Filter + Sort Bar -->
<div class="shop-mobile-filter-bar" id="shopMobileFilterBar">
    <button class="shop-mobile-filter-trigger" id="shopFilterTrigger">
        <i class="fa-regular fa-sliders"></i>
        <span>Filters</span>
        <span class="shop-filter-count-badge" id="shopFilterCount" style="display:none;"></span>
    </button>
    <div style="width:1px;background:#e5e7eb;align-self:stretch;margin:8px 0;"></div>
    <button class="shop-mobile-sort-trigger" id="shopSortTrigger">
        <i class="fa-regular fa-arrow-up-arrow-down"></i>
        <span id="shopSortLabel">Sort</span>
    </button>
</div>

<!-- Mobile Sort Sheet -->
<div class="shop-mobile-sort-sheet" id="shopMobileSortSheet">
    <div class="shop-sort-sheet-handle"></div>
    <div class="shop-sort-sheet-header">
        <span>Sort By</span>
        <button id="shopSortSheetClose" style="background:none;border:none;font-size:20px;cursor:pointer;color:#666;width:unset;padding:4px 8px;">
            <i class="fa-regular fa-xmark"></i>
        </button>
    </div>
    <div class="shop-sort-sheet-options">
        <button class="shop-sort-option active" data-value="latest">
            <i class="fa-regular fa-clock"></i> Latest First
            <i class="fa-solid fa-check shop-sort-check"></i>
        </button>
        <button class="shop-sort-option" data-value="price_low">
            <i class="fa-regular fa-arrow-up"></i> Price: Low to High
            <i class="fa-solid fa-check shop-sort-check"></i>
        </button>
        <button class="shop-sort-option" data-value="price_high">
            <i class="fa-regular fa-arrow-down"></i> Price: High to Low
            <i class="fa-solid fa-check shop-sort-check"></i>
        </button>
        <button class="shop-sort-option" data-value="name_asc">
            <i class="fa-regular fa-arrow-down-a-z"></i> Name: A to Z
            <i class="fa-solid fa-check shop-sort-check"></i>
        </button>
        <button class="shop-sort-option" data-value="name_desc">
            <i class="fa-regular fa-arrow-up-a-z"></i> Name: Z to A
            <i class="fa-solid fa-check shop-sort-check"></i>
        </button>
    </div>
</div>

<!-- Sort overlay -->
<div class="shop-sort-overlay" id="shopSortOverlay"></div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
<script src="{{ asset('frontend/assets/js/cart-wishlist.js') }}"></script>
<script src="{{ asset('frontend/assets/js/infinite-scroll.js') }}"></script>

<script>
// ============================================================
// GLOBAL FUNCTIONS — outside jQuery ready so onclick= works
// ============================================================
function toggleShopFilter() {
    var wrapper = document.getElementById('shopFilterSidebarWrapper');
    var overlay = document.getElementById('shopFilterOverlay');
    var trigger = document.getElementById('shopFilterTrigger');
    var isOpen  = wrapper.classList.contains('show');
    wrapper.classList.toggle('show', !isOpen);
    overlay.classList.toggle('show', !isOpen);
    if (trigger) trigger.classList.toggle('active', !isOpen);
    document.body.style.overflow = isOpen ? '' : 'hidden';
}

function closeShopFilter() {
    var wrapper = document.getElementById('shopFilterSidebarWrapper');
    var overlay = document.getElementById('shopFilterOverlay');
    var trigger = document.getElementById('shopFilterTrigger');
    if (wrapper) wrapper.classList.remove('show');
    if (overlay) overlay.classList.remove('show');
    if (trigger) trigger.classList.remove('active');
    document.body.style.overflow = '';
}

function toggleSortSheet() {
    var sheet   = document.getElementById('shopMobileSortSheet');
    var overlay = document.getElementById('shopSortOverlay');
    var trigger = document.getElementById('shopSortTrigger');
    var isOpen  = sheet.classList.contains('open');
    sheet.classList.toggle('open', !isOpen);
    overlay.classList.toggle('show', !isOpen);
    if (trigger) trigger.classList.toggle('active', !isOpen);
    document.body.style.overflow = isOpen ? '' : 'hidden';
}

function closeSortSheet() {
    var sheet   = document.getElementById('shopMobileSortSheet');
    var overlay = document.getElementById('shopSortOverlay');
    var trigger = document.getElementById('shopSortTrigger');
    if (sheet)   sheet.classList.remove('open');
    if (overlay) overlay.classList.remove('show');
    if (trigger) trigger.classList.remove('active');
    document.body.style.overflow = '';
}

// ============================================================
// MAIN SHOP LOGIC
// ============================================================
$(document).ready(function() {

    // Wire up buttons
    $('#shopFilterTrigger').on('click', toggleShopFilter);
    $('#shopSortTrigger').on('click', toggleSortSheet);
    $('#shopFilterClose').on('click', closeShopFilter);
    $('#shopFilterOverlay').on('click', closeShopFilter);
    $('#shopSortSheetClose').on('click', closeSortSheet);
    $('#shopSortOverlay').on('click', closeSortSheet);
    var currentSort = 'latest';

    // Apply filters button (mobile)
    $('#shopApplyFilters').on('click', function() {
        updateCategoryFilters();
        currentPage = 1;
        updateActiveFilters();
        loadProducts(true);
        closeShopFilter();
    });

    function handleImgError(img) {
        img.src = '/frontend/assets/images/grocery/01.jpg';
    }

    window.handleImgError = handleImgError;

    // Mobile sort option click
    $(document).on('click', '.shop-sort-option', function() {
        var value = $(this).data('value');
        var label = $(this).find('span').text();

        $('#shopSortBy').val(value);
        $('.shop-sort-option').removeClass('active');
        $(this).addClass('active');
        $('#shopSortLabel').text(label);

        closeSortSheet();
        currentPage = 1;
        loadProducts(true);
    });

    // ─────────────────────────────────────────
    var currentPage  = 1;
    var activeFilters = {
        categories: [],
        brands:     [],
        minPrice:   0,
        maxPrice:   10000
    };
    window.activeFilters = activeFilters;

    // ── Parse URL params ──
    var urlParams      = new URLSearchParams(window.location.search);
    var categoryParams = [];
    for (var pair of urlParams.entries()) {
        if (pair[0] === 'categories[]' || pair[0] === 'categories') {
            categoryParams.push(pair[1]);
        }
    }
    if (categoryParams.length > 0) {
        categoryParams.forEach(function(catId) {
            var checkbox = document.querySelector('.shop-category-filter[value="' + catId + '"]');
            if (checkbox) checkbox.checked = true;
        });
        activeFilters.categories = categoryParams;
        window.activeFilters     = activeFilters;
    }

    // ── Price Slider ──
    var priceSlider = document.getElementById('shopPriceSlider');
    noUiSlider.create(priceSlider, {
        start:   [0, 10000],
        connect: true,
        step:    10,
        range:   { 'min': 0, 'max': 10000 },
        format: {
            to:   function(v) { return Math.round(v); },
            from: function(v) { return Math.round(v); }
        }
    });

    priceSlider.noUiSlider.on('update', function(values) {
        $('#shopMinPrice').val(values[0]);
        $('#shopMaxPrice').val(values[1]);
    });

    priceSlider.noUiSlider.on('change', function(values) {
        activeFilters.minPrice = parseInt(values[0]);
        activeFilters.maxPrice = parseInt(values[1]);
        if (window.innerWidth >= 992) {
            currentPage = 1;
            updateActiveFilters();
            loadProducts(true);
        } else {
            updateFilterBadge();
        }
    });

    // ── Collapsible filter sections ──
    $('.shop-filter-title').on('click', function() {
        var targetId = $(this).data('toggle');
        $(this).toggleClass('collapsed');
        $('#' + targetId).toggleClass('collapsed');
    });

    // ── Category filter change ──
    $(document).on('change', '.shop-category-filter', function() {
        var checkbox   = $(this);
        var categoryId = checkbox.val();
        var isParent   = checkbox.hasClass('shop-parent-filter');
        var isChecked  = checkbox.is(':checked');

        if (isParent) {
            $('.shop-child-filter[data-parent-id="' + categoryId + '"]').prop('checked', isChecked);
        } else {
            var parentId = checkbox.data('parent-id');
            var siblings = $('.shop-child-filter[data-parent-id="' + parentId + '"]');
            var checked  = siblings.filter(':checked').length;
            $('.shop-parent-filter[value="' + parentId + '"]').prop('checked', checked === siblings.length);
            if (checked > 0) {
                $('.shop-parent-filter[value="' + parentId + '"]').closest('.shop-parent-category').addClass('has-selected-child');
            } else {
                $('.shop-parent-filter[value="' + parentId + '"]').closest('.shop-parent-category').removeClass('has-selected-child');
            }
        }

        if (window.innerWidth >= 992) {
            updateCategoryFilters();
            currentPage = 1;
            updateActiveFilters();
            loadProducts(true);
        } else {
            updateCategoryFilters();
            updateFilterBadge();
        }
    });

    // ── Brand filter change ──
    $(document).on('change', '.shop-brand-filter', function() {
        activeFilters.brands = [];
        $('.shop-brand-filter:checked').each(function() {
            activeFilters.brands.push($(this).val());
        });
        if (window.innerWidth >= 992) {
            currentPage = 1;
            updateActiveFilters();
            loadProducts(true);
        } else {
            updateFilterBadge();
        }
    });

    // ── Clear all filters ──
    $('#shopClearAllFilters').on('click', function(e) {
        e.preventDefault();
        clearAllFilters();
    });

    // ── Remove individual filter tag ──
    $(document).on('click', '.shop-remove-filter', function() {
        var type  = $(this).data('type');
        var value = $(this).data('value');
        if (type === 'category') {
            $('.shop-category-filter[value="' + value + '"]').prop('checked', false).trigger('change');
        } else if (type === 'brand') {
            $('.shop-brand-filter[value="' + value + '"]').prop('checked', false);
            activeFilters.brands = [];
            $('.shop-brand-filter:checked').each(function() {
                activeFilters.brands.push($(this).val());
            });
            currentPage = 1;
            updateActiveFilters();
            loadProducts(true);
        } else if (type === 'price') {
            priceSlider.noUiSlider.set([0, 10000]);
            activeFilters.minPrice = 0;
            activeFilters.maxPrice = 10000;
            currentPage = 1;
            updateActiveFilters();
            loadProducts(true);
        }
    });

    // ── Make entire filter row clickable ──
    $(document).on('click', '.shop-filter-option', function(e) {
        if (!$(e.target).is('input, label')) {
            var cb = $(this).find('input[type="checkbox"]');
            cb.prop('checked', !cb.is(':checked')).trigger('change');
        }
    });

    // Desktop sort button click — update currentSort instead:
    $(document).on('click', '.shop-desktop-sort-option', function() {
        var value = $(this).data('value');
        currentSort = value;  // ← store in variable

        $('.shop-sort-option').removeClass('active');
        $('.shop-sort-option[data-value="' + value + '"]').addClass('active');
        $('.shop-desktop-sort-option').removeClass('active');
        $(this).addClass('active');

        currentPage = 1;
        loadProducts(true);
    });

    // ── Desktop sort dropdown toggle ──
    $(document).on('click', '#shopDesktopSortTrigger', function(e) {
        e.stopPropagation();
        $('#shopDesktopSortWrapper').toggleClass('open');
    });

    // Close on outside click
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#shopDesktopSortWrapper').length) {
            $('#shopDesktopSortWrapper').removeClass('open');
        }
    });

    // Option selected
    $(document).on('click', '.shop-desktop-sort-option', function(e) {
        e.stopPropagation();
        var value = $(this).data('value');
        var label = $(this).data('label');
        var icon  = $(this).data('icon');

        currentSort = value;

        // Update trigger label + icon
        $('#shopDesktopSortLabel').text(label);
        $('#shopDesktopSortIcon').attr('class', icon);

        // Update active states
        $('.shop-desktop-sort-option').removeClass('active');
        $(this).addClass('active');

        // Sync mobile sort sheet
        $('.shop-sort-option').removeClass('active');
        $('.shop-sort-option[data-value="' + value + '"]').addClass('active');
        $('#shopSortLabel').text(label);

        $('#shopDesktopSortWrapper').removeClass('open');
        currentPage = 1;
        loadProducts(true);
    });

    // Mobile sort sheet click — update currentSort:
    $(document).on('click', '.shop-sort-option', function() {
        var value = $(this).data('value');
        var label = $(this).clone().children().remove().end().text().trim();
        currentSort = value;  // ← store in variable

        $('.shop-sort-option').removeClass('active');
        $(this).addClass('active');
        $('.shop-desktop-sort-option').removeClass('active');
        $('.shop-desktop-sort-option[data-value="' + value + '"]').addClass('active');
        $('#shopSortLabel').text(label);

        closeSortSheet();
        currentPage = 1;
        loadProducts(true);
    });

    // ─────────────────────────────────────────
    // HELPER FUNCTIONS
    // ─────────────────────────────────────────
    function updateCategoryFilters() {
        activeFilters.categories = [];
        $('.shop-category-filter:checked').each(function() {
            activeFilters.categories.push($(this).val());
        });
        window.activeFilters = activeFilters;
    }

    function updateFilterBadge() {
        var total = activeFilters.categories.length +
                    activeFilters.brands.length +
                    (activeFilters.minPrice > 0 || activeFilters.maxPrice < 10000 ? 1 : 0);
        var badge = $('#shopFilterCount');
        if (total > 0) {
            badge.text(total).css('display', 'inline-flex');
        } else {
            badge.hide();
        }
    }

    function clearAllFilters() {
        $('.shop-category-filter, .shop-brand-filter').prop('checked', false);
        priceSlider.noUiSlider.set([0, 10000]);
        currentSort = 'latest';
        $('.shop-sort-option').removeClass('active');
        $('.shop-sort-option[data-value="latest"]').addClass('active');
        $('#shopSortLabel').text('Sort');
        activeFilters = { categories: [], brands: [], minPrice: 0, maxPrice: 10000 };
        window.activeFilters = activeFilters;
        currentPage = 1;
        updateFilterBadge();
        updateActiveFilters();
        loadProducts(true);
    }

    function updateActiveFilters() {
        updateCategoryFilters();
        var html       = '';
        var hasFilters = activeFilters.categories.length > 0 ||
                         activeFilters.brands.length > 0 ||
                         activeFilters.minPrice > 0 ||
                         activeFilters.maxPrice < 10000;

        if (hasFilters) {
            if (activeFilters.minPrice > 0 || activeFilters.maxPrice < 10000) {
                html += '<span class="shop-active-filter-tag">£' + activeFilters.minPrice + ' - £' + activeFilters.maxPrice +
                        ' <i class="fa-solid fa-xmark shop-remove-filter" data-type="price"></i></span>';
            }
            activeFilters.categories.forEach(function(id) {
                var cb          = $('.shop-category-filter[value="' + id + '"]');
                var name        = cb.data('name');
                var isChild     = cb.hasClass('shop-child-filter');
                var displayName = isChild ? cb.data('parent-name') + ' > ' + name : name;
                html += '<span class="shop-active-filter-tag">' + displayName +
                        ' <i class="fa-solid fa-xmark shop-remove-filter" data-type="category" data-value="' + id + '"></i></span>';
            });
            activeFilters.brands.forEach(function(id) {
                var name = $('.shop-brand-filter[value="' + id + '"]').data('name');
                html += '<span class="shop-active-filter-tag">' + name +
                        ' <i class="fa-solid fa-xmark shop-remove-filter" data-type="brand" data-value="' + id + '"></i></span>';
            });
            $('#shopActiveFiltersBar').addClass('show');
        } else {
            $('#shopActiveFiltersBar').removeClass('show');
        }

        $('#shopActiveFilterTags').html(html);
        updateFilterBadge();
    }

    function loadProducts(reset) {
        if (reset) {
            currentPage = 1;
            if (window.InfiniteScroll) {
                window.InfiniteScroll.reset();
                window.InfiniteScroll.currentPage = 1;
            }
            $(document).trigger('shopFiltersChanged');

            // ✅ Skeleton cards instead of spinner
            var skeletonCard =
                '<div class="col-lg-3 col-md-4 col-sm-6 col-6 skeleton-col">' +
                    '<div class="shop-product-card skeleton-card">' +
                        '<div class="skeleton skeleton-image"></div>' +
                        '<div class="product-info" style="padding: 12px">' +
                            '<div class="skeleton skeleton-text short"></div>' +
                            '<div class="skeleton skeleton-text"></div>' +
                            '<div class="skeleton skeleton-text medium"></div>' +
                            '<div class="skeleton skeleton-price"></div>' +
                            '<div class="skeleton skeleton-btn"></div>' +
                        '</div>' +
                    '</div>' +
                '</div>';

            $('#shopProductsContainer').html(
                skeletonCard.repeat(8)
            );
        }

        var data = {
            page:       currentPage,
            min_price:  activeFilters.minPrice,
            max_price:  activeFilters.maxPrice,
            categories: activeFilters.categories,
            brands:     activeFilters.brands,
            sort: currentSort || 'latest'
        };

        $.ajax({
            url:      '{{ route("shop.filter") }}',
            type:     'GET',
            data:     data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayProducts(response.products, reset);
                    updateProductCount(response.total, response.from, response.to);
                    if (window.InfiniteScroll) {
                        window.InfiniteScroll.lastPage    = response.last_page;
                        window.InfiniteScroll.currentPage = response.current_page;
                    }
                    if (typeof window.initializeWishlistStates === 'function') window.initializeWishlistStates();
                    if (typeof window.Cart !== 'undefined') window.Cart.syncAllProductCards();
                } else {
                    showError('Failed to load products');
                }
            },
            error: function() { showError('Error loading products. Please try again.'); }
        });
    }

    window.loadProducts = loadProducts;

    function displayProducts(products, reset) {
        var container = $('#shopProductsContainer');
        if (reset) container.empty();

        if (products.length === 0 && reset) {
            container.html(
                '<div class="col-12"><div class="shop-empty-state">' +
                '<i class="fa-regular fa-box-open"></i>' +
                '<h4>No products found</h4>' +
                '<p>Try adjusting your filters</p>' +
                '<button class="rts-btn btn-primary" onclick="clearAllShopFilters()">Clear Filters</button>' +
                '</div></div>'
            );
            return;
        }

        var html = '';
        products.forEach(function(product) {
            var finalPrice   = parseFloat(product.price || 0);
            var basePrice    = parseFloat(product.base_price || product.price || 0);
            var showDiscount = parseInt(product.discount_percentage || 0) > 0;
            var showStrike   = basePrice > finalPrice;

            var discountBadge = showDiscount
                ? '<div class="product-badge-discount"><span>' + product.discount_percentage + '% OFF</span></div>'
                : '';
            var stockBadge = (product.stock <= 5 && product.stock > 0)
                ? '<div class="product-badge-stock"><span>Only ' + product.stock + ' left</span></div>'
                : '';
            var strikePriceHtml = showStrike
                ? '<span class="price-original">£' + basePrice.toFixed(2) + '</span>' +
                  '<span class="price-save">Save £' + (basePrice - finalPrice).toFixed(2) + '</span>'
                : '';
            var brandHtml = product.brand
                ? '<span class="meta-separator">•</span><span class="product-brand">' + product.brand.name + '</span>'
                : '';

            var starsHtml = '';
            if (product.reviews_count > 0) {
                var rating = Math.round(parseFloat(product.average_rating || 0));
                for (var i = 1; i <= 5; i++) {
                    starsHtml += '<i class="fa-' + (i <= rating ? 'solid' : 'regular') + ' fa-star"></i>';
                }
                starsHtml = '<div class="product-rating"><div class="stars">' + starsHtml + '</div>' +
                            '<span class="rating-count">(' + parseFloat(product.average_rating||0).toFixed(1) + ')</span></div>';
            } else {
                starsHtml = '<div class="product-rating"><div class="stars"><i class="fa-regular fa-star"></i>' +
                            '<i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i>' +
                            '<i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i></div>' +
                            '<span class="rating-count">(0.0)</span></div>';
            }

            var actionBtn = '';
            if (product.stock > 0) {
                if (product.is_weight_based) {
                    actionBtn = '<a href="/product/' + product.slug + '" class="btn-select-weight">' +
                                '<i class="fa-regular fa-weight-scale"></i><span>Select Weight</span></a>';
                } else {
                    actionBtn = '<button class="product-add-to-cart add-to-cart-btn" data-product-id="' + product.id + '">' +
                                '<i class="fa-regular fa-cart-shopping"></i><span>Add to Cart</span></button>';
                }
            } else {
                actionBtn = '<button class="product-add-to-cart disabled" disabled>' +
                            '<i class="fa-solid fa-circle-xmark"></i><span>Out of Stock</span></button>';
            }

            html += '<div class="col-lg-3 col-md-4 col-sm-6 col-6">' +
                    '<div class="shop-product-card">' +
                    '<div class="product-image-wrapper">' +
                    '<a href="/product/' + product.slug + '" class="product-image-link">' +
                    discountBadge + stockBadge +
                    '<img src="' + product.image_url + '" alt="' + product.name + '" class="product-main-image"' +
                    ' onerror="handleImgError(this)">' +
                    '</a>' +
                    '<div class="product-quick-actions">' +
                    '<button class="quick-action-btn wishlist-toggle-btn ' + (product.is_wishlisted ? 'active' : '') + '"' +
                    ' data-product-id="' + product.id + '">' +
                    '<i class="' + (product.is_wishlisted ? 'fa-solid fa-heart' : 'fa-regular fa-heart') + '"></i></button>' +
                    '<a href="/product/' + product.slug + '" class="quick-action-btn">' +
                    '<i class="fa-regular fa-eye"></i></a>' +
                    '</div></div>' +
                    '<div class="product-info">' +
                    '<div class="product-meta">' +
                    '<span class="product-category">' + (product.category ? product.category.name : 'Uncategorized') + '</span>' +
                    brandHtml + '</div>' +
                    '<a href="/product/' + product.slug + '" class="product-name-link">' +
                    '<h4 class="product-name">' + product.name + '</h4></a>' +
                    starsHtml +
                    '<div class="product-price">' +
                    '<span class="price-current">£' + finalPrice.toFixed(2) + '</span>' +
                    strikePriceHtml + '</div>' +
                    actionBtn +
                    '</div></div></div>';
        });

        container.append(html);
    }

    function clearAllShopFilters() {
        document.getElementById('shopClearAllFilters').click();
    }

    window.clearAllShopFilters = clearAllShopFilters;

    function updateProductCount(total, from, to) {
        $('#shopResultCount').text('Showing ' + from + '-' + to + ' of ' + total + ' products');
    }

    function showError(message) {
        $('#shopProductsContainer').html(
            '<div class="col-12"><div class="shop-empty-state">' +
            '<i class="fa-regular fa-triangle-exclamation"></i>' +
            '<h4>' + message + '</h4>' +
            '<button class="rts-btn btn-primary" onclick="location.reload()">Reload Page</button>' +
            '</div></div>'
        );
    }

    // Initial load
    updateActiveFilters();
    loadProducts(true);
});
</script>
@endpush
