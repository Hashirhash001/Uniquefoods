@php
    $maxSpent = $customers->max('orders_sum_total') ?: 1;
    $avatarColors = [
        ['bg' => '#eff6ff', 'text' => '#1d4ed8', 'border' => '#bfdbfe'],
        ['bg' => '#f5f3ff', 'text' => '#6d28d9', 'border' => '#ddd6fe'],
        ['bg' => '#ecfdf5', 'text' => '#065f46', 'border' => '#a7f3d0'],
        ['bg' => '#fff7ed', 'text' => '#c2410c', 'border' => '#fed7aa'],
        ['bg' => '#fdf2f8', 'text' => '#9d174d', 'border' => '#fbcfe8'],
        ['bg' => '#f0fdfa', 'text' => '#0f766e', 'border' => '#99f6e4'],
    ];
@endphp

@forelse($customers as $i => $c)
@php
    $spent    = $c->orders_sum_total ?? 0;
    $orders   = $c->orders_count    ?? 0;
    $isTop    = $c->id === $topSpenderId && $spent > 0;
    $barWidth = min(100, $maxSpent > 0 ? round(($spent / $maxSpent) * 100) : 0);
    $ac       = $avatarColors[$i % count($avatarColors)];
    $lastOrder= $c->orders->first();
@endphp

<tr class="ctr {{ $isTop ? 'ctr-top' : '' }}" data-id="{{ $c->id }}">

    {{-- ── Customer Info ── --}}
    <td class="td-customer">
        <div class="ci-wrap">

            {{-- Avatar --}}
            <div class="ci-av" style="background:{{ $ac['bg'] }};color:{{ $ac['text'] }};border-color:{{ $ac['border'] }};">
                {{ strtoupper(substr($c->name, 0, 1)) }}
                @if($isTop)
                    <div class="ci-crown">👑</div>
                @endif
            </div>

            {{-- Info --}}
            <div class="ci-info">
                <div class="ci-name-row">
                    <a href="{{ route('admin.customers.show', $c) }}" class="ci-name">
                        {{ $c->name }}
                    </a>
                    @if($isTop)
                        <span class="badge-top">Top Customer</span>
                    @endif
                    @if($c->created_at->gt(now()->subDays(7)))
                        <span class="badge-new">New</span>
                    @endif
                </div>
                <div class="ci-email">
                    <i class="fas fa-envelope"></i> {{ $c->email }}
                </div>
                @if($c->mobile)
                <div class="ci-phone">
                    <i class="fas fa-phone-alt"></i> {{ $c->mobile }}
                </div>
                @endif
            </div>
        </div>
    </td>

    {{-- ── Groups ── --}}
    <td class="td-groups">
        @forelse($c->groups as $g)
            @php
                $gcls = match($g->slug) {
                    'home-delivery' => 'g-hd',
                    'shop'          => 'g-sh',
                    'restaurant'    => 'g-rs',
                    default         => 'g-df',
                };
            @endphp
            <span class="gbadge {{ $gcls }}">
                <span class="gbadge-dot"></span>
                {{ $g->name }}
            </span>
        @empty
            <span class="no-group">Unassigned</span>
        @endforelse
    </td>

    {{-- ── Orders Count ── --}}
    <td class="td-orders">
        @if($orders > 0)
            <div class="ord-pill">
                <span class="ord-num">{{ $orders }}</span>
                <span class="ord-lbl">order{{ $orders !== 1 ? 's' : '' }}</span>
            </div>
            @if($lastOrder)
                <div class="ord-last">Last {{ $lastOrder->created_at->diffForHumans() }}</div>
            @endif
        @else
            <span class="ord-zero">No orders</span>
        @endif
    </td>

    {{-- ── Total Spent ── --}}
    <td class="td-spent">
        <div class="spent-amt {{ $isTop ? 'spent-top' : ($spent > 0 ? 'spent-has' : 'spent-none') }}">
            £{{ number_format($spent, 2) }}
        </div>
        @if($spent > 0)
            <div class="spent-bar-track">
                <div class="spent-bar-fill {{ $isTop ? 'bar-gold' : 'bar-blue' }}"
                     style="width:{{ $barWidth }}%"></div>
            </div>
            <div class="spent-pct">{{ $barWidth }}% of top</div>
        @endif
    </td>

    {{-- ── Joined ── --}}
    <td class="td-joined">
        <div class="joined-date">{{ $c->created_at->format('d M Y') }}</div>
        <div class="joined-rel">{{ $c->created_at->diffForHumans() }}</div>
    </td>

    {{-- ── Actions ── --}}
    <td class="td-actions">
        <div class="act-wrap">
            <a href="{{ route('admin.customers.show', $c) }}"
               class="act-btn act-view" title="View Profile">
                <i class="fas fa-eye"></i>
            </a>
            <button class="act-btn act-edit btn-edit"
                    data-id="{{ $c->id }}" title="Edit Customer">
                <i class="fas fa-pen"></i>
            </button>
            <button class="act-btn act-del btn-del"
                    data-id="{{ $c->id }}"
                    data-name="{{ $c->name }}"
                    title="Delete Customer">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </td>

</tr>
@empty
<tr>
    <td colspan="6">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="empty-title">No customers found</div>
            <div class="empty-sub">Try adjusting your search or filters</div>
        </div>
    </td>
</tr>
@endforelse
