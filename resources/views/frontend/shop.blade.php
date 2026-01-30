@extends('frontend.layouts.app')

@section('title', 'Shop - All Products')

@push('styles')
<!-- noUiSlider CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
<style>
/* Brand Color Variables */
:root {
    --color-primary: #629D23;
    --color-primary-hover: #518219;
    --color-primary-light: rgba(98, 157, 35, 0.1);
    --text-dark: #1a1a1a;
    --text-light: #666;
    --border-color: #e0e0e0;
    --bg-light: #f8f9fa;
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

/* Responsive */
@media (max-width: 767px) {
    .product-main-image {
        height: 200px;
    }

    .price-current {
        font-size: 18px;
    }
}

input[type=checkbox]~label::before{
    position: unset !important;
    opacity: 0 !important;
}

input[type=checkbox]~label::after{
    position: unset !important;
    opacity: 0 !important;
}

input[type=checkbox]~label, input[type=radio]~label{
    padding-left: 5px !important;
}

/* ===== SHOP FILTER SIDEBAR ===== */
.shop-filter-sidebar {
    background: #fff !important;
    border-radius: 12px !important;
    padding: 0 !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06) !important;
}

.shop-filter-header {
    padding: 20px 24px !important;
    border-bottom: 1px solid var(--border-color) !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
}

.shop-filter-header h5 {
    margin: 0 !important;
    font-size: 18px !important;
    font-weight: 700 !important;
    color: var(--text-dark) !important;
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
}

.shop-filter-header i {
    color: var(--color-primary) !important;
    font-size: 16px !important;
}

.shop-clear-all-btn {
    color: var(--color-primary) !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    text-decoration: none !important;
    transition: all 0.2s !important;
    border: none !important;
    background: none !important;
    padding: 6px 12px !important;
    border-radius: 6px !important;
    width: unset !important;
}

.shop-clear-all-btn:hover {
    background: var(--color-primary-light) !important;
    color: var(--color-primary-hover) !important;
}

.shop-filter-section {
    border-bottom: 1px solid var(--border-color) !important;
    padding: 20px 24px !important;
    overflow: visible !important;
}

.shop-filter-section:last-child {
    border-bottom: none !important;
}

.shop-filter-title {
    font-size: 15px !important;
    font-weight: 600 !important;
    color: var(--text-dark) !important;
    margin-bottom: 16px !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    cursor: pointer !important;
    user-select: none !important;
}

.shop-filter-title i {
    font-size: 12px !important;
    color: #999 !important;
    transition: transform 0.3s !important;
}

.shop-filter-title.collapsed i {
    transform: rotate(-90deg) !important;
}

.shop-filter-options {
    max-height: 300px !important;
    overflow-y: auto !important;
    overflow-x: visible !important;
    transition: max-height 0.3s ease !important;
}

.shop-filter-options.collapsed {
    max-height: 0 !important;
    overflow: hidden !important;
}

.shop-filter-options::-webkit-scrollbar {
    width: 6px !important;
}

.shop-filter-options::-webkit-scrollbar-thumb {
    background: #ddd !important;
    border-radius: 3px !important;
}

/* ===== CUSTOM CHECKBOX DESIGN ===== */
.shop-filter-option {
    display: flex !important;
    align-items: center !important;
    margin-bottom: 14px !important;
    cursor: pointer !important;
    position: relative !important;
}

.shop-filter-option input[type="checkbox"] {
    position: absolute !important;
    opacity: 0 !important;
    cursor: pointer !important;
    width: 0 !important;
    height: 0 !important;
}

.shop-checkbox-custom {
    width: 20px !important;
    height: 20px !important;
    border: 2px solid #ddd !important;
    border-radius: 6px !important;
    margin-right: 12px !important;
    cursor: pointer !important;
    position: relative !important;
    flex-shrink: 0 !important;
    transition: all 0.2s !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: #fff !important;
}

.shop-filter-option:hover .shop-checkbox-custom {
    border-color: var(--color-primary) !important;
}

.shop-filter-option input[type="checkbox"]:checked ~ .shop-checkbox-custom {
    background-color: var(--color-primary) !important;
    border-color: var(--color-primary) !important;
}

.shop-filter-option input[type="checkbox"]:checked ~ .shop-checkbox-custom::after {
    content: "✓" !important;
    color: white !important;
    font-size: 13px !important;
    font-weight: bold !important;
}

.shop-filter-option label {
    font-size: 14px !important;
    color: var(--text-light) !important;
    cursor: pointer !important;
    flex: 1 !important;
    margin: 0 !important;
    user-select: none !important;
}

.shop-filter-option:hover label {
    color: var(--text-dark) !important;
}

/* ===== MODERN PRICE SLIDER DESIGN ===== */
.shop-price-slider-wrapper {
    padding: 10px 15px 20px !important;
}

#shopPriceSlider {
    margin: 30px 0 25px !important;
    height: 6px !important;
}

/* Modern slider track */
.noUi-target {
    background: #e8eaed !important;
    border: none !important;
    box-shadow: none !important;
    border-radius: 999px !important;
    height: 6px !important;
}

/* Modern slider fill */
.noUi-connect {
    background: linear-gradient(90deg, var(--color-primary) 0%, var(--color-primary-hover) 100%) !important;
    box-shadow: 0 2px 8px rgba(98, 157, 35, 0.25) !important;
}

/* Modern slider handles */
.noUi-handle {
    width: 22px !important;
    height: 22px !important;
    border-radius: 50% !important;
    border: 3px solid var(--color-primary) !important;
    background: #ffffff !important;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15) !important;
    cursor: grab !important;
    top: -8px !important;
    right: -11px !important;
}

.noUi-handle:active {
    cursor: grabbing !important;
    box-shadow: 0 4px 16px rgba(98, 157, 35, 0.4) !important;
}

.noUi-handle:before,
.noUi-handle:after {
    display: none !important;
}

.noUi-horizontal .noUi-handle {
    width: 22px !important;
    height: 22px !important;
    right: -11px !important;
    top: -8px !important;
}

.shop-price-values {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
}

.shop-price-input-box {
    flex: 1 !important;
}

.shop-price-input-box label {
    font-size: 12px !important;
    color: var(--text-light) !important;
    display: block !important;
    margin-bottom: 8px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
}

.shop-price-input-box input {
    width: 100% !important;
    padding: 12px 14px !important;
    border: 2px solid #e8eaed !important;
    border-radius: 8px !important;
    font-size: 15px !important;
    font-weight: 600 !important;
    transition: all 0.2s !important;
    background: #f8f9fa !important;
    color: var(--text-dark) !important;
}

.shop-price-input-box input:focus {
    outline: none !important;
    border-color: var(--color-primary) !important;
    background: #fff !important;
}

.shop-price-separator {
    margin: 0 !important;
    padding-top: 30px !important;
    color: #ccc !important;
    font-weight: 300 !important;
}

/* ===== ACTIVE FILTERS BAR ===== */
.shop-active-filters-bar {
    background: var(--bg-light) !important;
    padding: 16px 24px !important;
    border-radius: 0 !important;
    margin: 0 !important;
    display: none !important;
    border-bottom: 1px solid var(--border-color) !important;
}

.shop-active-filters-bar.show {
    display: block !important;
}

.shop-active-filter-tag {
    display: inline-flex !important;
    align-items: center !important;
    background: #fff !important;
    border: 1px solid var(--border-color) !important;
    padding: 7px 14px !important;
    border-radius: 20px !important;
    margin: 4px !important;
    font-size: 13px !important;
    color: var(--text-dark) !important;
    font-weight: 500 !important;
}

.shop-active-filter-tag i {
    margin-left: 8px !important;
    cursor: pointer !important;
    color: #999 !important;
    transition: color 0.2s !important;
    font-size: 12px !important;
}

.shop-active-filter-tag i:hover {
    color: var(--color-primary-hover) !important;
}

/* ===== PRODUCT GRID HEADER ===== */
.shop-product-grid-header {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    padding: 18px 0 !important;
    border-bottom: 2px solid var(--border-color) !important;
    margin-bottom: 28px !important;
}

.shop-result-count {
    font-size: 15px !important;
    color: var(--text-light) !important;
    font-weight: 600 !important;
}

.shop-sort-dropdown-wrapper {
    position: relative !important;
}

.shop-sort-dropdown {
    padding: 12px 45px 12px 18px !important;
    border: 2px solid var(--border-color) !important;
    border-radius: 8px !important;
    font-size: 14px !important;
    background: #fff !important;
    cursor: pointer !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    background-repeat: no-repeat !important;
    background-position: right 15px center !important;
    min-width: 220px !important;
    transition: border-color 0.2s !important;
    color: var(--text-dark) !important;
    font-weight: 600 !important;
    line-height: 1 !important;
}

.shop-sort-dropdown:focus {
    outline: none !important;
    border-color: var(--color-primary) !important;
}

.shop-sort-dropdown::-ms-expand {
    display: none !important;
}

.shop-sort-dropdown option {
    padding: 10px !important;
    color: var(--text-dark) !important;
}

/* ===== PRODUCT DISCOUNT BADGE ===== */
.shop-discount-badge {
    position: absolute !important;
    top: 12px !important;
    left: 12px !important;
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
    color: #ffffff !important;
    padding: 6px 12px !important;
    border-radius: 8px !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    z-index: 5 !important;
    box-shadow: 0 3px 10px rgba(231, 76, 60, 0.35) !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
    letter-spacing: 0.3px !important;
}

.shop-discount-badge span {
    line-height: 1 !important;
    white-space: nowrap !important;
}

.shop-discount-badge i {
    display: none !important;
}

/* ===== MODERN PRODUCT CARD HOVER ACTIONS ===== */
.shop-action-buttons {
    position: absolute !important;
    bottom: 16px !important;
    left: 50% !important;
    transform: translateX(-50%) translateY(120%) !important;
    display: flex !important;
    gap: 0 !important;
    opacity: 0 !important;
    visibility: hidden !important;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
    z-index: 10 !important;
    background: rgba(255, 255, 255, 0.98) !important;
    border-radius: 50px !important;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
    padding: 4px !important;
}

.single-shopping-card-one:hover .shop-action-buttons {
    opacity: 1 !important;
    visibility: visible !important;
    transform: translateX(-50%) translateY(0) !important;
}

.shop-action-btn {
    width: 48px !important;
    height: 48px !important;
    background: transparent !important;
    border: none !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    border-radius: 50% !important;
    position: relative !important;
}

.shop-action-btn:hover {
    background: var(--color-primary-light) !important;
    /* transform: scale(1.15) !important; */
}

.shop-action-btn i {
    font-size: 20px !important;
    color: var(--text-dark) !important;
    transition: all 0.25s !important;
}

.shop-action-btn:hover i {
    color: var(--color-primary) !important;
}

.shop-action-btn.active {
    background: var(--color-primary-light) !important;
}

.shop-action-btn.active i {
    color: var(--color-primary) !important;
}

/* Wishlist active state */
.shop-action-btn.shop-wishlist-btn.active i {
    color: #e74c3c !important;
}

/* ===== MOBILE FILTER BUTTON ===== */
.shop-mobile-filter-btn {
    display: none !important;
    position: fixed !important;
    bottom: 24px !important;
    right: 24px !important;
    background: var(--color-primary) !important;
    color: #fff !important;
    padding: 16px 32px !important;
    border-radius: 50px !important;
    box-shadow: 0 6px 20px rgba(98,157,35,0.4) !important;
    z-index: 999 !important;
    border: none !important;
    font-weight: 700 !important;
    font-size: 15px !important;
    cursor: pointer !important;
    transition: all 0.3s !important;
}

.shop-mobile-filter-btn:hover {
    background: var(--color-primary-hover) !important;
    transform: translateY(-3px) !important;
    box-shadow: 0 8px 24px rgba(98,157,35,0.5) !important;
}

.shop-mobile-filter-btn i {
    margin-right: 8px !important;
}

@media (max-width: 991px) {
    .shop-mobile-filter-btn {
        display: flex !important;
        align-items: center !important;
    }

    .shop-filter-sidebar-wrapper {
        position: fixed !important;
        top: 0 !important;
        left: -100% !important;
        width: 340px !important;
        height: 100vh !important;
        background: #fff !important;
        z-index: 1000 !important;
        transition: left 0.3s !important;
        overflow-y: auto !important;
        box-shadow: 2px 0 20px rgba(0,0,0,0.1) !important;
    }

    .shop-filter-sidebar-wrapper.show {
        left: 0 !important;
    }

    .shop-filter-overlay {
        display: none !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100vh !important;
        background: rgba(0,0,0,0.5) !important;
        z-index: 999 !important;
        backdrop-filter: blur(4px) !important;
    }

    .shop-filter-overlay.show {
        display: block !important;
    }
}

@media (max-width: 767px) {
    .shop-action-buttons {
        gap: 4px !important;
        padding: 3px !important;
    }

    .shop-action-btn {
        width: 44px !important;
        height: 44px !important;
    }

    .shop-action-btn i {
        font-size: 18px !important;
    }
}

/* ===== EMPTY STATE ===== */
.shop-empty-state {
    text-align: center !important;
    padding: 80px 20px !important;
}

.shop-empty-state i {
    font-size: 72px !important;
    color: #ddd !important;
    margin-bottom: 24px !important;
}

.shop-empty-state h4 {
    color: var(--text-dark) !important;
    margin-bottom: 12px !important;
    font-size: 24px !important;
    font-weight: 700 !important;
}

.shop-empty-state p {
    color: var(--text-light) !important;
    margin-bottom: 24px !important;
    font-size: 15px !important;
}
/* ADD TO YOUR SHOP STYLES - UNIFORM CARD HEIGHT */

/* Ensure all product cards have same height */
.single-shopping-card-one {
    display: flex !important;
    flex-direction: column !important;
    height: 100% !important;
    min-height: 480px !important; /* Set minimum height */
}

/* Image container fixed height */
.image-and-action-area-wrapper {
    position: relative !important;
    overflow: hidden !important;
    background: #f9fafb !important;
    height: 280px !important; /* Fixed height for all images */
}

.thumbnail-preview {
    display: block !important;
    position: relative !important;
    text-decoration: none !important;
    height: 100% !important;
}

.thumbnail-preview img {
    width: 100% !important;
    height: 100% !important;
    object-fit: contain !important; /* Changed from cover to contain */
    object-position: center !important;
    transition: transform 0.4s ease !important;
    display: block !important;
    padding: 10px !important; /* Add padding so images don't touch edges */
}

/* Product body takes remaining space */
.body-content {
    padding: 20px !important;
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
}

/* Add to cart button stays at bottom */
.cart-counter-action {
    margin-top: auto !important;
}

/* Responsive adjustments */
@media (max-width: 767px) {
    .single-shopping-card-one {
        min-height: 420px !important;
    }

    .image-and-action-area-wrapper {
        height: 220px !important;
    }
}

@media (max-width: 575px) {
    .single-shopping-card-one {
        min-height: 380px !important;
    }

    .image-and-action-area-wrapper {
        height: 200px !important;
    }
}

</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="rts-navigation-area-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="navigator-breadcrumb-wrapper">
                    <a href="{{ route('home') }}">Home</a>
                    <i class="fa-regular fa-chevron-right"></i>
                    <a class="current" href="{{ route('shop') }}">Shop</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section-seperator">
    <div class="container">
        <hr class="section-seperator">
    </div>
</div>

{{-- Main Shop Area --}}
<div class="shop-grid-sidebar-area rts-section-gap">
    <div class="container">
        <div class="row">

            {{-- Filter Sidebar --}}
            <div class="col-lg-3 col-md-12 mb-4">
                <div class="shop-filter-overlay" id="shopFilterOverlay"></div>
                <div class="shop-filter-sidebar-wrapper" id="shopFilterSidebarWrapper">
                    <div class="shop-filter-sidebar">

                        {{-- Filter Header --}}
                        <div class="shop-filter-header">
                            <h5><i class="fa-solid fa-sliders"></i>Filters</h5>
                            <button type="button" class="shop-clear-all-btn" id="shopClearAllFilters">Clear All</button>
                        </div>

                        {{-- Active Filters Bar --}}
                        <div class="shop-active-filters-bar" id="shopActiveFiltersBar">
                            <div id="shopActiveFilterTags"></div>
                        </div>

                        {{-- Price Filter with Slider --}}
                        <div class="shop-filter-section">
                            <div class="shop-filter-title" data-toggle="shopPriceFilter">
                                <span>Price Range</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="shop-filter-options" id="shopPriceFilter">
                                <div class="shop-price-slider-wrapper">
                                    <div id="shopPriceSlider"></div>
                                    <div class="shop-price-values">
                                        <div class="shop-price-input-box">
                                            <label>Min Price</label>
                                            <input type="number" id="shopMinPrice" readonly value="0">
                                        </div>
                                        <span class="shop-price-separator">-</span>
                                        <div class="shop-price-input-box">
                                            <label>Max Price</label>
                                            <input type="number" id="shopMaxPrice" readonly value="10000">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Categories Filter --}}
                        <div class="shop-filter-section">
                            <div class="shop-filter-title" data-toggle="shopCategoryFilter">
                                <span>Categories</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="shop-filter-options" id="shopCategoryFilter">
                                @php
                                    $categories = \App\Models\Category::where('is_active', 1)
                                        ->whereNull('parent_id')
                                        ->orderBy('name')
                                        ->get();
                                @endphp

                                @foreach($categories as $category)
                                <div class="shop-filter-option">
                                    <input type="checkbox"
                                           id="shopCat{{ $category->id }}"
                                           class="shop-category-filter"
                                           value="{{ $category->id }}"
                                           data-name="{{ $category->name }}">
                                    <span class="shop-checkbox-custom"></span>
                                    <label for="shopCat{{ $category->id }}">{{ $category->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Brands Filter --}}
                        <div class="shop-filter-section">
                            <div class="shop-filter-title" data-toggle="shopBrandFilter">
                                <span>Brands</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="shop-filter-options" id="shopBrandFilter">
                                @php
                                    $brands = \App\Models\Brand::where('is_active', 1)
                                        ->orderBy('name')
                                        ->get();
                                @endphp

                                @foreach($brands as $brand)
                                <div class="shop-filter-option">
                                    <input type="checkbox"
                                           id="shopBrand{{ $brand->id }}"
                                           class="shop-brand-filter"
                                           value="{{ $brand->id }}"
                                           data-name="{{ $brand->name }}">
                                    <span class="shop-checkbox-custom"></span>
                                    <label for="shopBrand{{ $brand->id }}">{{ $brand->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Products Grid --}}
            <div class="col-lg-9 col-md-12">

                {{-- Product Grid Header --}}
                <div class="shop-product-grid-header">
                    <div class="shop-result-count">
                        <span id="shopResultCount">Loading...</span>
                    </div>
                    <div class="shop-sort-dropdown-wrapper">
                        <select id="shopSortBy" class="shop-sort-dropdown">
                            <option value="latest">Latest First</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="name_asc">Name: A to Z</option>
                            <option value="name_desc">Name: Z to A</option>
                        </select>
                    </div>
                </div>

                {{-- Products Container --}}
                <div class="row g-4" id="shopProductsContainer">
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-3">Loading products...</p>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="row mt-4">
                    <div class="col-12">
                        <div id="shopPaginationContainer"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Mobile Filter Button --}}
<button class="shop-mobile-filter-btn" id="shopMobileFilterBtn">
    <i class="fa-solid fa-sliders"></i> Filters
</button>

@endsection

@push('scripts')
<!-- noUiSlider JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
<script>
$(document).ready(function() {
    let currentPage = 1;
    let activeFilters = {
        categories: [],
        brands: [],
        minPrice: 0,
        maxPrice: 10000
    };

    // Initialize Price Slider
    const priceSlider = document.getElementById('shopPriceSlider');
    noUiSlider.create(priceSlider, {
        start: [0, 10000],
        connect: true,
        step: 10,
        range: {
            'min': 0,
            'max': 10000
        },
        format: {
            to: function(value) {
                return Math.round(value);
            },
            from: function(value) {
                return Math.round(value);
            }
        }
    });

    // Update inputs when slider moves
    priceSlider.noUiSlider.on('update', function(values) {
        $('#shopMinPrice').val(values[0]);
        $('#shopMaxPrice').val(values[1]);
    });

    // Apply price filter when slider changes
    priceSlider.noUiSlider.on('change', function(values) {
        activeFilters.minPrice = values[0];
        activeFilters.maxPrice = values[1];
        currentPage = 1;
        updateActiveFilters();
        loadProducts();
    });

    // Initialize
    loadProducts();

    // Collapsible filter sections
    $('.shop-filter-title').on('click', function() {
        const targetId = $(this).data('toggle');
        const $target = $('#' + targetId);

        $(this).toggleClass('collapsed');
        $target.toggleClass('collapsed');
    });

    // Mobile filter toggle
    $('#shopMobileFilterBtn').on('click', function() {
        $('#shopFilterSidebarWrapper').addClass('show');
        $('#shopFilterOverlay').addClass('show');
    });

    $('#shopFilterOverlay').on('click', function() {
        $('#shopFilterSidebarWrapper').removeClass('show');
        $('#shopFilterOverlay').removeClass('show');
    });

    // Category filter
    $(document).on('change', '.shop-category-filter', function() {
        console.log('Category changed:', $(this).val(), $(this).is(':checked'));
        updateFilterArray('categories');
        currentPage = 1;
        updateActiveFilters();
        loadProducts();
    });

    // Brand filter
    $(document).on('change', '.shop-brand-filter', function() {
        console.log('Brand changed:', $(this).val(), $(this).is(':checked'));
        updateFilterArray('brands');
        currentPage = 1;
        updateActiveFilters();
        loadProducts();
    });

    // Sort
    $('#shopSortBy').on('change', function() {
        currentPage = 1;
        loadProducts();
    });

    // Clear all filters
    $('#shopClearAllFilters').on('click', function(e) {
        e.preventDefault();
        clearAllFilters();
    });

    // Remove individual filter
    $(document).on('click', '.shop-remove-filter', function() {
        const type = $(this).data('type');
        const value = $(this).data('value');

        if (type === 'category') {
            $(`.shop-category-filter[value="${value}"]`).prop('checked', false);
            updateFilterArray('categories');
        } else if (type === 'brand') {
            $(`.shop-brand-filter[value="${value}"]`).prop('checked', false);
            updateFilterArray('brands');
        } else if (type === 'price') {
            priceSlider.noUiSlider.set([0, 10000]);
            activeFilters.minPrice = 0;
            activeFilters.maxPrice = 10000;
        }

        currentPage = 1;
        updateActiveFilters();
        loadProducts();
    });

    // Pagination
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            const urlObj = new URL(url);
            const page = urlObj.searchParams.get('page');
            if (page) {
                currentPage = page;
                loadProducts();
                $('html, body').animate({ scrollTop: 400 }, 'smooth');
            }
        }
    });

    // Helper functions
    function updateFilterArray(type) {
        activeFilters[type] = [];
        $(`.shop-${type.slice(0, -1)}-filter:checked`).each(function() {
            activeFilters[type].push($(this).val());
        });
        console.log('Updated filters:', activeFilters);
    }

    function clearAllFilters() {
        $('.shop-category-filter, .shop-brand-filter').prop('checked', false);
        priceSlider.noUiSlider.set([0, 10000]);
        $('#shopSortBy').val('latest');
        activeFilters = { categories: [], brands: [], minPrice: 0, maxPrice: 10000 };
        currentPage = 1;
        updateActiveFilters();
        loadProducts();
    }

    function updateActiveFilters() {
        let html = '';
        const hasFilters = activeFilters.categories.length > 0 ||
                          activeFilters.brands.length > 0 ||
                          activeFilters.minPrice > 0 ||
                          activeFilters.maxPrice < 10000;

        if (hasFilters) {
            if (activeFilters.minPrice > 0 || activeFilters.maxPrice < 10000) {
                html += `<span class="shop-active-filter-tag">
                    ₹${activeFilters.minPrice} - ₹${activeFilters.maxPrice}
                    <i class="fa-solid fa-xmark shop-remove-filter" data-type="price"></i>
                </span>`;
            }

            activeFilters.categories.forEach(id => {
                const name = $(`.shop-category-filter[value="${id}"]`).data('name');
                html += `<span class="shop-active-filter-tag">
                    ${name}
                    <i class="fa-solid fa-xmark shop-remove-filter" data-type="category" data-value="${id}"></i>
                </span>`;
            });

            activeFilters.brands.forEach(id => {
                const name = $(`.shop-brand-filter[value="${id}"]`).data('name');
                html += `<span class="shop-active-filter-tag">
                    ${name}
                    <i class="fa-solid fa-xmark shop-remove-filter" data-type="brand" data-value="${id}"></i>
                </span>`;
            });

            $('#shopActiveFiltersBar').addClass('show');
        } else {
            $('#shopActiveFiltersBar').removeClass('show');
        }

        $('#shopActiveFilterTags').html(html);
    }

    function loadProducts() {
        const data = {
            page: currentPage,
            min_price: activeFilters.minPrice,
            max_price: activeFilters.maxPrice,
            categories: activeFilters.categories,
            brands: activeFilters.brands,
            sort: $('#shopSortBy').val() || 'latest'
        };

        console.log('Loading products with data:', data);

        $('#shopProductsContainer').html(`
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3">Loading products...</p>
            </div>
        `);

        $.ajax({
            url: '{{ route("shop.filter") }}',
            type: 'GET',
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response);
                if (response.success) {
                    displayProducts(response.products);
                    displayPagination(response.pagination);
                    updateProductCount(response.total, response.from, response.to);

                    $('#shopFilterSidebarWrapper').removeClass('show');
                    $('#shopFilterOverlay').removeClass('show');
                } else {
                    showError('Failed to load products');
                }
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr);
                showError('Error loading products');
            }
        });
    }

    function displayProducts(products) {
        if (products.length === 0) {
            $('#shopProductsContainer').html(`
                <div class="col-12">
                    <div class="shop-empty-state">
                        <i class="fa-regular fa-box-open"></i>
                        <h4>No products found</h4>
                        <p>Try adjusting your filters</p>
                        <button class="rts-btn btn-primary" onclick="$('#shopClearAllFilters').click()">Clear Filters</button>
                    </div>
                </div>
            `);
            return;
        }

        let html = '';
        products.forEach(product => {
            const discount = product.discount_percentage > 0 ?
                `<div class="shop-discount-badge"><span>${product.discount_percentage}% Off</span></div>` : '';

            const mrp = product.mrp > product.price ?
                `<div class="previous">₹${parseFloat(product.mrp).toFixed(2)}</div>` : '';

            html += `
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    <div class="shop-product-card">
                        <!-- Image Area -->
                        <div class="product-image-wrapper">
                            <a href="/product/${product.slug}" class="product-image-link">
                                ${product.discount_percentage > 0 ? `
                                    <div class="product-badge-discount">
                                        <span>${product.discount_percentage}% OFF</span>
                                    </div>
                                ` : ''}

                                ${product.stock <= 5 && product.stock > 0 ? `
                                    <div class="product-badge-stock">
                                        <span>Only ${product.stock} left</span>
                                    </div>
                                ` : ''}

                                <img src="${product.image_url}" alt="${product.name}" class="product-main-image" onerror="this.src='/frontend/assets/images/grocery/01.jpg'">
                            </a>

                            <!-- Quick Actions -->
                            <div class="product-quick-actions">
                                <button class="quick-action-btn shop-wishlist-btn" title="Add to Wishlist" data-id="${product.id}">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                                <button class="quick-action-btn shop-quick-view-btn" title="Quick View" data-id="${product.id}">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <button class="quick-action-btn" title="Compare">
                                    <i class="fa-regular fa-arrows-rotate"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="product-info">
                            <!-- Category & Brand -->
                            <div class="product-meta">
                                <span class="product-category">${product.category?.name || 'Uncategorized'}</span>
                                ${product.brand ? `
                                    <span class="meta-separator">•</span>
                                    <span class="product-brand">${product.brand.name}</span>
                                ` : ''}
                            </div>

                            <!-- Product Name -->
                            <a href="/product/${product.slug}" class="product-name-link">
                                <h4 class="product-name">${product.name}</h4>
                            </a>

                            <!-- Rating -->
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

                            <!-- Price -->
                            <div class="product-price">
                                <span class="price-current">₹${parseFloat(product.price).toFixed(2)}</span>
                                ${product.mrp && product.mrp > product.price ? `
                                    <span class="price-original">₹${parseFloat(product.mrp).toFixed(2)}</span>
                                    <span class="price-save">Save ₹${(product.mrp - product.price).toFixed(2)}</span>
                                ` : ''}
                            </div>

                            <!-- Stock Status -->
                            ${product.stock > 0 ? `
                                <div class="product-stock in-stock">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>In Stock</span>
                                </div>
                            ` : `
                                <div class="product-stock out-of-stock">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    <span>Out of Stock</span>
                                </div>
                            `}

                            <!-- Add to Cart Button -->
                            <button class="product-add-to-cart" ${product.stock <= 0 ? 'disabled' : ''} data-id="${product.id}">
                                <i class="fa-regular fa-cart-shopping"></i>
                                <span>${product.stock > 0 ? 'Add to Cart' : 'Out of Stock'}</span>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        $('#shopProductsContainer').html(html);
    }

    // Wishlist toggle
    $(document).on('click', '.shop-wishlist-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).toggleClass('active');
        const icon = $(this).find('i');
        if ($(this).hasClass('active')) {
            icon.removeClass('fa-regular').addClass('fa-solid');
        } else {
            icon.removeClass('fa-solid').addClass('fa-regular');
        }
    });

    // Quick view (placeholder)
    $(document).on('click', '.shop-quick-view-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const productId = $(this).data('id');
        // Add your quick view modal logic here
        console.log('Quick view for product:', productId);
    });

    function displayPagination(pagination) {
        $('#shopPaginationContainer').html(pagination);
    }

    function updateProductCount(total, from, to) {
        $('#shopResultCount').text(`Showing ${from}–${to} of ${total} products`);
    }

    function showError(message) {
        $('#shopProductsContainer').html(`
            <div class="col-12">
                <div class="shop-empty-state">
                    <i class="fa-regular fa-triangle-exclamation"></i>
                    <h4>${message}</h4>
                    <button class="rts-btn btn-primary" onclick="location.reload()">Reload Page</button>
                </div>
            </div>
        `);
    }
});
</script>
@endpush
