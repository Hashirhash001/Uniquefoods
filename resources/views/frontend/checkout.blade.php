@extends('frontend.layouts.app')

@section('title', 'Checkout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/checkout.css') }}">
    <style>
        /* ── Saved Address Cards ── */
        .saved-address-list {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .saved-address-card {
            flex: 1 1 calc(50% - 6px);
            min-width: 220px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            background: #fff;
        }

        .saved-address-card:hover {
            border-color: #0f508d;
            background: #f0f7ff;
        }

        .saved-address-card.selected {
            border-color: #0f508d;
            background: #f0f7ff;
            box-shadow: 0 0 0 3px rgba(15, 80, 141, 0.1);
        }

        .saved-addr-top {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .saved-addr-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #0f508d;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .saved-addr-default-badge {
            font-size: 11px;
            font-weight: 600;
            color: #10b981;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            padding: 2px 8px;
            border-radius: 4px;
        }

        .saved-addr-delete {
            margin-left: auto;
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: color 0.2s;
            width: auto !important;
            font-size: 13px;
        }

        .saved-addr-delete:hover { color: #ef4444; }

        .saved-addr-name {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .saved-addr-details {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
        }

        .saved-addr-phone {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 6px;
        }

        /* Selected tick */
        .saved-address-card.selected::after {
            content: '\f058';
            font-family: 'Font Awesome 6 Pro', 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 10px;
            right: 12px;
            color: #0f508d;
            font-size: 16px;
        }

        /* "Use a different address" card */
        .new-address-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-style: dashed;
            min-height: 90px;
        }

        .new-address-card::after { display: none; }

        .new-addr-icon {
            width: 32px;
            height: 32px;
            background: #f3f4f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 14px;
        }

        .new-addr-text {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
        }

        /* Save address toggle */
        .save-address-label {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            gap: 8px;
        }

        .save-address-label input[type="checkbox"] {
            width: 16px !important;
            height: 16px !important;
            accent-color: #0f508d;
            cursor: pointer;
        }

        @media (max-width: 576px) {
            .saved-address-card {
                flex: 1 1 100%;
            }
        }
    </style>
@endpush

@section('content')
<div class="unique-checkout-wrapper mt-5">
    <div class="container">
        <div class="unique-checkout-container">

            <!-- ══════════════════════════════════════
                 LEFT: CHECKOUT FORMS
            ══════════════════════════════════════ -->
            <div class="unique-checkout-forms">

                <!-- ── SAVED ADDRESSES (auth users only) ── -->
                @auth
                @if(isset($savedAddresses) && $savedAddresses->isNotEmpty())
                <div class="unique-checkout-form-section" id="savedAddressSection">
                    <h3><i class="fa-solid fa-address-book"></i> Saved Addresses</h3>

                    <div class="saved-address-list" id="savedAddressList">
                        @foreach($savedAddresses as $addr)
                        <div class="saved-address-card {{ $loop->first ? 'selected' : '' }}"
                             data-address="{{ json_encode($addr) }}"
                             onclick="selectSavedAddress(this)">
                            <div class="saved-addr-top">
                                @if($addr->label)
                                    <span class="saved-addr-label">{{ $addr->label }}</span>
                                @endif
                                @if($addr->is_default)
                                    <span class="saved-addr-default-badge">Default</span>
                                @endif
                                <button type="button"
                                        class="saved-addr-delete"
                                        onclick="deleteSavedAddress(event, {{ $addr->id }}, this)"
                                        title="Remove address">
                                    <i class="fa-regular fa-trash"></i>
                                </button>
                            </div>
                            <div class="saved-addr-name">{{ $addr->recipient_name }}</div>
                            <div class="saved-addr-details">
                                {{ $addr->address_line1 }}
                                @if($addr->address_line2), {{ $addr->address_line2 }}@endif
                                @if($addr->restaurant_store)<br>{{ $addr->restaurant_store }}@endif
                                <br>{{ $addr->city }}, {{ $addr->postcode }}
                            </div>
                            <div class="saved-addr-phone">📞 {{ $addr->phone }}</div>
                        </div>
                        @endforeach

                        {{-- Use a different address --}}
                        <div class="saved-address-card new-address-card" onclick="selectNewAddress(this)">
                            <div class="new-addr-icon"><i class="fa-regular fa-plus"></i></div>
                            <div class="new-addr-text">Use a different address</div>
                        </div>
                    </div>
                </div>
                @endif
                @endauth

                {{-- ── CUSTOMER INFORMATION ── --}}
                <div class="unique-checkout-form-section"
                    id="customerInfoSection"
                    @auth @if(isset($savedAddresses) && $savedAddresses->isNotEmpty()) style="display:none;" @endif @endauth>
                    <h3><i class="fa-solid fa-user"></i> Customer Information</h3>

                    <div class="unique-form-group">
                        <label for="customer_name">
                            <i class="fa-solid fa-asterisk"></i> Full Name
                        </label>
                        <input type="text" id="customer_name" class="unique-form-control"
                            value="{{ Auth::user()->name ?? '' }}"
                            placeholder="Enter your full name" required>
                        <div class="error-message" id="customer_name_error"></div>
                    </div>

                    <div class="unique-form-row">
                        <div class="unique-form-group">
                            <label for="customer_email">
                                <i class="fa-solid fa-asterisk"></i> Email Address
                            </label>
                            <input type="email" id="customer_email" class="unique-form-control"
                                value="{{ Auth::user()->email ?? '' }}"
                                placeholder="your@email.com" required>
                            <div class="error-message" id="customer_email_error"></div>
                        </div>

                        <div class="unique-form-group">
                            <label for="customer_phone">
                                <i class="fa-solid fa-asterisk"></i> Phone Number
                            </label>
                            <input type="tel" id="customer_phone" class="unique-form-control"
                                value="{{ isset($lastAddress) && $lastAddress ? $lastAddress->phone : '' }}"
                                placeholder="+44 7XXX XXXXXX" required>
                            <div class="error-message" id="customer_phone_error"></div>
                        </div>
                    </div>
                </div>

                {{-- ── DELIVERY ADDRESS ── --}}
                <div class="unique-checkout-form-section"
                    id="newAddressFormSection"
                    @auth @if(isset($savedAddresses) && $savedAddresses->isNotEmpty()) style="display:none;" @endif @endauth>
                    <h3><i class="fa-solid fa-location-dot"></i> Delivery Address</h3>

                    <div class="unique-form-group">
                        <label for="address_line1">
                            <i class="fa-solid fa-asterisk"></i> Address Line 1
                        </label>
                        <input type="text" id="address_line1" class="unique-form-control"
                            value="{{ isset($lastAddress) && $lastAddress ? $lastAddress->address_line1 : '' }}"
                            placeholder="House number and street name" required>
                        <div class="error-message" id="address_line1_error"></div>
                    </div>

                    <div class="unique-form-group">
                        <label for="address_line2">
                            Address Line 2
                            <span style="color:#94a3b8;font-size:13px;font-weight:400">(Optional)</span>
                        </label>
                        <input type="text" id="address_line2" class="unique-form-control"
                            value="{{ isset($lastAddress) && $lastAddress ? $lastAddress->address_line2 : '' }}"
                            placeholder="Flat number, floor, etc.">
                    </div>

                    <div class="unique-form-group">
                        <label for="restaurant_store">
                            <i class="fa-regular fa-store"></i> Restaurant / Store Name
                            <span style="color:#94a3b8;font-size:13px;font-weight:400">(Optional)</span>
                        </label>
                        <input type="text" id="restaurant_store" class="unique-form-control"
                            value="{{ isset($lastAddress) && $lastAddress ? $lastAddress->restaurant_store : '' }}"
                            placeholder="e.g. The Spice Garden, Corner Convenience">
                    </div>

                    <div class="unique-form-row">
                        <div class="unique-form-group">
                            <label for="city">
                                <i class="fa-solid fa-asterisk"></i> Town/City
                            </label>
                            <input type="text" id="city" class="unique-form-control"
                                value="{{ isset($lastAddress) && $lastAddress ? $lastAddress->city : '' }}"
                                placeholder="e.g. London" required>
                            <div class="error-message" id="city_error"></div>
                        </div>

                        <div class="unique-form-group">
                            <label for="county">
                                County
                                <span style="color:#94a3b8;font-size:13px;font-weight:400">(Optional)</span>
                            </label>
                            <input type="text" id="county" class="unique-form-control"
                                value="{{ isset($lastAddress) && $lastAddress ? $lastAddress->county : '' }}"
                                placeholder="e.g. Greater London">
                        </div>
                    </div>

                    <div class="unique-form-group">
                        <label for="postcode">
                            <i class="fa-solid fa-asterisk"></i> Postcode
                        </label>
                        <input type="text" id="postcode" class="unique-form-control"
                            value="{{ isset($lastAddress) && $lastAddress ? $lastAddress->postcode : '' }}"
                            placeholder="e.g. SW1A 1AA" maxlength="8"
                            style="text-transform: uppercase;" required>
                        <div class="error-message" id="postcode_error"></div>
                    </div>

                    @auth
                    <div class="unique-form-group" id="saveAddressToggle"
                        style="{{ (isset($savedAddresses) && $savedAddresses->isNotEmpty()) ? 'display:none' : '' }}">
                        <label class="save-address-label">
                            <input type="checkbox" id="save_address">
                            Save this address for future orders
                        </label>
                        <div id="saveAddressOptions"
                            style="display:none;margin-top:12px;padding:14px;background:#f9fafb;border-radius:8px;border:1px solid #e5e7eb;">
                            <div class="unique-form-row" style="margin-bottom:0;gap:12px;">
                                <div class="unique-form-group" style="margin-bottom:0;">
                                    <label for="address_label">
                                        Label <span style="color:#94a3b8;font-size:12px">(e.g. Home, Work)</span>
                                    </label>
                                    <input type="text" id="address_label" class="unique-form-control" placeholder="e.g. Home">
                                </div>
                                <div class="unique-form-group" style="margin-bottom:0;display:flex;align-items:flex-end;padding-bottom:4px;">
                                    <label class="save-address-label">
                                        <input type="checkbox" id="set_as_default"> Set as default
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endauth
                </div>

                <!-- ── PAYMENT METHOD ── -->
                <div class="unique-checkout-form-section">
                    <h3><i class="fa-solid fa-credit-card"></i> Payment Method</h3>

                    <div class="unique-payment-methods">
                        <div class="unique-payment-method-card disabled" data-method="stripe">
                            <span class="unique-coming-soon-badge">Coming Soon</span>
                            <input type="radio" name="payment_method" value="stripe" disabled>
                            <i class="fa-brands fa-cc-stripe"></i>
                            <span>Card Payment</span>
                        </div>

                        <div class="unique-payment-method-card active" data-method="cash_on_delivery">
                            <input type="radio" name="payment_method" value="cash_on_delivery" checked>
                            <i class="fa-solid fa-money-bill-wave"></i>
                            <span>Cash on Delivery</span>
                        </div>
                    </div>

                    <div class="unique-cod-info">
                        <i class="fa-solid fa-circle-info"></i>
                        <div class="unique-cod-info-text">
                            <strong>Cash on Delivery</strong>
                            <p>Pay with cash when your order is delivered to your doorstep. Please keep exact change ready for a smooth delivery experience.</p>
                        </div>
                    </div>
                </div>

                <!-- ── ORDER NOTES ── -->
                <div class="unique-checkout-form-section">
                    <h3>
                        <i class="fa-solid fa-note-sticky"></i> Order Notes
                        <span style="color:#94a3b8;font-size:14px;font-weight:400">(Optional)</span>
                    </h3>

                    <div class="unique-form-group">
                        <label for="customer_notes">Special instructions or delivery notes</label>
                        <textarea id="customer_notes"
                                  class="unique-form-control"
                                  rows="3"
                                  placeholder="Any special requests or delivery instructions..."></textarea>
                    </div>
                </div>

            </div>{{-- end .unique-checkout-forms --}}

            <!-- ══════════════════════════════════════
                 RIGHT: ORDER SUMMARY
            ══════════════════════════════════════ -->
            <div class="unique-order-summary">
                <h3><i class="fa-solid fa-receipt"></i> Order Summary</h3>

                <div class="unique-order-items">
                    @foreach($cart as $item)
                        @php
                            $isWeightBased   = !empty($item['weight']) && floatval($item['weight']) > 0;
                            $itemSubtotal    = $isWeightBased
                                ? $item['price'] * floatval($item['weight'])
                                : $item['price'] * $item['quantity'];
                            $weightFormatted = $isWeightBased
                                ? rtrim(rtrim(number_format(floatval($item['weight']), 2), '0'), '.')
                                : null;
                        @endphp
                        <div class="unique-order-item">
                            <img src="{{ $item['image'] }}"
                                 alt="{{ $item['name'] }}"
                                 class="unique-order-item-image"
                                 onerror="this.src='/frontend/assets/images/products/product-placeholder.svg'">
                            <div class="unique-order-item-details">
                                <div class="unique-order-item-name">{{ $item['name'] }}</div>
                                @if($isWeightBased)
                                    <div class="unique-order-item-qty weight-qty">
                                        <i class="fa-regular fa-weight-scale"></i>
                                        {{ $weightFormatted }}kg &times; £{{ number_format($item['price'], 2) }}/kg
                                    </div>
                                @else
                                    <div class="unique-order-item-qty">
                                        Qty: {{ $item['quantity'] }} &times; £{{ number_format($item['price'], 2) }}
                                    </div>
                                @endif
                            </div>
                            <div class="unique-order-item-price">£{{ number_format($itemSubtotal, 2) }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="unique-order-totals">
                    <div class="unique-order-total-row">
                        <span>Subtotal:</span>
                        <span>£{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="unique-order-total-row">
                        <span>Shipping:</span>
                        <span>{{ $shippingCost > 0 ? '£' . number_format($shippingCost, 2) : 'FREE' }}</span>
                    </div>

                    @if($shippingCost == 0)
                        <div class="unique-free-shipping-badge">
                            <i class="fa-solid fa-truck-fast"></i> Free Shipping!
                        </div>
                    @endif

                    <div class="unique-order-total-row">
                        <span>VAT (20%):</span>
                        <span>£{{ number_format($tax, 2) }}</span>
                    </div>

                    <div class="unique-order-total-row final">
                        <span>Total:</span>
                        <span id="order-total">£{{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <button type="button" id="place-order-btn" class="unique-btn-place-order">
                    <i class="fa-solid fa-shield-check"></i>
                    <span>Place Order</span>
                </button>

                <div class="unique-secure-badge">
                    <i class="fa-solid fa-lock"></i> Secure Checkout
                </div>
            </div>

        </div>{{-- end .unique-checkout-container --}}
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    let isProcessing = false;

    $('.unique-form-control').on('input', function () {
        $(this).removeClass('error');
        $('#' + $(this).attr('id') + '_error').removeClass('show').text('');
    });

    $('.unique-payment-method-card:not(.disabled)').on('click', function () {
        $('.unique-payment-method-card').removeClass('active');
        $(this).addClass('active');
        $(this).find('input[type="radio"]').prop('checked', true);
    });

    $('#save_address').on('change', function () {
        $('#saveAddressOptions').slideToggle(200);
    });

    $('input, textarea').on('keypress', function (e) {
        if (e.which === 13) e.preventDefault();
    });

    let formModified = false;
    $('input, textarea').on('change input', function () { formModified = true; });
    $(window).on('beforeunload', function () {
        if (formModified && !isProcessing) return 'Are you sure you want to leave?';
    });

    $('#place-order-btn').on('click', async function () {
        if (isProcessing) return;

        const btn = $(this);
        $('.unique-form-control').removeClass('error');
        $('.error-message').removeClass('show').text('');

        const formData = {
            customer_name:    $('#customer_name').val().trim(),
            customer_email:   $('#customer_email').val().trim(),
            customer_phone:   $('#customer_phone').val().trim(),
            address_line1:    $('#address_line1').val().trim(),
            address_line2:    $('#address_line2').val().trim(),
            restaurant_store: $('#restaurant_store').val().trim(),
            city:             $('#city').val().trim(),
            county:           $('#county').val().trim(),
            postcode:         $('#postcode').val().trim().toUpperCase(),
            payment_method:   'cash_on_delivery',
            customer_notes:   $('#customer_notes').val(),
            save_address:     $('#save_address').is(':checked') ? 1 : 0,
            address_label:    $('#address_label').val().trim(),
            set_as_default:   $('#set_as_default').is(':checked') ? 1 : 0,
        };

        isProcessing = true;
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> <span>Processing...</span>');

        try {
            const orderResponse = await $.ajax({
                url:    '{{ route("checkout.process") }}',
                method: 'POST',
                data:   { _token: '{{ csrf_token() }}', ...formData }
            });

            if (orderResponse.success) {
                btn.html('<i class="fa-solid fa-check-circle"></i> <span>Order Placed!</span>');
                btn.css('background', 'linear-gradient(135deg, #10b981 0%, #059669 100%)');
                $('input, textarea, button').prop('disabled', true);

                await Swal.fire({
                    icon:             'success',
                    title:            'Order Placed!',
                    text:             `Order #${orderResponse.order_number} has been successfully placed. A confirmation email has been sent to you.`,
                    confirmButtonText: 'View Orders',
                    timer:            4000,
                    timerProgressBar: true
                });

                window.location.href = orderResponse.redirect;
            } else {
                throw new Error(orderResponse.message || 'Failed to process order');
            }

        } catch (error) {
            isProcessing = false;
            btn.prop('disabled', false).html('<i class="fa-solid fa-shield-check"></i> <span>Place Order</span>');

            if (error.status === 422 && error.responseJSON?.errors) {
                const errors = error.responseJSON.errors;

                // ✅ Auto-reveal hidden sections if they have errors
                const infoFields    = ['customer_name','customer_email','customer_phone'];
                const addressFields = ['address_line1','city','postcode'];
                if (infoFields.some(f => errors[f]))    $('#customerInfoSection').slideDown(250);
                if (addressFields.some(f => errors[f])) $('#newAddressFormSection').slideDown(250);

                Object.keys(errors).forEach(field => {
                    $('#' + field).addClass('error');
                    $('#' + field + '_error').addClass('show').text(errors[field][0]);
                });
            } else {
                let msg = 'Failed to place order. Please try again.';
                if      (error.status === 429)        msg = error.responseJSON?.message || 'Too many attempts. Please wait.';
                else if (error.responseJSON?.message) msg = error.responseJSON.message;
                else if (error.message)               msg = error.message;
                Swal.fire({ icon: 'error', title: 'Order Failed', text: msg });
            }
        }
    });
});

// ── Saved address helpers ──

window.fillAddressForm = function (addr) {
    $('#customer_phone').val(addr.phone             || '');
    $('#address_line1').val(addr.address_line1       || '');
    $('#address_line2').val(addr.address_line2       || '');
    $('#restaurant_store').val(addr.restaurant_store || '');
    $('#city').val(addr.city                         || '');
    $('#county').val(addr.county                     || '');
    $('#postcode').val(addr.postcode                 || '');
    $('.unique-form-control').removeClass('error');
    $('.error-message').removeClass('show').text('');
    $('#saveAddressToggle').hide();
};

window.selectSavedAddress = function (el) {
    $('.saved-address-card').removeClass('selected');
    $(el).addClass('selected');
    window.fillAddressForm(JSON.parse(el.getAttribute('data-address')));
    // ✅ Hide both forms when a saved address is selected
    $('#customerInfoSection').slideUp(250);
    $('#newAddressFormSection').slideUp(250);
};

window.selectNewAddress = function (el) {
    $('.saved-address-card').removeClass('selected');
    $(el).addClass('selected');
    // ✅ Show both forms
    $('#customerInfoSection').slideDown(250);
    $('#newAddressFormSection').slideDown(250);
    ['customer_phone','address_line1','address_line2','restaurant_store','city','county','postcode']
        .forEach(function (id) { $('#' + id).val(''); });
    $('#saveAddressToggle').show();
    $('#saveAddressOptions').hide();
    $('#save_address').prop('checked', false);
};

window.deleteSavedAddress = function (e, id, btn) {
    e.stopPropagation();

    // ✅ Custom inline confirm toast
    const card = $(btn).closest('.saved-address-card');

    // Remove any existing confirm box first
    $('.addr-delete-confirm').remove();

    const confirmBox = $(`
        <div class="addr-delete-confirm">
            <p><i class="fa-solid fa-triangle-exclamation"></i> Remove this address?</p>
            <div class="addr-confirm-actions">
                <button class="addr-confirm-yes" type="button">Yes, Remove</button>
                <button class="addr-confirm-no" type="button">Cancel</button>
            </div>
        </div>
    `);

    card.append(confirmBox);
    setTimeout(() => confirmBox.addClass('show'), 10);

    // Cancel
    confirmBox.find('.addr-confirm-no').on('click', function (ev) {
        ev.stopPropagation();
        confirmBox.removeClass('show');
        setTimeout(() => confirmBox.remove(), 250);
    });

    // Confirm
    confirmBox.find('.addr-confirm-yes').on('click', function (ev) {
        ev.stopPropagation();
        confirmBox.remove();

        $.ajax({
            url:    '/account/addresses/' + id,
            method: 'DELETE',
            data:   { _token: '{{ csrf_token() }}' },
            success: function () {
                const wasSelected = card.hasClass('selected');

                card.fadeOut(300, function () {
                    $(this).remove();
                    const remaining = $('.saved-address-card:not(.new-address-card)').length;

                    if (remaining === 0) {
                        $('#savedAddressSection').fadeOut(200);
                        $('#saveAddressToggle').show();
                        $('#customerInfoSection').slideDown(250);
                        $('#newAddressFormSection').slideDown(250);
                    }

                    if (wasSelected) {
                        const first = $('.saved-address-card:not(.new-address-card)').first();
                        if (first.length) {
                            first.addClass('selected');
                            window.fillAddressForm(JSON.parse(first.attr('data-address')));
                        } else {
                            $('.new-address-card').addClass('selected');
                            window.selectNewAddress($('.new-address-card')[0]);
                        }
                    }
                });

                Toast.success('Address removed successfully');
            },
            error: function () {
                Toast.error('Could not remove address. Please try again.');
            }
        });
    });
};
</script>
@endpush
