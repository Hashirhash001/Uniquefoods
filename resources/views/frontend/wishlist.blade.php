@extends('frontend.layouts.app')

@section('title', 'My Wishlist')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/assets/css/wishlist.css') }}">
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
                    <a class="current" href="{{ route('wishlist.index') }}">Wishlist</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section-seperator">
    <div class="container"><hr class="section-seperator"></div>
</div>

<!-- Wishlist Section -->
<div class="modern-wishlist-section rts-section-gap">
    <div class="container">
        <!-- Header -->
        <div class="row">
            <div class="col-lg-12">
                <div class="modern-wishlist-header">
                    <div class="header-left">
                        <div class="wishlist-icon-wrapper">
                            <i class="fa-solid fa-heart"></i>
                            <span class="wishlist-pulse"></span>
                        </div>
                        <div class="header-text">
                            <h2 class="wishlist-title">My Wishlist</h2>
                            <p class="wishlist-subtitle">
                                <span id="wishlistItemCount">0</span> products saved for later
                            </p>
                        </div>
                    </div>
                    <div class="header-right">
                        <a href="{{ route('shop') }}" class="btn-outline-primary">
                            <i class="fa-regular fa-store"></i>
                            Browse Products
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty Wishlist State -->
        <div class="row" id="emptyWishlistState" style="display: none;">
            <div class="col-lg-12">
                <div class="modern-empty-state">
                    <div class="empty-icon wishlist-empty">
                        <i class="fa-regular fa-heart"></i>
                        <div class="heart-animation"></div>
                    </div>
                    <h3>Your wishlist is empty</h3>
                    <p>Start adding products you love to your wishlist</p>
                    <a href="{{ route('shop') }}" class="btn-primary-large">
                        <i class="fa-regular fa-store"></i>
                        Discover Products
                    </a>
                </div>
            </div>
        </div>

        <!-- Wishlist Items -->
        <div class="row g-4" id="wishlistItemsGrid">
            <!-- Items loaded via JavaScript -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('frontend/assets/js/cart-wishlist.js') }}"></script>
<script>
$(document).ready(function() {
    loadWishlist();

    function loadWishlist() {
        showLoader();
        $.ajax({
            url: '{{ route("wishlist.get") }}',
            type: 'GET',
            success: function(response) {
                if (response.success && response.items.length > 0) {
                    loadWishlistProducts(response.items);
                } else {
                    hideLoader();
                    showEmptyState();
                }
            },
            error: function(xhr) {
                hideLoader();
                console.error('Failed to load wishlist:', xhr);
                showEmptyState();
            }
        });
    }

    function loadWishlistProducts(productIds) {
        $.ajax({
            url: '{{ route("shop.filter") }}',
            type: 'GET',
            data: { product_ids: productIds },
            success: function(response) {
                hideLoader();
                if (response.success && response.products.length > 0) {
                    displayWishlist(response.products);
                } else {
                    showEmptyState();
                }
            },
            error: function() {
                hideLoader();
                showEmptyState();
            }
        });
    }

    function displayWishlist(products) {
        const grid = $('#wishlistItemsGrid');
        grid.empty();

        if (products.length === 0) {
            showEmptyState();
            return;
        }

        $('#wishlistItemsGrid').show();
        $('#emptyWishlistState').hide();
        $('#wishlistItemCount').text(products.length);

        products.forEach(product => {
            const card = `
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="modern-wishlist-card">
                        <button class="wishlist-heart active" data-product-id="${product.id}" title="Remove from wishlist">
                            <i class="fa-solid fa-heart"></i>
                        </button>

                        ${product.discount_percentage > 0 ? `
                            <div class="discount-badge">
                                <span>${product.discount_percentage}%</span>
                                <small>OFF</small>
                            </div>
                        ` : ''}

                        <div class="card-image">
                            <a href="/product/${product.slug}">
                                <img src="${product.image_url}" alt="${product.name}" class="product-img">
                            </a>
                            ${product.stock <= 0 ? '<span class="stock-badge out">Out of Stock</span>' : ''}
                        </div>

                        <div class="card-content">
                            <div class="product-category">
                                <i class="fa-regular fa-tag"></i>
                                ${product.category?.name || 'General'}
                            </div>

                            <a href="/product/${product.slug}" class="product-title">
                                ${product.name}
                            </a>

                            <div class="product-rating">
                                <div class="stars">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-regular fa-star"></i>
                                </div>
                                <span class="rating-text">4.0 (128)</span>
                            </div>

                            <div class="product-pricing">
                                <span class="current-price">₹${parseFloat(product.price).toFixed(2)}</span>
                                ${product.mrp && product.mrp > product.price ? `
                                    <span class="original-price">₹${parseFloat(product.mrp).toFixed(2)}</span>
                                ` : ''}
                            </div>

                            <div class="card-actions">
                                ${product.stock > 0 ? `
                                    <button class="btn-add-cart add-to-cart-btn" data-product-id="${product.id}">
                                        <i class="fa-regular fa-cart-shopping"></i>
                                        Add to Cart
                                    </button>
                                ` : `
                                    <button class="btn-add-cart disabled" disabled>
                                        <i class="fa-regular fa-circle-xmark"></i>
                                        Out of Stock
                                    </button>
                                `}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            grid.append(card);
        });

        // Initialize cart states
        if (typeof window.Cart !== 'undefined') {
            window.Cart.syncAllProductCards();
        }
    }

    function showEmptyState() {
        $('#wishlistItemsGrid').hide();
        $('#emptyWishlistState').show();
        $('#wishlistItemCount').text('0');
    }

    // Remove from wishlist
    $(document).on('click', '.wishlist-heart', function() {
        const productId = $(this).data('product-id');
        const button = $(this);

        button.addClass('removing');

        $.ajax({
            url: '{{ route("wishlist.remove") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId
            },
            success: function(response) {
                if (response.success) {
                    // Update header count
                    if (typeof window.updateWishlistCount === 'function') {
                        window.updateWishlistCount(response.count);
                    }

                    // Reload wishlist with animation
                    setTimeout(() => {
                        loadWishlist();
                    }, 300);

                    // Show toast
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    }
                }
            }
        });
    });
});
</script>
@endpush
