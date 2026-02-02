/**
 * ================================================
 * CART & WISHLIST - FOOLPROOF VERSION
 * Handles all edge cases and UI syncing
 * ================================================
 */

(function($) {
    'use strict';

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // ================================================
    // TOAST NOTIFICATION SYSTEM
    // ================================================
    const Toast = {
        container: null,

        init() {
            if (!this.container) {
                this.container = $('<div class="toast-container"></div>');
                $('body').append(this.container);
            }
        },

        show(message, type = 'success', title = null, duration = 3000) {
            this.init();

            const icons = {
                success: 'fa-circle-check',
                error: 'fa-circle-xmark',
                warning: 'fa-triangle-exclamation'
            };

            const titles = {
                success: title || 'Success!',
                error: title || 'Error!',
                warning: title || 'Warning!'
            };

            const toast = $(`
                <div class="toast-notification toast-${type}">
                    <div class="toast-icon">
                        <i class="fa-solid ${icons[type]}"></i>
                    </div>
                    <div class="toast-content">
                        <p class="toast-title">${titles[type]}</p>
                        <p class="toast-message">${message}</p>
                    </div>
                    <button class="toast-close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div class="toast-progress" style="width: 100%"></div>
                </div>
            `);

            this.container.append(toast);
            setTimeout(() => toast.addClass('show'), 10);

            const progressBar = toast.find('.toast-progress');
            let width = 100;
            const interval = setInterval(() => {
                width -= (100 / duration) * 50;
                if (width <= 0) clearInterval(interval);
                progressBar.css('width', width + '%');
            }, 50);

            const dismissTimeout = setTimeout(() => this.dismiss(toast), duration);

            toast.find('.toast-close').on('click', () => {
                clearTimeout(dismissTimeout);
                clearInterval(interval);
                this.dismiss(toast);
            });
        },

        dismiss(toast) {
            toast.removeClass('show').addClass('hide');
            setTimeout(() => toast.remove(), 400);
        },

        success(message, title) { this.show(message, 'success', title); },
        error(message, title) { this.show(message, 'error', title); },
        warning(message, title) { this.show(message, 'warning', title); }
    };

    window.Toast = Toast;

    // ================================================
    // CART FUNCTIONS - FOOLPROOF VERSION
    // ================================================
    const Cart = {
        isProcessing: false,
        cartItems: {}, // {productId: quantity}
        processingProducts: new Set(), // Track which products are being processed

        add(productId, quantity = 1, weight = null) {
            if (this.isProcessing || this.processingProducts.has(productId)) {
                console.log('Cart operation already in progress for product:', productId);
                return;
            }

            const button = $(`.add-to-cart-btn[data-product-id="${productId}"]`);

            if (button.hasClass('btn-loading')) {
                return;
            }

            button.addClass('btn-loading').prop('disabled', true);
            this.isProcessing = true;
            this.processingProducts.add(productId);

            $.ajax({
                url: '/cart/add',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity,
                    weight: weight,
                    _token: csrfToken
                },
                success: (response) => {
                    if (response.success) {
                        Toast.success(response.message || 'Product added to cart');
                        this.updateUI(response.cart);

                        // Transform button to quantity controls
                        this.showQuantityControls(productId, quantity);

                        button.removeClass('btn-loading').addClass('added');
                        setTimeout(() => {
                            button.removeClass('added').prop('disabled', false);
                        }, 1000);
                    } else {
                        Toast.error(response.message || 'Failed to add to cart');
                        button.removeClass('btn-loading').prop('disabled', false);
                    }
                    this.isProcessing = false;
                    this.processingProducts.delete(productId);
                },
                error: (xhr) => {
                    const message = xhr.responseJSON?.message || 'Error adding to cart';
                    Toast.error(message);
                    button.removeClass('btn-loading').prop('disabled', false);
                    this.isProcessing = false;
                    this.processingProducts.delete(productId);
                }
            });
        },

        showQuantityControls(productId, quantity) {
            const button = $(`.add-to-cart-btn[data-product-id="${productId}"], .product-add-to-cart[data-product-id="${productId}"]`);

            // Check if already showing quantity controls
            const existingControls = $(`.cart-quantity-controls[data-product-id="${productId}"]`);
            if (existingControls.length > 0) {
                existingControls.find('.cart-qty-value').text(quantity);
                return;
            }

            const qtyControls = $(`
                <div class="cart-quantity-controls" data-product-id="${productId}">
                    <button class="cart-qty-btn cart-qty-minus" data-product-id="${productId}" type="button">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                    <span class="cart-qty-value">${quantity}</span>
                    <button class="cart-qty-btn cart-qty-plus" data-product-id="${productId}" type="button">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            `);

            button.replaceWith(qtyControls);
            this.cartItems[productId] = quantity;
        },

        updateQuantity(productId, action) {
            if (this.isProcessing || this.processingProducts.has(productId)) {
                console.log('Cart update already in progress for product:', productId);
                return;
            }

            this.isProcessing = true;
            this.processingProducts.add(productId);

            const qtyDisplay = $(`.cart-quantity-controls[data-product-id="${productId}"] .cart-qty-value`);
            const currentQty = parseInt(qtyDisplay.text());
            const newQty = action === 'plus' ? currentQty + 1 : currentQty - 1;

            // Don't allow negative quantities
            if (newQty < 0) {
                this.isProcessing = false;
                this.processingProducts.delete(productId);
                return;
            }

            // Optimistic update
            if (newQty >= 0) {
                qtyDisplay.text(newQty).addClass('updating');
                setTimeout(() => qtyDisplay.removeClass('updating'), 300);
            }

            $.ajax({
                url: '/cart/update',
                method: 'POST',
                data: {
                    product_id: productId,
                    action: action,
                    _token: csrfToken
                },
                success: (response) => {
                    if (response.success) {
                        this.updateUI(response.cart);

                        // If quantity is 0, revert to "Add to Cart" button
                        if (newQty === 0) {
                            this.showAddToCartButton(productId);
                            delete this.cartItems[productId];
                        } else {
                            this.cartItems[productId] = newQty;
                        }
                    } else {
                        // Revert on error
                        qtyDisplay.text(currentQty);
                        Toast.error('Failed to update quantity');
                    }
                    this.isProcessing = false;
                    this.processingProducts.delete(productId);
                },
                error: (xhr) => {
                    // Revert on error
                    qtyDisplay.text(currentQty);
                    Toast.error('Error updating cart');
                    this.isProcessing = false;
                    this.processingProducts.delete(productId);
                }
            });
        },

        showAddToCartButton(productId) {
            const qtyControls = $(`.cart-quantity-controls[data-product-id="${productId}"]`);
            const button = $(`
                <button class="product-add-to-cart add-to-cart-btn" data-product-id="${productId}">
                    <i class="fa-regular fa-cart-shopping"></i>
                    <span>Add to Cart</span>
                </button>
            `);
            qtyControls.replaceWith(button);
            delete this.cartItems[productId];
        },

        remove(productId) {
            if (this.isProcessing || this.processingProducts.has(productId)) {
                console.log('Cart remove already in progress for product:', productId);
                return;
            }

            this.isProcessing = true;
            this.processingProducts.add(productId);

            $.ajax({
                url: '/cart/remove',
                method: 'POST',
                data: { product_id: productId, _token: csrfToken },
                success: (response) => {
                    if (response.success) {
                        Toast.success(response.message || 'Product removed from cart');

                        // Update UI first
                        this.updateUI(response.cart);

                        // Revert ALL product cards with this ID to "Add to Cart"
                        this.showAddToCartButton(productId);

                        // Remove from tracking
                        delete this.cartItems[productId];
                    } else {
                        Toast.error(response.message || 'Failed to remove from cart');
                    }
                    this.isProcessing = false;
                    this.processingProducts.delete(productId);
                },
                error: (xhr) => {
                    const message = xhr.responseJSON?.message || 'Error removing from cart';
                    Toast.error(message);
                    this.isProcessing = false;
                    this.processingProducts.delete(productId);
                }
            });
        },

        updateUI(cartData) {
            if (!cartData) {
                this.loadCart();
                return;
            }

            const itemCount = cartData.items?.length || 0;
            const total = cartData.total || 0;
            const subtotal = cartData.subtotal || 0;

            const countBadges = $('#cartCount, #mobileCartCount, #headerCartCount, .cart-count-badge');
            countBadges.text(itemCount).addClass('badge-pulse');
            setTimeout(() => countBadges.removeClass('badge-pulse'), 400);

            $('#cartTotal, #headerCartTotal, .cart-total-amount').text(total.toFixed(2));
            $('#cartSubtotal, .cart-subtotal-amount').text(subtotal.toFixed(2));
            $('#cartItemCount, .cart-item-count').text(itemCount);

            if (itemCount === 0) {
                $('#emptyCartState').show();
                $('#cartItemsContainer, #cartFooter').hide();
            } else {
                $('#emptyCartState').hide();
                $('#cartItemsContainer, #cartFooter').show();
                this.renderCartItems(cartData.items);
            }

            // Update cartItems tracking
            const newCartItems = {};
            if (cartData.items) {
                cartData.items.forEach(item => {
                    newCartItems[item.id] = item.quantity;
                });
            }
            this.cartItems = newCartItems;

            // Sync ALL product cards
            this.syncAllProductCards();
        },

        // Sync ALL product cards on the page
        syncAllProductCards() {
            // First, reset all products not in cart
            $('.cart-quantity-controls').each((index, element) => {
                const productId = $(element).data('product-id');
                if (!this.cartItems[productId]) {
                    this.showAddToCartButton(productId);
                }
            });

            // Then, show quantity controls for products in cart
            Object.keys(this.cartItems).forEach(productId => {
                const qty = this.cartItems[productId];
                if (qty > 0) {
                    this.showQuantityControls(productId, qty);
                } else {
                    this.showAddToCartButton(productId);
                }
            });
        },

        renderCartItems(items) {
            const container = $('#cartItemsContainer');
            container.empty();

            items.forEach(item => {
                const itemHtml = `
                    <div class="unique-cart-item" data-product-id="${item.id}">
                        <img src="${item.image}" alt="${item.name}" class="unique-cart-item-image"
                             onerror="this.src='/frontend/assets/images/grocery/01.jpg'">
                        <div class="unique-cart-item-details">
                            <h5 class="unique-cart-item-name">${item.name}</h5>
                            <div class="unique-cart-item-meta">
                                <span class="unique-cart-item-quantity">Qty: ${item.quantity}</span>
                                <span class="unique-cart-item-price">₹${parseFloat(item.price).toFixed(2)}</span>
                            </div>
                        </div>
                        <button class="unique-cart-item-remove cart-remove-btn" data-product-id="${item.id}" type="button">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                `;
                container.append(itemHtml);
            });
        },

        loadCart() {
            $.ajax({
                url: '/cart/get',
                method: 'GET',
                success: (response) => {
                    if (response.success) {
                        this.updateUI(response.cart);
                    }
                },
                error: (xhr) => {
                    console.error('Error loading cart:', xhr.responseText);
                    $('#cartCount, #mobileCartCount, #headerCartCount').text('0');
                    $('#cartTotal, #headerCartTotal').text('0.00');
                }
            });
        }
    };

    window.Cart = Cart;
    window.updateCartUI = () => Cart.loadCart();

    // ================================================
    // WISHLIST FUNCTIONS
    // ================================================
    const Wishlist = {
        isProcessing: false,
        processingProducts: new Set(),

        toggle(productId, button) {
            if (this.isProcessing || this.processingProducts.has(productId)) {
                console.log('Wishlist operation already in progress for product:', productId);
                return;
            }

            const icon = button.find('i');
            const wasActive = button.hasClass('active');

            if (wasActive) {
                button.removeClass('active');
                icon.removeClass('fa-solid').addClass('fa-regular');
            } else {
                button.addClass('active');
                icon.removeClass('fa-regular').addClass('fa-solid');
            }

            this.isProcessing = true;
            this.processingProducts.add(productId);

            $.ajax({
                url: '/wishlist/toggle',
                method: 'POST',
                data: { product_id: productId, _token: csrfToken },
                success: (response) => {
                    if (response.success) {
                        Toast.success(response.message || 'Wishlist updated');
                        this.updateCount(response.count);
                    } else {
                        if (wasActive) {
                            button.addClass('active');
                            icon.removeClass('fa-regular').addClass('fa-solid');
                        } else {
                            button.removeClass('active');
                            icon.removeClass('fa-solid').addClass('fa-regular');
                        }
                        Toast.error(response.message || 'Failed to update wishlist');
                    }
                    this.isProcessing = false;
                    this.processingProducts.delete(productId);
                },
                error: () => {
                    if (wasActive) {
                        button.addClass('active');
                        icon.removeClass('fa-regular').addClass('fa-solid');
                    } else {
                        button.removeClass('active');
                        icon.removeClass('fa-solid').addClass('fa-regular');
                    }
                    Toast.error('Error updating wishlist');
                    this.isProcessing = false;
                    this.processingProducts.delete(productId);
                }
            });
        },

        updateCount(count) {
            const countBadges = $('#wishlistCount, #mobileWishlistCount, #headerWishlistCount, .wishlist-count-badge');
            countBadges.text(count).addClass('badge-pulse');
            setTimeout(() => countBadges.removeClass('badge-pulse'), 400);
        },

        loadCount() {
            $.ajax({
                url: '/wishlist/count',
                method: 'GET',
                success: (response) => {
                    if (response.success) {
                        this.updateCount(response.count);
                    }
                },
                error: (xhr) => {
                    console.error('Error loading wishlist count:', xhr.responseText);
                    $('#wishlistCount, #mobileWishlistCount, #headerWishlistCount').text('0');
                }
            });
        },

        loadItems() {
            $.ajax({
                url: '/wishlist/get',
                method: 'GET',
                success: (response) => {
                    if (response.success && response.items) {
                        response.items.forEach(productId => {
                            const buttons = $(`.wishlist-toggle-btn[data-product-id="${productId}"]`);
                            buttons.addClass('active');
                            buttons.find('i').removeClass('fa-regular').addClass('fa-solid');
                        });
                    }
                },
                error: (xhr) => {
                    console.error('Error loading wishlist items:', xhr.responseText);
                }
            });
        }
    };

    window.Wishlist = Wishlist;
    window.updateWishlistUI = () => {
        Wishlist.loadCount();
        Wishlist.loadItems();
    };
    window.initializeWishlistStates = () => Wishlist.loadItems();

    // ================================================
    // EVENT HANDLERS
    // ================================================
    $(document).ready(function() {
        Cart.loadCart();
        Wishlist.loadCount();
        Wishlist.loadItems();

        $(document).off('click', '.add-to-cart-btn, .product-add-to-cart');
        $(document).off('click', '.wishlist-toggle-btn, .shop-wishlist-btn');
        $(document).off('click', '.cart-remove-btn');
        $(document).off('click', '.cart-qty-plus');
        $(document).off('click', '.cart-qty-minus');

        $(document).on('click', '.add-to-cart-btn, .product-add-to-cart', function(e) {
            e.preventDefault();
            e.stopPropagation();

            if ($(this).hasClass('btn-loading') || $(this).hasClass('disabled')) return false;

            const productId = $(this).data('product-id') || $(this).data('id');
            if (productId) {
                Cart.add(productId);
            }
            return false;
        });

        $(document).on('click', '.cart-qty-plus', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const productId = $(this).data('product-id');
            if (productId && !Cart.processingProducts.has(productId)) {
                Cart.updateQuantity(productId, 'plus');
            }
            return false;
        });

        $(document).on('click', '.cart-qty-minus', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const productId = $(this).data('product-id');
            if (productId && !Cart.processingProducts.has(productId)) {
                Cart.updateQuantity(productId, 'minus');
            }
            return false;
        });

        $(document).on('click', '.wishlist-toggle-btn, .shop-wishlist-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const productId = $(this).data('product-id') || $(this).data('id');
            if (productId && !Wishlist.processingProducts.has(productId)) {
                Wishlist.toggle(productId, $(this));
            }
            return false;
        });

        $(document).on('click', '.cart-remove-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const productId = $(this).data('product-id');
            if (productId && !Cart.processingProducts.has(productId)) {
                Cart.remove(productId);
            }
            return false;
        });
    });

})(jQuery);
