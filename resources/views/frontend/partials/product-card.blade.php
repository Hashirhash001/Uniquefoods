@php
    $finalPrice = $product->final_price ?? (float)$product->price;
    $basePrice  = $product->base_price  ?? (float)$product->price;
    $discPct    = $product->discount_percentage_calc ?? $product->discount_percentage ?? 0;
    $avgRating  = (float)($product->average_rating ?? 0);
    $reviewsCnt = (int)($product->reviews_count ?? 0);
@endphp

<div class="shop-product-card">
    <div class="product-image-wrapper">
        <a href="{{ route('product.show', $product->slug) }}" class="product-image-link">
            @if($discPct > 0)
                <div class="product-badge-discount"><span>{{ $discPct }}% OFF</span></div>
            @endif
            @if($product->stock <= 5 && $product->stock > 0)
                <div class="product-badge-stock"><span>Only {{ $product->stock }} left</span></div>
            @endif
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-main-image"
                 onerror="this.src='/frontend/assets/images/grocery/01.jpg'">
        </a>
        <div class="product-quick-actions">
            <button class="quick-action-btn wishlist-toggle-btn {{ ($product->is_wishlisted ?? false) ? 'active' : '' }}"
                    title="{{ ($product->is_wishlisted ?? false) ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
                    data-product-id="{{ $product->id }}">
                <i class="{{ ($product->is_wishlisted ?? false) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
            </button>
            <a href="{{ route('product.show', $product->slug) }}"
               class="quick-action-btn shop-quick-view-btn" title="View Details">
                <i class="fa-regular fa-eye"></i>
            </a>
        </div>
    </div>

    <div class="product-info">
        <div class="product-meta">
            <span class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</span>
            @if($product->brand)
                <span class="meta-separator">•</span>
                <span class="product-brand">{{ $product->brand->name }}</span>
            @endif
        </div>

        <a href="{{ route('product.show', $product->slug) }}" class="product-name-link">
            <h4 class="product-name">{{ $product->name }}</h4>
        </a>

        {{-- Real rating --}}
        @if($reviewsCnt > 0)
            <div class="product-rating">
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-{{ $i <= round($avgRating) ? 'solid' : 'regular' }} fa-star"></i>
                    @endfor
                </div>
                <span class="rating-count">({{ number_format($avgRating, 1) }})</span>
            </div>
        @else
            <div class="product-rating">
                <div class="stars">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <span class="rating-count">(0.0)</span>
            </div>
        @endif

        <div class="product-price">
            <span class="price-current">£{{ number_format($finalPrice, 2) }}</span>
            @if($basePrice > $finalPrice)
                <span class="price-original">£{{ number_format($basePrice, 2) }}</span>
                <span class="price-save">Save £{{ number_format($basePrice - $finalPrice, 2) }}</span>
            @endif
        </div>

        {{-- ✅ Weight based vs standard --}}
        @if($product->stock > 0)
            @if($product->is_weight_based)
                <a href="{{ route('product.show', $product->slug) }}" class="btn-select-weight">
                    <i class="fa-regular fa-weight-scale"></i>
                    <span>Select Weight</span>
                </a>
            @else
                <button class="product-add-to-cart add-to-cart-btn"
                        data-product-id="{{ $product->id }}">
                    <i class="fa-regular fa-cart-shopping"></i>
                    <span>Add to Cart</span>
                </button>
            @endif
        @else
            <button class="product-add-to-cart disabled" disabled>
                <i class="fa-solid fa-circle-xmark"></i>
                <span>Out of Stock</span>
            </button>
        @endif
    </div>
</div>
