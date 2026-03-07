@extends('frontend.layouts.app')

@section('title', 'Checkout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/checkout.css') }}">
@endpush

@section('content')
<div class="unique-checkout-wrapper mt-5">
    <div class="container">
        <div class="unique-checkout-container">
            <!-- Checkout Form -->
            <div class="unique-checkout-forms">
                <!-- Customer Information -->
                <div class="unique-checkout-form-section">
                    <h3><i class="fa-solid fa-user"></i> Customer Information</h3>

                    <div class="unique-form-group">
                        <label for="customer_name">
                            <i class="fa-solid fa-asterisk"></i>
                            Full Name
                        </label>
                        <input type="text"
                               id="customer_name"
                               class="unique-form-control"
                               value="{{ Auth::user()->name ?? '' }}"
                               placeholder="Enter your full name"
                               required>
                        <div class="error-message" id="customer_name_error"></div>
                    </div>

                    <div class="unique-form-row">
                        <div class="unique-form-group">
                            <label for="customer_email">
                                <i class="fa-solid fa-asterisk"></i>
                                Email Address
                            </label>
                            <input type="email"
                                   id="customer_email"
                                   class="unique-form-control"
                                   value="{{ Auth::user()->email ?? '' }}"
                                   placeholder="your@email.com"
                                   required>
                            <div class="error-message" id="customer_email_error"></div>
                        </div>

                        <div class="unique-form-group">
                            <label for="customer_phone">
                                <i class="fa-solid fa-asterisk"></i>
                                Phone Number
                            </label>
                            <input type="tel"
                                   id="customer_phone"
                                   class="unique-form-control"
                                   placeholder="+44 7XXX XXXXXX"
                                   required>
                            <div class="error-message" id="customer_phone_error"></div>
                        </div>
                    </div>
                </div>

                <!-- UK Delivery Address -->
                <div class="unique-checkout-form-section">
                    <h3><i class="fa-solid fa-location-dot"></i> Delivery Address</h3>

                    <div class="unique-form-group">
                        <label for="address_line1">
                            <i class="fa-solid fa-asterisk"></i>
                            Address Line 1
                        </label>
                        <input type="text"
                               id="address_line1"
                               class="unique-form-control"
                               placeholder="House number and street name"
                               required>
                        <div class="error-message" id="address_line1_error"></div>
                    </div>

                    <div class="unique-form-group">
                        <label for="address_line2">
                            Address Line 2 (Optional)
                        </label>
                        <input type="text"
                               id="address_line2"
                               class="unique-form-control"
                               placeholder="Apartment, suite, etc.">
                    </div>

                    <div class="unique-form-row">
                        <div class="unique-form-group">
                            <label for="city">
                                <i class="fa-solid fa-asterisk"></i>
                                Town/City
                            </label>
                            <input type="text"
                                   id="city"
                                   class="unique-form-control"
                                   placeholder="e.g. London"
                                   required>
                            <div class="error-message" id="city_error"></div>
                        </div>

                        <div class="unique-form-group">
                            <label for="county">
                                County (Optional)
                            </label>
                            <input type="text"
                                   id="county"
                                   class="unique-form-control"
                                   placeholder="e.g. Greater London">
                        </div>
                    </div>

                    <div class="unique-form-group">
                        <label for="postcode">
                            <i class="fa-solid fa-asterisk"></i>
                            Postcode
                        </label>
                        <input type="text"
                               id="postcode"
                               class="unique-form-control"
                               placeholder="e.g. SW1A 1AA"
                               maxlength="8"
                               style="text-transform: uppercase;"
                               required>
                        <div class="error-message" id="postcode_error"></div>
                    </div>
                </div>

                <!-- Payment Method -->
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

                <!-- Order Notes -->
                <div class="unique-checkout-form-section">
                    <h3><i class="fa-solid fa-note-sticky"></i> Order Notes <span style="color: #94a3b8; font-size: 14px; font-weight: 400;">(Optional)</span></h3>

                    <div class="unique-form-group">
                        <label for="customer_notes">Special instructions or delivery notes</label>
                        <textarea id="customer_notes"
                                  class="unique-form-control"
                                  rows="3"
                                  placeholder="Any special requests or delivery instructions..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="unique-order-summary">
                <h3><i class="fa-solid fa-receipt"></i> Order Summary</h3>

                <div class="unique-order-items">
                    @foreach($cart as $item)
                        @php
                            $isWeightBased = !empty($item['weight']) && floatval($item['weight']) > 0;
                            $itemSubtotal  = $isWeightBased
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
                                onerror="this.src='/frontend/assets/images/grocery/01.jpg'">
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
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let selectedPaymentMethod = 'cash_on_delivery';
    let isProcessing = false;

    // Clear errors on input
    $('.unique-form-control').on('input', function() {
        $(this).removeClass('error');
        $(`#${$(this).attr('id')}_error`).removeClass('show').text('');
    });

    // Payment method selection
    $('.unique-payment-method-card:not(.disabled)').on('click', function() {
        $('.unique-payment-method-card').removeClass('active');
        $(this).addClass('active');
        $(this).find('input[type="radio"]').prop('checked', true);
        selectedPaymentMethod = $(this).data('method');
    });

    // Place order
    $('#place-order-btn').on('click', async function() {
        if (isProcessing) return;

        const btn = $(this);

        // Clear previous errors
        $('.unique-form-control').removeClass('error');
        $('.error-message').removeClass('show').text('');

        // Get form data
        const formData = {
            customer_name: $('#customer_name').val().trim(),
            customer_email: $('#customer_email').val().trim(),
            customer_phone: $('#customer_phone').val().trim(),
            address_line1: $('#address_line1').val().trim(),
            address_line2: $('#address_line2').val().trim(),
            city: $('#city').val().trim(),
            county: $('#county').val().trim(),
            postcode: $('#postcode').val().trim().toUpperCase(),
            payment_method: 'cash_on_delivery',
            customer_notes: $('#customer_notes').val()
        };

        // Show processing
        isProcessing = true;
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> <span>Processing...</span>');

        try {
            const orderResponse = await $.ajax({
                url: '{{ route('checkout.process') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ...formData
                }
            });

            if (orderResponse.success) {
                btn.html('<i class="fa-solid fa-check-circle"></i> <span>Order Placed!</span>');
                btn.css('background', 'linear-gradient(135deg, #10b981 0%, #059669 100%)');
                $('input, textarea, button').prop('disabled', true);

                // Minimalistic success alert
                await Swal.fire({
                    icon: 'success',
                    title: 'Order Placed!',
                    text: `Order #${orderResponse.order_number} has been successfully placed.`,
                    confirmButtonText: 'View Orders',
                    timer: 3000,
                    timerProgressBar: true
                });

                window.location.href = orderResponse.redirect;
            } else {
                throw new Error(orderResponse.message || 'Failed to process order');
            }

        } catch (error) {
            console.error('Order error:', error);
            isProcessing = false;
            btn.prop('disabled', false).html('<i class="fa-solid fa-shield-check"></i> <span>Place Order</span>');

            if (error.status === 422 && error.responseJSON && error.responseJSON.errors) {
                // Show validation errors
                const errors = error.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    $(`#${field}`).addClass('error');
                    $(`#${field}_error`).addClass('show').text(errors[field][0]);
                });

                // Swal.fire({
                //     icon: 'error',
                //     title: 'Validation Error',
                //     text: 'Please check the highlighted fields and try again.'
                // });
            } else {
                let errorMessage = 'Failed to place order. Please try again.';

                if (error.status === 429) {
                    errorMessage = error.responseJSON?.message || 'Too many attempts. Please wait.';
                } else if (error.responseJSON && error.responseJSON.message) {
                    errorMessage = error.responseJSON.message;
                } else if (error.message) {
                    errorMessage = error.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Order Failed',
                    text: errorMessage
                });
            }
        }
    });

    // Prevent Enter key
    $('input, textarea').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
        }
    });

    // Page leave warning
    let formModified = false;
    $('input, textarea').on('change input', function() {
        formModified = true;
    });

    $(window).on('beforeunload', function(e) {
        if (formModified && !isProcessing) {
            return 'Are you sure you want to leave?';
        }
    });
});
</script>
@endpush
