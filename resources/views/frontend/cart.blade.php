@extends('frontend.layouts.app')

@section('title', 'Shopping Cart')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/assets/css/cart.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/global-loader.css') }}">
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
                    <a class="current" href="{{ route('cart.index') }}">Shopping Cart</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section-seperator">
    <div class="container"><hr class="section-seperator"></div>
</div>

<!-- Cart Section -->
<div class="modern-cart-section rts-section-gap">
    <div class="container">
        <!-- Header -->
        <div class="row">
            <div class="col-lg-12">
                <div class="modern-cart-header">
                    <div class="header-left">
                        <div class="cart-icon-wrapper">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span class="cart-badge" id="headerCartCount">0</span>
                        </div>
                        <div class="header-text">
                            <h2 class="cart-title">Your Shopping Cart</h2>
                            <p class="cart-subtitle">Review your items before checkout</p>
                        </div>
                    </div>
                    <div class="header-right">
                        <a href="{{ route('shop') }}" class="btn-outline-primary">
                            <i class="fa-regular fa-store"></i>
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty Cart State -->
        <div class="row" id="emptyCartState" style="display: none;">
            <div class="col-lg-12">
                <div class="modern-empty-state">
                    <div class="empty-icon">
                        <i class="fa-regular fa-cart-shopping"></i>
                    </div>
                    <h3>Your cart is empty</h3>
                    <p>Looks like you haven't made your choice yet</p>
                    <a href="{{ route('shop') }}" class="btn-primary-large">
                        <i class="fa-regular fa-store"></i>
                        Explore Products
                    </a>
                </div>
            </div>
        </div>

        <!-- Cart Content -->
        <div class="row g-4" id="cartContentArea">
            <!-- Left: Cart Items -->
            <div class="col-lg-8">
                <div class="cart-items-container">
                    <div class="items-header">
                        <h4>Cart Items</h4>
                        <button class="btn-clear-all" id="clearCartBtn">
                            <i class="fa-regular fa-trash-can"></i>
                            Clear All
                        </button>
                    </div>

                    <div class="cart-items-list" id="cartItemsList">
                        <!-- Items loaded via AJAX -->
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="col-lg-4">
                <div class="modern-order-summary">
                    <h3 class="summary-title">Order Summary</h3>

                    <div class="summary-content">
                        <div class="summary-row">
                            <span class="label">Subtotal</span>
                            <span class="value">₹<span id="summarySubtotal">0.00</span></span>
                        </div>

                        <div class="summary-row">
                            <span class="label">Tax (18% GST)</span>
                            <span class="value">₹<span id="summaryTax">0.00</span></span>
                        </div>

                        <div class="summary-row shipping-row">
                            <span class="label">
                                Shipping
                                <small>Free delivery on orders ₹500+</small>
                            </span>
                            <span class="value shipping-value" id="summaryShipping">FREE</span>
                        </div>

                        <hr class="summary-divider">

                        <div class="summary-row total-row">
                            <span class="label">Total Amount</span>
                            <span class="value total-value">₹<span id="summaryTotal">0.00</span></span>
                        </div>
                    </div>

                    <div class="summary-actions">
                        <button class="btn-checkout-primary">
                            <i class="fa-regular fa-credit-card"></i>
                            Proceed to Checkout
                        </button>
                        <button class="btn-outline-secondary">
                            <i class="fa-regular fa-tag"></i>
                            Apply Coupon Code
                        </button>
                    </div>

                    <div class="trust-badges">
                        <div class="badge-item">
                            <i class="fa-solid fa-shield-check"></i>
                            <span>Secure Checkout</span>
                        </div>
                        <div class="badge-item">
                            <i class="fa-solid fa-truck-fast"></i>
                            <span>Fast Delivery</span>
                        </div>
                        <div class="badge-item">
                            <i class="fa-solid fa-rotate-left"></i>
                            <span>Easy Returns</span>
                        </div>
                    </div>
                </div>

                <!-- Promo Banner -->
                <div class="promo-banner">
                    <i class="fa-solid fa-gift"></i>
                    <div class="promo-text">
                        <strong>Special Offer!</strong>
                        <span>Get 10% off on orders above ₹1000</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('frontend/assets/js/global-loader.js') }}"></script>
<script src="{{ asset('frontend/assets/js/cart-wishlist.js') }}"></script>
<script>
$(document).ready(function() {
    // Initial load
    loadCart();

    // ==================== LOAD CART (AJAX) ====================
    function loadCart() {
        showLoader('Loading cart...');

        $.ajax({
            url: '{{ route("cart.get") }}',
            type: 'GET',
            success: function(response) {
                hideLoader();
                if (response.success) {
                    displayCart(response.cart);
                    updateAllCartCounts(response.cart);
                }
            },
            error: function(xhr) {
                hideLoader();
                console.error('Failed to load cart:', xhr);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to load cart. Please refresh the page.');
                }
            }
        });
    }

    // ==================== DISPLAY CART (NO RELOAD) ====================
    function displayCart(cart) {
        const container = $('#cartItemsList');
        container.empty();

        // Empty state
        if (!cart.items || cart.items.length === 0) {
            $('#cartContentArea').hide();
            $('#emptyCartState').show();
            return;
        }

        // Show cart content
        $('#cartContentArea').show();
        $('#emptyCartState').hide();

        // Render each item
        cart.items.forEach(item => {
            const itemHtml = createCartItemHtml(item);
            container.append(itemHtml);
        });

        // Update summary
        updateSummary(cart);
    }

    // ==================== CREATE CART ITEM HTML ====================
    function createCartItemHtml(item) {
        return `
            <div class="modern-cart-item" data-product-id="${item.id}">
                <div class="item-image">
                    <img src="${item.image}" alt="${item.name}">
                    ${item.stock <= 0 ? '<span class="stock-badge out-stock">Out of Stock</span>' : ''}
                </div>

                <div class="item-details">
                    <a href="/product/${item.slug}" class="item-name">${item.name}</a>
                    <div class="item-meta">
                        <span class="stock-indicator ${item.stock > 0 ? 'in-stock' : 'out-stock'}">
                            <i class="fa-solid fa-circle"></i>
                            ${item.stock > 0 ? 'In Stock' : 'Out of Stock'}
                        </span>
                    </div>
                    <div class="item-price-mobile">₹${parseFloat(item.price).toFixed(2)}</div>
                </div>

                <div class="item-quantity">
                    <div class="modern-quantity-control">
                        <button class="qty-btn qty-decrease" data-product-id="${item.id}" ${item.quantity <= 1 ? 'disabled' : ''}>
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <input type="text" class="qty-value" value="${item.quantity}" readonly>
                        <button class="qty-btn qty-increase" data-product-id="${item.id}" ${item.stock <= item.quantity ? 'disabled' : ''}>
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="item-price">
                    <div class="price-label">Price</div>
                    <div class="price-value">₹${parseFloat(item.price).toFixed(2)}</div>
                </div>

                <div class="item-subtotal">
                    <div class="subtotal-label">Subtotal</div>
                    <div class="subtotal-value">₹${(item.price * item.quantity).toFixed(2)}</div>
                </div>

                <div class="item-remove">
                    <button class="btn-remove" data-product-id="${item.id}" title="Remove item">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>
            </div>
        `;
    }

    // ==================== UPDATE SUMMARY ====================
    function updateSummary(cart) {
        const subtotal = cart.subtotal || 0;
        const tax = subtotal * 0.18;
        const shipping = subtotal >= 500 ? 0 : 50;
        const total = subtotal + tax + shipping;

        // Animate number changes
        animateValue('summarySubtotal', subtotal);
        animateValue('summaryTax', tax);
        animateValue('summaryTotal', total);

        if (shipping === 0) {
            $('#summaryShipping').html('<span class="free-badge">FREE</span>');
        } else {
            $('#summaryShipping').html('₹' + shipping.toFixed(2));
        }
    }

    // ==================== ANIMATE NUMBER CHANGES ====================
    function animateValue(elementId, endValue) {
        const element = document.getElementById(elementId);
        const startValue = parseFloat(element.textContent) || 0;
        const duration = 300;
        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const currentValue = startValue + (endValue - startValue) * progress;
            element.textContent = currentValue.toFixed(2);

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }

        requestAnimationFrame(update);
    }

    // ==================== UPDATE ALL COUNTS ====================
    function updateAllCartCounts(cart) {
        const count = cart.items ? cart.items.length : 0;
        const total = cart.total || 0;

        // Update all count elements
        $('#cartCount, #mobileCartCount, #headerCartCount, #cartItemCount').text(count);
        $('#cartTotal').text(total.toFixed(2));
    }

    // ==================== QUANTITY DECREASE (AJAX) ====================
    $(document).on('click', '.qty-decrease', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const button = $(this);

        if (button.prop('disabled')) return;

        // Show mini loader on button
        const originalHtml = button.html();
        button.html('<i class="btn-loader"></i>').prop('disabled', true);

        $.ajax({
            url: '{{ route("cart.update") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                action: 'minus'
            },
            success: function(response) {
                if (response.success) {
                    // Update cart display without reload
                    displayCart(response.cart);
                    updateAllCartCounts(response.cart);
                } else {
                    button.html(originalHtml).prop('disabled', false);
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message || 'Failed to update quantity');
                    }
                }
            },
            error: function(xhr) {
                button.html(originalHtml).prop('disabled', false);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to update cart. Please try again.');
                }
            }
        });
    });

    // ==================== QUANTITY INCREASE (AJAX) ====================
    $(document).on('click', '.qty-increase', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const button = $(this);

        if (button.prop('disabled')) return;

        // Show mini loader on button
        const originalHtml = button.html();
        button.html('<i class="btn-loader"></i>').prop('disabled', true);

        $.ajax({
            url: '{{ route("cart.update") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                action: 'plus'
            },
            success: function(response) {
                if (response.success) {
                    // Update cart display without reload
                    displayCart(response.cart);
                    updateAllCartCounts(response.cart);
                } else {
                    button.html(originalHtml).prop('disabled', false);
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message || 'Failed to update quantity');
                    }
                }
            },
            error: function(xhr) {
                button.html(originalHtml).prop('disabled', false);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to update cart. Please try again.');
                }
            }
        });
    });

    // ==================== REMOVE ITEM (AJAX) ====================
    $(document).on('click', '.btn-remove', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const cartItem = $(this).closest('.modern-cart-item');

        // Add removing animation
        cartItem.css('opacity', '0.5');

        $.ajax({
            url: '{{ route("cart.remove") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId
            },
            success: function(response) {
                if (response.success) {
                    // Slide up animation then remove
                    cartItem.slideUp(300, function() {
                        $(this).remove();
                        // Update cart display
                        displayCart(response.cart);
                        updateAllCartCounts(response.cart);
                    });

                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message || 'Item removed from cart');
                    }
                } else {
                    cartItem.css('opacity', '1');
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message || 'Failed to remove item');
                    }
                }
            },
            error: function(xhr) {
                cartItem.css('opacity', '1');
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to remove item. Please try again.');
                }
            }
        });
    });

    // ==================== CLEAR CART (AJAX WITH TOAST) ====================
    $('#clearCartBtn').on('click', function(e) {
        e.preventDefault();

        if (typeof toastr !== 'undefined') {
            toastr.warning(
                '<div style="text-align: center;">' +
                '<p style="margin-bottom: 15px; font-weight: 600;">Clear your entire cart?</p>' +
                '<button type="button" class="btn btn-sm btn-danger me-2" id="confirmClearCart" style="padding: 8px 20px;">Yes, Clear</button>' +
                '<button type="button" class="btn btn-sm btn-secondary" id="cancelClearCart" style="padding: 8px 20px;">Cancel</button>' +
                '</div>',
                '',
                {
                    closeButton: false,
                    tapToDismiss: false,
                    timeOut: 0,
                    extendedTimeOut: 0,
                    allowHtml: true,
                    positionClass: 'toast-top-center',
                    onShown: function() {
                        $('#confirmClearCart').on('click', function() {
                            toastr.clear();
                            performClearCart();
                        });
                        $('#cancelClearCart').on('click', function() {
                            toastr.clear();
                        });
                    }
                }
            );
        } else {
            if (confirm('Clear your entire cart?')) {
                performClearCart();
            }
        }
    });

    // ==================== PERFORM CLEAR CART ====================
    function performClearCart() {
        showLoader('Clearing cart...');

        $.ajax({
            url: '{{ route("cart.clear") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                hideLoader();
                if (response.success) {
                    // Fade out all items
                    $('.modern-cart-item').fadeOut(300, function() {
                        displayCart(response.cart);
                        updateAllCartCounts(response.cart);
                    });

                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message || 'Cart cleared successfully');
                    }
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message || 'Failed to clear cart');
                    }
                }
            },
            error: function(xhr) {
                hideLoader();
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to clear cart. Please try again.');
                }
            }
        });
    }
});
</script>
@endpush
