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

    @media (max-width: 767px) {

        /* Wrapper */
        .order-details-wrapper {
            padding: 20px 0 calc(80px + env(safe-area-inset-bottom, 0px)) 0 !important;
        }

        .order-details-container {
            padding: 0 14px !important;
        }

        /* Back button */
        .back-button {
            font-size: 13px !important;
            margin-bottom: 16px !important;
        }

        /* Main card */
        .order-details-card {
            padding: 16px !important;
            border-radius: 12px !important;
        }

        /* Order header — number left, status right */
        .order-header {
            flex-direction: row !important;
            align-items: flex-start !important;
            justify-content: space-between !important;
            gap: 10px !important;
            margin-bottom: 20px !important;
            padding-bottom: 16px !important;
        }

        .order-header h1 {
            font-size: 16px !important;
            margin-bottom: 5px !important;
            word-break: break-all !important;
        }

        .order-date {
            font-size: 12px !important;
        }

        .status-badge {
            padding: 6px 12px !important;
            font-size: 11px !important;
            border-radius: 14px !important;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
        }

        /* Info grid — single column */
        .order-info-grid {
            grid-template-columns: 1fr !important;
            gap: 12px !important;
            margin-bottom: 20px !important;
        }

        .info-section {
            padding: 16px !important;
            border-radius: 10px !important;
        }

        .info-section h3 {
            font-size: 14px !important;
            margin-bottom: 10px !important;
        }

        .info-section h3 i {
            font-size: 15px !important;
        }

        .info-section p {
            font-size: 13px !important;
            line-height: 1.6 !important;
        }

        /* Order items section */
        .order-items-section {
            margin-bottom: 24px !important;
        }

        .order-items-section h3 {
            font-size: 16px !important;
            margin-bottom: 14px !important;
        }

        /* Each item — horizontal, compact */
        .order-item {
            flex-direction: row !important;
            gap: 12px !important;
            padding: 12px 0 !important;
            align-items: flex-start !important;
        }

        .order-item-image {
            width: 70px !important;
            height: 70px !important;
            border-radius: 8px !important;
            flex-shrink: 0 !important;
        }

        .order-item-info {
            flex: 1 !important;
            min-width: 0 !important;
        }

        .order-item-name {
            font-size: 13px !important;
            font-weight: 600 !important;
            margin-bottom: 5px !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
        }

        .order-item-meta {
            font-size: 11px !important;
        }

        /* Price — right aligned, stacked */
        .order-item-price {
            text-align: right !important;
            flex-shrink: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-end !important;
            justify-content: center !important;
        }

        .order-item-price .unit-price {
            font-size: 11px !important;
            margin-bottom: 3px !important;
        }

        .order-item-price .total-price {
            font-size: 15px !important;
            font-weight: 700 !important;
        }

        /* Order summary */
        .order-summary {
            padding: 16px !important;
            border-radius: 10px !important;
        }

        .order-summary h3 {
            font-size: 16px !important;
            margin-bottom: 14px !important;
        }

        .summary-row {
            font-size: 13px !important;
            padding: 9px 0 !important;
        }

        .summary-row.total {
            font-size: 17px !important;
            padding-top: 14px !important;
            margin-top: 10px !important;
        }

        .payment-badge {
            font-size: 12px !important;
            padding: 7px 14px !important;
            margin-top: 12px !important;
            border-radius: 14px !important;
        }
    }

    @media (max-width: 400px) {

        /* Very small — order header stacks */
        .order-header {
            flex-direction: column !important;
            gap: 8px !important;
        }

        .order-item-image {
            width: 58px !important;
            height: 58px !important;
        }

        .order-item-name {
            font-size: 12px !important;
        }

        .order-item-price .total-price {
            font-size: 13px !important;
        }
    }

    /* ── Cancel Confirmation Modal ── */
    .cancel-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
    }

    .cancel-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .cancel-modal {
        background: #fff;
        border-radius: 20px;
        padding: 36px 32px 28px;
        max-width: 420px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.18);
        transform: scale(0.92) translateY(10px);
        transition: transform 0.25s ease;
        text-align: center;
    }

    .cancel-modal-overlay.active .cancel-modal {
        transform: scale(1) translateY(0);
    }

    .cancel-modal-icon {
        width: 64px;
        height: 64px;
        background: #fee2e2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .cancel-modal-icon i {
        font-size: 28px;
        color: #dc2626;
    }

    .cancel-modal h2 {
        font-size: 20px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 10px;
    }

    .cancel-modal p {
        font-size: 14px;
        color: #64748b;
        line-height: 1.7;
        margin-bottom: 28px;
    }

    .cancel-modal p strong {
        color: #0f508d;
    }

    .cancel-modal-actions {
        display: flex;
        gap: 12px;
    }

    .cancel-modal-actions button {
        flex: 1;
        padding: 13px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }

    .btn-keep {
        background: #f1f5f9;
        color: #475569;
        border: 2px solid #e2e8f0 !important;
    }

    .btn-keep:hover {
        background: #e2e8f0;
    }

    .btn-confirm-cancel {
        background: #dc2626;
        color: #fff;
    }

    .btn-confirm-cancel:hover {
        background: #b91c1c;
    }

    .btn-confirm-cancel:disabled {
        background: #fca5a5;
        cursor: not-allowed;
    }

    /* Order number chip inside modal */
    .cancel-modal-order-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        border-radius: 99px;
        padding: 4px 14px;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    @media (max-width: 400px) {
        .cancel-modal {
            padding: 24px 18px 20px;
        }

        .cancel-modal h2 {
            font-size: 17px;
        }

        .cancel-modal-actions {
            flex-direction: column;
        }
    }

    .cod-method-badge {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        margin-top: 10px !important;
        padding: 14px 16px !important;
        border-radius: 12px !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #f8fafc !important;
    }

    .cod-method-badge i {
        font-size: 22px !important;
        color: #0f508d !important;
        width: 36px !important;
        height: 36px !important;
        background: rgba(15, 80, 141, 0.08) !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
    }

    .cod-method-text {
        display: flex !important;
        flex-direction: column !important;
        gap: 2px !important;
    }

    .cod-method-label {
        font-size: 11px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        color: #94a3b8 !important;
    }

    .cod-method-value {
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #1e293b !important;
    }

    @media (max-width: 767px) {
        .cod-method-badge {
            padding: 12px 14px !important;
        }

        .cod-method-badge i {
            font-size: 18px !important;
            width: 30px !important;
            height: 30px !important;
        }

        .cod-method-value {
            font-size: 13px !important;
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
                                onerror="this.src='{{ asset('frontend/assets/images/products/product-placeholder.svg') }}'">
                        @else
                            <img src="{{ asset('frontend/assets/images/products/product-placeholder.svg') }}"
                                alt="{{ $item->product_name }}"
                                class="order-item-image">
                        @endif

                        <div class="order-item-info">
                            <div class="order-item-name">{{ $item->product_name }}</div>
                            <div class="order-item-meta">
                                @if($item->weight && floatval($item->weight) > 0)
                                    {{-- Weight-based item --}}
                                    <span style="display:inline-flex; align-items:center; gap:5px;
                                                background:#eff6ff; color:#08437b; border:1px solid #bfdbfe;
                                                border-radius:99px; padding:2px 10px; font-size:12px; font-weight:700;">
                                        <i class="fa-regular fa-weight-scale"></i>
                                        {{ rtrim(rtrim(number_format(floatval($item->weight), 2), '0'), '.') }}kg
                                        &times; £{{ number_format($item->price, 2) }}/kg
                                    </span>
                                @else
                                    {{-- Standard qty item --}}
                                    Qty: {{ $item->quantity }}
                                    &times; £{{ number_format($item->price, 2) }} each
                                @endif
                            </div>
                        </div>

                        <div class="order-item-price">
                            @if($item->weight && floatval($item->weight) > 0)
                                <div class="unit-price">£{{ number_format($item->price, 2) }}/kg</div>
                            @else
                                <div class="unit-price">£{{ number_format($item->price, 2) }} each</div>
                            @endif
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

                {{-- COD Delivery Method --}}
                @if($order->payment_method === 'cash_on_delivery' && $order->cod_delivery_method)
                    <div class="cod-method-badge">
                        @if($order->cod_delivery_method === 'cash')
                            <i class="fa-solid fa-money-bills"></i>
                            <div class="cod-method-text">
                                <span class="cod-method-label">Pay by</span>
                                <span class="cod-method-value">Cash on Delivery</span>
                            </div>
                        @elseif($order->cod_delivery_method === 'bank_transfer')
                            <i class="fa-solid fa-building-columns"></i>
                            <div class="cod-method-text">
                                <span class="cod-method-label">Pay by</span>
                                <span class="cod-method-value">Bank Transfer on Delivery</span>
                            </div>
                        @endif
                    </div>
                @endif

                @if($order->status === 'pending')
                    <button
                        class="cancel-order-btn"
                        data-order="{{ $order->order_number }}"
                        data-url="{{ route('orders.cancel', $order->order_number) }}"
                        style="
                            margin-top: 16px;
                            width: 100%;
                            padding: 12px;
                            background: #fee2e2;
                            color: #991b1b;
                            border: 2px solid #fca5a5;
                            border-radius: 10px;
                            font-weight: 700;
                            font-size: 15px;
                            cursor: pointer;
                            transition: all 0.2s;
                        ">
                        <i class="fa-solid fa-xmark"></i> Cancel Order
                    </button>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
(function () {
    // ── Build modal DOM once ────────────────────────────────────────
    const overlay = document.createElement('div');
    overlay.className = 'cancel-modal-overlay';
    overlay.innerHTML = `
        <div class="cancel-modal" role="dialog" aria-modal="true" aria-labelledby="cancelModalTitle">
            <div class="cancel-modal-icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h2 id="cancelModalTitle">Cancel this order?</h2>
            <div class="cancel-modal-order-chip">
                <i class="fa-solid fa-hashtag"></i>
                <span id="cancelModalOrderNum"></span>
            </div>
            <p>This action <strong>cannot be undone</strong>. Your items will be restocked and the order will be permanently cancelled.</p>
            <div class="cancel-modal-actions">
                <button class="btn-keep" id="cancelModalKeep">
                    <i class="fa-solid fa-arrow-left"></i> Keep Order
                </button>
                <button class="btn-confirm-cancel" id="cancelModalConfirm">
                    <i class="fa-solid fa-xmark"></i> Yes, Cancel
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(overlay);

    const orderNumEl  = overlay.querySelector('#cancelModalOrderNum');
    const keepBtn     = overlay.querySelector('#cancelModalKeep');
    const confirmBtn  = overlay.querySelector('#cancelModalConfirm');

    let activeTriggerBtn = null;
    let cancelUrl        = null;

    // ── Open ───────────────────────────────────────────────────────
    function openModal(triggerBtn) {
        activeTriggerBtn = triggerBtn;
        cancelUrl        = triggerBtn.dataset.url;
        orderNumEl.textContent = triggerBtn.dataset.order;
        overlay.classList.add('active');
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = '<i class="fa-solid fa-xmark"></i> Yes, Cancel';
        keepBtn.focus();
    }

    // ── Close ──────────────────────────────────────────────────────
    function closeModal() {
        overlay.classList.remove('active');
        activeTriggerBtn = null;
        cancelUrl        = null;
    }

    // ── Dismiss on overlay click ───────────────────────────────────
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closeModal();
    });

    // ── Dismiss on Escape ──────────────────────────────────────────
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay.classList.contains('active')) closeModal();
    });

    keepBtn.addEventListener('click', closeModal);

    // ── Confirm cancel ─────────────────────────────────────────────
    confirmBtn.addEventListener('click', function () {
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Cancelling...';

        // ✅ Capture BEFORE closeModal() nulls them
        const triggerBtn = activeTriggerBtn;
        const url        = cancelUrl;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            closeModal();

            if (data.success) {
                // ✅ Update status badge text + class
                const badge = document.querySelector('.status-badge');
                if (badge) {
                    badge.className = 'status-badge status-cancelled';
                    badge.textContent = 'Cancelled';
                }

                // ✅ Remove the cancel button from DOM
                if (triggerBtn) triggerBtn.remove();

                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Could not cancel order.', 'error');
                if (triggerBtn) {
                    triggerBtn.disabled = false;
                    triggerBtn.innerHTML = '<i class="fa-solid fa-xmark"></i> Cancel Order';
                }
            }
        })
        .catch(() => {
            closeModal();
            showToast('Something went wrong. Please try again.', 'error');
            if (triggerBtn) {
                triggerBtn.disabled = false;
                triggerBtn.innerHTML = '<i class="fa-solid fa-xmark"></i> Cancel Order';
            }
        });
    });

    // ── Attach to cancel buttons ───────────────────────────────────
    document.querySelectorAll('.cancel-order-btn').forEach(btn => {
        btn.addEventListener('click', () => openModal(btn));
    });

    // ── Lightweight toast (replaces alert) ────────────────────────
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        const isSuccess = type === 'success';
        toast.style.cssText = `
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: ${isSuccess ? '#065f46' : '#991b1b'};
            color: #fff;
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 8px 24px rgba(0,0,0,0.18);
            z-index: 10000;
            opacity: 0;
            transition: all 0.3s ease;
            max-width: 90vw;
            text-align: center;
        `;
        toast.innerHTML = `<i class="fa-solid fa-${isSuccess ? 'circle-check' : 'circle-xmark'}"></i> ${message}`;
        document.body.appendChild(toast);

        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(-50%) translateY(0)';
        });

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(-50%) translateY(10px)';
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }
})();
</script>
@endpush
