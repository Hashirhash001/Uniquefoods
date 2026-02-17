@forelse($orders as $order)
    <tr>
        <td>
            <input type="checkbox" class="order-checkbox order-checkbox-item" data-order-id="{{ $order->id }}">
        </td>
        <td>
            <a href="{{ route('admin.orders.show', $order) }}" class="order-number">
                {{ $order->order_number }}
            </a>
        </td>
        <td>
            <div class="customer-info">
                <span class="customer-name">{{ $order->customer_name }}</span>
                <span class="customer-email">{{ $order->customer_email }}</span>
            </div>
        </td>
        <td>{{ $order->items_count }} items</td>
        <td><strong>£{{ number_format($order->total, 2) }}</strong></td>
        <td>
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <span class="badge badge-{{ $order->payment_status }}">
                    {{ ucfirst($order->payment_status) }}
                </span>
                <small style="color: #64748b;">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</small>
            </div>
        </td>
        <td>
            <span class="badge badge-{{ $order->status }}">
                {{ ucfirst($order->status) }}
            </span>
        </td>
        <td>
            <div style="display: flex; flex-direction: column; gap: 2px;">
                <span>{{ $order->created_at->format('M d, Y') }}</span>
                <small style="color: #64748b;">{{ $order->created_at->format('h:i A') }}</small>
            </div>
        </td>
        <td>
            <div class="action-buttons">
                <a href="{{ route('admin.orders.show', $order) }}" class="action-btn view" title="View Details">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('admin.orders.invoice', $order) }}" class="action-btn" title="Print Invoice" target="_blank">
                    <i class="fas fa-print"></i>
                </a>
                <button class="action-btn delete delete-order"
                        data-order-id="{{ $order->id }}"
                        data-order-number="{{ $order->order_number }}"
                        title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9">
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <div class="empty-title">No orders found</div>
                <div class="empty-text">Try adjusting your filters or search criteria</div>
            </div>
        </td>
    </tr>
@endforelse
