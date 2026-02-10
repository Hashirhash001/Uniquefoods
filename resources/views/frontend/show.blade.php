@extends('frontend.layouts.app')

@section('title', $product->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/assets/css/product-details.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/shop.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/cart-wishlist.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/global-loader.css') }}">
<style>
/* Modern Share Modal Styles */
.share-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.share-modal-overlay.active {
    display: flex;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes modalSlideUp {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.share-modal {
    background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
    border-radius: 24px;
    padding: 0;
    max-width: 480px;
    width: 92%;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25);
    animation: modalSlideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
}

/* Modern gradient border effect */
.share-modal::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #25D366, #E1306C, #0088cc, #000000);
    background-size: 200% 100%;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.share-modal-header {
    padding: 28px 28px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.share-modal-header h3 {
    font-size: 24px;
    font-weight: 800;
    color: #111827;
    margin: 0;
    letter-spacing: -0.5px;
    background: linear-gradient(135deg, #111827, #374151);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.share-modal-close {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f3f4f6;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.share-modal-close::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: #ef4444;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translate(-50%, -50%);
}

.share-modal-close:hover::before {
    width: 100%;
    height: 100%;
}

.share-modal-close:hover {
    transform: rotate(90deg);
}

.share-modal-close i {
    font-size: 18px;
    color: #6b7280;
    position: relative;
    z-index: 1;
    transition: color 0.3s;
}

.share-modal-close:hover i {
    color: #ffffff;
}

.share-modal-body {
    padding: 0 28px 28px;
}

/* Modern Product Preview Card */
.share-product-preview {
    display: flex;
    gap: 16px;
    padding: 20px;
    background: linear-gradient(135deg, #ffffff, #f9fafb);
    border-radius: 16px;
    margin-bottom: 28px;
    border: 1px solid #e5e7eb;
    position: relative;
    overflow: hidden;
    transition: all 0.3s;
}

.share-product-preview::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(98, 157, 35, 0.03), rgba(98, 157, 35, 0.08));
    opacity: 0;
    transition: opacity 0.3s;
}

.share-product-preview:hover::before {
    opacity: 1;
}

.share-product-image {
    width: 90px;
    height: 90px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    position: relative;
}

.share-product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.share-product-preview:hover .share-product-image img {
    transform: scale(1.1);
}

.share-product-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.share-product-info h4 {
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
}

.share-product-price {
    font-size: 22px;
    font-weight: 800;
    background: linear-gradient(135deg, #629d23, #7cb929);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Modern Share Options Grid */
.share-options-section {
    margin-bottom: 24px;
}

.share-section-label {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 16px;
}

.share-options-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
}

.share-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 16px 8px;
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    background: #ffffff;
    border: 2px solid #f3f4f6;
    position: relative;
    overflow: hidden;
}

.share-option::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.02), rgba(0, 0, 0, 0.05));
    opacity: 0;
    transition: opacity 0.3s;
}

.share-option:hover {
    transform: translateY(-6px);
    border-color: transparent;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
}

.share-option:hover::before {
    opacity: 1;
}

.share-option:active {
    transform: translateY(-2px);
}

.share-option-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #ffffff;
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.share-option:hover .share-option-icon {
    transform: scale(1.15) rotate(-5deg);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
}

/* Modern gradient backgrounds with glass effect */
.share-option-icon.whatsapp {
    background: linear-gradient(135deg, #25D366, #128C7E);
}

.share-option-icon.instagram {
    background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
}

.share-option-icon.telegram {
    background: linear-gradient(135deg, #0088cc, #006699);
}

.share-option-icon.x {
    background: linear-gradient(135deg, #000000, #1a1a1a);
}

.share-option-icon.copy {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
}

.share-option-label {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-align: center;
    transition: color 0.3s;
}

.share-option:hover .share-option-label {
    color: #111827;
}

/* Modern Copy Link Section */
.share-link-section {
    background: linear-gradient(135deg, #f9fafb, #ffffff);
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    padding: 16px;
}

.share-link-label {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 12px;
    display: block;
}

.share-link-copy {
    display: flex;
    gap: 10px;
    background: #ffffff;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 12px 14px;
    transition: all 0.3s;
}

.share-link-copy:focus-within {
    border-color: #629d23;
    box-shadow: 0 0 0 4px rgba(98, 157, 35, 0.1);
}

.share-link-copy input {
    flex: 1;
    border: none;
    background: transparent;
    font-size: 14px;
    color: #374151;
    outline: none;
    font-weight: 500;
}

.share-copy-btn {
    background: linear-gradient(135deg, #629d23, #7cb929);
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(98, 157, 35, 0.3);
    position: relative;
    overflow: hidden;
}

.share-copy-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transition: all 0.5s;
    transform: translate(-50%, -50%);
}

.share-copy-btn:hover::before {
    width: 300px;
    height: 300px;
}

.share-copy-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(98, 157, 35, 0.4);
}

.share-copy-btn:active {
    transform: translateY(0);
}

.share-copy-btn.copied {
    background: linear-gradient(135deg, #10b981, #059669);
}

.share-copy-btn i {
    position: relative;
    z-index: 1;
}

/* Modern Share Button */
.share-option-shop-details {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.share-button-main {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #ffffff, #f9fafb);
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    color: #374151;
    font-size: 15px;
    font-weight: 700;
    position: relative;
    overflow: hidden;
}

.share-button-main::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(98, 157, 35, 0.1), transparent);
    transition: left 0.5s;
}

.share-button-main:hover::before {
    left: 100%;
}

.share-button-main:hover {
    border-color: #629d23;
    color: #629d23;
    background: #f0fdf4;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(98, 157, 35, 0.15);
}

.share-button-main i {
    font-size: 18px;
    transition: transform 0.3s;
}

.share-button-main:hover i {
    transform: rotate(15deg) scale(1.1);
}

/* Responsive */
@media (max-width: 576px) {
    .share-modal {
        width: 96%;
        border-radius: 20px;
    }

    .share-options-grid {
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
    }

    .share-option {
        padding: 12px 6px;
    }

    .share-option-icon {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }

    .share-option-label {
        font-size: 11px;
    }

    .share-modal-header h3 {
        font-size: 20px;
    }
}

/* Success animation */
@keyframes successPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.share-copy-btn.copied {
    animation: successPulse 0.4s ease;
}
</style>
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

<div class="section-seperator bglight-1">
    <div class="container"><hr class="section-seperator"></div>
</div>

<!-- Product Details Section -->
<div class="rts-shop-details-area rts-section-gap bglight-1">
    <div class="container">
        <div class="row g-5">
            <!-- Left: Product Images -->
            <div class="col-xl-8 col-lg-8 col-md-12">
                <div class="product-details-popup-wrapper in-shopdetails">
                    <div class="rts-product-details-section rts-product-details-section2 product-details-popup-section">
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
                                <div class="contents">
                                    <div class="product-status">
                                        @if($product->category)
                                            <span class="product-category">{{ $product->category->name }}</span>
                                        @endif
                                    </div>

                                    <div class="rating-stars-group">
                                        <div class="rating-star">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= 4)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="fas fa-star-half-alt"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span>(10 Reviews)</span>
                                    </div>

                                    <h2 class="product-title">{{ $product->name }}</h2>

                                    <p class="mt--20 mb--20">
                                        {{ $product->short_description ?? Str::limit($product->description, 150) }}
                                    </p>

                                    <div class="product-price-wrapper">
                                        <span class="product-price mb--15 d-block" style="color: #DC2626; font-weight: 600;">
                                            ₹{{ number_format($product->price, 2) }}
                                        </span>
                                        @if($product->mrp && $product->mrp > $product->price)
                                            <span class="old-price ml--15">₹{{ number_format($product->mrp, 2) }}</span>
                                            <span class="save-badge">Save ₹{{ number_format($product->mrp - $product->price, 2) }} ({{ round((($product->mrp - $product->price) / $product->mrp) * 100) }}%)</span>
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
                                        <button class="rts-btn btn-primary radious-sm with-icon add-to-cart-btn"
                                                data-product-id="{{ $product->id }}"
                                                {{ $product->stock == 0 ? 'disabled' : '' }}>
                                            <div class="btn-text">
                                                {{ $product->stock > 0 ? 'Add To Cart' : 'Out of Stock' }}
                                            </div>
                                            <div class="arrow-icon">
                                                <i class="fa-regular fa-cart-shopping"></i>
                                            </div>
                                        </button>

                                        <button class="rts-btn btn-primary wishlist-toggle-btn"
                                                data-product-id="{{ $product->id }}"
                                                title="Add to Wishlist">
                                            <i class="fa-light fa-heart"></i>
                                        </button>
                                    </div>

                                    <div class="product-uniques">
                                        <span class="sku product-unique mb--10">
                                            <span style="font-weight: 400; margin-right: 10px;">SKU:</span>
                                            <span>{{ $product->sku ?? 'N/A' }}</span>
                                        </span>

                                        @if($product->category)
                                            <span class="categories product-unique mb--10">
                                                <span style="font-weight: 400; margin-right: 10px;">Category:</span>
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
                                    <div class="share-option-shop-details">
                                        <button class="share-button-main" id="shareProductBtn">
                                            <i class="fa-solid fa-share-nodes"></i>
                                            <span>Share Product</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Tabs -->
                <div class="product-description-tab-shop mt--50">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab"
                                    data-bs-target="#details-tab-pane" type="button" role="tab">
                                Product Details
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="additional-tab" data-bs-toggle="tab"
                                    data-bs-target="#additional-tab-pane" type="button" role="tab">
                                Additional Information
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab"
                                    data-bs-target="#reviews-tab-pane" type="button" role="tab">
                                Customer Reviews (10)
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="details-tab-pane" role="tabpanel">
                            <div class="single-tab-content-shop-details">
                                <p class="disc">{!! nl2br(e($product->description)) !!}</p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="additional-tab-pane" role="tabpanel">
                            <div class="single-tab-content-shop-details">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td><strong>Weight</strong></td>
                                            <td>{{ $product->weight ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dimensions</strong></td>
                                            <td>{{ $product->dimensions ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Brand</strong></td>
                                            <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel">
                            <div class="single-tab-content-shop-details">
                                <p class="disc">Reviews coming soon...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="theiaStickySidebar">
                    <div class="shop-sight-sticky-sidebar mb--20">
                        <h6 class="title">Available Offers</h6>
                        <div class="single-offer-area">
                            <div class="icon">
                                <img src="{{ asset('frontend/assets/images/shop/01.svg') }}" alt="icon">
                            </div>
                            <div class="details">
                                <p>Get 5% instant discount for the 1st order using UPI T&C</p>
                            </div>
                        </div>
                        <div class="single-offer-area">
                            <div class="icon">
                                <img src="{{ asset('frontend/assets/images/shop/02.svg') }}" alt="icon">
                            </div>
                            <div class="details">
                                <p>Flat ₹250 off on Credit Card EMI on orders of ₹1000+ T&C</p>
                            </div>
                        </div>
                        <div class="single-offer-area">
                            <div class="icon">
                                <img src="{{ asset('frontend/assets/images/shop/03.svg') }}" alt="icon">
                            </div>
                            <div class="details">
                                <p>Free delivery on orders above ₹500 T&C</p>
                            </div>
                        </div>
                    </div>

                    <div class="shop-sight-sticky-sidebar">
                        <h6 class="title">Delivery & Returns</h6>
                        <div class="delivery-info">
                            <div class="info-item">
                                <i class="fa-solid fa-truck-fast"></i>
                                <div>
                                    <strong>Free Delivery</strong>
                                    <p>On orders above ₹500</p>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
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
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
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
                                <span class="price-current">₹{{ number_format($related->price, 2) }}</span>
                                @if($related->mrp && $related->mrp > $related->price)
                                    <span class="price-original">₹{{ number_format($related->mrp, 2) }}</span>
                                    <span class="price-save">Save ₹{{ number_format($related->mrp - $related->price, 2) }}</span>
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
                    <div class="share-product-price">₹{{ number_format($product->price, 2) }}</div>
                </div>
            </div>

            <!-- Share Options -->
            <div class="share-options-section">
                <div class="share-section-label">Share via</div>
                <div class="share-options-grid">
                    <a href="#" class="share-option" data-platform="whatsapp">
                        <div class="share-option-icon whatsapp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <span class="share-option-label">WhatsApp</span>
                    </a>
                    <a href="#" class="share-option" data-platform="instagram">
                        <div class="share-option-icon instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </div>
                        <span class="share-option-label">Instagram</span>
                    </a>
                    <a href="#" class="share-option" data-platform="telegram">
                        <div class="share-option-icon telegram">
                            <i class="fa-brands fa-telegram"></i>
                        </div>
                        <span class="share-option-label">Telegram</span>
                    </a>
                    <a href="#" class="share-option" data-platform="x">
                        <div class="share-option-icon x">
                            <i class="fa-brands fa-x-twitter"></i>
                        </div>
                        <span class="share-option-label">X</span>
                    </a>
                    <a href="#" class="share-option" data-platform="copy">
                        <div class="share-option-icon copy">
                            <i class="fa-solid fa-link"></i>
                        </div>
                        <span class="share-option-label">Copy Link</span>
                    </a>
                </div>
            </div>

            <!-- Copy Link Section -->
            <div class="share-link-section">
                <label class="share-link-label">Product Link</label>
                <div class="share-link-copy">
                    <input type="text" id="shareProductUrl" value="{{ url()->current() }}" readonly>
                    <button class="share-copy-btn" id="copyLinkBtn">
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
    const productPrice = '₹{{ number_format($product->price, 2) }}';
    const productImage = '{{ $product->image_url }}';

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
        const input = document.getElementById('shareProductUrl');
        input.select();
        input.setSelectionRange(0, 99999);

        try {
            document.execCommand('copy');
            const btn = $('#copyLinkBtn');
            btn.html('<i class="fa-solid fa-check"></i> Copied!').addClass('copied');

            setTimeout(() => {
                btn.html('<i class="fa-solid fa-copy"></i> Copy').removeClass('copied');
            }, 2000);
        } catch (err) {
            console.error('Failed to copy:', err);
        }
    }

    // Cart & Wishlist
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        if (typeof window.Cart !== 'undefined') {
            window.Cart.add(productId, 1);
        }
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
        const offsetX = e.offsetX ? e.offsetX : e.touches[0].pageX;
        const offsetY = e.offsetY ? e.offsetY : e.touches[0].pageY;
        const x = offsetX / zoomer.offsetWidth * 100;
        const y = offsetY / zoomer.offsetHeight * 100;
        zoomer.style.backgroundPosition = x + '% ' + y + '%';
    };

    // Close modal with Escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#shareModalOverlay').hasClass('active')) {
            $('#shareModalOverlay').removeClass('active');
            $('body').css('overflow', 'auto');
        }
    });
});
</script>
@endpush
