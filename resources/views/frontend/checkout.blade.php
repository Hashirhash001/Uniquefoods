@extends('frontend.layouts.app')

@section('title', 'Checkout')

@push('styles')
<style>
    /* ===== CHECKOUT WRAPPER ===== */
    .unique-checkout-wrapper {
        padding: 60px 0 !important;
        background: #f8f9fa !important;
        min-height: calc(100vh - var(--header-height, 140px)) !important;
    }

    .unique-checkout-container {
        max-width: 1200px !important;
        margin: 0 auto !important;
        display: grid !important;
        grid-template-columns: 1fr 400px !important;
        gap: 30px !important;
        padding: 0 20px !important;
    }

    .unique-checkout-title {
        text-align: center !important;
        margin-bottom: 40px !important;
        font-size: 32px !important;
        font-weight: 700 !important;
        color: #1a1a1a !important;
    }

    /* ===== FORM SECTIONS ===== */
    .unique-checkout-form-section {
        background: white !important;
        padding: 30px !important;
        border-radius: 12px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
        margin-bottom: 20px !important;
    }

    .unique-checkout-form-section h3 {
        font-size: 20px !important;
        font-weight: 700 !important;
        margin-bottom: 20px !important;
        margin-top: 0 !important;
        color: #1a1a1a !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }

    .unique-checkout-form-section h3 i {
        color: #0f508d !important;
        font-size: 22px !important;
    }

    /* ===== FORM ELEMENTS ===== */
    .unique-form-group {
        margin-bottom: 20px !important;
    }

    .unique-form-group label {
        display: block !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        margin-bottom: 8px !important;
        color: #333 !important;
    }

    .unique-form-control {
        width: 100% !important;
        padding: 12px 16px !important;
        border: 2px solid #e5e7eb !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        transition: all 0.3s !important;
        font-family: inherit !important;
        background: white !important;
        height: auto !important;
        min-height: unset !important;
        max-height: unset !important;
    }

    .unique-form-control:focus {
        outline: none !important;
        border-color: #0f508d !important;
        box-shadow: 0 0 0 3px rgba(15, 80, 141, 0.1) !important;
    }

    .unique-form-control::placeholder {
        color: #9ca3af !important;
    }

    textarea.unique-form-control {
        resize: vertical !important;
        min-height: 80px !important;
    }

    .unique-form-row {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 20px !important;
    }

    /* ===== PAYMENT METHODS ===== */
    .unique-payment-methods {
        display: flex !important;
        gap: 15px !important;
        margin-top: 10px !important;
    }

    .unique-payment-method-card {
        flex: 1 !important;
        border: 2px solid #e5e7eb !important;
        border-radius: 8px !important;
        padding: 20px !important;
        cursor: pointer !important;
        transition: all 0.3s !important;
        text-align: center !important;
        background: white !important;
    }

    .unique-payment-method-card:hover {
        border-color: #0f508d !important;
        transform: translateY(-2px) !important;
    }

    .unique-payment-method-card.active {
        border-color: #0f508d !important;
        background: #f0f7ff !important;
    }

    .unique-payment-method-card input[type="radio"] {
        display: none !important;
    }

    .unique-payment-method-card i {
        font-size: 32px !important;
        color: #0f508d !important;
        margin-bottom: 10px !important;
        display: block !important;
    }

    .unique-payment-method-card span {
        display: block !important;
        font-weight: 600 !important;
        color: #333 !important;
        font-size: 14px !important;
    }

    /* ===== STRIPE CARD ELEMENT ===== */
    .unique-stripe-card-container {
        margin-top: 20px !important;
    }

    #stripe-card-element {
        padding: 12px !important;
        border: 2px solid #e5e7eb !important;
        border-radius: 8px !important;
        background: white !important;
        height: auto !important;
        min-height: 40px !important;
    }

    #card-errors {
        color: #e74c3c !important;
        margin-top: 10px !important;
        font-size: 14px !important;
        min-height: unset !important;
    }

    /* ===== ORDER SUMMARY ===== */
    .unique-order-summary {
        background: white !important;
        padding: 30px !important;
        border-radius: 12px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
        height: fit-content !important;
        position: sticky !important;
        top: 20px !important;
    }

    .unique-order-summary h3 {
        font-size: 20px !important;
        font-weight: 700 !important;
        margin-bottom: 20px !important;
        margin-top: 0 !important;
        color: #1a1a1a !important;
    }

    .unique-order-items {
        max-height: 400px !important;
        overflow-y: auto !important;
        margin-bottom: 20px !important;
    }

    .unique-order-item {
        display: flex !important;
        gap: 15px !important;
        padding: 15px 0 !important;
        border-bottom: 1px solid #e5e7eb !important;
    }

    .unique-order-item:first-child {
        padding-top: 0 !important;
    }

    .unique-order-item:last-child {
        border-bottom: none !important;
    }

    .unique-order-item-image {
        width: 60px !important;
        height: 60px !important;
        border-radius: 8px !important;
        object-fit: cover !important;
        flex-shrink: 0 !important;
    }

    .unique-order-item-details {
        flex: 1 !important;
        min-width: 0 !important;
    }

    .unique-order-item-name {
        font-size: 14px !important;
        font-weight: 600 !important;
        color: #333 !important;
        margin-bottom: 5px !important;
        line-height: 1.4 !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
    }

    .unique-order-item-qty {
        font-size: 13px !important;
        color: #666 !important;
    }

    .unique-order-item-price {
        font-size: 16px !important;
        font-weight: 700 !important;
        color: #0f508d !important;
        white-space: nowrap !important;
    }

    /* ===== ORDER TOTALS ===== */
    .unique-order-totals {
        margin-top: 20px !important;
        padding-top: 20px !important;
        border-top: 2px solid #e5e7eb !important;
    }

    .unique-order-total-row {
        display: flex !important;
        justify-content: space-between !important;
        padding: 8px 0 !important;
        font-size: 14px !important;
        color: #333 !important;
    }

    .unique-order-total-row.final {
        font-size: 18px !important;
        font-weight: 700 !important;
        color: #0f508d !important;
        padding-top: 15px !important;
        border-top: 2px solid #e5e7eb !important;
        margin-top: 10px !important;
    }

    /* ===== BADGES ===== */
    .unique-free-shipping-badge {
        background: #10b981 !important;
        color: white !important;
        padding: 8px 12px !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        display: inline-block !important;
        margin-top: 10px !important;
    }

    .unique-free-shipping-badge i {
        margin-right: 5px !important;
    }

    /* ===== PLACE ORDER BUTTON ===== */
    .unique-btn-place-order {
        width: 100% !important;
        padding: 16px !important;
        background: linear-gradient(135deg, #0f508d 0%, #08437b 100%) !important;
        color: white !important;
        border: none !important;
        border-radius: 8px !important;
        font-size: 16px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        margin-top: 20px !important;
        transition: all 0.3s !important;
        height: auto !important;
        min-height: 56px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
    }

    .unique-btn-place-order:hover:not(:disabled) {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px rgba(15, 80, 141, 0.3) !important;
    }

    .unique-btn-place-order:disabled {
        opacity: 0.6 !important;
        cursor: not-allowed !important;
        transform: none !important;
    }

    .unique-secure-badge {
        text-align: center !important;
        margin-top: 15px !important;
        font-size: 13px !important;
        color: #666 !important;
    }

    .unique-secure-badge i {
        color: #10b981 !important;
        margin-right: 5px !important;
    }

    /* ===== MOBILE RESPONSIVE ===== */
    @media (max-width: 992px) {
        .unique-checkout-container {
            grid-template-columns: 1fr !important;
        }

        .unique-order-summary {
            position: static !important;
            order: -1 !important;
        }

        .unique-checkout-form-section {
            padding: 20px !important;
        }
    }

    @media (max-width: 768px) {
        .unique-checkout-wrapper {
            padding: 40px 0 !important;
        }

        .unique-checkout-title {
            font-size: 24px !important;
            margin-bottom: 30px !important;
        }

        .unique-form-row {
            grid-template-columns: 1fr !important;
            gap: 0 !important;
        }

        .unique-checkout-form-section {
            padding: 20px 15px !important;
            margin-bottom: 15px !important;
        }

        .unique-checkout-form-section h3 {
            font-size: 18px !important;
        }

        .unique-payment-methods {
            flex-direction: column !important;
        }

        .unique-order-summary {
            padding: 20px !important;
        }

        .unique-order-item {
            gap: 10px !important;
        }

        .unique-order-item-image {
            width: 50px !important;
            height: 50px !important;
        }

        .unique-order-item-name {
            font-size: 13px !important;
        }

        .unique-order-item-price {
            font-size: 14px !important;
        }
    }

    @media (max-width: 480px) {
        .unique-checkout-wrapper {
            padding: 30px 0 !important;
        }

        .unique-checkout-container {
            padding: 0 10px !important;
        }

        .unique-checkout-title {
            font-size: 20px !important;
        }

        .unique-form-control {
            padding: 10px 12px !important;
            font-size: 13px !important;
        }

        .unique-btn-place-order {
            padding: 14px !important;
            font-size: 15px !important;
            min-height: 50px !important;
        }
    }

    /* ===== SCROLLBAR STYLING ===== */
    .unique-order-items::-webkit-scrollbar {
        width: 6px !important;
    }

    .unique-order-items::-webkit-scrollbar-track {
        background: #f1f1f1 !important;
        border-radius: 10px !important;
    }

    .unique-order-items::-webkit-scrollbar-thumb {
        background: #0f508d !important;
        border-radius: 10px !important;
    }

    .unique-order-items::-webkit-scrollbar-thumb:hover {
        background: #08437b !important;
    }
</style>
@endpush

@section('content')
<div class="unique-checkout-wrapper mt-5">
    <div class="container">
        {{-- <h1 class="unique-checkout-title">Secure Checkout</h1> --}}

        <div class="unique-checkout-container">
            <!-- Checkout Form -->
            <div class="unique-checkout-forms">
                <!-- Customer Information -->
                <div class="unique-checkout-form-section">
                    <h3><i class="fa-regular fa-user"></i> Customer Information</h3>

                    <div class="unique-form-group">
                        <label for="customer_name">Full Name *</label>
                        <input type="text"
                               id="customer_name"
                               class="unique-form-control"
                               value="{{ Auth::user()->name ?? '' }}"
                               required>
                    </div>

                    <div class="unique-form-row">
                        <div class="unique-form-group">
                            <label for="customer_email">Email Address *</label>
                            <input type="email"
                                   id="customer_email"
                                   class="unique-form-control"
                                   value="{{ Auth::user()->email ?? '' }}"
                                   required>
                        </div>

                        <div class="unique-form-group">
                            <label for="customer_phone">Phone Number *</label>
                            <input type="tel"
                                   id="customer_phone"
                                   class="unique-form-control"
                                   placeholder="+44 7XXX XXXXXX"
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="unique-checkout-form-section">
                    <h3><i class="fa-regular fa-location-dot"></i> Shipping Address</h3>

                    <div class="unique-form-group">
                        <label for="shipping_address">Street Address *</label>
                        <textarea id="shipping_address"
                                  class="unique-form-control"
                                  rows="3"
                                  placeholder="Enter your full address"
                                  required></textarea>
                    </div>

                    <div class="unique-form-row">
                        <div class="unique-form-group">
                            <label for="shipping_city">City *</label>
                            <input type="text"
                                   id="shipping_city"
                                   class="unique-form-control"
                                   placeholder="e.g. London"
                                   required>
                        </div>

                        <div class="unique-form-group">
                            <label for="shipping_postcode">Postcode *</label>
                            <input type="text"
                                   id="shipping_postcode"
                                   class="unique-form-control"
                                   placeholder="e.g. SW1A 1AA"
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="unique-checkout-form-section">
                    <h3><i class="fa-regular fa-credit-card"></i> Payment Method</h3>

                    <div class="unique-payment-methods">
                        <div class="unique-payment-method-card active" data-method="stripe">
                            <input type="radio" name="payment_method" value="stripe" checked>
                            <i class="fa-brands fa-cc-stripe"></i>
                            <span>Card Payment</span>
                        </div>

                        <div class="unique-payment-method-card" data-method="cash_on_delivery">
                            <input type="radio" name="payment_method" value="cash_on_delivery">
                            <i class="fa-regular fa-money-bill-wave"></i>
                            <span>Cash on Delivery</span>
                        </div>
                    </div>

                    <!-- Stripe Card Element -->
                    <div id="stripe-card-container" class="unique-stripe-card-container">
                        <div id="stripe-card-element"></div>
                        <div id="card-errors"></div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="unique-checkout-form-section">
                    <h3><i class="fa-regular fa-note"></i> Order Notes (Optional)</h3>

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
                <h3>Order Summary</h3>

                <div class="unique-order-items">
                    @foreach($cart as $item)
                        <div class="unique-order-item">
                            <img src="{{ $item['image'] }}"
                                 alt="{{ $item['name'] }}"
                                 class="unique-order-item-image"
                                 onerror="this.src='/frontend/assets/images/grocery/01.jpg'">
                            <div class="unique-order-item-details">
                                <div class="unique-order-item-name">{{ $item['name'] }}</div>
                                <div class="unique-order-item-qty">Qty: {{ $item['quantity'] }}</div>
                            </div>
                            <div class="unique-order-item-price">£{{ number_format($item['price'] * $item['quantity'], 2) }}</div>
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
                            <i class="fa-solid fa-truck-fast"></i> Free Shipping Applied!
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
                    <i class="fa-regular fa-lock"></i>
                    <span>Place Order</span>
                </button>

                <div class="unique-secure-badge">
                    <i class="fa-solid fa-shield-check"></i> Secure checkout powered by Stripe
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
$(document).ready(function() {
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#1a1a1a',
                fontFamily: 'inherit',
                '::placeholder': {
                    color: '#9ca3af',
                },
            },
            invalid: {
                color: '#e74c3c',
            },
        },
    });

    cardElement.mount('#stripe-card-element');

    let selectedPaymentMethod = 'stripe';
    let clientSecret = null;

    // Payment method selection
    $('.unique-payment-method-card').on('click', function() {
        $('.unique-payment-method-card').removeClass('active');
        $(this).addClass('active');
        $(this).find('input[type="radio"]').prop('checked', true);
        selectedPaymentMethod = $(this).data('method');

        if (selectedPaymentMethod === 'stripe') {
            $('#stripe-card-container').show();
        } else {
            $('#stripe-card-container').hide();
        }
    });

    // Card element errors
    cardElement.on('change', function(event) {
        const displayError = $('#card-errors');
        if (event.error) {
            displayError.text(event.error.message);
        } else {
            displayError.text('');
        }
    });

    // Place order
    $('#place-order-btn').on('click', async function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Processing...');

        // Validate form
        const customerName = $('#customer_name').val().trim();
        const customerEmail = $('#customer_email').val().trim();
        const customerPhone = $('#customer_phone').val().trim();
        const shippingAddress = $('#shipping_address').val().trim();
        const shippingCity = $('#shipping_city').val().trim();
        const shippingPostcode = $('#shipping_postcode').val().trim();

        if (!customerName || !customerEmail || !customerPhone || !shippingAddress || !shippingCity || !shippingPostcode) {
            toastr.error('Please fill in all required fields');
            btn.prop('disabled', false).html('<i class="fa-regular fa-lock"></i> <span>Place Order</span>');
            return;
        }

        try {
            let paymentIntentId = null;

            if (selectedPaymentMethod === 'stripe') {
                // Create payment intent
                const totalAmount = {{ $total * 100 }}; // Convert to pence

                const response = await $.ajax({
                    url: '{{ route('checkout.create-payment-intent') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        amount: totalAmount
                    }
                });

                if (!response.success) {
                    throw new Error('Failed to create payment intent');
                }

                clientSecret = response.clientSecret;

                // Confirm card payment
                const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: customerName,
                            email: customerEmail,
                        }
                    }
                });

                if (error) {
                    throw new Error(error.message);
                }

                paymentIntentId = paymentIntent.id;
            }

            // Submit order
            const orderResponse = await $.ajax({
                url: '{{ route('checkout.process') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    customer_name: customerName,
                    customer_email: customerEmail,
                    customer_phone: customerPhone,
                    shipping_address: shippingAddress,
                    shipping_city: shippingCity,
                    shipping_postcode: shippingPostcode,
                    payment_method: selectedPaymentMethod,
                    stripe_payment_intent_id: paymentIntentId,
                    customer_notes: $('#customer_notes').val()
                }
            });

            if (orderResponse.success) {
                toastr.success('Order placed successfully!');
                window.location.href = orderResponse.redirect;
            } else {
                throw new Error(orderResponse.message || 'Failed to process order');
            }

        } catch (error) {
            console.error(error);
            toastr.error(error.message || 'Payment failed. Please try again.');
            btn.prop('disabled', false).html('<i class="fa-regular fa-lock"></i> <span>Place Order</span>');
        }
    });
});
</script>
@endpush
