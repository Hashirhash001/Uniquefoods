<!DOCTYPE html>
<html>
<body style="font-family:sans-serif;color:#111827;background:#f9fafb;padding:32px;">
<div style="max-width:560px;margin:0 auto;background:white;border-radius:12px;padding:32px;border:1px solid #e5e7eb;">

    <h2 style="color:#08437b;margin-bottom:4px;">Order Updated</h2>
    <p style="color:#6b7280;font-size:14px;">Order {{ $order->order_number }}</p>

    <p style="font-size:14px;">Hi <strong>{{ $order->customer_name }}</strong>,</p>
    <p style="font-size:14px;line-height:1.6;">
        Your order has been updated by our team. Please find the revised invoice attached.
        If you have any questions, please contact us.
    </p>

    <table style="width:100%;border-collapse:collapse;font-size:13px;margin:20px 0;">
        <thead>
            <tr style="background:#f3f4f6;">
                <th style="padding:10px;text-align:left;border-bottom:1px solid #e5e7eb;">Product</th>
                <th style="padding:10px;text-align:center;border-bottom:1px solid #e5e7eb;">Qty</th>
                <th style="padding:10px;text-align:center;border-bottom:1px solid #e5e7eb;">VAT %</th>
                <th style="padding:10px;text-align:right;border-bottom:1px solid #e5e7eb;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="padding:10px;border-bottom:1px solid #f3f4f6;">{{ $item->product->name ?? $item->product_name }}</td>
                <td style="padding:10px;text-align:center;border-bottom:1px solid #f3f4f6;">{{ $item->quantity }}</td>
                <td style="padding:10px;text-align:center;border-bottom:1px solid #f3f4f6;color:#6b7280;">
                    {{ ($item->vat_rate ?? 0) > 0 ? number_format($item->vat_rate, 0).'%' : '—' }}
                </td>
                <td style="padding:10px;text-align:right;border-bottom:1px solid #f3f4f6;">£{{ number_format($item->quantity * $item->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align:right;font-size:14px;">
        <div style="margin-bottom:6px;color:#6b7280;">Subtotal: £{{ number_format($order->subtotal, 2) }}</div>
        <div style="margin-bottom:6px;color:#6b7280;">Shipping: £{{ number_format($order->shipping_cost, 2) }}</div>
        @php $totalVat = $order->items->sum('vat_amount'); @endphp
        @if($totalVat > 0)
        <div style="margin-bottom:6px;color:#6b7280;">
            VAT: £{{ number_format($totalVat, 2) }}
        </div>
        @endif
        @if($order->discount > 0)
        <div style="margin-bottom:6px;color:#059669;">Discount: -£{{ number_format($order->discount, 2) }}</div>
        @endif
        <div style="font-size:16px;font-weight:800;color:#08437b;">
            Total: £{{ number_format($order->total, 2) }}
        </div>
    </div>

    <p style="font-size:12px;color:#9ca3af;margin-top:24px;border-top:1px solid #f3f4f6;padding-top:16px;">
        This is an automated message. Please do not reply directly to this email.
    </p>
</div>
</body>
</html>
