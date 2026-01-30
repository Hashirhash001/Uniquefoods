{{-- Modern Header Style - FINAL FIX --}}
<header class="unique-modern-header">

    {{-- Top Bar --}}
    <div class="unique-topbar">
        <div class="container-2">
            <div class="unique-topbar-wrapper">
                <div class="unique-topbar-left">
                    <div class="unique-welcome-msg">
                        <i class="fa-regular fa-sparkles"></i>
                        <span>Welcome to Unique Foods - Your Premium Grocery Store</span>
                    </div>
                </div>
                <div class="unique-topbar-right">
                    <div class="unique-topbar-links">
                        <a href="#" class="unique-toplink">
                            <i class="fa-regular fa-location-dot"></i>
                            <span>Track Order</span>
                        </a>
                        <span class="unique-divider">|</span>
                        <a href="#" class="unique-toplink">
                            <i class="fa-regular fa-circle-question"></i>
                            <span>Help</span>
                        </a>
                        <span class="unique-divider">|</span>
                        <a href="tel:+258326821485" class="unique-toplink">
                            <i class="fa-regular fa-phone"></i>
                            <span>+258 3268 21485</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Header --}}
    <div class="unique-main-header">
        <div class="container-2">
            <div class="unique-header-wrapper">

                {{-- Logo --}}
                <div class="unique-logo-section">
                    <a href="{{ route('home') }}">
                        <div class="unique-logo-badge">
                            <i class="fa-solid fa-leaf"></i>
                        </div>
                        <div class="unique-logo-info">
                            <span class="unique-brand-title">Unique Foods</span>
                            <span class="unique-brand-subtitle">Fresh & Organic</span>
                        </div>
                    </a>
                </div>

                {{-- Search Bar --}}
                <div class="unique-search-section">
                    <form action="{{ route('shop') }}" method="GET" class="unique-search-form">
                        <div class="unique-search-field-wrapper">
                            <input type="text" name="q" placeholder="Search for products, brands, and more..." value="{{ request('q') }}" class="unique-search-input">
                            <button type="submit" class="unique-search-button">
                                <i class="fa-regular fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Header Actions --}}
                <div class="unique-header-actions">

                    {{-- Account --}}
                    <div class="unique-action-wrapper unique-account-wrapper">
                        <button class="unique-action-trigger">
                            <i class="fa-regular fa-user"></i>
                            <div class="unique-action-info">
                                <span class="unique-action-label">Account</span>
                                <span class="unique-action-value">Sign In</span>
                            </div>
                        </button>
                        <div class="unique-action-menu">
                            <div class="unique-menu-header">
                                <h4>Welcome!</h4>
                                <p>Sign in to access your account</p>
                            </div>
                            <div class="unique-menu-body">
                                <a href="#" class="unique-menu-link">
                                    <i class="fa-regular fa-user"></i>
                                    <span>My Profile</span>
                                </a>
                                <a href="#" class="unique-menu-link">
                                    <i class="fa-regular fa-box"></i>
                                    <span>Orders</span>
                                </a>
                                <a href="#" class="unique-menu-link">
                                    <i class="fa-regular fa-heart"></i>
                                    <span>Wishlist</span>
                                </a>
                                <a href="#" class="unique-menu-link">
                                    <i class="fa-regular fa-gear"></i>
                                    <span>Settings</span>
                                </a>
                            </div>
                            <div class="unique-menu-footer">
                                <a href="#" class="unique-btn-signin">Sign In</a>
                                <a href="#" class="unique-btn-signup">Create Account</a>
                            </div>
                        </div>
                    </div>

                    {{-- Wishlist --}}
                    <a href="#" class="unique-action-wrapper">
                        <div class="unique-action-trigger">
                            <i class="fa-regular fa-heart"></i>
                            <span class="unique-badge">0</span>
                            <div class="unique-action-info">
                                <span class="unique-action-label">Wishlist</span>
                                <span class="unique-action-value">My Items</span>
                            </div>
                        </div>
                    </a>

                    {{-- Cart --}}
                    <div class="unique-action-wrapper unique-cart-wrapper">
                        <button class="unique-action-trigger">
                            <i class="fa-regular fa-cart-shopping"></i>
                            <span class="unique-badge">0</span>
                            <div class="unique-action-info">
                                <span class="unique-action-label">Cart</span>
                                <span class="unique-action-value">₹0.00</span>
                            </div>
                        </button>
                        <div class="unique-action-menu unique-cart-menu">
                            <div class="unique-menu-header">
                                <h4>Shopping Cart</h4>
                                <span class="unique-cart-total">0 Items</span>
                            </div>
                            <div class="unique-cart-empty">
                                <i class="fa-regular fa-cart-shopping"></i>
                                <p>Your cart is empty</p>
                                <a href="{{ route('shop') }}" class="unique-btn-signin">Start Shopping</a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- Navigation Bar --}}
    <div class="unique-navbar">
        <div class="container-2">
            <div class="unique-navbar-wrapper">

                {{-- All Categories Mega Menu - NO BOX VERSION --}}
                <div class="unique-categories-container">
                    <button class="unique-categories-button">
                        <i class="fa-regular fa-bars"></i>
                        <span>All Categories</span>
                        <i class="fa-regular fa-chevron-down"></i>
                    </button>

                    {{-- Main Categories List (NO BOX!) --}}
                    <div class="unique-categories-dropdown">
                        @if(isset($categories) && $categories->count())
                            @foreach($categories as $cat)
                                <div class="unique-category-item">
                                    <a href="{{ route('category.show', $cat->slug) }}" class="unique-category-link">
                                        @if($cat->image)
                                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="unique-cat-img">
                                        @else
                                            <i class="fa-regular fa-box unique-cat-icon"></i>
                                        @endif
                                        <span class="unique-cat-name">{{ $cat->name }}</span>
                                        @if($cat->activeChildren->count() > 0)
                                            <i class="fa-regular fa-chevron-right unique-cat-arrow"></i>
                                        @endif
                                    </a>

                                    {{-- Submenu (appears to right, NO BOX, NO SCROLL!) --}}
                                    @if($cat->activeChildren->count() > 0)
                                        <div class="unique-category-submenu">
                                            <div class="unique-submenu-title">{{ $cat->name }}</div>
                                            <div class="unique-submenu-items">
                                                @foreach($cat->activeChildren as $subCat)
                                                    <a href="{{ route('category.show', $subCat->slug) }}" class="unique-subcat-link">
                                                        {{ $subCat->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Main Navigation --}}
                <nav class="unique-main-nav">
                    <ul class="unique-nav-list">
                        <li class="unique-nav-item {{ request()->routeIs('home') ? 'unique-active' : '' }}">
                            <a href="{{ route('home') }}" class="unique-navlink">
                                <i class="fa-regular fa-house"></i>
                                <span>Home</span>
                            </a>
                        </li>
                        <li class="unique-nav-item {{ request()->routeIs('shop*') ? 'unique-active' : '' }}">
                            <a href="{{ route('shop') }}" class="unique-navlink">
                                <i class="fa-regular fa-store"></i>
                                <span>Shop</span>
                            </a>
                        </li>
                        <li class="unique-nav-item">
                            <a href="#" class="unique-navlink">
                                <i class="fa-regular fa-tags"></i>
                                <span>Deals</span>
                                <span class="unique-hot-badge">Hot</span>
                            </a>
                        </li>
                        <li class="unique-nav-item">
                            <a href="#" class="unique-navlink">
                                <i class="fa-regular fa-newspaper"></i>
                                <span>Blog</span>
                            </a>
                        </li>
                        <li class="unique-nav-item">
                            <a href="#" class="unique-navlink">
                                <i class="fa-regular fa-headset"></i>
                                <span>Contact</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                {{-- Promo Banner --}}
                <div class="unique-promo-banner">
                    <i class="fa-solid fa-gift"></i>
                    <span>Free delivery on orders over ₹500</span>
                </div>

            </div>
        </div>
    </div>

    {{-- Mobile Header --}}
    <div class="unique-mobile-header">
        <div class="unique-mobile-top">
            <button class="unique-mobile-menu-btn" id="uniqueMobileMenu">
                <i class="fa-regular fa-bars"></i>
            </button>
            <a href="{{ route('home') }}" class="unique-mobile-logo">
                <i class="fa-solid fa-leaf"></i>
                <span>Unique Foods</span>
            </a>
            <div class="unique-mobile-actions">
                <button class="unique-mobile-search-btn" id="uniqueMobileSearch">
                    <i class="fa-regular fa-magnifying-glass"></i>
                </button>
                <a href="#" class="unique-mobile-cart-btn">
                    <i class="fa-regular fa-cart-shopping"></i>
                    <span class="unique-badge">0</span>
                </a>
            </div>
        </div>

        {{-- Mobile Search --}}
        <div class="unique-mobile-searchbar" id="uniqueMobileSearchBar">
            <form action="{{ route('shop') }}" method="GET">
                <input type="text" name="q" placeholder="Search products..." value="{{ request('q') }}">
                <button type="submit">
                    <i class="fa-regular fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
    </div>

</header>

{{-- Mobile Sidebar --}}
<div class="unique-mobile-sidebar" id="uniqueMobileSidebar">
    <div class="unique-sidebar-header">
        <div class="unique-sidebar-user">
            <i class="fa-regular fa-user-circle"></i>
            <div class="unique-user-details">
                <h4>Welcome!</h4>
                <a href="#">Sign In / Register</a>
            </div>
        </div>
        <button class="unique-sidebar-close" id="uniqueSidebarClose">
            <i class="fa-regular fa-xmark"></i>
        </button>
    </div>

    <div class="unique-sidebar-content">
        <nav class="unique-mobile-nav">
            <a href="{{ route('home') }}" class="unique-mobile-link">
                <i class="fa-regular fa-house"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('shop') }}" class="unique-mobile-link">
                <i class="fa-regular fa-store"></i>
                <span>Shop</span>
            </a>
            <a href="#" class="unique-mobile-link">
                <i class="fa-regular fa-heart"></i>
                <span>Wishlist</span>
                <span class="unique-count-badge">0</span>
            </a>
            <a href="#" class="unique-mobile-link">
                <i class="fa-regular fa-box"></i>
                <span>Orders</span>
            </a>
            <a href="#" class="unique-mobile-link">
                <i class="fa-regular fa-newspaper"></i>
                <span>Blog</span>
            </a>
            <a href="#" class="unique-mobile-link">
                <i class="fa-regular fa-headset"></i>
                <span>Contact Us</span>
            </a>
        </nav>

        <div class="unique-mobile-categories">
            <h3>Categories</h3>
            @if(isset($categories) && $categories->count())
                @foreach($categories as $cat)
                    <div class="unique-mobile-cat-item">
                        <a href="{{ route('category.show', $cat->slug) }}" class="unique-mobile-cat-link">
                            @if($cat->image)
                                <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}">
                            @endif
                            <span>{{ $cat->name }}</span>
                            @if($cat->activeChildren->count() > 0)
                                <i class="fa-regular fa-chevron-right"></i>
                            @endif
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="unique-sidebar-footer">
        <div class="unique-sidebar-contacts">
            <a href="tel:+258326821485">
                <i class="fa-regular fa-phone"></i>
                <span>+258 3268 21485</span>
            </a>
            <a href="mailto:info@uniquefoods.com">
                <i class="fa-regular fa-envelope"></i>
                <span>info@uniquefoods.com</span>
            </a>
        </div>
    </div>
</div>

<div class="unique-mobile-overlay" id="uniqueMobileOverlay"></div>

<style>
/* ===== UNIQUE HEADER STYLES WITH !IMPORTANT ===== */
:root {
    --unique-green: #629D23;
    --unique-green-dark: #518219;
    --unique-white: #ffffff;
    --unique-black: #1a1a1a;
    --unique-gray: #666666;
    --unique-light: #f8f9fa;
    --unique-border: #e5e7eb;
    --unique-shadow: 0 2px 12px rgba(0,0,0,0.08);
    --unique-red: #e74c3c;
}

* {
    box-sizing: border-box !important;
}

body * {
    /* margin: revert !important;
    padding: revert !important; */
}

/* ===== STICKY HEADER ===== */
.unique-modern-header {
    position: sticky !important;
    top: 0 !important;
    z-index: 999 !important;
    background: var(--unique-white) !important;
}

/* ===== TOP BAR ===== */
.unique-topbar {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    border-bottom: 1px solid var(--unique-border) !important;
    padding: 8px 0 !important;
}

.unique-topbar-wrapper {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
}

.unique-welcome-msg {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    font-size: 13px !important;
    color: var(--unique-gray) !important;
}

.unique-welcome-msg i {
    color: var(--unique-green) !important;
    font-size: 14px !important;
}

.unique-topbar-links {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
}

.unique-toplink {
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
    font-size: 13px !important;
    color: var(--unique-gray) !important;
    text-decoration: none !important;
    transition: color 0.3s !important;
}

.unique-toplink:hover {
    color: var(--unique-green) !important;
}

.unique-divider {
    color: var(--unique-border) !important;
}

/* ===== MAIN HEADER ===== */
.unique-main-header {
    background: var(--unique-white) !important;
    padding: 20px 0 !important;
    box-shadow: var(--unique-shadow) !important;
}

.unique-header-wrapper {
    display: flex !important;
    align-items: center !important;
    gap: 32px !important;
}

/* Logo */
.unique-logo-section a {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    text-decoration: none !important;
}

.unique-logo-badge {
    width: 50px !important;
    height: 50px !important;
    background: linear-gradient(135deg, var(--unique-green) 0%, var(--unique-green-dark) 100%) !important;
    border-radius: 12px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: white !important;
    font-size: 24px !important;
    box-shadow: 0 4px 12px rgba(98, 157, 35, 0.3) !important;
}

.unique-logo-info {
    display: flex !important;
    flex-direction: column !important;
}

.unique-brand-title {
    font-size: 22px !important;
    font-weight: 700 !important;
    color: var(--unique-black) !important;
    line-height: 1 !important;
}

.unique-brand-subtitle {
    font-size: 12px !important;
    color: var(--unique-gray) !important;
    margin-top: 2px !important;
}

/* Search */
.unique-search-section {
    flex: 1 !important;
    max-width: 700px !important;
}

.unique-search-form {
    display: flex !important;
    background: #f8f9fa !important;
    border-radius: 50px !important;
    overflow: hidden !important;
    border: 2px solid var(--unique-border) !important;
    transition: all 0.3s !important;
}

.unique-search-form:focus-within {
    border-color: var(--unique-green) !important;
    box-shadow: 0 4px 16px rgba(98, 157, 35, 0.15) !important;
}

.unique-search-field-wrapper {
    flex: 1 !important;
    display: flex !important;
    align-items: center !important;
}

.unique-search-input {
    flex: 1 !important;
    border: none !important;
    background: transparent !important;
    padding: 14px 20px !important;
    font-size: 15px !important;
    outline: none !important;
}

.unique-search-button {
    padding: 14px 24px !important;
    background: var(--unique-green) !important;
    border: none !important;
    border-radius: 0 50px 50px 0 !important;
    color: white !important;
    font-size: 18px !important;
    cursor: pointer !important;
    transition: all 0.3s !important;
    width: unset !important;
}

.unique-search-button:hover {
    background: var(--unique-green-dark) !important;
}

/* Header Actions */
.unique-header-actions {
    display: flex !important;
    align-items: center !important;
    gap: 20px !important;
}

.unique-action-wrapper {
    position: relative !important;
    text-decoration: none !important;
}

.unique-action-trigger {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    background: none !important;
    border: none !important;
    cursor: pointer !important;
    padding: 8px 12px !important;
    border-radius: 12px !important;
    transition: all 0.3s !important;
    position: relative !important;
}

.unique-action-trigger:hover {
    background: #f8f9fa !important;
}

.unique-action-trigger > i {
    font-size: 24px !important;
    color: var(--unique-black) !important;
}

.unique-badge {
    position: absolute !important;
    top: 0 !important;
    right: 0 !important;
    background: var(--unique-red) !important;
    color: white !important;
    font-size: 10px !important;
    font-weight: 700 !important;
    padding: 2px 6px !important;
    border-radius: 10px !important;
    min-width: 18px !important;
    text-align: center !important;
}

.unique-action-info {
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
}

.unique-action-label {
    font-size: 11px !important;
    color: var(--unique-gray) !important;
    line-height: 1 !important;
}

.unique-action-value {
    font-size: 14px !important;
    font-weight: 600 !important;
    color: var(--unique-black) !important;
    margin-top: 2px !important;
}

/* Action Dropdowns */
.unique-action-menu {
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    width: 300px !important;
    background: white !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
    margin-top: 12px !important;
    opacity: 0 !important;
    visibility: hidden !important;
    transform: translateY(-10px) !important;
    transition: all 0.3s !important;
    z-index: 100 !important;
}

.unique-action-wrapper:hover .unique-action-menu {
    opacity: 1 !important;
    visibility: visible !important;
    transform: translateY(0) !important;
}

.unique-menu-header {
    padding: 20px !important;
    border-bottom: 1px solid var(--unique-border) !important;
}

.unique-menu-header h4 {
    font-size: 18px !important;
    font-weight: 700 !important;
    margin-bottom: 4px !important;
}

.unique-menu-header p {
    font-size: 13px !important;
    color: var(--unique-gray) !important;
}

.unique-menu-body {
    padding: 12px 0 !important;
}

.unique-menu-link {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    padding: 12px 20px !important;
    color: var(--unique-black) !important;
    text-decoration: none !important;
    font-size: 14px !important;
    transition: all 0.3s !important;
}

.unique-menu-link:hover {
    background: #f8f9fa !important;
    color: var(--unique-green) !important;
}

.unique-menu-link i {
    width: 20px !important;
    text-align: center !important;
}

.unique-menu-footer {
    padding: 16px 20px !important;
    border-top: 1px solid var(--unique-border) !important;
    display: flex !important;
    gap: 12px !important;
}

.unique-btn-signin,
.unique-btn-signup {
    flex: 1 !important;
    padding: 10px !important;
    text-align: center !important;
    border-radius: 8px !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    transition: all 0.3s !important;
}

.unique-btn-signin {
    background: var(--unique-green) !important;
    color: white !important;
}

.unique-btn-signin:hover {
    background: var(--unique-green-dark) !important;
}

.unique-btn-signup {
    background: #f8f9fa !important;
    color: var(--unique-black) !important;
    border: 1px solid var(--unique-border) !important;
}

.unique-btn-signup:hover {
    background: white !important;
    border-color: var(--unique-green) !important;
    color: var(--unique-green) !important;
}

/* Cart */
.unique-cart-menu {
    width: 320px !important;
}

.unique-cart-total {
    font-size: 13px !important;
    color: var(--unique-gray) !important;
}

.unique-cart-empty {
    text-align: center !important;
    padding: 40px 20px !important;
}

.unique-cart-empty i {
    font-size: 48px !important;
    color: #ddd !important;
    margin-bottom: 16px !important;
}

.unique-cart-empty p {
    font-size: 14px !important;
    color: var(--unique-gray) !important;
    margin-bottom: 16px !important;
}

/* ===== NAVIGATION BAR ===== */
.unique-navbar {
    background: var(--unique-green) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
}

.unique-navbar-wrapper {
    display: flex !important;
    align-items: center !important;
    gap: 24px !important;
}

/* ===== CATEGORIES DROPDOWN - NO BOX, NO SCROLL VERSION ===== */
.unique-categories-container {
    position: relative !important;
}

.unique-categories-button {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    padding: 16px 24px !important;
    background: rgba(255,255,255,0.1) !important;
    border: none !important;
    color: white !important;
    font-size: 15px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    border-radius: 8px !important;
    transition: all 0.3s !important;
}

.unique-categories-button:hover {
    background: rgba(255,255,255,0.2) !important;
}

/* Main dropdown - NO SCROLLBAR, PROPER WIDTH */
.unique-categories-dropdown {
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    width: auto !important;
    min-width: 280px !important;
    background: white !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15) !important;
    margin-top: 10px !important;
    padding: 8px !important;
    opacity: 0 !important;
    visibility: hidden !important;
    transform: translateY(-10px) !important;
    transition: all 0.3s !important;
    z-index: 2000 !important;
    overflow: visible !important;
    max-height: none !important;
}

.unique-categories-container:hover .unique-categories-dropdown {
    opacity: 1 !important;
    visibility: visible !important;
    transform: translateY(0) !important;
}

/* Category item */
.unique-category-item {
    position: relative !important;
}

.unique-category-link {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    padding: 12px 16px !important;
    color: var(--unique-black) !important;
    text-decoration: none !important;
    font-size: 14px !important;
    transition: all 0.3s !important;
    border-radius: 8px !important;
    white-space: nowrap !important;
}

.unique-category-link:hover {
    background: #f8f9fa !important;
    color: var(--unique-green) !important;
}

.unique-cat-img,
.unique-cat-icon {
    width: 24px !important;
    height: 24px !important;
    object-fit: contain !important;
    flex-shrink: 0 !important;
}

.unique-cat-name {
    flex: 1 !important;
}

.unique-cat-arrow {
    font-size: 10px !important;
    color: var(--unique-gray) !important;
    margin-left: 8px !important;
}

/* ===== SUBMENU - NO BOX, APPEARS TO RIGHT ===== */
.unique-category-submenu {
    position: absolute !important;
    left: 100% !important;
    top: 0 !important;
    width: 400px !important;
    background: white !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15) !important;
    margin-left: 12px !important;
    padding: 20px !important;
    opacity: 0 !important;
    visibility: hidden !important;
    transition: all 0.3s !important;
    z-index: 2001 !important;
}

.unique-category-item:hover .unique-category-submenu {
    opacity: 1 !important;
    visibility: visible !important;
}

.unique-submenu-title {
    font-size: 16px !important;
    font-weight: 700 !important;
    margin-bottom: 16px !important;
    padding-bottom: 12px !important;
    border-bottom: 1px solid var(--unique-border) !important;
    color: var(--unique-black) !important;
}

.unique-submenu-items {
    display: grid !important;
    grid-template-columns: repeat(2, 1fr) !important;
    gap: 8px !important;
}

.unique-subcat-link {
    padding: 10px 12px !important;
    color: var(--unique-gray) !important;
    text-decoration: none !important;
    font-size: 13px !important;
    border-radius: 6px !important;
    transition: all 0.3s !important;
    display: block !important;
}

.unique-subcat-link:hover {
    background: #f8f9fa !important;
    color: var(--unique-green) !important;
}

/* Main Navigation */
.unique-main-nav {
    flex: 1 !important;
}

.unique-nav-list {
    display: flex !important;
    list-style: none !important;
    gap: 8px !important;
    margin: 0 !important;
    padding: 0 !important;
}

.unique-navlink {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    padding: 16px 20px !important;
    color: white !important;
    text-decoration: none !important;
    font-size: 15px !important;
    font-weight: 500 !important;
    border-radius: 8px !important;
    transition: all 0.3s !important;
    position: relative !important;
}

.unique-nav-item.unique-active .unique-navlink,
.unique-navlink:hover {
    background: rgba(255,255,255,0.15) !important;
}

.unique-hot-badge {
    position: absolute !important;
    top: 4px !important;
    right: 4px !important;
    background: var(--unique-red) !important;
    color: white !important;
    font-size: 10px !important;
    padding: 2px 6px !important;
    border-radius: 10px !important;
    font-weight: 700 !important;
}

/* Promo */
.unique-promo-banner {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    padding: 12px 20px !important;
    background: rgba(255,255,255,0.1) !important;
    border-radius: 8px !important;
    color: white !important;
    font-size: 13px !important;
    font-weight: 600 !important;
}

.unique-promo-banner i {
    color: #ffd700 !important;
}

/* ===== MOBILE HEADER ===== */
.unique-mobile-header {
    display: none !important;
    background: white !important;
    box-shadow: var(--unique-shadow) !important;
    position: sticky !important;
    top: 0 !important;
    z-index: 999 !important;
}

.unique-mobile-top {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    padding: 12px 16px !important;
}

.unique-mobile-menu-btn,
.unique-mobile-search-btn,
.unique-mobile-cart-btn {
    background: none !important;
    border: none !important;
    font-size: 22px !important;
    color: var(--unique-black) !important;
    cursor: pointer !important;
    padding: 8px !important;
}

.unique-mobile-logo {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    text-decoration: none !important;
    font-size: 18px !important;
    font-weight: 700 !important;
    color: var(--unique-black) !important;
}

.unique-mobile-logo i {
    color: var(--unique-green) !important;
    font-size: 24px !important;
}

.unique-mobile-actions {
    display: flex !important;
    gap: 8px !important;
}

.unique-mobile-cart-btn {
    position: relative !important;
}

.unique-mobile-searchbar {
    padding: 0 16px 12px !important;
    border-top: 1px solid var(--unique-border) !important;
    display: none !important;
}

.unique-mobile-searchbar.active {
    display: block !important;
}

.unique-mobile-searchbar form {
    display: flex !important;
    background: #f8f9fa !important;
    border-radius: 50px !important;
    overflow: hidden !important;
    border: 2px solid var(--unique-border) !important;
}

.unique-mobile-searchbar input {
    flex: 1 !important;
    border: none !important;
    background: transparent !important;
    padding: 12px 16px !important;
    font-size: 14px !important;
}

.unique-mobile-searchbar button {
    padding: 12px 20px !important;
    background: var(--unique-green) !important;
    border: none !important;
    color: white !important;
    font-size: 16px !important;
}

/* Mobile Sidebar */
.unique-mobile-sidebar {
    position: fixed !important;
    top: 0 !important;
    left: -100% !important;
    width: 320px !important;
    height: 100vh !important;
    background: white !important;
    z-index: 1001 !important;
    transition: left 0.3s !important;
    overflow-y: auto !important;
    box-shadow: 2px 0 20px rgba(0,0,0,0.1) !important;
}

.unique-mobile-sidebar.active {
    left: 0 !important;
}

.unique-sidebar-header {
    padding: 20px !important;
    background: var(--unique-green) !important;
    color: white !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: flex-start !important;
}

.unique-sidebar-user {
    display: flex !important;
    gap: 12px !important;
}

.unique-sidebar-user i {
    font-size: 48px !important;
}

.unique-user-details h4 {
    font-size: 16px !important;
    margin-bottom: 4px !important;
}

.unique-user-details a {
    font-size: 13px !important;
    color: rgba(255,255,255,0.9) !important;
    text-decoration: none !important;
}

.unique-sidebar-close {
    background: none !important;
    border: none !important;
    color: white !important;
    font-size: 24px !important;
    cursor: pointer !important;
}

.unique-sidebar-content {
    padding: 20px 0 !important;
}

.unique-mobile-link {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    padding: 14px 20px !important;
    color: var(--unique-black) !important;
    text-decoration: none !important;
    font-size: 15px !important;
    transition: all 0.3s !important;
}

.unique-mobile-link:hover {
    background: #f8f9fa !important;
    color: var(--unique-green) !important;
}

.unique-mobile-link i {
    width: 24px !important;
}

.unique-count-badge {
    margin-left: auto !important;
    background: var(--unique-green) !important;
    color: white !important;
    font-size: 11px !important;
    padding: 2px 8px !important;
    border-radius: 10px !important;
}

.unique-mobile-categories {
    margin-top: 20px !important;
    padding-top: 20px !important;
    border-top: 1px solid var(--unique-border) !important;
}

.unique-mobile-categories h3 {
    padding: 0 20px 12px !important;
    font-size: 14px !important;
    color: var(--unique-gray) !important;
    text-transform: uppercase !important;
}

.unique-mobile-cat-item {
    border-bottom: 1px solid var(--unique-border) !important;
}

.unique-mobile-cat-link {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    padding: 14px 20px !important;
    color: var(--unique-black) !important;
    text-decoration: none !important;
    font-size: 14px !important;
}

.unique-mobile-cat-link img {
    width: 32px !important;
    height: 32px !important;
    object-fit: contain !important;
}

.unique-sidebar-footer {
    padding: 20px !important;
    border-top: 1px solid var(--unique-border) !important;
}

.unique-sidebar-contacts a {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    padding: 12px 0 !important;
    color: var(--unique-black) !important;
    text-decoration: none !important;
    font-size: 14px !important;
}

.unique-mobile-overlay {
    display: none !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100vh !important;
    background: rgba(0,0,0,0.5) !important;
    z-index: 1000 !important;
    backdrop-filter: blur(4px) !important;
}

.unique-mobile-overlay.active {
    display: block !important;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 991px) {
    .unique-topbar,
    .unique-main-header,
    .unique-navbar {
        display: none !important;
    }

    .unique-mobile-header {
        display: block !important;
    }
}

@media (max-width: 767px) {
    .unique-topbar-left {
        display: none !important;
    }

    .unique-mobile-sidebar {
        width: 280px !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu
    const menuBtn = document.getElementById('uniqueMobileMenu');
    const sidebar = document.getElementById('uniqueMobileSidebar');
    const sidebarClose = document.getElementById('uniqueSidebarClose');
    const overlay = document.getElementById('uniqueMobileOverlay');

    if (menuBtn) {
        menuBtn.addEventListener('click', function() {
            sidebar.classList.add('active');
            overlay.classList.add('active');
        });
    }

    if (sidebarClose) {
        sidebarClose.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    // Mobile search
    const searchBtn = document.getElementById('uniqueMobileSearch');
    const searchBar = document.getElementById('uniqueMobileSearchBar');

    if (searchBtn && searchBar) {
        searchBtn.addEventListener('click', function() {
            searchBar.classList.toggle('active');
        });
    }
});
</script>
