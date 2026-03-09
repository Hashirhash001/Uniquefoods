@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')

{{-- Banner Slider - KEPT ORIGINAL --}}
<div class="banner-three-swiper-main-wrapper">
    <div class="swiper banner-swiper swiper-data" data-swiper='{
        "spaceBetween": 0,
        "slidesPerView": 1,
        "loop": true,
        "speed": 700,
        "effect": "fade",
        "navigation": {
            "nextEl": ".banner-button-next",
            "prevEl": ".banner-button-prev"
        },
        "pagination": {
            "el": ".banner-pagination",
            "clickable": true
        },
        "autoplay": {
            "delay": 5000
        }
    }'>
        <div class="swiper-wrapper">
            @forelse($banners as $banner)
                <div class="swiper-slide">
                    <div class="rts-section-gap rts-banner-area-three banner-bg-full1"
                         style="background-color: {{ $banner->background_color }};
                                @if($banner->image) background-image: url('{{ $banner->image_url }}'); background-size: cover; background-position: center; @endif">
                        <div class="container-2">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="banner-inner-content-three">
                                        @if($banner->subtitle)
                                            <span class="pre" style="color: ;">{{ $banner->subtitle }}</span>
                                        @endif

                                        <h1 class="title" style="color: {{ $banner->text_color }};">{!! nl2br(e($banner->title)) !!}</h1>

                                        @if($banner->description)
                                            <p class="dsicription" style="color: {{ $banner->text_color }};">{{ $banner->description }}</p>
                                        @endif

                                        @if($banner->button_text && $banner->button_link)
                                            <a href="{{ $banner->button_link }}" class="rts-btn btn-primary radious-sm with-icon">
                                                <div class="btn-text">{{ $banner->button_text }}</div>
                                                <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                                                <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide">
                    <div class="rts-section-gap rts-banner-area-three banner-bg-full1">
                        <div class="container-2">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="banner-inner-content-three">
                                        <span class="pre">Get up to 30% off on your first £150 purchase</span>
                                        <h1 class="title">Don't miss our amazing<br>grocery deals</h1>
                                        <p class="dsicription">We have prepared special discounts for you on grocery products. Don't miss these opportunities...</p>
                                        <a href="{{ route('shop') }}" class="rts-btn btn-primary radious-sm with-icon">
                                            <div class="btn-text">Shop Now</div>
                                            <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                                            <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        @if($banners->count() > 1)
            <button class="swiper-button-next banner-button-next"><i class="fa-regular fa-arrow-right"></i></button>
            <button class="swiper-button-prev banner-button-prev"><i class="fa-regular fa-arrow-left"></i></button>
        @endif

        <div class="banner-pagination"></div>
    </div>
</div>

{{-- Featured Categories Section - UPDATED WITH SHOP FILTERS --}}
<div class="rts-category-area rts-section-gapTop">
    <div class="container-2">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left mb--0">Featured Categories</h2>
                    @if($featuredCategories->count() > 7)
                        <div class="next-prev-swiper-wrapper">
                            <div class="swiper-button-prev category-button-prev"><i class="fa-regular fa-chevron-left"></i></div>
                            <div class="swiper-button-next category-button-next"><i class="fa-regular fa-chevron-right"></i></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="cover-card-main-over">
                    <div class="rts-caregory-area-one">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="category-area-main-wrapper-one">
                                        <div class="swiper category-swiper swiper-data" data-swiper='{
                                            "spaceBetween": 16,
                                            "slidesPerView": 7,
                                            "loop": false,
                                            "speed": 1000,
                                            "navigation": {
                                                "nextEl": ".category-button-next",
                                                "prevEl": ".category-button-prev"
                                            },
                                            "breakpoints": {
                                                "0": { "slidesPerView": 2, "spaceBetween": 12 },
                                                "320": { "slidesPerView": 2, "spaceBetween": 12 },
                                                "480": { "slidesPerView": 3, "spaceBetween": 12 },
                                                "640": { "slidesPerView": 4, "spaceBetween": 14 },
                                                "840": { "slidesPerView": 5, "spaceBetween": 14 },
                                                "1140": { "slidesPerView": 7, "spaceBetween": 16 }
                                            }
                                        }' style="padding: 20px;">
                                            <div class="swiper-wrapper">
                                                @forelse($featuredCategories as $category)
                                                    <div class="swiper-slide">
                                                        <div class="single-category-one">
                                                            {{-- ✅ Updated to use shop with category filter --}}
                                                            <a href="{{ route('shop') }}?categories[]={{ $category->id }}"
                                                               class="category-filter-link"
                                                               data-category-id="{{ $category->id }}"
                                                               data-category-name="{{ $category->name }}">
                                                                @if($category->image)
                                                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}">
                                                                @else
                                                                    <img src="{{ asset('frontend/assets/images/category/01.png') }}" alt="{{ $category->name }}">
                                                                @endif
                                                                <p>{{ $category->name }}</p>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="swiper-slide">
                                                        <div class="single-category-one">
                                                            <a href="{{ route('shop') }}">
                                                                <img src="{{ asset('frontend/assets/images/category/01.png') }}" alt="category">
                                                                <p>No Categories</p>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Featured Products Section - UPDATED WITH SHOP STYLE --}}
<div class="rts-section-gap rts-featured-products">
    <div class="container-2">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left mb--0">Featured Products</h2>
                    <a href="{{ route('shop') }}" class="view-all-link">View All <i class="fa-regular fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row g-4 mt--20">
            @forelse($products as $product)
                <div class="col-lg-20 col-md-4 col-sm-6 col-6">
                    @include('frontend.partials.product-card', ['product' => $product])
                </div>
            @empty
                <div class="col-12">
                    <div class="rts-empty-state">
                        <div class="empty-icon"><i class="fa-light fa-box-open"></i></div>
                        <h3>No Products Available</h3>
                        <p>Please check back later for amazing deals!</p>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</div>

{{-- Popular Products Section with Category Tabs - UPDATED WITH SHOP STYLE --}}
@if(isset($popularProducts) && $popularProducts->count() > 0)
<div class="rts-section-gap rts-popular-product-area">
    <div class="container-2">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left mb--0">Popular Products</h2>

                    {{-- Category Filter Tabs --}}
                    <ul class="nav nav-tabs filter-button-group" id="popularProductTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                                All
                                <span class="tab-count">{{ $popularProducts->count() }}</span>
                            </button>
                        </li>
                        @foreach($popularCategories as $category)
                            @php
                                $count = $popularProducts->where('category_id', $category->id)->count();
                            @endphp
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="cat-{{ $category->id }}-tab" data-bs-toggle="tab" data-bs-target="#cat-{{ $category->id }}" type="button" role="tab">
                                    {{ $category->name }}
                                    @if($count)
                                        <span class="tab-count">{{ $count }}</span>
                                    @endif
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="tab-content mt--30" id="popularProductTabContent">
            {{-- All Products Tab --}}
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                <div class="row g-4">
                    @foreach($popularProducts as $product)
                        <div class="col-lg-20 col-md-4 col-sm-6 col-6">
                            @include('frontend.partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Category Filtered Tabs --}}
            @foreach($popularCategories as $category)
                <div class="tab-pane fade" id="cat-{{ $category->id }}" role="tabpanel">
                    <div class="row g-4">
                        @foreach($popularProducts->where('category_id', $category->id) as $product)
                            <div class="col-lg-20 col-md-4 col-sm-6 col-6">
                                @include('frontend.partials.product-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            showLoader('');
        });
    </script>
@endpush

@push('styles')
<style>
    /* ============================================
    CONTAINER & LAYOUT (ORIGINAL)
    ============================================ */
    .container-2 {
        max-width: 1320px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .rts-section-gap,
    .rts-section-gapTop {
        padding-top: 60px;
        padding-bottom: 60px;
    }

    .rts-category-area {
        background: #fafafa;
        overflow: visible;
        padding-bottom: 80px;
    }

    .rts-featured-products {
        background: #ffffff;
    }

    .rts-popular-product-area {
        background: #f7f7f7;
    }

    /* ============================================
    TITLE AREA (ORIGINAL)
    ============================================ */
    .title-area-between {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .title-area-between .title-left {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .view-all-link {
        font-size: 14px;
        font-weight: 600;
        color: #08437b;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .view-all-link:hover {
        color: #4d7a1b;
        gap: 10px;
    }

    /* ============================================
    BANNER (ORIGINAL - KEPT INTACT)
    ============================================ */
    .banner-three-swiper-main-wrapper {
        position: relative;
        overflow: hidden;
    }

    .rts-banner-area-three {
        min-height: 670px;
        display: flex;
        align-items: center;
        position: relative;
        background-size: cover;
        background-position: center;
    }

    .rts-banner-area-three .container-2,
    .rts-banner-area-three .row,
    .rts-banner-area-three .col-lg-12 {
        position: relative;
        z-index: 2;
    }

    .banner-inner-content-three {
        position: relative;
        max-width: 560px;
        z-index: 3;
    }

    .banner-inner-content-three .pre {
        display: inline-flex;
        padding: 8px 16px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.95);
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .banner-inner-content-three .title {
        font-size: 48px;
        line-height: 1.1;
        font-weight: 800;
        margin-bottom: 16px;
    }

    .banner-inner-content-three .dsicription {
        font-size: 16px;
        max-width: 480px;
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .banner-button-next,
    .banner-button-prev {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        font-size: 18px;
        color: #111827;
    }

    .banner-button-next:hover,
    .banner-button-prev:hover {
        background: #08437b;
        color: white;
        transform: translateY(-50%) scale(1.08);
    }

    .banner-button-next {
        right: 32px;
    }

    .banner-button-prev {
        left: 32px;
    }

    .banner-pagination {
        position: absolute;
        left: 50%;
        bottom: 28px;
        transform: translateX(-50%);
        z-index: 10;
        display: flex;
        gap: 8px;
    }

    .banner-pagination .swiper-pagination-bullet {
        width: 10px;
        height: 10px;
        background: rgba(255, 255, 255, 0.6);
        opacity: 1;
        margin: 0;
        border-radius: 999px;
        transition: all 0.3s ease;
    }

    .banner-pagination .swiper-pagination-bullet-active {
        background: #08437b;
        width: 28px;
    }

    /* ============================================
    CATEGORY SECTION (ORIGINAL - KEPT INTACT)
    ============================================ */
    .next-prev-swiper-wrapper {
        display: flex;
        gap: 10px;
    }

    .category-button-next,
    .category-button-prev {
        width: 42px;
        height: 42px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 16px;
        color: #111827;
    }

    .category-button-next:hover,
    .category-button-prev:hover {
        background: #08437b;
        color: white;
        border-color: #08437b;
        transform: scale(1.08);
    }

    .category-area-main-wrapper-one {
        padding: 0 4px;
    }

    .swiper-slide {
        height: auto;
    }

    .single-category-one {
        background: #ffffff;
        border-radius: 14px;
        padding: 20px 16px;
        text-align: center;
        border: 1px solid #e5e7eb;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease,
                    border-color 0.25s ease;
        position: relative;
        z-index: 1;
    }

    .single-category-one:hover {
        border-color: #08437b;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
        transform: translateY(-4px);
        z-index: 10;
    }

    .single-category-one a {
        text-decoration: none;
        display: block;
    }

    .single-category-one img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        margin: 0 auto 14px;
        display: block;
    }

    .single-category-one p {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        margin: 0;
        line-height: 1.4;
    }

    .single-category-one:hover p {
        color: #08437b;
    }

    /* ============================================
    NEW SHOP-STYLE PRODUCT CARDS
    ============================================ */
    :root {
        --shop-primary: #08437b;
        --shop-primary-dark: #518219;
        --shop-success: #10b981;
        --shop-danger: #ef4444;
        --shop-text: #111827;
        --shop-text-light: #6b7280;
        --shop-bg: #ffffff;
        --shop-bg-alt: #f9fafb;
        --shop-border: #e5e7eb;
        --shop-shadow-lg: 0 10px 40px rgba(0,0,0,0.12);
    }

    .shop-product-card {
        background: var(--shop-bg);
        border: 1px solid var(--shop-border);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .shop-product-card:hover {
        border-color: var(--shop-primary);
        box-shadow: var(--shop-shadow-lg);
        transform: translateY(-8px);
    }

    /* Product Image */
    .product-image-wrapper {
        position: relative;
        overflow: hidden;
        background: var(--shop-bg-alt);
        aspect-ratio: 1;
    }

    .product-image-link {
        display: block;
        height: 100%;
    }

    .product-main-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .shop-product-card:hover .product-main-image {
        transform: scale(1.08);
    }

    /* Badges */
    .product-badge-discount,
    .product-badge-stock {
        position: absolute;
        top: 12px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 2;
    }

    .product-badge-discount {
        left: 12px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    .product-badge-stock {
        right: 12px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    /* Quick Actions */
    .product-quick-actions {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        gap: 8px;
        padding: 16px;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        opacity: 0;
        transform: translateY(100%);
        transition: all 0.3s ease;
    }

    .shop-product-card:hover .product-quick-actions {
        opacity: 1;
        transform: translateY(0);
    }

    .quick-action-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .quick-action-btn:hover {
        background: var(--shop-primary);
        color: white;
        transform: scale(1.15);
    }

    .quick-action-btn i {
        font-size: 16px;
    }

    /* Product Info */
    .product-info {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
    }

    .product-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--shop-text-light);
    }

    .product-category,
    .product-brand {
        font-weight: 500;
    }

    .meta-separator {
        color: var(--shop-border);
    }

    .product-name-link {
        text-decoration: none;
    }

    .product-name {
        font-size: 15px;
        font-weight: 600;
        color: var(--shop-text);
        margin: 0;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 42px;
        transition: color 0.3s;
    }

    .product-name-link:hover .product-name {
        color: var(--shop-primary);
    }

    /* Rating */
    .product-rating {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .product-rating .stars {
        display: flex;
        gap: 2px;
    }

    .product-rating .stars i {
        font-size: 12px;
        color: #fbbf24;
    }

    .product-rating .stars .fa-regular {
        color: #d1d5db;
    }

    .rating-count {
        font-size: 12px;
        color: var(--shop-text-light);
    }

    /* Price */
    .product-price {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 4px;
    }

    .price-current {
        font-size: 20px;
        font-weight: 700;
        color: var(--shop-primary);
    }

    .price-original {
        font-size: 14px;
        color: var(--shop-text-light);
        text-decoration: line-through;
    }

    .price-save {
        font-size: 11px;
        font-weight: 600;
        color: var(--shop-success);
        background: rgba(16, 185, 129, 0.1);
        padding: 2px 8px;
        border-radius: 4px;
    }

    /* Stock Status */
    .product-stock {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 0;
    }

    .product-stock i {
        font-size: 14px;
    }

    .product-stock.in-stock {
        color: var(--shop-success);
    }

    .product-stock.out-of-stock {
        color: var(--shop-danger);
    }

    /* Add to Cart Button */
    .product-add-to-cart {
        width: 100%;
        height: 44px;
        background: var(--shop-primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        margin-top: auto;
        transition: all 0.3s ease;
    }

    .product-add-to-cart:hover:not(:disabled) {
        background: var(--shop-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(98, 157, 35, 0.4);
    }

    .product-add-to-cart:disabled {
        background: #d1d5db;
        cursor: not-allowed;
    }

    .product-add-to-cart i {
        font-size: 16px;
    }

    /* Category Filter Tabs */
    .filter-button-group {
        border: none;
        gap: 10px;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .filter-button-group .nav-link {
        background: white;
        border: 1px solid var(--shop-border);
        border-radius: 25px;
        padding: 10px 22px;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        transition: all 0.3s ease;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .filter-button-group .nav-link:hover,
    .filter-button-group .nav-link.active {
        background: var(--shop-primary);
        color: white;
        border-color: var(--shop-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(98, 157, 35, 0.25);
    }

    .filter-button-group .tab-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 22px;
        height: 20px;
        padding: 0 6px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        background: rgba(15, 23, 42, 0.08);
        color: #374151;
    }

    .filter-button-group .nav-link.active .tab-count {
        background: rgba(255, 255, 255, 0.25);
        color: white;
    }

    .mt--30 {
        margin-top: 30px;
    }

    .mt--20 {
        margin-top: 20px;
    }

    /* Empty State */
    .rts-empty-state {
        text-align: center;
        padding: 80px 20px;
        background: var(--shop-bg-alt);
        border-radius: 16px;
    }

    .rts-empty-state .empty-icon {
        font-size: 80px;
        color: #d1d5db;
        margin-bottom: 20px;
    }

    .rts-empty-state h3 {
        font-size: 24px;
        font-weight: 600;
        color: var(--shop-text);
        margin-bottom: 10px;
    }

    .rts-empty-state p {
        font-size: 16px;
        color: var(--shop-text-light);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .rts-section-gap,
        .rts-section-gapTop {
            padding-top: 50px;
            padding-bottom: 50px;
        }

        .title-area-between .title-left {
            font-size: 24px;
        }

        .rts-banner-area-three {
            min-height: 480px;
        }

        .banner-inner-content-three .title {
            font-size: 36px;
        }

        .banner-button-next,
        .banner-button-prev {
            width: 48px;
            height: 48px;
        }

        .banner-button-next {
            right: 20px;
        }

        .banner-button-prev {
            left: 20px;
        }
    }

    @media (max-width: 767px) {
        .rts-section-gap,
        .rts-section-gapTop {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .title-area-between .title-left {
            font-size: 20px;
        }

        .rts-banner-area-three {
            min-height: 400px;
        }

        .banner-inner-content-three .title {
            font-size: 28px;
        }

        .price-current {
            font-size: 18px;
        }

        /* Title + tabs stack vertically */
        .title-area-between {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px !important;
        }

        /* Make tabs scroll horizontally */
        .filter-button-group {
            width: 100% !important;
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
            -webkit-overflow-scrolling: touch !important;
            scroll-snap-type: x mandatory !important;
            padding-bottom: 6px !important;
            gap: 8px !important;
            /* Hide scrollbar visually */
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }

        .filter-button-group::-webkit-scrollbar {
            display: none !important;
        }

        /* Each tab snaps into place */
        .filter-button-group .nav-item {
            flex-shrink: 0 !important;
            scroll-snap-align: start !important;
        }

        /* Smaller tab pills */
        .filter-button-group .nav-link {
            padding: 7px 14px !important;
            font-size: 12px !important;
            border-radius: 20px !important;
            white-space: nowrap !important;
            transform: none !important; /* disable hover lift on mobile */
        }

        .filter-button-group .nav-link:hover {
            transform: none !important;
        }

        /* Count badge */
        .filter-button-group .tab-count {
            font-size: 10px !important;
            min-width: 18px !important;
            height: 16px !important;
            padding: 0 4px !important;
        }

        /* View all link stays inline with title */
        .title-area-between .view-all-link {
            align-self: flex-end !important;
            margin-top: -8px !important;
            font-size: 13px !important;
        }
    }

    .btn-select-weight {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 10px 16px;
        background: var(--shop-primary);
        color: white;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-top: 10px;
    }

    .btn-select-weight:hover {
        opacity: 0.9;
        color: white;
        transform: translateY(-1px);
    }

    /* ===== MOBILE PRODUCT CARD FIXES ===== */
    @media (max-width: 575px) {

        /* Tighter grid gap */
        .row.g-4 {
            --bs-gutter-x: 10px;
            --bs-gutter-y: 10px;
        }

        /* Smaller card padding */
        .product-info {
            padding: 10px !important;
            gap: 5px !important;
        }

        .filter-button-group .nav-link {
            padding: 6px 12px !important;
            font-size: 11px !important;
        }

        /* Fade edge hint — shows user tabs are scrollable */
        .rts-popular-product-area .title-area-between {
            position: relative !important;
        }

        .filter-button-group::after {
            content: '' !important;
            position: sticky !important;
            right: 0 !important;
            flex-shrink: 0 !important;
            width: 32px !important;
            background: linear-gradient(to right, transparent, #f7f7f7) !important;
            pointer-events: none !important;
        }

        /* Smaller image */
        .product-image-wrapper {
            aspect-ratio: 1 !important;
        }

        /* Smaller product name */
        .product-name {
            font-size: 12px !important;
            min-height: 34px !important;
            -webkit-line-clamp: 2 !important;
        }

        /* Hide meta (category • brand) to save space */
        .product-meta {
            display: none !important;
        }

        /* Smaller price */
        .price-current {
            font-size: 14px !important;
        }

        .price-original {
            font-size: 11px !important;
        }

        /* Hide save badge on tiny screens */
        .price-save {
            display: none !important;
        }

        /* Smaller add to cart button */
        .product-add-to-cart,
        .btn-select-weight {
            height: 36px !important;
            font-size: 12px !important;
            padding: 0 8px !important;
            margin-top: 6px !important;
        }

        .product-add-to-cart i,
        .btn-select-weight i {
            font-size: 13px !important;
        }

        /* Smaller discount badge */
        .product-badge-discount,
        .product-badge-stock {
            font-size: 9px !important;
            padding: 4px 7px !important;
            border-radius: 4px !important;
        }

        /* Smaller rating */
        .product-rating .stars i {
            font-size: 10px !important;
        }

        .rating-count {
            font-size: 10px !important;
        }

        /* Section titles */
        .title-area-between .title-left {
            font-size: 17px !important;
        }

        /* Popular tabs — scrollable, smaller */
        .filter-button-group .nav-link {
            padding: 7px 14px !important;
            font-size: 12px !important;
        }

        /* Banner */
        .rts-banner-area-three {
            min-height: 300px !important;
        }

        .banner-inner-content-three .title {
            font-size: 22px !important;
        }

        .banner-inner-content-three .dsicription {
            font-size: 13px !important;
            display: none !important;
        }

        .banner-inner-content-three .pre {
            font-size: 11px !important;
            padding: 6px 12px !important;
        }

        /* Hide banner nav arrows on very small screens */
        .banner-button-next,
        .banner-button-prev {
            width: 36px !important;
            height: 36px !important;
            font-size: 14px !important;
        }

        /* Category cards */
        .single-category-one {
            padding: 12px 8px !important;
        }

        .single-category-one img {
            width: 52px !important;
            height: 52px !important;
            margin-bottom: 8px !important;
        }

        .single-category-one p {
            font-size: 11px !important;
        }

        /* Section spacing */
        .rts-section-gap,
        .rts-section-gapTop {
            padding-top: 28px !important;
            padding-bottom: 28px !important;
        }

        .container {
            padding: 0 !important;
        }
    }

</style>
@endpush
