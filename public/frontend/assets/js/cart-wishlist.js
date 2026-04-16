/**
 * ================================================
 * CART & WISHLIST - OPTIMIZED v2
 * Fixes: duplicate toast / double AJAX on product detail page
 * Optimizes: debounce qty, request deduplication, batch DOM sync
 * ================================================
 */

(function($) {
    'use strict';

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // ── Fetch guards ──
    let _cartLoading = false;
    let _wishlistCountLoading = false;
    let _wishlistItemsLoading = false;

    // ── Global flag: prevent any second handler from firing ──
    // Set to true for the duration of a cart AJAX call
    let _addToCartLocked = false;

    // ================================================
    // TOAST
    // ================================================
    const Toast = {
        container: null,
        _dedupeMap: {},   // ← NEW: prevent same message twice within 800ms

        init() {
            if (!this.container) {
                this.container = $('<div class="toast-container"></div>');
                $('body').append(this.container);
            }
        },

        show(message, type = 'success', title = null, duration = 3000) {
            this.init();

            // ── Deduplicate identical toasts ──
            const key = type + '|' + message;
            if (this._dedupeMap[key]) return;
            this._dedupeMap[key] = true;
            setTimeout(() => delete this._dedupeMap[key], 800);

            const icons  = { success: 'fa-circle-check', error: 'fa-circle-xmark', warning: 'fa-triangle-exclamation' };
            const titles = { success: title || 'Success!', error: title || 'Error!', warning: title || 'Warning!' };

            const toast = $(`
                <div class="toast-notification toast-${type}">
                    <div class="toast-icon"><i class="fa-solid ${icons[type]}"></i></div>
                    <div class="toast-content">
                        <p class="toast-title">${titles[type]}</p>
                        <p class="toast-message">${message}</p>
                    </div>
                    <button class="toast-close"><i class="fa-solid fa-xmark"></i></button>
                    <div class="toast-progress" style="width:100%"></div>
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

        success(msg, title) { this.show(msg, 'success', title); },
        error(msg, title)   { this.show(msg, 'error', title); },
        warning(msg, title) { this.show(msg, 'warning', title); }
    };

    window.Toast = Toast;

    // ================================================
    // CART
    // ================================================
    const Cart = {
        isProcessing: false,
        cartItems: {},
        processingProducts: new Set(),
        _qtyDebounceTimers: {},   // ← NEW: debounce rapid +/- taps

        add(productId, quantity = 1, weight = null) {
            // ── Primary duplicate-call guard ──
            if (_addToCartLocked) return;
            if (this.processingProducts.has(productId)) return;

            const button = $(`.add-to-cart-btn[data-product-id="${productId}"]`);
            if (button.hasClass('btn-loading')) return;

            _addToCartLocked = true;
            button.addClass('btn-loading').prop('disabled', true);
            this.processingProducts.add(productId);

            $.ajax({
                url: '/cart/add',
                method: 'POST',
                data: { product_id: productId, quantity, weight, _token: csrfToken },
                success: (response) => {
                    if (response.success) {
                        Toast.success(response.message || 'Product added to cart');
                        this.updateUI(response.cart);
                        this.showQuantityControls(productId, quantity);
                        button.removeClass('btn-loading').addClass('added');
                        setTimeout(() => button.removeClass('added').prop('disabled', false), 1000);
                    } else {
                        Toast.error(response.message || 'Failed to add to cart');
                        button.removeClass('btn-loading').prop('disabled', false);
                    }
                },
                error: (xhr) => {
                    Toast.error(xhr.responseJSON?.message || 'Error adding to cart');
                    button.removeClass('btn-loading').prop('disabled', false);
                },
                complete: () => {
                    // Always release locks in complete so they release even on network failure
                    _addToCartLocked = false;
                    this.processingProducts.delete(productId);
                }
            });
        },

        showQuantityControls(productId, quantity) {
            const button = $(`.add-to-cart-btn[data-product-id="${productId}"], .product-add-to-cart[data-product-id="${productId}"]`);
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
            if (this.processingProducts.has(productId)) return;

            const qtyDisplay = $(`.cart-quantity-controls[data-product-id="${productId}"] .cart-qty-value`);
            const currentQty = parseInt(qtyDisplay.text()) || 0;
            const newQty = action === 'plus' ? currentQty + 1 : currentQty - 1;

            if (newQty < 0) return;

            // Optimistic UI immediately
            qtyDisplay.text(newQty).addClass('updating');
            setTimeout(() => qtyDisplay.removeClass('updating'), 300);

            // ── Debounce: wait 350ms before sending AJAX ──
            clearTimeout(this._qtyDebounceTimers[productId]);
            this._qtyDebounceTimers[productId] = setTimeout(() => {
                this.processingProducts.add(productId);

                $.ajax({
                    url: '/cart/update',
                    method: 'POST',
                    data: { product_id: productId, action, _token: csrfToken },
                    success: (response) => {
                        if (response.success) {
                            this.updateUI(response.cart);
                            if (newQty === 0) {
                                this.showAddToCartButton(productId);
                                delete this.cartItems[productId];
                            } else {
                                this.cartItems[productId] = newQty;
                            }
                        } else {
                            qtyDisplay.text(currentQty);
                            Toast.error('Failed to update quantity');
                        }
                    },
                    error: () => {
                        qtyDisplay.text(currentQty);
                        Toast.error('Error updating cart');
                    },
                    complete: () => {
                        this.processingProducts.delete(productId);
                    }
                });
            }, 350);
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
            if (this.processingProducts.has(productId)) return;
            this.processingProducts.add(productId);

            $.ajax({
                url: '/cart/remove',
                method: 'POST',
                data: { product_id: productId, _token: csrfToken },
                success: (response) => {
                    if (response.success) {
                        Toast.success(response.message || 'Product removed from cart');
                        this.updateUI(response.cart);
                        this.showAddToCartButton(productId);
                        delete this.cartItems[productId];
                    } else {
                        Toast.error(response.message || 'Failed to remove from cart');
                    }
                },
                error: (xhr) => {
                    Toast.error(xhr.responseJSON?.message || 'Error removing from cart');
                },
                complete: () => {
                    this.processingProducts.delete(productId);
                }
            });
        },

        updateUI(cartData) {
            if (!cartData) { this.loadCart(); return; }

            const itemCount = cartData.items?.length || 0;
            const total     = cartData.total    || 0;
            const subtotal  = cartData.subtotal || 0;

            // ── Batch DOM writes ──
            requestAnimationFrame(() => {
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
            });

            // Build new cartItems map
            const newCartItems = {};
            if (cartData.items) {
                cartData.items.forEach(item => { newCartItems[item.id] = item.quantity; });
            }
            this.cartItems = newCartItems;
            this.syncAllProductCards();
        },

        syncAllProductCards() {
            // Remove controls for items no longer in cart
            $('.cart-quantity-controls').each((i, el) => {
                const pid = $(el).data('product-id');
                if (!this.cartItems[pid]) this.showAddToCartButton(pid);
            });

            // Show controls for items in cart
            Object.entries(this.cartItems).forEach(([pid, qty]) => {
                if (qty > 0) this.showQuantityControls(pid, qty);
                else         this.showAddToCartButton(pid);
            });
        },

        renderCartItems(items) {
            const container = $('#cartItemsContainer');
            if (!container.length) return;

            // Build all HTML at once, single DOM write
            const html = items.map(item => {
                const isWeightBased = item.weight && parseFloat(item.weight) > 0;
                const weightFormatted = isWeightBased
                    ? (parseFloat(item.weight) % 1 === 0
                        ? parseInt(item.weight)
                        : parseFloat(item.weight))
                    : null;

                const quantityDisplay = isWeightBased
                    ? `<span class="unique-cart-item-weight"><i class="fa-regular fa-weight-scale"></i> ${weightFormatted}kg</span>`
                    : `<span class="unique-cart-item-quantity">Qty: ${item.quantity}</span>`;

                return `
                    <div class="unique-cart-item" data-product-id="${item.id}">
                        <img src="${item.image}" alt="${item.name}" class="unique-cart-item-image"
                            onerror="this.src='/frontend/assets/images/products/product-placeholder.svg'">
                        <div class="unique-cart-item-details">
                            <h5 class="unique-cart-item-name">${item.name}</h5>
                            <div class="unique-cart-item-meta">
                                ${quantityDisplay}
                                <span class="unique-cart-item-price">£${parseFloat(item.price).toFixed(2)}</span>
                            </div>
                        </div>
                        <button class="unique-cart-item-remove cart-remove-btn" data-product-id="${item.id}" type="button">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>`;
            }).join('');

            container.html(html);  // single DOM write instead of .append() per item
        },

        loadCart() {
            if (_cartLoading) return;
            _cartLoading = true;

            $.ajax({
                url: '/cart/get',
                method: 'GET',
                success: (response) => {
                    if (response.success) this.updateUI(response.cart);
                },
                error: (xhr) => {
                    console.error('Cart load error:', xhr.responseText);
                    $('#cartCount, #mobileCartCount').text('0');
                    $('#cartTotal').text('0.00');
                },
                complete: () => { _cartLoading = false; }
            });
        }
    };

    window.Cart = Cart;
    window.updateCartUI = () => { _cartLoading = false; Cart.loadCart(); };

    // ================================================
    // WISHLIST
    // ================================================
    const Wishlist = {
        processingProducts: new Set(),

        toggle(productId, button) {
            if (this.processingProducts.has(productId)) return;

            const icon = button.find('i');
            const wasActive = button.hasClass('active');

            // Optimistic UI
            button.toggleClass('active', !wasActive);
            icon.toggleClass('fa-solid', !wasActive).toggleClass('fa-regular', wasActive);

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
                        // Revert
                        button.toggleClass('active', wasActive);
                        icon.toggleClass('fa-solid', wasActive).toggleClass('fa-regular', !wasActive);
                        Toast.error(response.message || 'Failed to update wishlist');
                    }
                },
                error: () => {
                    button.toggleClass('active', wasActive);
                    icon.toggleClass('fa-solid', wasActive).toggleClass('fa-regular', !wasActive);
                    Toast.error('Error updating wishlist');
                },
                complete: () => { this.processingProducts.delete(productId); }
            });
        },

        updateCount(count) {
            const badges = $('#wishlistCount, #mobileWishlistCount, #headerWishlistCount, .wishlist-count-badge');
            badges.text(count).addClass('badge-pulse');
            setTimeout(() => badges.removeClass('badge-pulse'), 400);
        },

        loadCount() {
            if (_wishlistCountLoading) return;
            _wishlistCountLoading = true;

            $.ajax({
                url: '/wishlist/count',
                method: 'GET',
                success: (r) => { if (r.success) this.updateCount(r.count); },
                error: () => { $('#wishlistCount, #mobileWishlistCount').text('0'); },
                complete: () => { _wishlistCountLoading = false; }
            });
        },

        loadItems() {
            if (_wishlistItemsLoading) return;
            _wishlistItemsLoading = true;

            $.ajax({
                url: '/wishlist/get',
                method: 'GET',
                success: (r) => {
                    if (r.success && r.items) {
                        // Build a Set for O(1) lookup
                        const ids = new Set(r.items.map(p => String(p.id)));
                        $(`.wishlist-toggle-btn[data-product-id]`).each((i, el) => {
                            const pid = String($(el).data('product-id'));
                            if (ids.has(pid)) {
                                $(el).addClass('active').find('i').removeClass('fa-regular').addClass('fa-solid');
                            }
                        });
                    }
                },
                error: () => {},
                complete: () => { _wishlistItemsLoading = false; }
            });
        }
    };

    window.Wishlist = Wishlist;
    window.updateWishlistCount = (count) => Wishlist.updateCount(count);
    window.updateWishlistUI = () => {
        _wishlistCountLoading = false;
        _wishlistItemsLoading = false;
        Wishlist.loadCount();
        Wishlist.loadItems();
    };
    window.initializeWishlistStates = () => {
        _wishlistItemsLoading = false;
        Wishlist.loadItems();
    };

    // ================================================
    // INIT
    // ================================================
    $(document).ready(function() {

        if (typeof serverCart !== 'undefined' && serverCart !== null) {
            Cart.updateUI(serverCart);
        } else {
            Cart.loadCart();
        }

        Wishlist.loadCount();
        Wishlist.loadItems();

        // Clean up stale handlers
        $(document).off('click.cw', '.add-to-cart-btn, .product-add-to-cart');
        $(document).off('click.cw', '.wishlist-toggle-btn, .shop-wishlist-btn');
        $(document).off('click.cw', '.cart-remove-btn');
        $(document).off('click.cw', '.cart-qty-plus');
        $(document).off('click.cw', '.cart-qty-minus');

        // ── Add to Cart ──
        // Uses stopImmediatePropagation to prevent the inline blade handler
        // from also firing on the product detail page
        $(document).on('click.cw', '.add-to-cart-btn, .product-add-to-cart', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();   // ← KEY FIX: kills any other handler on same element
            if ($(this).hasClass('btn-loading') || $(this).prop('disabled')) return;
            const productId = $(this).data('product-id') || $(this).data('id');
            if (productId) Cart.add(productId);
        });

        // ── Quantity + ──
        $(document).on('click.cw', '.cart-qty-plus', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const pid = $(this).data('product-id');
            if (pid) Cart.updateQuantity(pid, 'plus');
        });

        // ── Quantity - ──
        $(document).on('click.cw', '.cart-qty-minus', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const pid = $(this).data('product-id');
            if (pid) Cart.updateQuantity(pid, 'minus');
        });

        // ── Wishlist ──
        $(document).on('click.cw', '.wishlist-toggle-btn, .shop-wishlist-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if ($(this).hasClass('wishlist-page-btn')) return;
            const pid = $(this).data('product-id') || $(this).data('id');
            if (pid && !Wishlist.processingProducts.has(pid)) Wishlist.toggle(pid, $(this));
        });

        // ── Cart Remove ──
        $(document).on('click.cw', '.cart-remove-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const pid = $(this).data('product-id');
            if (pid) Cart.remove(pid);
        });

    });

})(jQuery);
