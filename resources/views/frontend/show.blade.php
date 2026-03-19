@extends('frontend.layouts.app')

@section('title', $product->name . ' - Unique Foods')

@section('meta_description', Str::limit(strip_tags($product->description ?? $product->short_description), 155))
@section('meta_keywords',    $product->category?->name . ', ' . $product->name . ', buy online')
@section('meta_canonical',   route('product.show', $product->slug))

@section('og_type',          'product')
@section('og_title',         $product->name)
@section('og_description',   Str::limit(strip_tags($product->description ?? ''), 155))
@section('og_url',           route('product.show', $product->slug))
@section('og_image',         $product->image_url)

@section('twitter_title',       $product->name)
@section('twitter_description', Str::limit(strip_tags($product->description ?? ''), 155))
@section('twitter_image',       $product->image_url)

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/assets/css/product-details.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/shop.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/cart-wishlist.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/global-loader.css') }}">
@endpush

@section('content')
<!-- Breadcrumb -->
<div class="rts-navigation-area-breadcrumb bglight-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="navigator-breadcrumb-wrapper">
                    <a href="{{ route('home') }}">Home</a>
                    <i class="fa-regular fa-chevron-right"></i>
                    <a href="{{ route('shop') }}">Shop</a>
                    <i class="fa-regular fa-chevron-right"></i>
                    @if($product->category)
                        <a href="{{ route('shop') }}?category={{ $product->category->id }}">{{ $product->category->name }}</a>
                        <i class="fa-regular fa-chevron-right"></i>
                    @endif
                    <a class="current">{{ Str::limit($product->name, 30) }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="section-seperator bglight-1">
    <div class="container"><hr class="section-seperator"></div>
</div> --}}

<!-- Product Details Section -->
<div class="rts-shop-details-area rts-section-gap bglight-1">
    <div class="container">
        <div class="row g-5">
            <!-- Left: Product Images -->
            <div class="col-xl-8 col-lg-8 col-md-12">
                <div class="product-details-popup-wrapper in-shopdetails">
                    <div class="rts-product-details-section rts-product-details-section2 product-details-popup-section w-100">
                        <div class="product-details-popup">
                            <div class="details-product-area">
                                <!-- Product Image Gallery -->
                                <div class="product-thumb-area">
                                    <div class="cursor"></div>

                                    @php
                                        $additionalImages = $product->images()->where('is_primary', false)->get();
                                    @endphp

                                    <!-- Main Image -->
                                    <div class="thumb-wrapper one filtered-items figure">
                                        <div class="product-thumb zoom" onmousemove="zoom(event)"
                                             style="background-image: url('{{ $product->image_url }}')">
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-main-image">
                                        </div>
                                    </div>

                                    <!-- Additional Images -->
                                    @foreach($additionalImages as $index => $image)
                                        <div class="thumb-wrapper thumb-{{ $index + 2 }} filtered-items hide">
                                            <div class="product-thumb zoom" onmousemove="zoom(event)"
                                                 style="background-image: url('{{ asset('storage/' . $image->image_path) }}')">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="product-main-image">
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Thumbnails -->
                                    <div class="product-thumb-filter-group">
                                        <div class="thumb-filter filter-btn active" data-show=".one">
                                            <img src="{{ $product->image_url }}" alt="product-thumb-filter">
                                        </div>
                                        @foreach($additionalImages as $index => $image)
                                            <div class="thumb-filter filter-btn" data-show=".thumb-{{ $index + 2 }}">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="product-thumb-filter">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Product Information -->
                                <div class="contents w-100">
                                    <div class="product-status">
                                        @if($product->category)
                                            <span class="product-category">{{ $product->category->name }}</span>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mb--10 w-100">
                                        <div class="rating-stars-group">
                                            <div class="rating-star">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa-{{ $i <= round($product->average_rating) ? 'solid' : 'regular' }} fa-star {{ $product->average_rating > 0 ? 'text-warning' : '' }}"></i>
                                                @endfor
                                            </div>
                                            @if($product->reviews_count > 0)
                                                <span>{{ $product->reviews_count }} {{ Str::plural('Review', $product->reviews_count) }}</span>
                                            @else
                                                <span>No reviews yet</span>
                                            @endif
                                        </div>

                                        <div class="product-top-actions">
                                            <button class="share-button-main" id="shareProductBtn">
                                                <i class="fa-solid fa-share-nodes"></i>
                                                <span>Share</span>
                                            </button>
                                        </div>
                                    </div>

                                    <h2 class="product-title">{{ $product->name }}</h2>

                                    <p class="mt--20 mb--20">
                                        {{ $product->short_description ?? Str::limit($product->description, 150) }}
                                    </p>

                                    <div class="product-price-wrapper">
                                        <span class="product-price mb--15 d-block" style="color:#DC2626;font-weight:600;">
                                            £{{ number_format($product->final_price, 2) }}
                                        </span>

                                        @if($product->base_price > $product->final_price)
                                            <span class="old-price ml--15">£{{ number_format($product->base_price, 2) }}</span>
                                            <span class="save-badge">
                                                Save £{{ number_format($product->base_price - $product->final_price, 2) }}
                                                ({{ $product->discount_percentage_calc }}%)
                                            </span>
                                        @elseif($product->mrp && $product->mrp > $product->final_price)
                                            <span class="old-price ml--15">£{{ number_format($product->mrp, 2) }}</span>
                                            <span class="save-badge">
                                                Save £{{ number_format($product->mrp - $product->final_price, 2) }}
                                                ({{ round((($product->mrp - $product->final_price) / $product->mrp) * 100) }}%)
                                            </span>
                                        @endif
                                    </div>

                                    <div class="stock-status mb--20">
                                        @if($product->stock > 0)
                                            <span class="in-stock">
                                                <i class="fa-solid fa-circle-check"></i>
                                                In Stock ({{ $product->stock }} available)
                                            </span>
                                        @else
                                            <span class="out-of-stock">
                                                <i class="fa-solid fa-circle-xmark"></i>
                                                Out of Stock
                                            </span>
                                        @endif
                                    </div>

                                    <div class="product-bottom-action">
                                        @if($product->is_weight_based)
                                            <div class="weight-purchase-block">

                                                {{-- Stepper --}}
                                                <div class="weight-selector-row">
                                                    <button type="button" class="weight-step-btn" id="weightMinus">
                                                        <i class="fa-solid fa-minus"></i>
                                                    </button>
                                                    <div class="weight-input-wrapper">
                                                        <input type="number"
                                                            id="weightInput"
                                                            value="{{ number_format($product->min_weight ?? 0.5, 1) }}"
                                                            min="{{ $product->min_weight ?? 0.5 }}"
                                                            max="{{ $product->max_weight ?? 3 }}"
                                                            step="0.5">
                                                        <span class="weight-unit-label">kg</span>
                                                    </div>
                                                    <button type="button" class="weight-step-btn" id="weightPlus">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                </div>

                                                {{-- Quick weight pills --}}
                                                <div class="weight-quick-btns">
                                                    @php
                                                        $minW = (float)($product->min_weight ?? 0.5);
                                                        $maxW = (float)($product->max_weight ?? 3);
                                                        $presetWeights = [0.5, 1, 2, 3];
                                                    @endphp
                                                    @foreach($presetWeights as $w)
                                                        @if($w >= $minW && $w <= $maxW)
                                                            <button type="button"
                                                                    class="weight-quick-btn {{ $w == $minW ? 'active' : '' }}"
                                                                    data-weight="{{ $w }}" style="width: unset;">
                                                                {{ rtrim(rtrim(number_format($w, 2), '0'), '.') }}kg
                                                            </button>
                                                        @endif
                                                    @endforeach
                                                </div>

                                                {{-- Live price --}}
                                                <div class="weight-total-row">
                                                    <span class="weight-price-label">
                                                        £{{ number_format($product->final_price, 2) }}/kg × <span id="weightDisplay">{{ number_format($product->min_weight ?? 0.5, 1) }}</span>kg
                                                    </span>
                                                    <span class="weight-total-price" id="weightTotalPrice">
                                                        £{{ number_format($product->final_price * ($product->min_weight ?? 0.5), 2) }}
                                                    </span>
                                                </div>

                                                @if($product->min_weight)
                                                    <p class="weight-min-note">
                                                        <i class="fa-regular fa-circle-info"></i>
                                                        Min order: {{ $product->min_weight }}kg
                                                        @if($product->max_weight) · Max: {{ $product->max_weight }}kg @endif
                                                    </p>
                                                @endif

                                                {{-- Add To Cart + Wishlist side by side --}}
                                                <div class="cart-action-row">
                                                    <button type="button"
                                                            class="rts-btn btn-primary radious-sm with-icon"
                                                            id="weightAddToCartBtn"
                                                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                                        <div class="btn-text">{{ $product->stock > 0 ? 'Add To Cart' : 'Out of Stock' }}</div>
                                                        <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                                    </button>

                                                    <button class="wishlist-toggle-btn wishlist-icon-btn"
                                                            data-product-id="{{ $product->id }}"
                                                            title="Add to Wishlist">
                                                        <i class="fa-light fa-heart"></i>
                                                    </button>
                                                </div>

                                            </div>

                                        @else
                                            {{-- Standard product: Add To Cart + Wishlist side by side --}}
                                            <div class="cart-action-row">
                                                <button class="rts-btn btn-primary radious-sm with-icon add-to-cart-btn"
                                                        data-product-id="{{ $product->id }}"
                                                        {{ $product->stock === 0 ? 'disabled' : '' }}>
                                                    <div class="btn-text">{{ $product->stock > 0 ? 'Add To Cart' : 'Out of Stock' }}</div>
                                                    <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                                </button>

                                                <button class="wishlist-toggle-btn wishlist-icon-btn"
                                                        data-product-id="{{ $product->id }}"
                                                        title="Add to Wishlist">
                                                    <i class="fa-light fa-heart"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="product-uniques">
                                        {{-- <span class="sku product-unique mb--10">
                                            <span style="font-weight: 400; margin-right: 10px;">SKU:</span>
                                            <span>{{ $product->sku ?? 'N/A' }}</span>
                                        </span> --}}

                                        @if($product->category)
                                            <span class="categories product-unique mb--10" style="font-size: 15px;">
                                                <span style="font-weight: 500; margin-right: 10px;">Category:</span>
                                                <span>{{ $product->category->name }}</span>
                                            </span>
                                        @endif

                                        @if($product->brand)
                                            <span class="brand product-unique mb--10">
                                                <span style="font-weight: 400; margin-right: 10px;">Brand:</span>
                                                <span>{{ $product->brand->name }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Modern Share Button -->
                                    {{-- <div class="share-option-shop-details">
                                        <button class="share-button-main" id="shareProductBtn">
                                            <i class="fa-solid fa-share-nodes"></i>
                                            <span>Share Product</span>
                                        </button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Product Tabs --}}
                <div class="product-description-tab-shop mt--50">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">

                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab"
                                    data-bs-toggle="tab" data-bs-target="#details-tab-pane"
                                    type="button" role="tab">
                                Product Details
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab"
                                    data-bs-toggle="tab" data-bs-target="#reviews-tab-pane"
                                    type="button" role="tab">
                                Reviews
                                @if($product->reviews_count > 0)
                                    <span class="badge bg-primary ms-1">{{ $product->reviews_count }}</span>
                                @endif
                            </button>
                        </li>

                    </ul>

                    <div class="tab-content" id="myTabContent">

                        {{-- Product Details (merged with Additional Info) --}}
                        <div class="tab-pane fade show active" id="details-tab-pane" role="tabpanel">
                            <div class="single-tab-content-shop-details">
                                @if($product->description)
                                    <p class="disc">{!! nl2br(e($product->description)) !!}</p>
                                @endif

                                @if($product->weight || $product->brand_id || $product->unit)
                                <table class="table table-bordered mt-3">
                                    <tbody>
                                        @if($product->weight)
                                        <tr><td><strong>Weight</strong></td><td>{{ $product->weight }}</td></tr>
                                        @endif
                                        @if($product->brand)
                                        <tr><td><strong>Brand</strong></td><td>{{ $product->brand->name }}</td></tr>
                                        @endif
                                        @if($product->unit)
                                        <tr><td><strong>Unit</strong></td><td>{{ $product->unit }}</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                                @endif

                                @if(!$product->description && !$product->weight && !$product->brand_id && !$product->unit)
                                    <p class="text-muted">No product details available.</p>
                                @endif
                            </div>
                        </div>

                        {{-- Reviews Tab --}}
                        <div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel">
                            <div class="reviews-section">

                                {{-- Rating Summary --}}
                                <div id="reviewSummaryBar">
                                    @if($product->reviews_count > 0)
                                    <div class="review-summary-bar">
                                        <div class="review-avg-score">
                                            <span class="avg-number">{{ $product->average_rating }}</span>
                                            <div class="avg-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa-{{ $i <= round($product->average_rating) ? 'solid' : 'regular' }} fa-star"></i>
                                                @endfor
                                            </div>
                                            <span class="avg-label">{{ $product->reviews_count }} {{ Str::plural('review', $product->reviews_count) }}</span>
                                        </div>
                                        <div class="review-bars">
                                            @for($star = 5; $star >= 1; $star--)
                                                @php
                                                    $count = $product->reviews->where('rating', $star)->count();
                                                    $pct   = $product->reviews_count > 0
                                                                ? round(($count / $product->reviews_count) * 100)
                                                                : 0;
                                                @endphp
                                                <div class="rating-bar-row">
                                                    <span class="bar-label">{{ $star }} <i class="fa-solid fa-star"></i></span>
                                                    <div class="bar-track"><div class="bar-fill" style="width: {{ $pct }}%"></div></div>
                                                    <span class="bar-count">{{ $count }}</span>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                {{-- Write Review --}}
                                @auth
                                    {{-- Write form container - always rendered, content swapped by JS --}}
                                    <div id="reviewFormArea">
                                        @if($hasPurchased && !$hasReviewed)
                                        <div class="write-review-card" id="writeReviewCard">
                                            <h5 class="review-form-title">
                                                <i class="fa-solid fa-pen-to-square"></i> Write a Review
                                            </h5>
                                            <form id="reviewForm">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <div class="star-rating-input mb-3">
                                                    <label class="form-label">Your Rating <span class="text-danger">*</span></label>
                                                    <div class="star-input-group" id="starInput">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fa-regular fa-star star-pick" data-value="{{ $i }}"></i>
                                                        @endfor
                                                    </div>
                                                    <input type="hidden" name="rating" id="ratingValue" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Your Review</label>
                                                    <textarea name="body" class="form-control" rows="4"
                                                            placeholder="Share your honest experience with this product..."
                                                            maxlength="1000"></textarea>
                                                </div>
                                                <button type="submit" class="btn-submit-review" id="submitReviewBtn" style="width:unset;">
                                                    <i class="fa-solid fa-paper-plane"></i> Submit Review
                                                </button>
                                            </form>
                                        </div>

                                        @elseif($hasReviewed)
                                        <div class="review-notice reviewed" id="alreadyReviewedNotice">
                                            <i class="fa-solid fa-circle-check"></i>
                                            <div>
                                                <strong>You've already reviewed this product.</strong>
                                                <p>Thank you for sharing your feedback!</p>
                                            </div>
                                        </div>

                                        @elseif(!$hasPurchased)
                                        <div class="review-notice not-purchased">
                                            <i class="fa-solid fa-lock"></i>
                                            <div>
                                                <strong>Purchase required to review</strong>
                                                <p>Only customers who have purchased and received this product can leave a review.</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="review-notice login-required">
                                        <i class="fa-solid fa-user-circle"></i>
                                        <div>
                                            <strong>Login to write a review</strong>
                                            <p>Already have an account? <a href="{{ route('login') }}">Sign in</a> to share your experience.</p>
                                        </div>
                                    </div>
                                @endauth

                                {{-- Reviews List --}}
                                <div class="reviews-list mt-4" id="reviewsList">
                                    @if($product->reviews->count() > 0)
                                        @foreach($product->reviews->sortByDesc('created_at') as $review)
                                        <div class="review-card" id="review-card-{{ $review->id }}">
                                            <div class="review-card-header">
                                                <img src="{{ $review->user->profile_picture }}" alt="{{ $review->user->name }}" class="reviewer-avatar">
                                                <div class="reviewer-info">
                                                    <span class="reviewer-name">{{ $review->user->name }}</span>
                                                    <span class="review-date">{{ $review->created_at->format('d M Y') }}</span>
                                                </div>
                                                <div class="review-stars ms-auto">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                                    @endfor
                                                </div>
                                                @auth
                                                    @if(Auth::id() === $review->user_id)
                                                    <div class="review-actions ms-2 d-flex gap-2">
                                                        <button class="btn-review-edit"
                                                                data-review-id="{{ $review->id }}"
                                                                data-rating="{{ $review->rating }}"
                                                                data-body="{{ $review->body }}"
                                                                title="Edit">
                                                            <i class="fa-regular fa-pen"></i>
                                                        </button>
                                                        <button class="btn-review-delete"
                                                                data-review-id="{{ $review->id }}"
                                                                title="Delete">
                                                            <i class="fa-regular fa-trash-can"></i>
                                                        </button>
                                                    </div>
                                                    @endif
                                                @endauth
                                            </div>
                                            <div class="review-body-content">
                                                @if($review->body)
                                                    <p class="review-body">{{ $review->body }}</p>
                                                @endif
                                            </div>
                                            {{-- <span class="verified-badge">
                                                <i class="fa-solid fa-circle-check"></i> Verified Purchase
                                            </span> --}}
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="no-reviews-state" id="noReviewsState">
                                            <i class="fa-regular fa-star"></i>
                                            <p>No reviews yet. Be the first to review this product!</p>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="theiaStickySidebar">
                    {{-- Available Offers --}}
                    <div class="shop-sight-sticky-sidebar mb--20">
                        <h6 class="title">Available Offers</h6>

                        @if(Auth::check() && $offers->count() > 0)
                            @foreach($offers as $offer)
                            <div class="single-offer-area">
                                <div class="icon">
                                    @if($offer->discount_type === 'percentage')
                                        <img src="{{ asset('frontend/assets/images/shop/01.svg') }}" alt="offer">
                                    @else
                                        <img src="{{ asset('frontend/assets/images/shop/02.svg') }}" alt="offer">
                                    @endif
                                </div>
                                <div class="details">
                                    <p>
                                        @if($offer->offer_price)
                                            Special price <strong>£{{ number_format($offer->offer_price, 2) }}</strong>
                                        @elseif($offer->discount_type === 'percentage')
                                            <strong>{{ $offer->discount_value }}% off</strong>
                                        @else
                                            <strong>£{{ number_format($offer->discount_value, 2) }} off</strong>
                                        @endif
                                        on {{ $offer->offer_name }}
                                        @if($offer->ends_at)
                                            · Valid till {{ $offer->ends_at->format('d M') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @endforeach

                        @else
                            <div class="no-offers-state">
                                <i class="fa-regular fa-tag"></i>
                                <p>
                                    @auth
                                        No special offers available for this product at the moment.
                                    @else
                                        <a href="{{ route('login') }}">Sign in</a> to see exclusive offers available for your account.
                                    @endauth
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- <div class="shop-sight-sticky-sidebar">
                        <h6 class="title">Delivery & Returns</h6>
                        <div class="delivery-info">
                            <div class="info-item">
                                <i class="fa-solid fa-truck-fast"></i>
                                <div>
                                    <strong>Free Delivery</strong>
                                    <p>On orders above £500</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fa-solid fa-rotate-left"></i>
                                <div>
                                    <strong>7 Days Return</strong>
                                    <p>Easy return policy</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fa-solid fa-shield-check"></i>
                                <div>
                                    <strong>Secure Payment</strong>
                                    <p>100% secure transaction</p>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
@if ($relatedProducts->isNotEmpty())
<div class="rts-grocery-feature-area rts-section-gap bglight-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left">You May Also Like</h2>
                    <a href="{{ route('shop') }}" class="rts-btn btn-primary">View All Products</a>
                </div>
            </div>
        </div>

        <div class="row g-4 mt--10">
            @php
                $relatedProducts = App\Models\Product::where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->where('is_active', 1)
                    ->limit(4)
                    ->get();
            @endphp

            @foreach($relatedProducts as $related)
                <div class="col-lg-2 col-md-4 col-sm-6 col-6">
                    <div class="shop-product-card">
                        <div class="product-image-wrapper">
                            <a href="{{ route('product.show', $related->slug) }}" class="product-image-link">
                                @if($related->discount_percentage > 0)
                                    <div class="product-badge-discount">
                                        <span>{{ $related->discount_percentage }}% OFF</span>
                                    </div>
                                @endif
                                @if($related->stock <= 5 && $related->stock > 0)
                                    <div class="product-badge-stock">
                                        <span>Only {{ $related->stock }} left</span>
                                    </div>
                                @endif
                                <img src="{{ $related->image_url }}" alt="{{ $related->name }}" class="product-main-image">
                            </a>
                            <div class="product-quick-actions">
                                <button class="quick-action-btn wishlist-toggle-btn"
                                        data-product-id="{{ $related->id }}">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                                <a href="{{ route('product.show', $related->slug) }}" class="quick-action-btn">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-meta">
                                <span class="product-category">{{ $related->category->name ?? 'Uncategorized' }}</span>
                                @if($related->brand)
                                    <span class="meta-separator">•</span>
                                    <span class="product-brand">{{ $related->brand->name }}</span>
                                @endif
                            </div>
                            <a href="{{ route('product.show', $related->slug) }}" class="product-name-link">
                                <h4 class="product-name">{{ $related->name }}</h4>
                            </a>
                            <div class="product-rating">
                                <div class="stars">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-regular fa-star"></i>
                                </div>
                                <span class="rating-count">(4.0)</span>
                            </div>
                            <div class="product-price">
                                <span class="price-current">£{{ number_format($related->final_price, 2) }}</span>

                                @if($related->base_price > $related->final_price)
                                    <span class="price-original">£{{ number_format($related->base_price, 2) }}</span>
                                    <span class="price-save">Save £{{ number_format($related->base_price - $related->final_price, 2) }}</span>
                                @endif
                            </div>

                            @if($related->stock > 0)
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
                            <button class="product-add-to-cart add-to-cart-btn {{ $related->stock == 0 ? 'disabled' : '' }}"
                                    data-product-id="{{ $related->id }}"
                                    {{ $related->stock == 0 ? 'disabled' : '' }}>
                                <i class="fa-regular fa-cart-shopping"></i>
                                <span>{{ $related->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Review Edit Modal -->
<div class="review-edit-overlay" id="reviewEditOverlay" style="display:none;">
    <div class="review-edit-modal">
        <div class="review-edit-header">
            <h5><i class="fa-solid fa-pen-to-square"></i> Edit Your Review</h5>
            <button id="closeReviewEdit" style="width: unset;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="editReviewForm">
            @csrf
            <input type="hidden" id="editReviewId">

            <div class="star-rating-input mb-4">
                <label class="form-label">Your Rating <span class="text-danger">*</span></label>
                <div class="star-input-group" id="editStarInput">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-regular fa-star star-pick" data-value="{{ $i }}"></i>
                    @endfor
                </div>
                <input type="hidden" id="editRatingValue" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Your Review</label>
                <textarea id="editReviewBody" class="form-control" rows="5"
                          placeholder="Share your experience..." maxlength="1000" style="font-size: 1.5rem;"></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-submit-review flex-grow-1" id="updateReviewBtn">
                    <i class="fa-solid fa-floppy-disk"></i> Save Changes
                </button>
                <button type="button" class="btn-cancel-review" id="cancelReviewEdit">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Custom Delete Review Modal -->
<div class="delete-modal-overlay" id="deleteReviewOverlay" style="display:none;">
    <div class="delete-modal">
        <div class="delete-modal-icon">
            <i class="fa-regular fa-trash-can"></i>
        </div>
        <h4 class="delete-modal-title">Delete Review?</h4>
        <p class="delete-modal-desc">This action cannot be undone. Your review will be permanently removed.</p>
        <div class="delete-modal-actions">
            <button class="btn-delete-cancel" id="cancelDeleteModal">Keep Review</button>
            <button class="btn-delete-confirm" id="confirmDeleteModal">
                <i class="fa-regular fa-trash-can"></i> Yes, Delete
            </button>
        </div>
    </div>
</div>


<!-- Modern Share Modal -->
<div class="share-modal-overlay" id="shareModalOverlay">
    <div class="share-modal">
        <div class="share-modal-header">
            <h3>Share this product</h3>
            <button class="share-modal-close" id="closeShareModal">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="share-modal-body">
            <!-- Product Preview -->
            <div class="share-product-preview">
                <div class="share-product-image">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                </div>
                <div class="share-product-info">
                    <h4>{{ $product->name }}</h4>
                    <div class="share-product-price">£{{ number_format($product->final_price, 2) }}</div>
                </div>
            </div>

            <!-- Share Options -->
            <div class="share-options-section">
                <div class="share-section-label">Share via</div>
                <div class="share-options-grid">
                    <a href="#" class="share-option" data-platform="whatsapp">
                        <div class="share-option-icon whatsapp"><i class="fa-brands fa-whatsapp"></i></div>
                        <span class="share-option-label">WhatsApp</span>
                    </a>
                    <a href="#" class="share-option" data-platform="instagram">
                        <div class="share-option-icon instagram"><i class="fa-brands fa-instagram"></i></div>
                        <span class="share-option-label">Instagram</span>
                    </a>
                    <a href="#" class="share-option" data-platform="telegram">
                        <div class="share-option-icon telegram"><i class="fa-brands fa-telegram"></i></div>
                        <span class="share-option-label">Telegram</span>
                    </a>
                    {{-- <a href="#" class="share-option" data-platform="x">
                        <div class="share-option-icon x"><i class="fa-brands fa-x-twitter"></i></div>
                        <span class="share-option-label">X</span>
                    </a> --}}
                    <a href="" class="share-option" data-platform="facebook">
                        <div class="share-option-icon facebook"><i class="fa-brands fa-facebook-f"></i></div>
                        <span class="share-option-label">Facebook</span>
                    </a>
                </div>
            </div>

            <!-- Copy Link Section -->
            <div class="share-link-section">
                <label class="share-link-label">Product Link</label>
                <div class="share-link-copy">
                    <input type="text" id="shareProductUrl" value="{{ url()->current() }}" readonly>
                    <button class="share-copy-btn" id="copyLinkBtn" style="width: unset;">
                        <i class="fa-solid fa-copy"></i> Copy
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('frontend/assets/js/global-loader.js') }}"></script>
<script src="{{ asset('frontend/assets/js/cart-wishlist.js') }}"></script>
<script>
$(document).ready(function() {
    const productUrl = '{{ url()->current() }}';
    const productTitle = '{{ $product->name }}';
    const productPrice = '£{{ number_format($product->price, 2) }}';
    const productImage = '{{ $product->image_url }}';

    function handleProductImgError(img) {
        img.onerror = null;
        img.src = window.PLACEHOLDER_IMG;
        img.classList.add('product-placeholder-image');

        const thumb = img.closest('.product-thumb');
        if (thumb) {
            thumb.style.backgroundImage = "url('" + window.PLACEHOLDER_IMG + "')";
            // Disable zoom cursor on placeholder
            thumb.style.cursor = 'default';
            thumb.removeAttribute('onmousemove');
        }
    }

    // Open share modal
    $('#shareProductBtn').on('click', function() {
        $('#shareModalOverlay').addClass('active');
        $('body').css('overflow', 'hidden');
    });

    // Close share modal
    $('#closeShareModal').on('click', function() {
        $('#shareModalOverlay').removeClass('active');
        $('body').css('overflow', 'auto');
    });

    $('#shareModalOverlay').on('click', function(e) {
        if (e.target === this) {
            $(this).removeClass('active');
            $('body').css('overflow', 'auto');
        }
    });

    // Share platform handlers
    $('.share-option').on('click', function(e) {
        e.preventDefault();
        const platform = $(this).data('platform');
        const shareText = `Check out ${productTitle} - ${productPrice}`;
        let shareUrl = '';

        switch(platform) {
            case 'whatsapp':
                shareUrl = `https://wa.me/?text=${encodeURIComponent(shareText + '\n' + productUrl)}`;
                window.open(shareUrl, '_blank');
                break;
            case 'instagram':
                // Instagram doesn't have direct share URL, copy link instead
                copyToClipboard();
                alert('Link copied! You can now share it on Instagram.');
                break;
            case 'telegram':
                shareUrl = `https://t.me/share/url?url=${encodeURIComponent(productUrl)}&text=${encodeURIComponent(shareText)}`;
                window.open(shareUrl, '_blank');
                break;
            case 'x':
                shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(shareText)}&url=${encodeURIComponent(productUrl)}`;
                window.open(shareUrl, '_blank', 'width=600,height=400');
                break;

            case 'facebook':
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${productUrl}`;
                break;

            case 'copy':
                copyToClipboard();
                break;
        }
    });

    // Copy link button
    $('#copyLinkBtn').on('click', function() {
        copyToClipboard();
    });

    function copyToClipboard() {
        const url = window.location.href; // ✅ Always use current URL
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(() => {
                const btn = document.getElementById('copyLinkBtn');
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
                btn.classList.add('copied');
                setTimeout(() => {
                    btn.innerHTML = '<i class="fa-solid fa-copy"></i> Copy';
                    btn.classList.remove('copied');
                }, 2000);
            });
        } else {
            // Fallback for non-HTTPS
            const input = document.getElementById('shareProductUrl');
            input.select();
            document.execCommand('copy');
        }
    }

    document.getElementById('copyLinkBtn').onclick = copyToClipboard;

    // Cart & Wishlist
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();

        const btn       = $(this);
        const productId = btn.data('product-id');

        if (btn.data('pending')) return;

        btn.data('pending', true)
        .prop('disabled', true)
        .html(`<div class="btn-text">Adding...</div><div class="arrow-icon"><i class="fa-solid fa-spinner fa-spin"></i></div>`);

        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', product_id: productId, quantity: 1 },
            success: function (res) {
                if (res.success) {
                    btn.html(`<div class="btn-text">Added!</div><div class="arrow-icon"><i class="fa-solid fa-circle-check"></i></div>`);
                    $(document).trigger('cart:updated', [res.cart]);
                    Toast.success(res.message);
                    setTimeout(() => {
                        btn.prop('disabled', false)
                        .data('pending', false)
                        .html(`<div class="btn-text">Add To Cart</div><div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>`);
                    }, 1500);
                } else {
                    Toast.error(res.message);
                    btn.prop('disabled', false).data('pending', false)
                    .html(`<div class="btn-text">Add To Cart</div><div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>`);
                }
            },
            error: function (xhr) {
                Toast.error(xhr.responseJSON?.message || 'Failed to add to cart.');
                btn.prop('disabled', false).data('pending', false)
                .html(`<div class="btn-text">Add To Cart</div><div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>`);
            }
        });
    });

    if (typeof window.initializeWishlistStates === 'function') {
        window.initializeWishlistStates();
    }

    // Image gallery
    $('.thumb-filter').on('click', function() {
        $('.thumb-filter').removeClass('active');
        $(this).addClass('active');
        const target = $(this).data('show');
        $('.thumb-wrapper').addClass('hide');
        $(target).removeClass('hide');
    });

    // Zoom
    window.zoom = function(e) {
        const zoomer = e.currentTarget;
        // Only apply position tracking — CSS :hover handles background-size
        const rect = zoomer.getBoundingClientRect();
        const offsetX = e.clientX - rect.left;
        const offsetY = e.clientY - rect.top;
        const x = (offsetX / zoomer.offsetWidth) * 100;
        const y = (offsetY / zoomer.offsetHeight) * 100;
        zoomer.style.backgroundPosition = x + '% ' + y + '%';
    }

    // Close modal with Escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#shareModalOverlay').hasClass('active')) {
            $('#shareModalOverlay').removeClass('active');
            $('body').css('overflow', 'auto');
        }
    });

    // ── Star Rating Picker (Write form) ──────────────────
    $(document).on('mouseover', '#starInput .star-pick', function () {
        const val = $(this).data('value');
        $('#starInput .star-pick').each(function () {
            $(this).toggleClass('fa-solid hovered', $(this).data('value') <= val)
                .toggleClass('fa-regular', $(this).data('value') > val);
        });
    }).on('mouseleave', '#starInput', function () {
        const selected = parseInt($('#ratingValue').val()) || 0;
        $('#starInput .star-pick').each(function () {
            $(this).toggleClass('fa-solid selected', $(this).data('value') <= selected)
                .toggleClass('fa-regular', $(this).data('value') > selected);
        });
    }).on('click', '#starInput .star-pick', function () {
        const val = $(this).data('value');
        $('#ratingValue').val(val);
        $('#starInput .star-pick').each(function () {
            $(this).toggleClass('fa-solid selected', $(this).data('value') <= val)
                .toggleClass('fa-regular', $(this).data('value') > val);
        });
    });

    // ── Submit Review ───────────────────────
    $(document).on('submit', '#reviewForm', function (e) {
        e.preventDefault();

        if (!$('#ratingValue').val()) {
            Toast.warning('Please select a star rating.');
            return;
        }

        const btn = $('#submitReviewBtn');
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Submitting...');

        $.ajax({
            url: '{{ route("reviews.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    Toast.success(res.message);

                    // Build and prepend the new review card
                    const newCard = buildReviewCard(res.review);
                    $('#noReviewsState').remove();
                    $('#reviewsList').prepend(newCard);

                    // Hide the write form, show reviewed notice
                    $('#writeReviewCard').html(`
                        <div class="review-notice reviewed">
                            <i class="fa-solid fa-circle-check"></i>
                            <div>
                                <strong>Review submitted successfully!</strong>
                                <p>Thank you for sharing your feedback.</p>
                            </div>
                        </div>
                    `);
                } else {
                    Toast.error(res.message);
                    btn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane"></i> Submit Review');
                }
            },
            error: function (xhr) {
                Toast.error(xhr.responseJSON?.message || 'Failed to submit review.');
                btn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane"></i> Submit Review');
            }
        });
    });

    // Build a review card HTML from data
    function buildReviewCard(review) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += `<i class="fa-${i <= review.rating ? 'solid' : 'regular'} fa-star"></i>`;
        }
        return `
            <div class="review-card" id="review-card-${review.id}">
                <div class="review-card-header">
                    <img src="${review.avatar}" alt="${review.name}" class="reviewer-avatar">
                    <div class="reviewer-info">
                        <span class="reviewer-name">${review.name}</span>
                        <span class="review-date">${review.date}</span>
                    </div>
                    <div class="review-stars ms-auto">${stars}</div>
                    <div class="review-actions ms-2 d-flex gap-2">
                        <button class="btn-review-edit"
                                data-review-id="${review.id}"
                                data-rating="${review.rating}"
                                data-body="${review.body || ''}"
                                title="Edit">
                            <i class="fa-regular fa-pen"></i>
                        </button>
                        <button class="btn-review-delete"
                                data-review-id="${review.id}"
                                title="Delete">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                </div>
                <div class="review-body-content">
                    ${review.body ? `<p class="review-body">${review.body}</p>` : ''}
                </div>
            </div>
        `;
    }

    // ── Edit Review ─────────────────────────────────────
    $(document).on('click', '.btn-review-edit', function () {
        const id     = $(this).data('review-id');
        const rating = $(this).data('rating');
        const body   = $(this).data('body') || '';

        $('#editReviewId').val(id);
        $('#editReviewBody').val(body);
        $('#editRatingValue').val(rating);

        $('#editStarInput .star-pick').each(function () {
            const v = parseInt($(this).data('value'));
            $(this).toggleClass('fa-solid selected', v <= rating)
                .toggleClass('fa-regular', v > rating);
        });

        $('#reviewEditOverlay').fadeIn(200);
        $('body').css('overflow', 'hidden');
    });

    $('#closeReviewEdit, #cancelReviewEdit').on('click', function () {
        $('#reviewEditOverlay').fadeOut(200);
        $('body').css('overflow', 'auto');
    });

    $('#reviewEditOverlay').on('click', function (e) {
        if ($(e.target).is('#reviewEditOverlay')) {
            $(this).fadeOut(200);
            $('body').css('overflow', 'auto');
        }
    });

    // Edit modal stars
    $(document).on('mouseover', '#editStarInput .star-pick', function () {
        const val = $(this).data('value');
        $('#editStarInput .star-pick').each(function () {
            $(this).toggleClass('fa-solid hovered', $(this).data('value') <= val)
                .toggleClass('fa-regular', $(this).data('value') > val);
        });
    }).on('mouseleave', '#editStarInput', function () {
        const selected = parseInt($('#editRatingValue').val()) || 0;
        $('#editStarInput .star-pick').each(function () {
            $(this).toggleClass('fa-solid selected', $(this).data('value') <= selected)
                .toggleClass('fa-regular', $(this).data('value') > selected);
        });
    }).on('click', '#editStarInput .star-pick', function () {
        const val = $(this).data('value');
        $('#editRatingValue').val(val);
        $('#editStarInput .star-pick').each(function () {
            $(this).toggleClass('fa-solid selected', $(this).data('value') <= val)
                .toggleClass('fa-regular', $(this).data('value') > val);
        });
    });

    // Submit edit
    $('#editReviewForm').on('submit', function (e) {
        e.preventDefault();
        const id  = $('#editReviewId').val();
        const btn = $('#updateReviewBtn');

        if (!$('#editRatingValue').val()) {
            Toast.warning('Please select a rating.');
            return;
        }

        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: `/reviews/${id}`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PUT',
                rating: $('#editRatingValue').val(),
                body:   $('#editReviewBody').val(),
            },
            success: function (res) {
                if (res.success) {
                    const card = $(`#review-card-${id}`);
                    let stars = '';
                    for (let i = 1; i <= 5; i++) {
                        stars += `<i class="fa-${i <= res.review.rating ? 'solid' : 'regular'} fa-star"></i>`;
                    }
                    card.find('.review-stars').html(stars);
                    card.find('.review-body').text(res.review.body || '').toggle(!!res.review.body);
                    card.find('.btn-review-edit')
                        .data('rating', res.review.rating)
                        .data('body', res.review.body);

                    $('#reviewEditOverlay').fadeOut(200);
                    $('body').css('overflow', 'auto');
                    Toast.success(res.message);
                } else {
                    Toast.error(res.message);
                }
                btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i> Save Changes');
            },
            error: function (xhr) {
                Toast.error(xhr.responseJSON?.message || 'Failed to update review.');
                btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i> Save Changes');
            }
        });
    });

    // ── Delete Review ─────────────────────────────────
    let pendingDeleteId = null;

    $(document).on('click', '.btn-review-delete', function () {
        pendingDeleteId = $(this).data('review-id');
        $('#deleteReviewOverlay').fadeIn(200);
        $('body').css('overflow', 'hidden');
    });

    $('#cancelDeleteModal').on('click', function () {
        $('#deleteReviewOverlay').fadeOut(200);
        $('body').css('overflow', 'auto');
        pendingDeleteId = null;
    });

    $('#deleteReviewOverlay').on('click', function (e) {
        if ($(e.target).is('#deleteReviewOverlay')) {
            $(this).fadeOut(200);
            $('body').css('overflow', 'auto');
            pendingDeleteId = null;
        }
    });

    $('#confirmDeleteModal').on('click', function () {
        if (!pendingDeleteId) return;

        const id   = pendingDeleteId;
        const card = $(`#review-card-${id}`);
        const btn  = $(this);

        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Deleting...');

        $.ajax({
            url: `/reviews/${id}`,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
            success: function (res) {
                if (res.success) {
                    $('#deleteReviewOverlay').fadeOut(200);
                    $('body').css('overflow', 'auto');

                    card.slideUp(300, function () { $(this).remove(); });

                    // ✅ Replace "already reviewed" notice with write form
                    $('#reviewFormArea').html(`
                        <div class="write-review-card" id="writeReviewCard">
                            <h5 class="review-form-title">
                                <i class="fa-solid fa-pen-to-square"></i> Write a Review
                            </h5>
                            <form id="reviewForm">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="star-rating-input mb-3">
                                    <label class="form-label">Your Rating <span class="text-danger">*</span></label>
                                    <div class="star-input-group" id="starInput">
                                        <i class="fa-regular fa-star star-pick" data-value="1"></i>
                                        <i class="fa-regular fa-star star-pick" data-value="2"></i>
                                        <i class="fa-regular fa-star star-pick" data-value="3"></i>
                                        <i class="fa-regular fa-star star-pick" data-value="4"></i>
                                        <i class="fa-regular fa-star star-pick" data-value="5"></i>
                                    </div>
                                    <input type="hidden" name="rating" id="ratingValue" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Your Review</label>
                                    <textarea name="body" class="form-control" rows="4"
                                            placeholder="Share your honest experience with this product..."
                                            maxlength="1000"></textarea>
                                </div>
                                <button type="submit" class="btn-submit-review" id="submitReviewBtn" style="width:unset;">
                                    <i class="fa-solid fa-paper-plane"></i> Submit Review
                                </button>
                            </form>
                        </div>
                    `);

                    Toast.success(res.message);
                    pendingDeleteId = null;
                } else {
                    Toast.error(res.message);
                }
                btn.prop('disabled', false).html('<i class="fa-regular fa-trash-can"></i> Yes, Delete');
            },
            error: function () {
                Toast.error('Failed to delete review.');
                btn.prop('disabled', false).html('<i class="fa-regular fa-trash-can"></i> Yes, Delete');
            }
        });
    });

    // ── Listen for cart:updated from product detail page ──
    $(document).on('cart:updated', function (e, cart) {
        Cart.updateUI(cart);
    });

});

// ── Weight-based product logic ─────────────────────────
@if($product->is_weight_based)
(function () {
    const pricePerKg = parseFloat('{{ (float)$product->final_price }}');
    const minWeight  = parseFloat('{{ (float)($product->min_weight ?? 0.5) }}');
    const maxWeight  = parseFloat('{{ (float)($product->max_weight ?? 3) }}');
    const step       = 0.5;

    const $input        = $('#weightInput');
    const $display      = $('#weightDisplay');
    const $totalPrice   = $('#weightTotalPrice');
    const $minusBtn     = $('#weightMinus');
    const $plusBtn      = $('#weightPlus');
    const $quickBtns    = $('.weight-quick-btn');
    const $addToCartBtn = $('#weightAddToCartBtn');

    function clamp(val) {
        return Math.max(minWeight, Math.min(maxWeight, val));
    }

    function snapToStep(val) {
        const snapped = Math.round(val / step) * step;
        return clamp(parseFloat(snapped.toFixed(2)));
    }

    function getCurrentWeight() {
        const raw = parseFloat($input.val()) || minWeight;
        return snapToStep(raw);
    }

    function updateUI(weight) {
        const w = snapToStep(weight);
        $input.val(w.toFixed(1));
        $display.text(w.toFixed(1));
        $totalPrice.text((w * pricePerKg).toFixed(2));

        $quickBtns.removeClass('active');
        $quickBtns.each(function () {
            const val = parseFloat($(this).data('weight'));
            if (Math.abs(val - w) < 0.001) {
                $(this).addClass('active');
            }
        });

        $minusBtn.prop('disabled', w <= minWeight);
        $plusBtn.prop('disabled', w >= maxWeight);
    }

    // Init
    updateUI(minWeight);

    $input.on('input', function () {
        const w = getCurrentWeight();
        updateUI(w);
    });

    $plusBtn.on('click', function () {
        const w = getCurrentWeight() + step;
        updateUI(w);
    });

    $minusBtn.on('click', function () {
        const w = getCurrentWeight() - step;
        updateUI(w);
    });

    $quickBtns.on('click', function () {
        const w = parseFloat($(this).data('weight'));
        updateUI(w);
    });

    let weightCartPending = false;

    $addToCartBtn.on('click', function () {
        if (weightCartPending) return;

        const btn = $(this);
        const w   = getCurrentWeight();

        if (w < minWeight) {
            Toast.warning(`Minimum order is ${minWeight}kg.`);
            return;
        }

        weightCartPending = true;
        btn.prop('disabled', true).html(`
            <div class="btn-text">Adding...</div>
            <div class="arrow-icon"><i class="fa-solid fa-spinner fa-spin"></i></div>
        `);

        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                _token:     '{{ csrf_token() }}',
                product_id: '{{ $product->id }}',
                quantity:   1,
                weight:     w,
            },
            success: function (res) {
                if (res.success) {
                    btn.html(`
                        <div class="btn-text">Added!</div>
                        <div class="arrow-icon"><i class="fa-solid fa-circle-check"></i></div>
                    `);

                    // Update cart count/UI if your cart-wishlist.js exposes this
                    if (typeof window.Cart !== 'undefined' && window.Cart.updateUI) {
                        window.Cart.updateUI(res.cart);
                    }

                    // Also trigger the global cart count update
                    $(document).trigger('cart:updated', [res.cart]);

                    Toast.success(res.message);

                    setTimeout(() => {
                        btn.prop('disabled', false).html(`
                            <div class="btn-text">Add To Cart</div>
                            <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                        `);
                        weightCartPending = false;
                    }, 1500);
                } else {
                    Toast.error(res.message);
                    btn.prop('disabled', false).html(`
                        <div class="btn-text">Add To Cart</div>
                        <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                    `);
                    weightCartPending = false;
                }
            },
            error: function (xhr) {
                Toast.error(xhr.responseJSON?.message || 'Failed to add to cart.');
                btn.prop('disabled', false).html(`
                    <div class="btn-text">Add To Cart</div>
                    <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                `);
                weightCartPending = false;
            }
        });
    });

})();
@endif

</script>
@endpush
