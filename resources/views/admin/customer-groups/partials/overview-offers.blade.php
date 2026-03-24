@if($offers->count())
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Offer Name</th>
                    <th>Type</th>
                    <th>Discount</th>
                    <th>Valid From</th>
                    <th>Valid Until</th>
                    <th>Applies To</th>
                    <th>Status</th>
                    <th style="text-align:center;width:80px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offers as $offer)
                <tr>
                    <td>
                        <div style="font-weight:500;color:#111827;">{{ $offer->offer_name }}</div>
                        <div style="font-size:12px;color:#9ca3af;">{{ ucfirst($offer->offer_type) }} offer</div>
                    </td>
                    <td>
                        <span class="pill pill-blue">{{ ucfirst($offer->offer_type) }}</span>
                    </td>
                    <td>
                        @if($offer->offer_price)
                            <span style="font-size:16px;font-weight:700;color:#08437b;">
                                £{{ number_format($offer->offer_price, 2) }}
                                <small style="font-size:11px;color:#6b7280;">fixed</small>
                            </span>
                        @else
                            <span style="font-size:16px;font-weight:700;color:#08437b;">
                                {{ number_format($offer->discount_value, 2) }}
                                <small style="font-size:11px;color:#6b7280;">
                                    {{ $offer->discount_type === 'percentage' ? '%' : '£' }} off
                                </small>
                            </span>
                        @endif
                    </td>
                    <td style="font-size:13px;">
                        {{ $offer->starts_at ? $offer->starts_at->format('d M Y') : '—' }}
                    </td>
                    <td style="font-size:13px;">
                        @if($offer->ends_at)
                            @php $expired = $offer->ends_at->isPast(); @endphp
                            <span class="{{ $expired ? 'pill pill-red' : 'pill pill-green' }}">
                                {{ $offer->ends_at->format('d M Y') }}
                            </span>
                        @else
                            <span class="pill pill-gray">No Expiry</span>
                        @endif
                    </td>
                    <td>
                        @if($offer->offer_type === 'product' && $offer->product)
                            <span class="pill pill-gray">{{ Str::limit($offer->product->name, 25) }}</span>
                        @elseif($offer->offer_type === 'category' && $offer->category)
                            <span class="pill pill-blue">{{ $offer->category->name }}</span>
                        @elseif($offer->offer_type === 'brand' && $offer->brand)
                            <span class="pill pill-blue" style="background:#ede9fe;color:#5b21b6;">{{ $offer->brand->name }}</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $isActive = $offer->starts_at && $offer->ends_at
                                && $offer->starts_at->lte(now())
                                && $offer->ends_at->gte(now());
                        @endphp
                        @if($isActive)
                            <span class="pill pill-green">Active</span>
                        @elseif($offer->ends_at && $offer->ends_at->isPast())
                            <span class="pill pill-red">Expired</span>
                        @else
                            <span class="pill pill-yellow">Upcoming</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <a href="{{ route('admin.customer-groups.product-offers', ['customerGroup' => $customerGroup->id]) }}"
                           class="icon-btn" title="Manage Offers">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($offers->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #e5e7eb;">
            {{ $offers->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @endif
@else
    <div class="empty-state">
        <i class="fas fa-percentage"></i>
        <h5>No offers yet</h5>
        <p>
            @if(request()->hasAny(['offer_search','offer_status']))
                Try adjusting your offer filters.
            @else
                No offers or discounts have been set up for this group yet.
            @endif
        </p>
    </div>
@endif
