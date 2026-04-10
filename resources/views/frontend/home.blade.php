@extends('frontend.layouts.app')

@section('title', 'Unique Foods')
@section('meta_description', 'Order fresh groceries and unique foods online. Fast delivery to your door.')
@section('og_type',          'website')
@section('og_url',           route('home'))

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
                                            <span class="pre" style="
                                                color: {{ $banner->subtitle_color ?? $banner->text_color ?? '#111827' }};
                                                background-color: {{ $banner->subtitle_bg_color ?? 'rgba(255,255,255,0.95)' }};
                                            ">{{ $banner->subtitle }}</span>
                                        @endif

                                        <h1 class="title" style="color: {{ $banner->title_color ?? $banner->text_color ?? '#111827' }};">
                                            {!! nl2br(e($banner->title)) !!}
                                        </h1>

                                        @if($banner->description)
                                            <p class="dsicription" style="color: {{ $banner->description_color ?? $banner->text_color ?? '#111827' }};">
                                                {{ $banner->description }}
                                            </p>
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

@if($featuredSubCategories->count() > 0)
{{-- Featured Categories Section - UPDATED WITH SHOP FILTERS --}}
<div class="rts-category-area rts-section-gapTop">
    <div class="container-2">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left mb--0">Featured Categories</h2>
                    @if($featuredSubCategories->count() > 7)
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
                                            "pagination": {
                                                "el": "#categoryPagination",
                                                "type": "progressbar"
                                            },
                                            "breakpoints": {
                                                "0": { "slidesPerView": 2, "spaceBetween": 12 },
                                                "320": { "slidesPerView": 2, "spaceBetween": 12 },
                                                "480": { "slidesPerView": 3, "spaceBetween": 12 },
                                                "640": { "slidesPerView": 4, "spaceBetween": 14 },
                                                "840": { "slidesPerView": 5, "spaceBetween": 14 },
                                                "1140": { "slidesPerView": 7, "spaceBetween": 16 }
                                            }
                                        }' style="padding: 5px;">
                                            <div class="swiper-wrapper">
                                                @forelse($featuredSubCategories as $category)
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
                                    {{-- Pagination bar — placed OUTSIDE the swiper div, right below it --}}
                                    <div class="category-pagination" id="categoryPagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($products->count() > 0)
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
@endif

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
<link rel="stylesheet" href="{{ asset('frontend/assets/css/home.css') }}">
@endpush
