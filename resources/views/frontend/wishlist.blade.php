@extends('frontend.layouts.app')

@section('title', 'My Wishlist')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/assets/css/wishlist.css') }}">
<style>
    .price-save {
        font-size: 11px;
        font-weight: 600;
        color: var(--shop-success);
        background: rgba(16, 185, 129, 0.1);
        padding: 2px 8px;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
<!-- Wishlist Section -->
<div class="modern-wishlist-section rts-section-gap">
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
    <div class="container">
        <!-- Header -->
        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="modern-wishlist-header">
                    <div class="header-left d-flex align-items-center gap-3">
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
        </div> --}}

        <!-- Empty Wishlist State -->
        <div class="row" id="emptyWishlistState" style="display: none;">
            <div class="col-lg-12">
                <div class="modern-empty-state">
                    <div class="empty-icon wishlist-empty">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                    <h3>Your wishlist is empty</h3>
                    <p>Save products you love and come back to them anytime</p>
                    <div class="empty-state-actions">
                        <a href="{{ route('shop') }}" class="btn-primary-large">
                            <i class="fa-regular fa-store"></i>
                            Browse Products
                        </a>
                        @guest
                        <a href="{{ route('login') }}" class="btn-outline-secondary">
                            <i class="fa-regular fa-user"></i>
                            Sign in to see saved items
                        </a>
                        @endguest
                    </div>
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
// Products already available from server — no AJAX needed on load
const serverProducts = @json($products->values());

$(document).ready(function() {
    if (serverProducts.length > 0) {
        displayWishlist(serverProducts);
    } else {
        showEmptyState();
    }

    function displayWishlist(products) {
        const grid = $('#wishlistItemsGrid');
        grid.empty();
        $('#wishlistItemsGrid').show();
        $('#emptyWishlistState').hide();
        $('#wishlistItemCount').text(products.length);

        products.forEach(product => {
            const card = `
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="modern-wishlist-card">
                        <button class="wishlist-heart active wishlist-toggle-btn wishlist-page-btn"
                                data-product-id="${product.id}"
                                title="Remove from wishlist">
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
                                <img src="${product.image_url}" alt="${product.name}" class="product-img" loading="lazy">
                            </a>
                            ${product.stock <= 0 ? '<span class="stock-badge out">Out of Stock</span>' : ''}
                        </div>

                        <div class="card-content">
                            <div class="product-category">
                                <i class="fa-regular fa-tag"></i>
                                ${product.category?.name || 'General'}
                            </div>

                            <a href="/product/${product.slug}" class="product-title">${product.name}</a>

                            <div class="product-pricing">
                                <span class="current-price">£${parseFloat(product.price).toFixed(2)}</span>
                                ${product.base_price > product.price ? `
                                    <span class="original-price">£${parseFloat(product.base_price).toFixed(2)}</span>
                                    <span class="price-save">Save £${(parseFloat(product.base_price) - parseFloat(product.price)).toFixed(2)}</span>
                                ` : ''}
                            </div>

                            <div class="card-actions">
                                ${product.stock > 0 ? `
                                    ${product.is_weight_based ? `
                                        <a href="/product/${product.slug}" class="btn-add-cart">
                                            <i class="fa-regular fa-weight-scale"></i>
                                            Select Weight
                                        </a>
                                    ` : `
                                        <button class="btn-add-cart add-to-cart-btn" data-product-id="${product.id}">
                                            <i class="fa-regular fa-cart-shopping"></i>
                                            Add to Cart
                                        </button>
                                    `}
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

        if (typeof window.initializeWishlistStates === 'function') {
            window.initializeWishlistStates();
        }
    }

    function showEmptyState() {
        $('#wishlistItemsGrid').hide().empty();
        $('#emptyWishlistState').show();
        $('#wishlistItemCount').text('0');
    }

    // Remove from wishlist
    $(document).on('click', '.wishlist-heart.wishlist-page-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation(); // belt-and-suspenders guard

        const button  = $(this);
        const productId = button.data('product-id');
        const card    = button.closest('[class*="col-"]');

        card.css('opacity', '0.5');

        $.ajax({
            url: '{{ route("wishlist.remove") }}',  // hard remove, not toggle
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', product_id: productId },
            success: function(response) {
                if (response.success) {
                    if (typeof window.updateWishlistCount === 'function') {
                        window.updateWishlistCount(response.count);
                    }
                    card.slideUp(300, function() {
                        $(this).remove();
                        const remaining = $('#wishlistItemsGrid > [class*="col-"]').length;
                        remaining === 0 ? showEmptyState() : $('#wishlistItemCount').text(remaining);
                    });
                    if (typeof toastr !== 'undefined') toastr.success('Removed from wishlist');
                } else {
                    card.css('opacity', '1');
                    if (typeof toastr !== 'undefined') toastr.error(response.message);
                }
            },
            error: function() {
                card.css('opacity', '1');
                if (typeof toastr !== 'undefined') toastr.error('Failed to remove from wishlist');
            }
        });
    });

});
</script>
@endpush
