<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size:13px; color:#1e293b; background:#fff; padding:30px; }
    .inv-header { display:table; width:100%; margin-bottom:30px; }
    .inv-logo-wrap { display:table-cell; vertical-align:top; width:50%; }
    .inv-logo-wrap h2 { font-size:22px; font-weight:800; color:#08437b; }
    .inv-logo-wrap p  { font-size:12px; color:#6b7280; margin-top:3px; }
    .inv-meta { display:table-cell; vertical-align:top; text-align:right; }
    .inv-meta h1 { font-size:26px; font-weight:800; color:#111827; letter-spacing:-0.5px; }
    .inv-meta p  { font-size:12px; color:#6b7280; margin-top:4px; }
    .inv-num  { font-size:14px; font-weight:700; color:#08437b; margin-top:6px; }

    hr.div { border:none; border-top:2px solid #e5e7eb; margin:18px 0; }

    .addr-row { display:table; width:100%; margin-bottom:24px; }
    .addr-box { display:table-cell; width:50%; vertical-align:top; }
    .addr-box h4 { font-size:11px; font-weight:700; text-transform:uppercase; color:#9ca3af; letter-spacing:0.05em; margin-bottom:8px; }
    .addr-box p  { font-size:13px; color:#374151; line-height:1.6; }

    .status-row { display:table; width:100%; margin-bottom:24px; }
    .s-cell { display:table-cell; text-align:center; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:10px; width:20%; }
    .s-cell-lbl { font-size:10px; font-weight:700; text-transform:uppercase; color:#9ca3af; margin-bottom:4px; }
    .s-cell-val { font-size:13px; font-weight:700; color:#111827; }

    table.items { width:100%; border-collapse:collapse; margin-bottom:20px; }
    table.items thead th {
        background:#f1f5f9; padding:9px 12px;
        font-size:11px; font-weight:700; text-transform:uppercase;
        color:#64748b; letter-spacing:0.04em; border-bottom:2px solid #e2e8f0;
        text-align:left;
    }
    table.items tbody td { padding:10px 12px; border-bottom:1px solid #f3f4f6; font-size:13px; color:#334155; }
    table.items tfoot td { padding:8px 12px; font-size:13px; color:#334155; }
    table.items tfoot tr.total-row td { font-size:15px; font-weight:800; color:#111827; border-top:2px solid #e2e8f0; padding-top:12px; }

    /* Bank details box */
    .bank-box { background:#f0f7ff; border:1px solid #bfdbfe; border-radius:6px; padding:14px 16px; margin-bottom:16px; }
    .bank-box h4 { font-size:11px; font-weight:700; text-transform:uppercase; color:#1e40af; letter-spacing:0.05em; margin-bottom:10px; }
    .bank-grid { display:table; width:100%; }
    .bank-row  { display:table-row; }
    .bank-lbl  { display:table-cell; font-size:12px; color:#6b7280; width:38%; padding:3px 0; }
    .bank-val  { display:table-cell; font-size:12px; font-weight:700; color:#1e293b; padding:3px 0; }

    .badge { padding:3px 9px; border-radius:12px; font-size:11px; font-weight:700; }
    .b-paid      { background:#d1fae5; color:#065f46; }
    .b-pending   { background:#fef3c7; color:#92400e; }
    .b-completed { background:#d1fae5; color:#065f46; }
    .b-delivered { background:#d1fae5; color:#065f46; }
    .b-cancelled { background:#fee2e2; color:#991b1b; }
    .b-shipped   { background:#ede9fe; color:#5b21b6; }

    .footer { margin-top:0px; padding-top:5px; border-top:1px solid #e5e7eb; text-align:center; font-size:11px; color:#9ca3af; }
</style>
</head>
<body>

    {{-- Header --}}
    <div class="inv-header">
        <div class="inv-logo-wrap">
            @if($logoData)
                <img src="{{ $logoData }}"
                    alt="{{ config('app.name') }}"
                    style="max-height:80px; max-width:200px;">
            @else
                <h2>{{ config('app.name') }}</h2>
            @endif
            <p style="margin-top:4px;">{{ config('app.url') }}</p>
        </div>
        <div class="inv-meta">
            <h1>INVOICE</h1>
            <div class="inv-num">{{ $order->order_number }}</div>
            <p>Issued: {{ $order->created_at->format('d M Y') }}</p>
        </div>
    </div>

    <hr class="div">

    {{-- Addresses --}}
    <div class="addr-row">
        <div class="addr-box">
            <h4>Billed To</h4>
            <p>
                <strong>{{ $order->customer_name }}</strong><br>
                {{ $order->customer_email }}<br>
                @if($order->customer_phone) {{ $order->customer_phone }}<br> @endif
                @if($order->shipping_address)
                    {{ $order->shipping_address }}<br>
                @endif
                @if($order->shipping_city) {{ $order->shipping_city }}, @endif
                @if($order->shipping_postcode) {{ $order->shipping_postcode }} @endif
            </p>
        </div>
        <div class="addr-box" style="text-align:right;">
            <h4>From</h4>
            <p>
                <strong>UNIQUE FOOD SERVICE LTD</strong><br>
                Unit 14 Redhills Industrial Estate<br>
                South Woodham Ferrers Essex<br>
                Chelmsford CM3 5UP<br>
                +44 7425 837716
            </p>
        </div>
    </div>

    {{-- Status summary --}}
    <div class="status-row">
        <div class="s-cell">
            <div class="s-cell-lbl">Order Status</div>
            <div class="s-cell-val">{{ ucfirst($order->status) }}</div>
        </div>
        <div class="s-cell">
            <div class="s-cell-lbl">Payment</div>
            <div class="s-cell-val">{{ ucfirst($order->payment_status) }}</div>
        </div>
        <div class="s-cell">
            <div class="s-cell-lbl">Method</div>
            <div class="s-cell-val">{{ ucfirst(str_replace('_',' ',$order->payment_method)) }}</div>
        </div>
        <div class="s-cell">
            <div class="s-cell-lbl">Payment Through</div>
            <div class="s-cell-val">{{ ucfirst(str_replace('_',' ',$order->cod_delivery_method)) }}</div>
        </div>
        <div class="s-cell">
            <div class="s-cell-lbl">Date</div>
            <div class="s-cell-val">{{ $order->created_at->format('d/m/Y') }}</div>
        </div>
    </div>

    {{-- Items table --}}
    <table class="items">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th style="text-align:center;">Qty</th>
                <th style="text-align:right;">Unit Price</th>
                <th style="text-align:right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
            <tr>
                <td style="color:#9ca3af;">{{ $i + 1 }}</td>
                <td><strong>{{ $item->product->name ?? $item->product_name ?? 'Product' }}</strong></td>
                <td style="text-align:center;">{{ $item->quantity }}</td>
                <td style="text-align:right;">£{{ number_format($item->price, 2) }}</td>
                <td style="text-align:right;font-weight:700;">£{{ number_format($item->quantity * $item->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;color:#6b7280;">Subtotal</td>
                <td style="text-align:right;">£{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->shipping_cost > 0)
            <tr>
                <td colspan="4" style="text-align:right;color:#6b7280;">Shipping</td>
                <td style="text-align:right;">£{{ number_format($order->shipping_cost, 2) }}</td>
            </tr>
            @endif
            @if($order->discount > 0)
            <tr>
                <td colspan="4" style="text-align:right;color:#059669;">Discount</td>
                <td style="text-align:right;color:#059669;">-£{{ number_format($order->discount, 2) }}</td>
            </tr>
            @endif
            @if($order->tax > 0)
            <tr>
                <td colspan="4" style="text-align:right;color:#6b7280;">Tax</td>
                <td style="text-align:right;">£{{ number_format($order->tax, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td colspan="4" style="text-align:right;">TOTAL</td>
                <td style="text-align:right;color:#08437b;">£{{ number_format($order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Bank Details --}}
    <div class="bank-box">
        <h4>&#x1F3E6; Bank Transfer Details</h4>
        <div class="bank-grid">

            <div class="bank-row">
                <div class="bank-lbl">Account Name</div>
                <div class="bank-val">UNIQUE FOOD SERVICE LTD</div>
            </div>

            <div class="bank-row">
                <div class="bank-lbl">Account Type</div>
                <div class="bank-val">Business</div>
            </div>

            <div class="bank-row">
                <div class="bank-lbl">Sort Code</div>
                <div class="bank-val">23-05-80</div>
            </div>

            <div class="bank-row">
                <div class="bank-lbl">Account Number</div>
                <div class="bank-val">39057425</div>
            </div>

            <div class="bank-row">
                <div class="bank-lbl">IBAN</div>
                <div class="bank-val">GB51MYMB23058039057425</div>
            </div>

            <div class="bank-row">
                <div class="bank-lbl">SWIFT / BIC</div>
                <div class="bank-val">MYMBGB2L</div>
            </div>

            <div class="bank-row">
                <div class="bank-lbl">Reference</div>
                <div class="bank-val">{{ $order->order_number }}</div>
            </div>

        </div>

        <p style="font-size:11px;color:#6b7280;margin-top:10px;">
            Please use your order number <strong>{{ $order->order_number }}</strong> as the payment reference.
            International payments may take 2–5 business days.
        </p>
    </div>

    @if($order->notes || $order->admin_notes)
    <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:12px;margin-bottom:16px;">
        @if($order->notes)
            <p style="font-size:12px;color:#6b7280;"><strong>Customer Note:</strong> {{ $order->notes }}</p>
        @endif
        @if($order->admin_notes)
            <p style="font-size:12px;color:#6b7280;margin-top:6px;"><strong>Admin Note:</strong> {{ $order->admin_notes }}</p>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>Thank you for your order! For questions, contact info@uniquefoodsonline.co.uk</p>
        <p style="margin-top:4px;">© {{ now()->year }} Unique Foods Online Ltd. All rights reserved. | Registered in England &amp; Wales</p>
    </div>

</body>
</html>
