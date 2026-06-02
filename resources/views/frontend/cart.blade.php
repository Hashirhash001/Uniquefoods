@extends('frontend.layouts.app')

@section('title', 'Shopping Cart')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/assets/css/cart.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/global-loader.css') }}">
@endpush

@section('content')

<div class="modern-cart-section rts-section-gap">
    <div class="container">

        {{--
            IDs are prefixed with "page" to avoid conflicts with the
            header mini-cart which uses the same bare IDs (#emptyCartState etc.)
        --}}

        {{-- Empty state --}}
        <div class="row" id="pageEmptyCartState" style="display:none;">
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

        {{-- Cart content --}}
        <div id="pageCartContentArea" style="display:none;">
            <div class="row g-4">

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
                        <div class="cart-items-list" id="pageCartItemsList">
                            {{-- Items rendered by JS --}}
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
                                <span class="value">£<span id="pageSummarySubtotal">0.00</span></span>
                            </div>
                        </div>

                        <div class="summary-actions">
                            <a href="{{ route('checkout.index') }}">
                                <button class="btn-checkout-primary">
                                    <i class="fa-regular fa-credit-card"></i>
                                    Proceed to Checkout
                                </button>
                            </a>
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
                </div>

            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('frontend/assets/js/global-loader.js') }}"></script>
<script>
@php
    $safeCart = $cartData ?? ['items' => [], 'subtotal' => 0, 'tax' => 0, 'shipping' => 0, 'total' => 0];
@endphp
var serverCart = @json($safeCart);

$(document).ready(function () {

    // Render immediately — no AJAX needed, data came from the server
    pageDisplayCart(serverCart);

    // Also keep the header mini-cart in sync
    if (typeof Cart !== 'undefined') {
        Cart.updateUI(serverCart);
    }

    // =========================================================
    //  DISPLAY CART  (page-scoped — touches only #page* IDs)
    // =========================================================
    function pageDisplayCart(cart) {
        var container = $('#pageCartItemsList');
        container.empty();

        var hasItems = cart && Array.isArray(cart.items) && cart.items.length > 0;

        if (!hasItems) {
            $('#pageCartContentArea').hide();
            $('#pageEmptyCartState').show();
            pageUpdateSummary({ subtotal: 0, total: 0 });
            return;
        }

        $('#pageEmptyCartState').hide();
        $('#pageCartContentArea').show();

        cart.items.forEach(function (item) {
            container.append(buildCartItemHtml(item));
        });

        pageUpdateSummary(cart);
    }

    // =========================================================
    //  BUILD ITEM HTML  (all comparisons extracted first)
    // =========================================================
    function buildCartItemHtml(item) {

        var isWeightBased = !!(item.weight && parseFloat(item.weight) > 0);
        var price         = parseFloat(item.price)    || 0;
        var weight        = parseFloat(item.weight)   || 0;
        var qty           = parseInt(item.quantity, 10) || 1;
        var stock         = parseInt(item.stock, 10)    || 0;

        // Resolved values — no comparisons inside strings
        var subtotal, priceLabel, weightDisplay;

        if (isWeightBased) {
            subtotal      = price.toFixed(2);
            priceLabel    = '&pound;' + (weight > 0 ? (price / weight).toFixed(2) : '0.00') + '<small>/kg</small>';
            weightDisplay = weight % 1 === 0 ? parseInt(weight, 10).toString() : weight.toString();
        } else {
            subtotal   = (price * qty).toFixed(2);
            priceLabel = '&pound;' + price.toFixed(2);
        }

        var outOfStockBadge = stock <= 0
            ? '<span class="stock-badge out-stock">Out of Stock</span>'
            : '';

        var weightTagHtml = isWeightBased
            ? '<span class="weight-tag"><i class="fa-regular fa-weight-scale"></i> Weight-based</span>'
            : '';

        var stockClass    = stock > 0 ? 'in-stock'  : 'out-stock';
        var stockText     = stock > 0 ? 'In Stock'  : 'Out of Stock';
        var priceColLabel = isWeightBased ? 'Per kg' : 'Price';
        var disableDec    = qty <= 1          ? 'disabled' : '';
        var disableInc    = stock <= qty      ? 'disabled' : '';

        var quantityBlock;
        if (isWeightBased) {
            quantityBlock
                = '<div class="item-quantity" data-subtotal="&pound;' + subtotal + '">'
                +     '<div class="weight-display-badge">'
                +         '<i class="fa-regular fa-weight-scale"></i>'
                +         '<span>' + weightDisplay + 'kg</span>'
                +     '</div>'
                +     '<a href="/product/' + item.slug + '" class="weight-change-link">'
                +         '<i class="fa-regular fa-pen"></i> Change'
                +     '</a>'
                + '</div>';
        } else {
            quantityBlock
                = '<div class="item-quantity product-cart-ui"'
                +     ' data-product-id="' + item.id + '"'
                +     ' data-stock="' + stock + '"'
                +     ' data-state="saved"'
                +     ' data-saved-qty="' + qty + '">'

                +     '<button type="button" class="product-add-to-cart add-to-cart-btn d-none"'
                +         ' data-product-id="' + item.id + '"'
                +         ' data-stock="' + stock + '">'
                +         '<i class="fa-regular fa-cart-shopping"></i>'
                +         '<span>Add to Cart</span>'
                +     '</button>'

                +     '<div class="product-inline-editor d-none">'
                +         '<button type="button" class="cart-inline-btn cart-inline-cancel"'
                +             ' data-product-id="' + item.id + '">'
                +             '<i class="fa-regular fa-xmark"></i>'
                +         '</button>'
                +         '<input type="number" class="cart-inline-input"'
                +             ' data-product-id="' + item.id + '"'
                +             ' value="' + qty + '"'
                +             ' min="1" max="' + stock + '" step="1" inputmode="numeric">'
                +         '<button type="button" class="cart-inline-btn cart-inline-save"'
                +             ' data-product-id="' + item.id + '">'
                +             '<i class="fa-regular fa-check"></i>'
                +         '</button>'
                +     '</div>'

                +     '<div class="product-cart-summary">'
                +         '<div class="cart-summary-meta">'
                +             '<span class="cart-summary-label">In cart</span>'
                +             '<span class="cart-summary-value">'
                +                 '<strong class="cart-summary-qty">' + qty + '</strong>'
                +                 '<span class="cart-summary-unit"> pcs</span>'
                +             '</span>'
                +         '</div>'
                +         '<button type="button" class="cart-summary-edit"'
                +             ' data-product-id="' + item.id + '">'
                +             '<i class="fa-regular fa-pen-to-square"></i>'
                +             '<span>Edit</span>'
                +         '</button>'
                +     '</div>'

                +     '<div class="cart-inline-error d-none"></div>'
                + '</div>';
        }

        return '<div class="modern-cart-item" data-product-id="' + item.id + '">'
            +     '<div class="item-image">'
            +         '<img src="' + item.image + '" alt="' + item.name + '" onerror="this.src=\'/frontend/assets/images/products/product-placeholder.svg\'">'
            +         outOfStockBadge
            +     '</div>'
            +     '<div class="item-details">'
            +         '<a href="/product/' + item.slug + '" class="item-name">' + item.name + '</a>'
            +         '<div class="item-meta">'
            +             weightTagHtml
            +             '<span class="stock-indicator ' + stockClass + '">'
            +                 '<i class="fa-solid fa-circle"></i> ' + stockText
            +             '</span>'
            +         '</div>'
            +         '<div class="item-price-mobile" data-subtotal="£' + subtotal + '">' + priceLabel + '</div>'
            +     '</div>'
            +     quantityBlock
            +     '<div class="item-price">'
            +         '<div class="price-label">' + priceColLabel + '</div>'
            +         '<div class="price-value">'  + priceLabel   + '</div>'
            +     '</div>'
            +     '<div class="item-subtotal">'
            +         '<div class="subtotal-label">Subtotal</div>'
            +         '<div class="subtotal-value">&pound;' + subtotal + '</div>'
            +     '</div>'
            +     '<div class="item-remove">'
            +         '<button class="btn-remove" data-product-id="' + item.id + '" title="Remove item">'
            +             '<i class="fa-regular fa-trash-can"></i>'
            +         '</button>'
            +     '</div>'
            + '</div>';
    }

    // =========================================================
    //  SUMMARY
    // =========================================================
    function pageUpdateSummary(cart) {
        pageAnimateValue('pageSummarySubtotal', parseFloat(cart.subtotal) || 0);
        pageAnimateValue('pageSummaryTotal',    parseFloat(cart.total)    || 0);
    }

    function pageAnimateValue(elementId, endValue) {
        var element = document.getElementById(elementId);
        if (!element) return;
        var startValue = parseFloat(element.textContent) || 0;
        var startTime  = performance.now();
        function step(now) {
            var p = Math.min((now - startTime) / 300, 1);
            element.textContent = (startValue + (endValue - startValue) * p).toFixed(2);
            if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    // Update both the page summary AND the header badges
    function syncAll(cart) {
        pageDisplayCart(cart);
        if (typeof Cart !== 'undefined') Cart.updateUI(cart);
    }

    // =========================================================
    //  INLINE EDITOR — helper functions
    // =========================================================
    function getProductCartUI(productId) {
        return $('.product-cart-ui[data-product-id="' + productId + '"]');
    }

    function setCartUIState(productId, state, qty) {
        var $wrap    = getProductCartUI(productId);
        if (!$wrap.length) return;

        var $addBtn  = $wrap.find('.add-to-cart-btn');
        var $editor  = $wrap.find('.product-inline-editor');
        var $summary = $wrap.find('.product-cart-summary');
        var $input   = $wrap.find('.cart-inline-input');
        var $qtyText = $wrap.find('.cart-summary-qty');
        var $error   = $wrap.find('.cart-inline-error');

        $wrap.attr('data-state', state);
        $error.addClass('d-none').text('');

        if (qty !== undefined && qty !== null) {
            qty = parseInt(qty, 10) || 1;
            $wrap.attr('data-saved-qty', qty);
            $input.val(qty);
            $qtyText.text(qty);
        }

        $addBtn.addClass('d-none');
        $editor.addClass('d-none');
        $summary.addClass('d-none');

        if (state === 'default') {
            $addBtn.removeClass('d-none');
        } else if (state === 'editing') {
            $editor.removeClass('d-none');
            setTimeout(function () { $input.trigger('focus').trigger('select'); }, 20);
        } else if (state === 'saved') {
            $summary.removeClass('d-none');
        }
    }

    function validateCartQty(productId) {
        var $wrap  = getProductCartUI(productId);
        var $input = $wrap.find('.cart-inline-input');
        var stock  = parseInt($wrap.data('stock'), 10) || 0;
        var raw    = $.trim($input.val());
        var qty    = parseInt(raw, 10);

        if (raw === '' || isNaN(qty))  return { valid: false, message: 'Please enter quantity' };
        if (qty < 1)                   return { valid: false, message: 'Minimum quantity is 1' };
        if (qty > stock)               return { valid: false, message: 'Only ' + stock + ' in stock' };
        return { valid: true, qty: qty };
    }

    function showCartInlineError(productId, message) {
        getProductCartUI(productId).find('.cart-inline-error').text(message).removeClass('d-none');
    }

    // =========================================================
    //  INLINE EDITOR — open / cancel
    // =========================================================
    $(document).on('click', '.cart-summary-edit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var productId = $(this).data('product-id');
        var savedQty  = parseInt(getProductCartUI(productId).attr('data-saved-qty'), 10) || 1;
        setCartUIState(productId, 'editing', savedQty);
    });

    $(document).on('click', '.cart-inline-cancel', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var productId = $(this).data('product-id');
        var savedQty  = parseInt(getProductCartUI(productId).attr('data-saved-qty'), 10) || 0;
        setCartUIState(productId, savedQty > 0 ? 'saved' : 'default', savedQty || undefined);
    });

    $(document).on('input', '.cart-inline-input', function () {
        getProductCartUI($(this).data('product-id'))
            .find('.cart-inline-error').addClass('d-none').text('');
    });

    $(document).on('keydown', '.cart-inline-input', function (e) {
        var productId = $(this).data('product-id');
        if (e.key === 'Enter')  { e.preventDefault(); triggerCartSave(productId); }
        if (e.key === 'Escape') { e.preventDefault(); $(document).find('.cart-inline-cancel[data-product-id="' + productId + '"]').trigger('click'); }
    });

    // =========================================================
    //  INLINE EDITOR — save (calls /cart/update with new qty)
    // =========================================================
    $(document).on('click', '.cart-inline-save', function (e) {
        e.preventDefault();
        e.stopPropagation();
        triggerCartSave($(this).data('product-id'));
    });

    function triggerCartSave(productId) {
        var validation = validateCartQty(productId);
        if (!validation.valid) {
            showCartInlineError(productId, validation.message);
            return;
        }

        var qty     = validation.qty;
        var $wrap   = getProductCartUI(productId);
        var savedQty = parseInt($wrap.attr('data-saved-qty'), 10) || 0;

        // If qty hasn't changed, just close the editor
        if (qty === savedQty) {
            setCartUIState(productId, 'saved', qty);
            return;
        }

        var $saveBtn = $wrap.find('.cart-inline-save');
        $saveBtn.html('<i class="fa-regular fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            url:  '{{ route("cart.update") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', product_id: productId, quantity: qty },
            success: function (r) {
                $saveBtn.html('<i class="fa-regular fa-check"></i>').prop('disabled', false);
                if (r.success) {
                    setCartUIState(productId, 'saved', qty);
                    syncAll(r.cart);
                    if (typeof Toast !== 'undefined') Toast.success('Quantity updated');
                } else {
                    showCartInlineError(productId, r.message || 'Update failed');
                }
            },
            error: function () {
                $saveBtn.html('<i class="fa-regular fa-check"></i>').prop('disabled', false);
                showCartInlineError(productId, 'Failed to update. Try again.');
            }
        });
    }

    // =========================================================
    //  REMOVE ITEM
    // =========================================================
    $(document).on('click', '.btn-remove', function (e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        var cartItem  = $(this).closest('.modern-cart-item');
        cartItem.css({ opacity: '0.5', 'pointer-events': 'none' });

        $.ajax({
            url:  '{{ route("cart.remove") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', product_id: productId },
            success: function (r) {
                if (r.success) {
                    if (typeof Toast !== 'undefined' && window.innerWidth >= 768) Toast.success(r.message);
                    var remaining = (r.cart && Array.isArray(r.cart.items)) ? r.cart.items.length : 0;
                    if (remaining === 0) {
                        cartItem.remove();
                        syncAll(r.cart);
                    } else {
                        cartItem.slideUp(200, function () {
                            $(this).remove();
                            syncAll(r.cart);
                        });
                    }
                } else {
                    cartItem.css({ opacity: '1', 'pointer-events': 'auto' });
                    if (typeof Toast !== 'undefined') Toast.error(r.message);
                }
            },
            error: function () {
                cartItem.css({ opacity: '1', 'pointer-events': 'auto' });
                if (typeof Toast !== 'undefined') Toast.error('Failed to remove item.');
            }
        });
    });

    // =========================================================
    //  CLEAR CART
    // =========================================================
    $('#clearCartBtn').on('click', function (e) {
        e.preventDefault();
        $('#clearCartConfirm').remove();

        var confirmBox = $(
            '<div id="clearCartConfirm" class="cart-clear-confirm">'
            + '<p><i class="fa-solid fa-triangle-exclamation"></i> Clear your entire cart?</p>'
            + '<div class="cart-confirm-actions">'
            +     '<button type="button" id="confirmClearCart">Yes, Clear All</button>'
            +     '<button type="button" id="cancelClearCart">Cancel</button>'
            + '</div>'
            + '</div>'
        );

        $('.items-header').after(confirmBox);
        setTimeout(function () { confirmBox.addClass('show'); }, 10);

        $('#cancelClearCart').on('click', function () {
            confirmBox.removeClass('show');
            setTimeout(function () { confirmBox.remove(); }, 250);
        });

        $('#confirmClearCart').on('click', function () {
            confirmBox.remove();
            performClearCart();
        });
    });

    function performClearCart() {
        if (typeof showLoader === 'function') showLoader('Clearing cart...');

        $.ajax({
            url:  '{{ route("cart.clear") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (r) {
                if (typeof hideLoader === 'function') hideLoader();
                if (r.success) {
                    syncAll(r.cart);
                    if (typeof Toast !== 'undefined' && window.innerWidth >= 768) Toast.success(r.message);
                } else {
                    if (typeof Toast !== 'undefined') Toast.error(r.message);
                }
            },
            error: function () {
                if (typeof hideLoader === 'function') hideLoader();
                if (typeof Toast !== 'undefined') Toast.error('Failed to clear cart.');
            }
        });
    }

}); // end document.ready
</script>
@endpush
