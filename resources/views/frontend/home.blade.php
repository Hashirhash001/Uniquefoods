@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')

{{-- Banner Slider --}}
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
                                    <div class="banner-inner-content-three" style="color: {{ $banner->text_color }}">
                                        @if($banner->subtitle)
                                            <span class="pre">{{ $banner->subtitle }}</span>
                                        @endif

                                        <h1 class="title">{!! nl2br(e($banner->title)) !!}</h1>

                                        @if($banner->description)
                                            <p class="dsicription">{{ $banner->description }}</p>
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
    </div>
</div>

{{-- Featured Categories Section --}}
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
                                            "spaceBetween": 12,
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
                                                "640": { "slidesPerView": 4, "spaceBetween": 12 },
                                                "840": { "slidesPerView": 5, "spaceBetween": 12 },
                                                "1140": { "slidesPerView": 7, "spaceBetween": 12 }
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

{{-- Featured Products Section --}}
<div class="rts-section-gap">
    <div class="container-2">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left mb--0">Featured Products</h2>
                </div>
            </div>
        </div>

        <div class="row g-4 mt--20">
            @forelse($products as $product)
                <div class="col-lg-20 col-md-4 col-sm-6 col-12">
                    <div class="single-shopping-card-one">
                        {{-- Image Area --}}
                        <div class="image-and-action-area-wrapper">
                            <a href="{{ route('product.show', $product->slug) }}" class="thumbnail-preview">
                                @if($product->discount_percentage > 0)
                                    <div class="badge">
                                        <span>{{ $product->discount_percentage }}% <br> Off</span>
                                        <i class="fa-solid fa-bookmark"></i>
                                    </div>
                                @endif
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                            </a>

                            {{-- Hover Actions --}}
                            <div class="action-share-option">
                                <div class="single-action openuptip" data-flow="up" title="Add To Wishlist">
                                    <i class="fa-light fa-heart"></i>
                                </div>
                                {{-- <div class="single-action openuptip" data-flow="up" title="Compare">
                                    <i class="fa-solid fa-arrows-retweet"></i>
                                </div> --}}
                                <div class="single-action openuptip" data-flow="up" title="Quick View">
                                    <i class="fa-regular fa-eye"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Body Content --}}
                        <div class="body-content">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <h4 class="title">{{ $product->name }}</h4>
                            </a>

                            <span class="availability">{{ $product->category->name ?? 'Uncategorized' }}</span>

                            <div class="price-area">
                                <span class="current">₹{{ number_format($product->price, 2) }}</span>
                                @if($product->mrp && $product->mrp > $product->price)
                                    <div class="previous">₹{{ number_format($product->mrp, 2) }}</div>
                                @endif
                            </div>

                            {{-- Simple Add to Cart Button --}}
                            <a href="#" class="rts-btn btn-primary radious-sm with-icon w-100 add-to-cart-btn">
                                <div class="btn-text">Add To Cart</div>
                                <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                            </a>
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

{{-- Popular Products Section with Category Tabs --}}
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
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">All</button>
                        </li>
                        @foreach($popularCategories as $category)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="cat-{{ $category->id }}-tab" data-bs-toggle="tab" data-bs-target="#cat-{{ $category->id }}" type="button" role="tab">{{ $category->name }}</button>
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
                            <div class="single-shopping-card-one">
                                <div class="image-and-action-area-wrapper">
                                    <a href="{{ route('product.show', $product->slug) }}" class="thumbnail-preview">
                                        @if($product->discount_percentage > 0)
                                            <div class="badge">
                                                <span>{{ $product->discount_percentage }}% <br> Off</span>
                                                <i class="fa-solid fa-bookmark"></i>
                                            </div>
                                        @endif
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    </a>

                                    <div class="action-share-option">
                                        <div class="single-action openuptip" data-flow="up" title="Add To Wishlist">
                                            <i class="fa-light fa-heart"></i>
                                        </div>
                                        <div class="single-action openuptip" data-flow="up" title="Compare">
                                            <i class="fa-solid fa-arrows-retweet"></i>
                                        </div>
                                        <div class="single-action openuptip" data-flow="up" title="Quick View">
                                            <i class="fa-regular fa-eye"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="body-content">
                                    <a href="{{ route('product.show', $product->slug) }}">
                                        <h4 class="title">{{ $product->name }}</h4>
                                    </a>

                                    <span class="availability">{{ $product->category->name ?? 'Uncategorized' }}</span>

                                    <div class="price-area">
                                        <span class="current">₹{{ number_format($product->price, 2) }}</span>
                                        @if($product->mrp && $product->mrp > $product->price)
                                            <div class="previous">₹{{ number_format($product->mrp, 2) }}</div>
                                        @endif
                                    </div>

                                    <a href="#" class="rts-btn btn-primary radious-sm with-icon w-100 add-to-cart-btn">
                                        <div class="btn-text">Add To Cart</div>
                                        <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                        <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                    </a>
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
                                <div class="single-shopping-card-one">
                                    <div class="image-and-action-area-wrapper">
                                        <a href="{{ route('product.show', $product->slug) }}" class="thumbnail-preview">
                                            @if($product->discount_percentage > 0)
                                                <div class="badge">
                                                    <span>{{ $product->discount_percentage }}% <br> Off</span>
                                                    <i class="fa-solid fa-bookmark"></i>
                                                </div>
                                            @endif
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                        </a>

                                        <div class="action-share-option">
                                            <div class="single-action openuptip" data-flow="up" title="Add To Wishlist">
                                                <i class="fa-light fa-heart"></i>
                                            </div>
                                            <div class="single-action openuptip" data-flow="up" title="Compare">
                                                <i class="fa-solid fa-arrows-retweet"></i>
                                            </div>
                                            <div class="single-action openuptip" data-flow="up" title="Quick View">
                                                <i class="fa-regular fa-eye"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="body-content">
                                        <a href="{{ route('product.show', $product->slug) }}">
                                            <h4 class="title">{{ $product->name }}</h4>
                                        </a>

                                        <span class="availability">{{ $product->category->name ?? 'Uncategorized' }}</span>

                                        <div class="price-area">
                                            <span class="current">₹{{ number_format($product->price, 2) }}</span>
                                            @if($product->mrp && $product->mrp > $product->price)
                                                <div class="previous">₹{{ number_format($product->mrp, 2) }}</div>
                                            @endif
                                        </div>

                                        <a href="#" class="rts-btn btn-primary radious-sm with-icon w-100 add-to-cart-btn">
                                            <div class="btn-text">Add To Cart</div>
                                            <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                            <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                        </a>
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
    /* ============= Banner Section ============= */
    .banner-three-swiper-main-wrapper {
        position: relative;
    }

    .rts-banner-area-three {
        min-height: 550px;
        display: flex;
        align-items: center;
        position: relative;
    }

    .banner-button-next,
    .banner-button-prev {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .banner-button-next:hover,
    .banner-button-prev:hover {
        background: #629D23;
        color: white;
        transform: translateY(-50%) scale(1.1);
    }

    .banner-button-next {
        right: 30px;
    }

    .banner-button-prev {
        left: 30px;
    }

    /* ============= Category Section ============= */
    .next-prev-swiper-wrapper {
        display: flex;
        gap: 10px;
    }

    .category-button-next,
    .category-button-prev {
        width: 40px;
        height: 40px;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .category-button-next:hover,
    .category-button-prev:hover {
        background: #629D23;
        color: white;
        border-color: #629D23;
    }

    .single-category-one {
        background: #fff;
        border-radius: 10px;
        padding: 20px 15px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        height: 100%;
    }

    .single-category-one:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-5px);
        border-color: #629D23;
    }

    .single-category-one img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        margin: 0 auto 15px;
    }

    .single-category-one p {
        margin: 0;
        font-size: 14px;
        font-weight: 500;
        color: #333;
    }

    /* ============= Product Cards ============= */
    .single-shopping-card-one {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .single-shopping-card-one:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        transform: translateY(-5px);
        border-color: #629D23;
    }

    /* Product Image Area */
    .image-and-action-area-wrapper {
        position: relative;
        overflow: hidden;
        background: #f8f8f8;
    }

    .thumbnail-preview {
        display: block;
        position: relative;
    }

    .thumbnail-preview img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .single-shopping-card-one:hover .thumbnail-preview img {
        transform: scale(1.05);
    }

    /* Badge - NEUTRAL CLEAN STYLE (NO RED) */
    .badge {
        position: absolute;
        top: 12px;
        left: 12px;
        /* background: rgba(255, 255, 255, 0.95); */
        color: #333;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
        z-index: 5;
        /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); */
        display: inline-block;
        /* border: 1px solid rgba(0, 0, 0, 0.08); */
    }

    .badge span {
        display: block;
        line-height: 1.4;
    }

    /* Optional: Different badge color variations */
    .badge.badge-primary {
        background: rgba(98, 157, 35, 0.1);
        color: #629D23;
        border-color: #629D23;
    }

    .badge.badge-secondary {
        background: rgba(100, 100, 100, 0.1);
        color: #555;
        border-color: #aaa;
    }

    .badge.badge-dark {
        background: rgba(50, 50, 50, 0.9);
        color: #fff;
        border-color: #333;
    }

    .badge.badge-gold {
        background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        color: #fff;
        border-color: #f6d365;
    }

    /* Action Icons Bar - CENTERED AT BOTTOM */
    .action-share-option {
        position: absolute;
        bottom: 15px;
        left: 50%;
        transform: translateX(-50%) translateY(100%);
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0;
        background: rgba(98, 157, 35, 0.95);
        padding: 0;
        height: 50px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s ease;
        z-index: 10;
        border-radius: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        width: auto;
        min-width: 150px;
    }

    .single-shopping-card-one:hover .action-share-option {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    .single-action {
        width: 50px;
        height: 50px;
        background: transparent;
        border: none;
        border-right: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 18px;
        color: white;
        flex-shrink: 0;
    }

    .single-action:first-child {
        border-radius: 25px 0 0 25px;
    }

    .single-action:last-child {
        border-right: none;
        border-radius: 0 25px 25px 0;
    }

    .single-action:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    .single-action i {
        pointer-events: none;
    }

    /* Product Body */
    .body-content {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .body-content .title {
        font-size: 15px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 42px;
    }

    .body-content .title:hover {
        color: #629D23;
    }

    .availability {
        font-size: 13px;
        color: #999;
        margin-bottom: 10px;
        display: block;
    }

    .price-area {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .price-area .current {
        font-size: 20px;
        font-weight: 700;
        color: #629D23;
    }

    .price-area .previous {
        font-size: 16px;
        color: #999;
        text-decoration: line-through;
    }

    /* Add to Cart Button */
    .add-to-cart-btn {
        margin-top: auto;
        height: 45px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(98, 157, 35, 0.3);
    }

    /* Popular Products Section */
    .rts-popular-product-area {
        background: #f8f8f8;
    }

    .filter-button-group {
        border: none;
        gap: 10px;
    }

    .filter-button-group .nav-link {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 25px;
        padding: 8px 24px;
        font-size: 14px;
        font-weight: 600;
        color: #333;
        transition: all 0.3s ease;
    }

    .filter-button-group .nav-link:hover,
    .filter-button-group .nav-link.active {
        background: #629D23;
        color: white;
        border-color: #629D23;
    }

    /* Empty State */
    .rts-empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .rts-empty-state .empty-icon {
        font-size: 80px;
        color: #ddd;
        margin-bottom: 20px;
    }

    .rts-empty-state h3 {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }

    .rts-empty-state p {
        font-size: 16px;
        color: #666;
    }

    /* Tooltip for action buttons */
    .openuptip {
        position: relative;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .rts-banner-area-three {
            min-height: 450px;
        }

        .banner-button-next,
        .banner-button-prev {
            width: 40px;
            height: 40px;
        }

        .banner-button-next {
            right: 15px;
        }

        .banner-button-prev {
            left: 15px;
        }
    }

    @media (max-width: 767px) {
        .rts-banner-area-three {
            min-height: 400px;
        }

        .thumbnail-preview img {
            height: 200px;
        }

        .body-content {
            padding: 15px;
        }

        .filter-button-group {
            flex-wrap: wrap;
        }

        .filter-button-group .nav-link {
            padding: 6px 16px;
            font-size: 13px;
        }

        .action-share-option {
            height: 45px;
            min-width: 135px;
            bottom: 10px;
        }

        .single-action {
            width: 45px;
            height: 45px;
            font-size: 16px;
        }

        .badge {
            top: 8px;
            left: 8px;
            font-size: 12px;
            padding: 5px 10px;
        }
    }
</style>
@endpush
