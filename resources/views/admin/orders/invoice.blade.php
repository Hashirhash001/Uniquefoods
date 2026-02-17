<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 40px;
            background: #f5f5f5;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 60px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 3px solid #2563eb;
        }

        .company-info h1 {
            font-size: 32px;
            color: #2563eb;
            margin-bottom: 10px;
        }

        .company-info p {
            color: #666;
            font-size: 14px;
            line-height: 1.8;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-meta h2 {
            font-size: 36px;
            color: #2563eb;
            margin-bottom: 10px;
        }

        .invoice-meta p {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }

        .invoice-meta .invoice-number {
            font-weight: bold;
            color: #333;
            font-size: 16px;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .detail-section {
            flex: 1;
        }

        .detail-section h3 {
            font-size: 14px;
            text-transform: uppercase;
            color: #2563eb;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .detail-section p {
            font-size: 14px;
            color: #666;
            line-height: 1.8;
        }

        .detail-section strong {
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .invoice-items {
            margin-bottom: 30px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table thead {
            background: #f8fafc;
        }

        .items-table th {
            padding: 15px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }

        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: #334155;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #e2e8f0;
        }

        .items-table th:last-child,
        .items-table td:last-child {
            text-align: right;
        }

        .invoice-summary {
            margin-left: auto;
            width: 300px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            font-size: 14px;
            color: #666;
        }

        .summary-row:not(:last-child) {
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-row.total {
            font-size: 20px;
            font-weight: bold;
            color: #1e293b;
            padding-top: 20px;
            margin-top: 10px;
            border-top: 3px solid #2563eb;
        }

        .invoice-footer {
            margin-top: 60px;
            padding-top: 30px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
        }

        .invoice-footer h3 {
            font-size: 14px;
            color: #2563eb;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .invoice-footer p {
            font-size: 13px;
            color: #666;
            line-height: 1.8;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-paid {
            background: #d1fae5;
            color: #059669;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-failed {
            background: #fee2e2;
            color: #dc2626;
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }

            .invoice-container {
                box-shadow: none;
                padding: 40px;
            }

            @page {
                margin: 20mm;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>Unique Foods</h1>
                <p>
                    123 Business Street<br>
                    Kerala, India 682001<br>
                    Phone: +91 1234567890<br>
                    Email: info@uniquefoods.com
                </p>
            </div>
            <div class="invoice-meta">
                <h2>INVOICE</h2>
                <p class="invoice-number">#{{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                <p>
                    <span class="status-badge status-{{ $order->payment_status }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="detail-section">
                <h3>Bill To</h3>
                <strong>{{ $order->customer_name }}</strong>
                <p>
                    {{ $order->customer_email }}<br>
                    {{ $order->customer_phone }}
                </p>
            </div>
            <div class="detail-section">
                <h3>Ship To</h3>
                <strong>{{ $order->customer_name }}</strong>
                <p>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_postcode }}<br>
                    {{ $order->shipping_country }}
                </p>
            </div>
            <div class="detail-section">
                <h3>Payment Method</h3>
                <p>
                    <strong>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</strong>
                </p>
                @if($order->paid_at)
                    <p style="margin-top: 10px;">
                        <small>Paid: {{ $order->paid_at->format('M d, Y h:i A') }}</small>
                    </p>
                @endif
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="invoice-items">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product_name }}</strong>
                                @if($item->weight)
                                    <br><small>Weight: {{ number_format($item->weight, 3) }} kg</small>
                                @endif
                            </td>
                            <td>{{ $item->product_sku ?? 'N/A' }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Invoice Summary -->
        <div class="invoice-summary">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>${{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Shipping:</span>
                <span>${{ number_format($order->shipping_cost, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Tax (20%):</span>
                <span>${{ number_format($order->tax, 2) }}</span>
            </div>
            @if($order->discount > 0)
                <div class="summary-row">
                    <span>Discount:</span>
                    <span style="color: #10b981;">-${{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
            <div class="summary-row total">
                <span>Total:</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Invoice Footer -->
        <div class="invoice-footer">
            <h3>Thank You For Your Business!</h3>
            <p>
                If you have any questions about this invoice, please contact us at<br>
                <strong>info@uniquefoods.com</strong> or call <strong>+91 1234567890</strong>
            </p>
            @if($order->customer_notes)
                <p style="margin-top: 20px;">
                    <strong>Customer Notes:</strong><br>
                    {{ $order->customer_notes }}
                </p>
            @endif
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
