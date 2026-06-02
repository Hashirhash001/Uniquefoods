<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 9px;
        color: #111827;
        background: #fff;
    }

    .header {
        background: #08437b;
        color: #fff;
        padding: 14px 20px;
        margin-bottom: 14px;
        border-radius: 4px;
    }

    .header h1 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .header-meta {
        font-size: 8px;
        opacity: 0.85;
        display: flex;
        gap: 20px;
        margin-top: 4px;
    }

    .filters-bar {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 3px;
        padding: 6px 10px;
        font-size: 8px;
        color: #374151;
        margin-bottom: 12px;
    }

    .filters-bar strong { color: #08437b; }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 8px;
    }

    thead tr {
        background: #08437b;
        color: #fff;
    }

    thead th {
        padding: 6px 5px;
        text-align: left;
        font-weight: 600;
        font-size: 7.5px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    tbody tr:nth-child(even) { background: #f9fafb; }
    tbody tr:nth-child(odd)  { background: #fff; }

    tbody tr:hover { background: #eff6ff; }

    tbody td {
        padding: 5px 5px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .badge {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 7px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-active   { background: #d1fae5; color: #065f46; }
    .badge-inactive { background: #fee2e2; color: #991b1b; }
    .badge-stock    { background: #dbeafe; color: #1e40af; }
    .badge-low      { background: #fef3c7; color: #92400e; }
    .badge-out      { background: #fee2e2; color: #991b1b; }
    .badge-weight   { background: #ede9fe; color: #5b21b6; }

    .price-main { font-weight: 700; color: #111827; }
    .price-mrp  { text-decoration: line-through; color: #9ca3af; font-size: 7px; }

    .footer {
        margin-top: 14px;
        padding-top: 8px;
        border-top: 1px solid #e5e7eb;
        font-size: 7.5px;
        color: #9ca3af;
        display: flex;
        justify-content: space-between;
    }

    .summary-row {
        background: #f0f9ff !important;
        font-weight: 700;
        font-size: 8px;
        color: #08437b;
        border-top: 2px solid #08437b;
    }

    .text-right { text-align: right; }
    .text-center { text-align: center; }
</style>
</head>
<body>

<div class="header">
    <h1>🛒 Product Export — Unique Foods</h1>
    <div class="header-meta">
        <span>Exported: {{ $exportedAt }}</span>
        <span>Total Products: {{ $products->count() }}</span>
    </div>
</div>

<div class="filters-bar">
    <strong>Filters applied:</strong> {{ $filters }}
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>SKU</th>
            <th>Category</th>
            <th>Brand</th>
            <th class="text-right">Price</th>
            <th class="text-right">MRP</th>
            <th class="text-center">Stock</th>
            <th>Unit</th>
            <th class="text-center">Type</th>
            <th class="text-right">Tax %</th>
            <th class="text-center">Status</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalStock = 0;
            $totalValue = 0;
        @endphp

        @foreach($products as $i => $product)
            @php
                $totalStock += $product->stock;
                $totalValue += $product->price * $product->stock;
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $product->name }}</strong></td>
                <td style="color:#6b7280;">{{ $product->sku }}</td>
                <td>{{ $product->category->name ?? '—' }}</td>
                <td>{{ $product->brand->name ?? '—' }}</td>
                <td class="text-right">
                    <span class="price-main">£{{ number_format($product->price, 2) }}</span>
                </td>
                <td class="text-right">
                    @if($product->mrp && $product->mrp > $product->price)
                        <span class="price-mrp">£{{ number_format($product->mrp, 2) }}</span>
                    @else
                        —
                    @endif
                </td>
                <td class="text-center">
                    @if($product->stock > 10)
                        <span class="badge badge-stock">{{ $product->stock }}</span>
                    @elseif($product->stock > 0)
                        <span class="badge badge-low">{{ $product->stock }}</span>
                    @else
                        <span class="badge badge-out">0</span>
                    @endif
                </td>
                <td>{{ strtoupper($product->unit) }}</td>
                <td class="text-center">
                    @if($product->is_weight_based)
                        <span class="badge badge-weight">Weight</span>
                    @else
                        <span style="color:#9ca3af;">Qty</span>
                    @endif
                </td>
                <td class="text-right">{{ $product->tax_rate ?? '0' }}%</td>
                <td class="text-center">
                    @if($product->is_active)
                        <span class="badge badge-active">Active</span>
                    @else
                        <span class="badge badge-inactive">Inactive</span>
                    @endif
                </td>
                <td style="color:#6b7280;">{{ $product->created_at->format('d/m/Y') }}</td>
            </tr>
        @endforeach

        {{-- Summary row --}}
        <tr class="summary-row">
            <td colspan="7" class="text-right">Totals →</td>
            <td class="text-center">{{ number_format($totalStock) }}</td>
            <td colspan="3"></td>
            <td colspan="2">Stock Value: £{{ number_format($totalValue, 2) }}</td>
        </tr>
    </tbody>
</table>

<div class="footer">
    <span>Unique Foods — Product Report</span>
    <span>Generated {{ $exportedAt }}</span>
</div>

</body>
</html>
