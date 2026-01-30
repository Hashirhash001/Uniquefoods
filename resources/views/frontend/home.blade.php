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
                                        <span class="pre">Get up to 30% off on your first ₹150 purchase</span>
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

{{-- Featured Categories Section - KEPT ORIGINAL --}}
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
                                        }'>
                                            <div class="swiper-wrapper">
                                                @forelse($featuredCategories as $category)
                                                    <div class="swiper-slide">
                                                        <div class="single-category-one">
                                                            <a href="{{ route('category.show', $category->slug) }}">
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
                                                            <a href="#">
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
                <div class="col-lg-20 col-md-4 col-sm-6 col-12">
                    <div class="shop-product-card">
                        {{-- Image Area --}}
                        <div class="product-image-wrapper">
                            <a href="{{ route('product.show', $product->slug) }}" class="product-image-link">
                                @if($product->discount_percentage > 0)
                                    <div class="product-badge-discount">
                                        <span>{{ $product->discount_percentage }}% OFF</span>
                                    </div>
                                @endif

                                @if($product->stock <= 5 && $product->stock > 0)
                                    <div class="product-badge-stock">
                                        <span>Only {{ $product->stock }} left</span>
                                    </div>
                                @endif

                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-main-image">
                            </a>

                            {{-- Quick Actions --}}
                            <div class="product-quick-actions">
                                <button class="quick-action-btn" title="Add to Wishlist">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                                <button class="quick-action-btn" title="Quick View">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <button class="quick-action-btn" title="Compare">
                                    <i class="fa-regular fa-arrows-rotate"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Product Info --}}
                        <div class="product-info">
                            {{-- Category & Brand --}}
                            <div class="product-meta">
                                <span class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                @if($product->brand)
                                    <span class="meta-separator">•</span>
                                    <span class="product-brand">{{ $product->brand->name }}</span>
                                @endif
                            </div>

                            {{-- Product Name --}}
                            <a href="{{ route('product.show', $product->slug) }}" class="product-name-link">
                                <h4 class="product-name">{{ $product->name }}</h4>
                            </a>

                            {{-- Rating --}}
                            <div class="product-rating">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= 4)
                                            <i class="fa-solid fa-star"></i>
                                        @else
                                            <i class="fa-regular fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="rating-count">(4.0)</span>
                            </div>

                            {{-- Price --}}
                            <div class="product-price">
                                <span class="price-current">₹{{ number_format($product->price, 2) }}</span>
                                @if($product->mrp && $product->mrp > $product->price)
                                    <span class="price-original">₹{{ number_format($product->mrp, 2) }}</span>
                                    <span class="price-save">Save ₹{{ number_format($product->mrp - $product->price, 2) }}</span>
                                @endif
                            </div>

                            {{-- Stock Status --}}
                            @if($product->stock > 0)
                                <div class="product-stock in-stock">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>In Stock</span>
                                </div>
                            @else
                                <div class="product-stock out-of-stock">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    <span>Out of Stock</span>
                                </div>
                            @endif

                            {{-- Add to Cart Button --}}
                            <button class="product-add-to-cart" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fa-regular fa-cart-shopping"></i>
                                <span>{{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="rts-empty-state">
                        <div class="empty-icon">
                            <i class="fa-light fa-box-open"></i>
                        </div>
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
                        <div class="col-lg-20 col-md-4 col-sm-6 col-12">
                            <div class="shop-product-card">
                                <div class="product-image-wrapper">
                                    <a href="{{ route('product.show', $product->slug) }}" class="product-image-link">
                                        @if($product->discount_percentage > 0)
                                            <div class="product-badge-discount">
                                                <span>{{ $product->discount_percentage }}% OFF</span>
                                            </div>
                                        @endif

                                        @if($product->stock <= 5 && $product->stock > 0)
                                            <div class="product-badge-stock">
                                                <span>Only {{ $product->stock }} left</span>
                                            </div>
                                        @endif

                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-main-image">
                                    </a>

                                    <div class="product-quick-actions">
                                        <button class="quick-action-btn" title="Add to Wishlist">
                                            <i class="fa-regular fa-heart"></i>
                                        </button>
                                        <button class="quick-action-btn" title="Quick View">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                        <button class="quick-action-btn" title="Compare">
                                            <i class="fa-regular fa-arrows-rotate"></i>
                                        </button>
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

                                    <div class="product-rating">
                                        <div class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= 4)
                                                    <i class="fa-solid fa-star"></i>
                                                @else
                                                    <i class="fa-regular fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="rating-count">(4.0)</span>
                                    </div>

                                    <div class="product-price">
                                        <span class="price-current">₹{{ number_format($product->price, 2) }}</span>
                                        @if($product->mrp && $product->mrp > $product->price)
                                            <span class="price-original">₹{{ number_format($product->mrp, 2) }}</span>
                                            <span class="price-save">Save ₹{{ number_format($product->mrp - $product->price, 2) }}</span>
                                        @endif
                                    </div>

                                    @if($product->stock > 0)
                                        <div class="product-stock in-stock">
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span>In Stock</span>
                                        </div>
                                    @else
                                        <div class="product-stock out-of-stock">
                                            <i class="fa-solid fa-circle-xmark"></i>
                                            <span>Out of Stock</span>
                                        </div>
                                    @endif

                                    <button class="product-add-to-cart" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                        <i class="fa-regular fa-cart-shopping"></i>
                                        <span>{{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Category Filtered Tabs --}}
            @foreach($popularCategories as $category)
                <div class="tab-pane fade" id="cat-{{ $category->id }}" role="tabpanel">
                    <div class="row g-4">
                        @foreach($popularProducts->where('category_id', $category->id) as $product)
                            <div class="col-lg-20 col-md-4 col-sm-6 col-12">
                                <div class="shop-product-card">
                                    <div class="product-image-wrapper">
                                        <a href="{{ route('product.show', $product->slug) }}" class="product-image-link">
                                            @if($product->discount_percentage > 0)
                                                <div class="product-badge-discount">
                                                    <span>{{ $product->discount_percentage }}% OFF</span>
                                                </div>
                                            @endif

                                            @if($product->stock <= 5 && $product->stock > 0)
                                                <div class="product-badge-stock">
                                                    <span>Only {{ $product->stock }} left</span>
                                                </div>
                                            @endif

                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-main-image">
                                        </a>

                                        <div class="product-quick-actions">
                                            <button class="quick-action-btn" title="Add to Wishlist">
                                                <i class="fa-regular fa-heart"></i>
                                            </button>
                                            <button class="quick-action-btn" title="Quick View">
                                                <i class="fa-regular fa-eye"></i>
                                            </button>
                                            <button class="quick-action-btn" title="Compare">
                                                <i class="fa-regular fa-arrows-rotate"></i>
                                            </button>
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

                                        <div class="product-rating">
                                            <div class="stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= 4)
                                                        <i class="fa-solid fa-star"></i>
                                                    @else
                                                        <i class="fa-regular fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="rating-count">(4.0)</span>
                                        </div>

                                        <div class="product-price">
                                            <span class="price-current">₹{{ number_format($product->price, 2) }}</span>
                                            @if($product->mrp && $product->mrp > $product->price)
                                                <span class="price-original">₹{{ number_format($product->mrp, 2) }}</span>
                                                <span class="price-save">Save ₹{{ number_format($product->mrp - $product->price, 2) }}</span>
                                            @endif
                                        </div>

                                        @if($product->stock > 0)
                                            <div class="product-stock in-stock">
                                                <i class="fa-solid fa-circle-check"></i>
                                                <span>In Stock</span>
                                            </div>
                                        @else
                                            <div class="product-stock out-of-stock">
                                                <i class="fa-solid fa-circle-xmark"></i>
                                                <span>Out of Stock</span>
                                            </div>
                                        @endif

                                        <button class="product-add-to-cart" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                            <i class="fa-regular fa-cart-shopping"></i>
                                            <span>{{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}</span>
                                        </button>
                                    </div>
                                </div>
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
    color: #629D23;
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
    min-height: 560px;
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
    background: #629D23;
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
    background: #629D23;
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
    background: #629D23;
    color: white;
    border-color: #629D23;
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
    border-color: #629D23;
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
    color: #629D23;
}

/* ============================================
   NEW SHOP-STYLE PRODUCT CARDS
============================================ */
:root {
    --shop-primary: #629D23;
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
}
</style>
@endpush
