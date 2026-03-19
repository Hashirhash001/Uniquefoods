@forelse($orders as $order)
<tr>
    <td><input type="checkbox" class="ord-cb" value="{{ $order->id }}" style="width:15px;height:15px;accent-color:#08437b;"></td>
    <td>
        <a href="{{ route('admin.orders.show', $order) }}"
           style="font-weight:700;color:#08437b;text-decoration:none;font-size:13px;">
            {{ $order->order_number }}
        </a>
    </td>
    <td>
        <div style="font-weight:600;color:#111827;font-size:13px;">{{ $order->customer_name }}</div>
        <div style="font-size:11px;color:#9ca3af;">{{ $order->customer_email }}</div>
    </td>
    <td style="color:#6b7280;">{{ $order->items_count }} items</td>
    <td style="font-weight:700;white-space:nowrap;">£{{ number_format($order->total,2) }}</td>
    <td>
        <span class="ob ob-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
        <div style="font-size:11px;color:#9ca3af;margin-top:3px;">{{ ucfirst(str_replace('_',' ',$order->payment_method)) }}</div>
    </td>
    <td><span class="ob ob-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
    <td>
        <div style="font-size:13px;color:#374151;">{{ $order->created_at->format('d M Y') }}</div>
        <div style="font-size:11px;color:#9ca3af;">{{ $order->created_at->format('h:i A') }}</div>
    </td>
    <td>
        <div style="display:flex;gap:5px;align-items:center;">
            <a href="{{ route('admin.orders.show', $order) }}" class="ab" title="View">
                <i class="fas fa-eye"></i>
            </a>
            {{-- Opens PDF in new tab ── --}}
            <a href="{{ route('admin.orders.invoice', $order) }}" class="ab ab-grn" title="PDF Invoice" target="_blank">
                <i class="fas fa-file-pdf"></i>
            </a>
            <button class="ab ab-red del-order"
                    data-id="{{ $order->id }}"
                    data-num="{{ $order->order_number }}"
                    title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="9">
        <div class="o-empty">
            <i class="fas fa-shopping-bag"></i>
            <h4>No orders found</h4>
            <p>Try adjusting your filters</p>
        </div>
    </td>
</tr>
@endforelse
