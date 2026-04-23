<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed – #{{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f3f4f6; color: #111827; }
        a { color: #0f508d; text-decoration: none; }

        .email-wrapper { max-width: 620px; margin: 32px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }

        /* Header */
        .email-header { background: #0f508d; padding: 8px 12px; text-align: center; }
        .email-header img { height: 48px; }
        .email-header h1 { color: white; font-size: 22px; margin-top: 16px; font-weight: 700; letter-spacing: -0.3px; }
        .email-header p { color: rgba(255,255,255,0.82); font-size: 14px; margin-top: 6px; }

        /* Hero banner */
        .email-hero { background: #f0f7ff; border-bottom: 1px solid #dbeafe; padding: 28px 32px; }
        .email-hero table { width: 100%; border-collapse: collapse; }
        .email-hero td { vertical-align: middle; }
        .hero-icon-cell { width: 60px; padding-right: 16px; }

        /* ✅ Hero icon — table-based centering, no flex */
        .hero-icon {
            width: 52px;
            height: 52px;
            background: #10b981;
            border-radius: 50%;
            text-align: center;
            line-height: 52px; /* vertically centers inline content */
        }

        /* ✅ Checkmark rendered as HTML entity — no SVG/fill issues */
        .hero-icon-check {
            color: white;
            font-size: 26px;
            font-weight: 900;
            line-height: 52px;
            display: inline-block;
            vertical-align: middle;
        }

        .hero-text h2 { font-size: 19px; font-weight: 700; color: #111827; margin: 0 0 4px; }
        .hero-text p { font-size: 14px; color: #6b7280; margin: 0; }

        /* Body */
        .email-body { padding: 32px; }

        /* Section */
        .section { margin-bottom: 28px; }
        .section-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: #6b7280; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 2px solid #f3f4f6; }

        /* Order meta */
        .order-meta-grid { width: 100%; border-collapse: collapse; }
        .order-meta-grid td { width: 50%; padding: 6px; vertical-align: top; }
        .meta-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px 16px; }
        .meta-label { font-size: 11px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; display: block; }
        .meta-value { font-size: 14px; font-weight: 700; color: #111827; margin-top: 4px; display: block; }

        /* Items table */
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th { font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #9ca3af; font-weight: 600; padding: 10px 12px; background: #f9fafb; border: 1px solid #e5e7eb; text-align: left; }
        .items-table td { padding: 14px 12px; border: 1px solid #f3f4f6; font-size: 14px; vertical-align: middle; }
        .items-table tr:nth-child(even) td { background: #fafafa; }
        .item-name { font-weight: 600; color: #111827; }
        .item-meta { font-size: 12px; color: #6b7280; margin-top: 3px; }
        .item-price { font-weight: 700; color: #0f508d; white-space: nowrap; }

        /* Totals */
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 9px 12px; font-size: 14px; }
        .totals-table td:last-child { text-align: right; font-weight: 600; }
        .totals-table .total-row td { background: #0f508d; color: white; font-size: 16px; font-weight: 700; padding: 13px 16px; }
        .free-shipping { color: #10b981; font-weight: 700; }

        /* Address box */
        .address-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px 18px; font-size: 14px; line-height: 1.7; color: #374151; }
        .address-name { font-weight: 700; color: #111827; font-size: 15px; display: block; margin-bottom: 4px; }

        /* Payment badge */
        .payment-badge { display: inline-block; background: #fef3c7; border: 1px solid #fde68a; color: #92400e; padding: 10px 16px; border-radius: 8px; font-size: 14px; font-weight: 600; }

        /* CTA */
        .cta-section { text-align: center; margin: 32px 0 20px; }
        .cta-btn { display: inline-block; background: #0f508d; color: white !important; padding: 14px 36px; border-radius: 8px; font-size: 15px; font-weight: 700; letter-spacing: 0.3px; text-decoration: none; }

        /* ✅ What's Next — table-based, no flex/grid */
        .steps-table { width: 100%; border-collapse: separate; border-spacing: 8px 0; }
        .steps-table td { width: 33.33%; vertical-align: top; text-align: center; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px 10px; }

        /* ✅ Step number — line-height centering, no flex */
        .step-num {
            width: 28px;
            height: 28px;
            background: #0f508d;
            color: white;
            border-radius: 50%;
            font-size: 13px;
            font-weight: 700;
            line-height: 28px;        /* ✅ vertically centers the number */
            text-align: center;       /* ✅ horizontally centers the number */
            display: inline-block;    /* ✅ respects width/height */
            margin: 0 auto 8px;
        }

        .step-label { font-size: 12px; color: #374151; font-weight: 600; display: block; margin-top: 6px; }
        .step-sub { font-size: 11px; color: #9ca3af; margin-top: 3px; display: block; }

        /* Footer */
        .email-footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 24px 32px; text-align: center; }
        .footer-links { margin-bottom: 12px; }
        .footer-links a { font-size: 13px; color: #6b7280; margin: 0 10px; }
        .footer-copy { font-size: 12px; color: #9ca3af; line-height: 1.6; }
        .footer-brand { font-size: 13px; font-weight: 700; color: #0f508d; margin-bottom: 6px; }

        @media (max-width: 520px) {
            .email-body { padding: 20px; }
            .email-header { padding: 20px; }
            .order-meta-grid td { display: block; width: 100%; }
            .steps-table td { display: block; width: 100% !important; margin-bottom: 8px; }
            .items-table th:nth-child(3),
            .items-table td:nth-child(3) { display: none; }
        }
    </style>
</head>
<body>
<div class="email-wrapper">

    {{-- ── HEADER ── --}}
    <div class="email-header">
        <img src="{{ url('/admin/assets/images/logo/unique-food-logo3.png') }}"
            alt="Unique Foods"
            style="max-height:50px; max-width:200px;">
        <h1>Order Confirmed!</h1>
        <p>Thank you for shopping with Unique Foods</p>
    </div>

    {{-- ── HERO — table layout so icon aligns in all email clients ── --}}
    <div class="email-hero">
        <table>
            <tr>
                <td class="hero-icon-cell">
                    {{-- ✅ &#10003; = ✓ checkmark, works everywhere without SVG --}}
                    <div class="hero-icon">
                        <span class="hero-icon-check">&#10003;</span>
                    </div>
                </td>
                <td class="hero-text">
                    <h2>Hi {{ $order->customer_name }}, your order is confirmed!</h2>
                    <p>We've received your order and it's being prepared for delivery.</p>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── BODY ── --}}
    <div class="email-body">

        {{-- Order Details --}}
        <div class="section">
            <div class="section-title">Order Details</div>
            {{-- ✅ Table grid instead of CSS grid --}}
            <table class="order-meta-grid">
                <tr>
                    <td>
                        <div class="meta-box">
                            <span class="meta-label">Order Number</span>
                            <span class="meta-value">#{{ $order->order_number }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="meta-box">
                            <span class="meta-label">Order Date</span>
                            <span class="meta-value">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="meta-box">
                            <span class="meta-label">Payment Method</span>
                            <span class="meta-value">Cash on Delivery</span>
                        </div>
                    </td>
                    <td>
                        <div class="meta-box">
                            <span class="meta-label">Order Status</span>
                            <span class="meta-value" style="color:#10b981;">Confirmed</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Items Ordered --}}
        <div class="section">
            <div class="section-title">Items Ordered</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty / Weight</th>
                        <th>Unit Price</th>
                        <th>VAT</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    @php
                        $isWeight  = $item->weight && floatval($item->weight) > 0;
                        $weightFmt = $isWeight
                            ? rtrim(rtrim(number_format(floatval($item->weight), 2), '0'), '.')
                            : null;
                    @endphp
                    <tr>
                        <td>
                            <div class="item-name">{{ $item->product_name }}</div>
                            @if($item->product_sku)
                                <div class="item-meta">SKU: {{ $item->product_sku }}</div>
                            @endif
                        </td>
                        <td>{{ $isWeight ? $weightFmt . ' kg' : '× ' . $item->quantity }}</td>
                        <td>£{{ number_format($item->price, 2) }}{{ $isWeight ? '/kg' : '' }}</td>
                        <td style="color:#6b7280;">
                            {{ ($item->vat_rate ?? 0) > 0 ? number_format($item->vat_rate, 0).'%' : '—' }}
                        </td>
                        <td class="item-price">£{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Order Summary --}}
        <div class="section">
            <div class="section-title">Order Summary</div>
            <table class="totals-table">
                <tr>
                    <td style="color:#6b7280;">Subtotal</td>
                    <td>£{{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td style="color:#6b7280;">Shipping</td>
                    <td>
                        @if($order->shipping_cost > 0)
                            £{{ number_format($order->shipping_cost, 2) }}
                        @else
                            <span class="free-shipping">FREE</span>
                        @endif
                    </td>
                </tr>

                {{-- ── Per-rate VAT breakdown from order items ── --}}
                @php
                    $taxGroups = [];
                    foreach ($order->items as $item) {
                        $rate    = (float) ($item->vat_rate ?? 0);
                        $lineTax = (float) ($item->vat_amount ?? 0);
                        if ($lineTax <= 0) continue;
                        if (!isset($taxGroups[$rate])) {
                            $taxGroups[$rate] = ['amount' => 0, 'names' => []];
                        }
                        $taxGroups[$rate]['amount']  += $lineTax;
                        $taxGroups[$rate]['names'][]  = $item->product_name;
                    }
                    ksort($taxGroups);
                @endphp

                @if(count($taxGroups) === 0)
                    <tr>
                        <td style="color:#6b7280;">VAT</td>
                        <td>£0.00</td>
                    </tr>
                @elseif(count($taxGroups) === 1)
                    @foreach($taxGroups as $rate => $group)
                        <tr>
                            <td style="color:#6b7280;">
                                VAT ({{ (int)$rate }}%)
                            </td>
                            <td>£{{ number_format($group['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    {{-- Multiple rates — show each with product names --}}
                    @foreach($taxGroups as $rate => $group)
                        <tr>
                            <td style="color:#6b7280; font-size:13px;">
                                VAT ({{ (int)$rate }}%)
                                <span style="display:block; font-size:11px; color:#9ca3af; margin-top:2px;">
                                    {{ implode(', ', array_map(fn($n) => \Illuminate\Support\Str::limit($n, 22), array_unique($group['names']))) }}
                                </span>
                            </td>
                            <td>£{{ number_format($group['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                    {{-- Total VAT row when mixed rates --}}
                    <tr>
                        <td style="color:#6b7280; font-weight:700;">Total VAT</td>
                        <td style="font-weight:700;">£{{ number_format($order->tax, 2) }}</td>
                    </tr>
                @endif

                <tr class="total-row">
                    <td>Total Charged</td>
                    <td>£{{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
        </div>

        {{-- Delivery Address --}}
        <div class="section">
            <div class="section-title">Delivery Address</div>
            <div class="address-box">
                <span class="address-name">{{ $order->customer_name }}</span>
                {{ $order->shipping_address }}<br>
                @if($order->restaurant_store)
                    {{ $order->restaurant_store }}<br>
                @endif
                {{ $order->shipping_city }}, {{ $order->shipping_postcode }}<br>
                {{ $order->shipping_country }}<br>
                <span style="color:#6b7280;">&#128222; {{ $order->customer_phone }}</span>
            </div>
        </div>

        {{-- Payment --}}
        <div class="section">
            <div class="section-title">Payment</div>
            <div class="payment-badge">
                &#128181; Cash on Delivery &mdash; Pay when your order arrives
            </div>
        </div>

        {{-- What's Next — ✅ pure table, no CSS grid/flex --}}
        <div class="section">
            <div class="section-title">What Happens Next?</div>
            <table class="steps-table">
                <tr>
                    <td>
                        <div class="step-num">1</div>
                        <span class="step-label">Order Processing</span>
                        <span class="step-sub">We're preparing your items</span>
                    </td>
                    <td>
                        <div class="step-num">2</div>
                        <span class="step-label">Out for Delivery</span>
                        <span class="step-sub">Your order is on its way</span>
                    </td>
                    <td>
                        <div class="step-num">3</div>
                        <span class="step-label">Delivered</span>
                        <span class="step-sub">Pay &amp; enjoy your order</span>
                    </td>
                </tr>
            </table>
        </div>

        {{-- CTA --}}
        <div class="cta-section">
            <a href="{{ route('orders.details', $order->order_number) }}" class="cta-btn">
                View My Order
            </a>
        </div>

    </div>

    {{-- ── FOOTER ── --}}
    <div class="email-footer">
        <div class="footer-brand">Unique Foods</div>
        <div class="footer-links">
            <a href="{{ route('shop') }}">Shop</a>
            <a href="{{ route('orders.index') }}">My Orders</a>
            <a href="mailto:info@unique-food.co.uk">Support</a>
        </div>
        <div class="footer-copy">
            &copy; {{ date('Y') }} Unique Foods. All rights reserved.<br>
            Questions? Email us at
            <a href="mailto:info@unique-food.co.uk">info@unique-food.co.uk</a>
            or call
            <a href="tel:+447425837716">+44 7425 837716</a>
        </div>
    </div>

</div>
</body>
</html>
