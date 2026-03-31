<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .header {
            background: #f1f5f9;
            padding: 8px 5px;
            text-align: center;
        }
        .header img {
            height: 50px;
            max-width: 200px;
        }
        .body {
            padding: 40px 32px;
            color: #475569; /* LIGHTER than 1e293b */
        }
        .status-badge {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 15px;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .shipped   {
            background: #dbeafe;
            color: #2563eb;
            border: 2px solid #bfdbfe;
        }
        .delivered {
            background: #d1fae5;
            color: #059669;
            border: 2px solid #a7f3d0;
        }
        .cancelled {
            background: #fee2e2;
            color: #dc2626;
            border: 2px solid #fecaca;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
            background: #f8fafc;
            border-radius: 12px;
            overflow: hidden;
        }
        .details-table td {
            padding: 16px 20px;
            font-size: 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        .details-table td:last-child {
            text-align: right;
            font-weight: 600;
            color: #1e40af;
        }
        .details-table tr:last-child td { border-bottom: none; }
        .footer {
            background: #f1f5f9;
            padding: 24px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
        }
        .btn {
            display: inline-block;
            margin-top: 28px;
            padding: 14px 32px;
            background: #3b82f6;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }
        h1, h2, h3 {
            color: #334155; /* LIGHTER than 1e293b */
        }
        p {
            line-height: 1.6;
            margin-bottom: 16px;
            color: #475569;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <img src="{{ url('/admin/assets/images/logo/unique-food-logo3.png') }}"
             alt="Unique Foods"
             style="max-height:50px; max-width:200px;">
    </div>

    <div class="body">
        <p>Hi <strong style="color: #334155;">{{ $order->customer_name }}</strong>,</p>

        @if ($newStatus === 'shipped')
            <p>🎉 Great news! Your order has been <strong style="color: #1e40af;">shipped</strong> and is on its way to you.</p>
        @elseif ($newStatus === 'delivered')
            <p>✅ Your order has been marked as <strong style="color: #059669;">delivered</strong>. We hope you enjoy your purchase!</p>
        @elseif ($newStatus === 'cancelled')
            <p>We're sorry to let you know that your order has been <strong style="color: #dc2626;">cancelled</strong>.</p>
            @if ($order->admin_notes)
                <p><strong style="color: #475569;">Reason:</strong> {{ $order->admin_notes }}</p>
            @endif
        @endif

        <span class="status-badge {{ $newStatus }}">
            {{ ucfirst($newStatus) }}
        </span>

        <table class="details-table">
            <tr>
                <td>Order Number</td>
                <td>#{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td>Order Date</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
            </tr>
            <tr>
                <td>Total Amount</td>
                <td>£{{ number_format($order->total, 2) }}</td>
            </tr>
            <tr>
                <td>Payment Method</td>
                <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
            </tr>
        </table>

        <a href="{{ url('/orders/' . $order->order_number) }}" class="btn" style="color: #ffffff">View Your Order</a>

        <p style="font-size: 14px; color: #64748b; margin-top: 32px;">
            <strong>📎 Invoice attached</strong> — download for your records.
        </p>
    </div>

    <div class="footer">
        © {{ date('Y') }} Unique Foods. All rights reserved.<br>
        If you have questions, reply to this email or contact our support team.
    </div>
</div>
</body>
</html>
