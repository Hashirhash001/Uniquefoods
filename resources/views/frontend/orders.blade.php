@extends('frontend.layouts.app')

@section('title', 'My Orders')

@push('styles')
<style>
    .orders-wrapper {
        padding: 80px 0 !important;
        background: linear-gradient(135deg, #f8fafc 0%, #e8f0fe 100%) !important;
        min-height: calc(100vh - var(--header-height, 140px)) !important;
    }

    .orders-container {
        max-width: 1200px !important;
        margin: 0 auto !important;
        padding: 0 20px !important;
    }

    .orders-header {
        text-align: center !important;
        margin-bottom: 50px !important;
    }

    .orders-header h1 {
        font-size: 36px !important;
        font-weight: 800 !important;
        color: #1e293b !important;
        margin-bottom: 12px !important;
    }

    .orders-header p {
        font-size: 16px !important;
        color: #64748b !important;
    }

    .order-card {
        background: white !important;
        border-radius: 16px !important;
        padding: 30px !important;
        margin-bottom: 25px !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06) !important;
        border: 1px solid rgba(15, 80, 141, 0.08) !important;
        transition: all 0.3s ease !important;
    }

    .order-card:hover {
        transform: translateY(-4px) !important;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1) !important;
    }

    .order-card-header {
        display: flex !important;
        justify-content: space-between !important;
        align-items: start !important;
        margin-bottom: 25px !important;
        padding-bottom: 20px !important;
        border-bottom: 2px solid #f1f5f9 !important;
    }

    .order-info {
        flex: 1 !important;
    }

    .order-number {
        font-size: 20px !important;
        font-weight: 700 !important;
        color: #0f508d !important;
        margin-bottom: 8px !important;
    }

    .order-date {
        font-size: 14px !important;
        color: #64748b !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
    }

    .order-status-badge {
        padding: 8px 16px !important;
        border-radius: 20px !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
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

    .order-items {
        margin-bottom: 20px !important;
    }

    .order-item {
        display: flex !important;
        gap: 15px !important;
        padding: 15px 0 !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    .order-item:last-child {
        border-bottom: none !important;
    }

    .order-item-image {
        width: 80px !important;
        height: 80px !important;
        border-radius: 12px !important;
        object-fit: cover !important;
        border: 2px solid #f1f5f9 !important;
    }

    .order-item-details {
        flex: 1 !important;
    }

    .order-item-name {
        font-size: 16px !important;
        font-weight: 600 !important;
        color: #1e293b !important;
        margin-bottom: 6px !important;
    }

    .order-item-meta {
        font-size: 14px !important;
        color: #64748b !important;
    }

    .order-item-price {
        font-size: 18px !important;
        font-weight: 700 !important;
        color: #0f508d !important;
    }

    .order-card-footer {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding-top: 20px !important;
        border-top: 2px solid #f1f5f9 !important;
    }

    .order-total {
        font-size: 14px !important;
        color: #64748b !important;
    }

    .order-total strong {
        font-size: 22px !important;
        font-weight: 800 !important;
        color: #0f508d !important;
        margin-left: 10px !important;
    }

    .btn-view-order {
        padding: 12px 28px !important;
        background: linear-gradient(135deg, #0f508d 0%, #08437b 100%) !important;
        color: white !important;
        border: none !important;
        border-radius: 10px !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        transition: all 0.3s !important;
    }

    .btn-view-order:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(15, 80, 141, 0.3) !important;
        color: white !important;
    }

    .empty-orders {
        text-align: center !important;
        padding: 80px 20px !important;
        background: white !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06) !important;
    }

    .empty-orders i {
        font-size: 80px !important;
        color: #cbd5e1 !important;
        margin-bottom: 24px !important;
    }

    .empty-orders h2 {
        font-size: 28px !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 12px !important;
    }

    .empty-orders p {
        font-size: 16px !important;
        color: #64748b !important;
        margin-bottom: 30px !important;
    }

    .btn-shop-now {
        padding: 14px 32px !important;
        background: linear-gradient(135deg, #0f508d 0%, #08437b 100%) !important;
        color: white !important;
        border: none !important;
        border-radius: 12px !important;
        font-size: 16px !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        display: inline-block !important;
        transition: all 0.3s !important;
    }

    .btn-shop-now:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px rgba(15, 80, 141, 0.3) !important;
        color: white !important;
    }

    .pagination {
        display: flex !important;
        justify-content: center !important;
        gap: 10px !important;
        margin-top: 40px !important;
    }

    .pagination .page-link {
        padding: 10px 18px !important;
        border-radius: 10px !important;
        border: 2px solid #e2e8f0 !important;
        color: #64748b !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        transition: all 0.3s !important;
    }

    .pagination .page-link:hover {
        border-color: #0f508d !important;
        color: #0f508d !important;
    }

    .pagination .page-link.active {
        background: linear-gradient(135deg, #0f508d 0%, #08437b 100%) !important;
        color: white !important;
        border-color: #0f508d !important;
    }

    @media (max-width: 768px) {
        .order-card {
            padding: 20px !important;
        }

        .order-card-header {
            flex-direction: column !important;
            gap: 15px !important;
        }

        .order-card-footer {
            flex-direction: column !important;
            gap: 15px !important;
            text-align: center !important;
        }

        .order-item {
            flex-direction: column !important;
        }

        .order-item-image {
            width: 100% !important;
            height: 200px !important;
        }
    }
</style>
@endpush

@section('content')
<div class="orders-wrapper">
    <div class="orders-container">
        <div class="orders-header">
            <h1><i class="fa-solid fa-box"></i> My Orders</h1>
            <p>Track and manage all your orders</p>
        </div>

        @if($orders->count() > 0)
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-card-header">
                        <div class="order-info">
                            <div class="order-number">
                                <i class="fa-solid fa-hashtag"></i> {{ $order->order_number }}
                            </div>
                            <div class="order-date">
                                <i class="fa-regular fa-calendar"></i>
                                {{ $order->created_at->format('d M Y, h:i A') }}
                            </div>
                        </div>
                        <span class="order-status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="order-items">
                        @foreach($order->items->take(3) as $item)
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

                                <div class="order-item-details">
                                    <div class="order-item-name">{{ $item->product_name }}</div>
                                    <div class="order-item-meta">
                                        Qty: {{ $item->quantity }} × £{{ number_format($item->price, 2) }}
                                    </div>
                                </div>
                                <div class="order-item-price">
                                    £{{ number_format($item->subtotal, 2) }}
                                </div>
                            </div>
                        @endforeach

                        @if($order->items->count() > 3)
                            <div style="text-align: center; padding: 10px; color: #64748b; font-size: 14px;">
                                <i class="fa-solid fa-plus"></i> {{ $order->items->count() - 3 }} more item(s)
                            </div>
                        @endif
                    </div>

                    <div class="order-card-footer">
                        <div class="order-total">
                            Total: <strong>£{{ number_format($order->total, 2) }}</strong>
                        </div>
                        <a href="{{ route('orders.details', $order->order_number) }}" class="btn-view-order">
                            <i class="fa-solid fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="pagination">
                    @if($orders->onFirstPage())
                        <span class="page-link disabled"><i class="fa-solid fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" class="page-link">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="page-link {{ $orders->currentPage() == $page ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    @if($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="page-link">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="page-link disabled"><i class="fa-solid fa-chevron-right"></i></span>
                    @endif
                </div>
            @endif
        @else
            <div class="empty-orders">
                <i class="fa-solid fa-box-open"></i>
                <h2>No Orders Yet</h2>
                <p>You haven't placed any orders yet. Start shopping now!</p>
                <a href="{{ route('shop') }}" class="btn-shop-now">
                    <i class="fa-solid fa-shopping-cart"></i> Start Shopping
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
