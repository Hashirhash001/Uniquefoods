@forelse($customers as $customer)
    <tr>
        <td>
            <div class="d-flex align-items-center gap-2">
                @if($customer->avatar)
                    <img src="{{ $customer->avatar }}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                @else
                    <div class="avatar-circle">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                @endif
                <div style="min-width:0;">
                    <div class="customer-name">{{ $customer->name }}</div>
                    @if($customer->mobile)
                        <div class="customer-phone">{{ $customer->mobile }}</div>
                    @endif
                </div>
            </div>
        </td>
        <td>
            <div class="customer-email">{{ $customer->email }}</div>
        </td>
        <td style="white-space:nowrap;color:#6b7280;font-size:12px;">
            {{ $customer->created_at->format('d M Y') }}
        </td>
        <td>
            <span style="font-weight:700;color:#08437b;font-size:15px;">{{ $customer->orders_count }}</span>
        </td>
        <td style="font-weight:700;white-space:nowrap;">
            £{{ number_format($customer->orders_sum_total, 2) }}
        </td>
        <td style="color:#6b7280;font-size:12px;white-space:nowrap;">
            {{ $customer->orders->first()?->created_at->diffForHumans() ?? '—' }}
        </td>
        <td>
            @if($customer->email_verified_at)
                <span class="badge-status verified">Verified</span>
            @else
                <span class="badge-status unverified">Unverified</span>
            @endif
        </td>
        <td>
            <a href="{{ route('admin.customers.show', $customer) }}" class="view-btn">View</a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-5" style="color:#9ca3af;">
            <i class="fas fa-users" style="font-size:36px;opacity:0.2;display:block;margin-bottom:10px;"></i>
            No customers yet
        </td>
    </tr>
@endforelse
