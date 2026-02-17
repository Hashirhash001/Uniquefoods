@extends('frontend.layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@push('styles')
<style>
    .order-details-wrapper {
        padding: 80px 0 !important;
        background: linear-gradient(135deg, #f8fafc 0%, #e8f0fe 100%) !important;
        min-height: calc(100vh - var(--header-height, 140px)) !important;
    }

    .order-details-container {
        max-width: 1000px !important;
        margin: 0 auto !important;
        padding: 0 20px !important;
    }

    .back-button {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        color: #0f508d !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        margin-bottom: 30px !important;
        transition: all 0.3s !important;
    }

    .back-button:hover {
        gap: 12px !important;
        color: #08437b !important;
    }

    .order-details-card {
        background: white !important;
        border-radius: 16px !important;
        padding: 40px !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06) !important;
        border: 1px solid rgba(15, 80, 141, 0.08) !important;
    }

    .order-header {
        display: flex !important;
        justify-content: space-between !important;
        align-items: start !important;
        margin-bottom: 30px !important;
        padding-bottom: 30px !important;
        border-bottom: 2px solid #f1f5f9 !important;
    }

    .order-header h1 {
        font-size: 28px !important;
        font-weight: 800 !important;
        color: #0f508d !important;
        margin-bottom: 8px !important;
    }

    .order-date {
        font-size: 15px !important;
        color: #64748b !important;
    }

    .status-badge {
        padding: 10px 20px !important;
        border-radius: 25px !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
    }

    .status-pending {
        background: #fef3c7 !important;
        color: #92400e !important;
    }

    .status-processing {
        background: #dbeafe !important;
        color: #1e40af !important;
    }

    .status-shipped {
        background: #e0e7ff !important;
        color: #4338ca !important;
    }

    .status-delivered {
        background: #d1fae5 !important;
        color: #065f46 !important;
    }

    .status-cancelled {
        background: #fee2e2 !important;
        color: #991b1b !important;
    }

    .order-info-grid {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 30px !important;
        margin-bottom: 40px !important;
    }

    .info-section {
        background: #f8fafc !important;
        padding: 25px !important;
        border-radius: 12px !important;
        border: 1px solid #e2e8f0 !important;
    }

    .info-section h3 {
        font-size: 16px !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 15px !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }

    .info-section h3 i {
        color: #0f508d !important;
        font-size: 18px !important;
    }

    .info-section p {
        font-size: 14px !important;
        color: #64748b !important;
        line-height: 1.8 !important;
        margin: 0 !important;
    }

    .order-items-section {
        margin-bottom: 40px !important;
    }

    .order-items-section h3 {
        font-size: 20px !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 20px !important;
    }

    .order-item {
        display: flex !important;
        gap: 20px !important;
        padding: 20px 0 !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    .order-item:last-child {
        border-bottom: none !important;
    }

    .order-item-image {
        width: 100px !important;
        height: 100px !important;
        border-radius: 12px !important;
        object-fit: cover !important;
        border: 2px solid #f1f5f9 !important;
    }

    .order-item-info {
        flex: 1 !important;
    }

    .order-item-name {
        font-size: 18px !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 8px !important;
    }

    .order-item-meta {
        font-size: 14px !important;
        color: #64748b !important;
    }

    .order-item-price {
        text-align: right !important;
    }

    .order-item-price .unit-price {
        font-size: 16px !important;
        color: #64748b !important;
        margin-bottom: 5px !important;
    }

    .order-item-price .total-price {
        font-size: 20px !important;
        font-weight: 700 !important;
        color: #0f508d !important;
    }

    .order-summary {
        background: #f8fafc !important;
        padding: 30px !important;
        border-radius: 12px !important;
        border: 2px solid #e2e8f0 !important;
    }

    .order-summary h3 {
        font-size: 20px !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 20px !important;
    }

    .summary-row {
        display: flex !important;
        justify-content: space-between !important;
        padding: 12px 0 !important;
        font-size: 15px !important;
        color: #64748b !important;
    }

    .summary-row.total {
        font-size: 22px !important;
        font-weight: 800 !important;
        color: #0f508d !important;
        border-top: 2px dashed #cbd5e1 !important;
        padding-top: 20px !important;
        margin-top: 15px !important;
    }

    .payment-badge {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 8px 16px !important;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%) !important;
        color: #1e40af !important;
        border-radius: 20px !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        margin-top: 15px !important;
    }

    @media (max-width: 768px) {
        .order-info-grid {
            grid-template-columns: 1fr !important;
        }

        .order-header {
            flex-direction: column !important;
            gap: 20px !important;
        }

        .order-item {
            flex-direction: column !important;
        }

        .order-item-image {
            width: 100% !important;
            height: 200px !important;
        }

        .order-item-price {
            text-align: left !important;
        }
    }
</style>
@endpush

@section('content')
<div class="order-details-wrapper">
    <div class="order-details-container">
        <a href="{{ route('orders.index') }}" class="back-button">
            <i class="fa-solid fa-arrow-left"></i> Back to Orders
        </a>

        <div class="order-details-card">
            <div class="order-header">
                <div>
                    <h1><i class="fa-solid fa-hashtag"></i> {{ $order->order_number }}</h1>
                    <div class="order-date">
                        <i class="fa-regular fa-calendar"></i>
                        Placed on {{ $order->created_at->format('d M Y, h:i A') }}
                    </div>
                </div>
                <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <div class="order-info-grid">
                <div class="info-section">
                    <h3><i class="fa-solid fa-user"></i> Customer Information</h3>
                    <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                    <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                </div>

                <div class="info-section">
                    <h3><i class="fa-solid fa-location-dot"></i> Delivery Address</h3>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_postcode }}</p>
                    <p>{{ $order->shipping_country }}</p>
                </div>
            </div>

            @if($order->customer_notes)
                <div class="info-section" style="margin-bottom: 40px;">
                    <h3><i class="fa-solid fa-note-sticky"></i> Order Notes</h3>
                    <p>{{ $order->customer_notes }}</p>
                </div>
            @endif

            <div class="order-items-section">
                <h3><i class="fa-solid fa-box"></i> Order Items</h3>
                @foreach($order->items as $item)
                    <div class="order-item">
                        @if($item->product)
                            <img src="{{ $item->product->image_url }}"
                                alt="{{ $item->product_name }}"
                                class="order-item-image"
                                onerror="this.src='{{ asset('frontend/assets/images/grocery/01.jpg') }}'">
                        @else
                            <img src="{{ asset('frontend/assets/images/grocery/01.jpg') }}"
                                alt="{{ $item->product_name }}"
                                class="order-item-image">
                        @endif

                        <div class="order-item-info">
                            <div class="order-item-name">{{ $item->product_name }}</div>
                            <div class="order-item-meta">
                                Quantity: {{ $item->quantity }}
                                @if($item->weight)
                                    | Weight: {{ $item->weight }}g
                                @endif
                            </div>
                        </div>

                        <div class="order-item-price">
                            <div class="unit-price">£{{ number_format($item->price, 2) }} each</div>
                            <div class="total-price">£{{ number_format($item->subtotal, 2) }}</div>
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="order-summary">
                <h3><i class="fa-solid fa-receipt"></i> Order Summary</h3>

                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>£{{ number_format($order->subtotal, 2) }}</span>
                </div>

                <div class="summary-row">
                    <span>Shipping:</span>
                    <span>{{ $order->shipping_cost > 0 ? '£' . number_format($order->shipping_cost, 2) : 'FREE' }}</span>
                </div>

                <div class="summary-row">
                    <span>VAT (20%):</span>
                    <span>£{{ number_format($order->tax, 2) }}</span>
                </div>

                <div class="summary-row total">
                    <span>Total:</span>
                    <span>£{{ number_format($order->total, 2) }}</span>
                </div>

                <div class="payment-badge">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    {{ $order->payment_status === 'paid' ? 'Payment Completed' : 'Cash on Delivery' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
