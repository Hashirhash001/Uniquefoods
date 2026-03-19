<tr>
    <td>
        <a href="{{ route('admin.orders.show', $order) }}"
           style="font-weight:700;color:#08437b;text-decoration:none;font-size:12px;">
            {{ $order->order_number }}
        </a>
    </td>
    <td style="color:#9ca3af;font-size:11px;white-space:nowrap;">
        {{ $order->created_at->format('d M Y') }}
    </td>
    <td>
        @foreach($order->items->take(2) as $item)
            <div style="font-size:11px;color:#374151;">{{ $item->product_name }} × {{ $item->quantity }}</div>
        @endforeach
        @if($order->items->count() > 2)
            <div style="font-size:10px;color:#9ca3af;">+{{ $order->items->count() - 2 }} more</div>
        @endif
    </td>
    <td style="font-weight:700;white-space:nowrap;">£{{ number_format($order->total, 2) }}</td>
    <td><span class="order-status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
    <td><span class="order-status status-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span></td>
</tr>
