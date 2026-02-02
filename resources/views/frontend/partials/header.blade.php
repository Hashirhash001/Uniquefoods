<!-- Modern Header Style - MOBILE OPTIMIZED WITH AJAX CART & WISHLIST -->
<header class="unique-modern-header">
    <!-- Top Bar -->
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
                        <a href="tel:258326821485" class="unique-toplink">
                            <i class="fa-regular fa-phone"></i>
                            <span>258 3268 21485</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="unique-main-header">
        <div class="container-2">
            <div class="unique-header-wrapper">
                <!-- Logo -->
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

                <!-- Search Bar -->
                <div class="unique-search-section">
                    <form action="{{ route('shop') }}" method="GET" class="unique-search-form">
                        <div class="unique-search-field-wrapper">
                            <input type="text" name="q" placeholder="Search for products, brands, and more..."
                                   value="{{ request('q') }}" class="unique-search-input">
                            <button type="submit" class="unique-search-button">
                                <i class="fa-regular fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Header Actions -->
                <div class="unique-header-actions">
                    <!-- Account Dropdown -->
                    <div class="unique-action-wrapper unique-account-wrapper">
                        <button class="unique-action-trigger">
                            <i class="fa-regular fa-user"></i>
                            <div class="unique-action-info">
                                <span class="unique-action-label">Account</span>
                                <span class="unique-action-value">
                                    @auth
                                        {{ Str::limit(Auth::user()->name, 10) }}
                                    @else
                                        Sign In
                                    @endauth
                                </span>
                            </div>
                        </button>

                        <!-- Account Menu -->
                        <div class="unique-action-menu">
                            @auth
                                <!-- Logged In User Menu -->
                                <div class="unique-menu-header">
                                    <h4>Hello, {{ Auth::user()->name }}!</h4>
                                    <p>Manage your account and orders</p>
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
                                    <a href="{{ route('wishlist.index') }}" class="unique-menu-link">
                                        <i class="fa-regular fa-heart"></i>
                                        <span>Wishlist</span>
                                    </a>
                                    <a href="#" class="unique-menu-link">
                                        <i class="fa-regular fa-location-dot"></i>
                                        <span>Addresses</span>
                                    </a>
                                    <a href="#" class="unique-menu-link">
                                        <i class="fa-regular fa-gear"></i>
                                        <span>Settings</span>
                                    </a>
                                </div>
                                <div class="unique-menu-footer">
                                    <form action="{{ route('logout') }}" method="POST" style="width: 100%">
                                        @csrf
                                        <button type="submit" class="unique-btn-logout">
                                            <i class="fa-regular fa-arrow-right-from-bracket"></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <!-- Guest User Menu -->
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
                                    <a href="{{ route('wishlist.index') }}" class="unique-menu-link">
                                        <i class="fa-regular fa-heart"></i>
                                        <span>Wishlist</span>
                                    </a>
                                    <a href="#" class="unique-menu-link">
                                        <i class="fa-regular fa-gear"></i>
                                        <span>Settings</span>
                                    </a>
                                </div>
                                <div class="unique-menu-footer">
                                    <a href="{{ route('login') }}" class="unique-btn-signin">Sign In</a>
                                    <a href="{{ route('register') }}" class="unique-btn-signup">Create Account</a>
                                </div>
                            @endauth
                        </div>
                    </div>

                    <!-- Wishlist -->
                    <a href="{{ route('wishlist.index') }}" class="unique-action-wrapper">
                        <div class="unique-action-trigger">
                            <i class="fa-regular fa-heart"></i>
                            <span class="unique-badge" id="wishlistCount">0</span>
                        </div>
                        <div class="unique-action-info">
                            <span class="unique-action-label">Wishlist</span>
                            <span class="unique-action-value">My Items</span>
                        </div>
                    </a>

                    <!-- Cart -->
                    <div class="unique-action-wrapper unique-cart-wrapper">
                        <button class="unique-action-trigger">
                            <i class="fa-regular fa-cart-shopping"></i>
                            <span class="unique-badge" id="cartCount">0</span>
                            <div class="unique-action-info">
                                <span class="unique-action-label">Cart</span>
                                <span class="unique-action-value">₹<span id="cartTotal">0.00</span></span>
                            </div>
                        </button>

                        <!-- Cart Dropdown -->
                        <div class="unique-action-menu unique-cart-menu">
                            <div class="unique-menu-header">
                                <h4>Shopping Cart</h4>
                                <span class="unique-cart-total"><span id="cartItemCount">0</span> Items</span>
                            </div>

                            <!-- Empty Cart State -->
                            <div class="unique-cart-empty" id="emptyCartState">
                                <i class="fa-regular fa-cart-shopping"></i>
                                <p>Your cart is empty</p>
                                <a href="{{ route('shop') }}" class="unique-btn-signin">Start Shopping</a>
                            </div>

                            <!-- Cart Items Container -->
                            <div class="unique-cart-items" id="cartItemsContainer" style="display: none;">
                                <!-- Dynamically loaded cart items -->
                            </div>

                            <!-- Cart Footer -->
                            <div class="unique-cart-footer" id="cartFooter" style="display: none;">
                                <div class="unique-cart-subtotal">
                                    <span>Subtotal:</span>
                                    <strong>₹<span id="cartSubtotal">0.00</span></strong>
                                </div>
                                <a href="{{ route('cart.index') }}" class="unique-btn-view-cart">View Cart</a>
                                <a href="#" class="unique-btn-checkout">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="unique-navbar">
        <div class="container-2">
            <div class="unique-navbar-wrapper">
                <!-- All Categories Mega Menu -->
                <div class="unique-categories-container">
                    <button class="unique-categories-button">
                        <i class="fa-regular fa-bars"></i>
                        <span>All Categories</span>
                        <i class="fa-regular fa-chevron-down"></i>
                    </button>

                    <!-- Main Categories List -->
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

                <!-- Main Navigation -->
                <nav class="unique-main-nav">
                    <ul class="unique-nav-list">
                        <li class="unique-nav-item {{ request()->routeIs('home') ? 'unique-active' : '' }}">
                            <a href="{{ route('home') }}" class="unique-navlink">
                                <i class="fa-regular fa-house"></i>
                                <span>Home</span>
                            </a>
                        </li>
                        <li class="unique-nav-item {{ request()->routeIs('shop') ? 'unique-active' : '' }}">
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

                <!-- Promo Banner -->
                <div class="unique-promo-banner">
                    <i class="fa-solid fa-gift"></i>
                    <span>Free delivery on orders over ₹500</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Header -->
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
                <a href="{{ route('cart.index') }}" class="unique-mobile-cart-btn">
                    <i class="fa-regular fa-cart-shopping"></i>
                    <span class="unique-badge" id="mobileCartCount">0</span>
                </a>
            </div>
        </div>

        <!-- Mobile Search -->
        <div class="unique-mobile-searchbar" id="uniqueMobileSearchBar">
            <form action="{{ route('shop') }}" method="GET">
                <input type="text" name="q" placeholder="Search products..." value="{{ request('q') }}">
                <button type="submit"><i class="fa-regular fa-magnifying-glass"></i></button>
            </form>
        </div>
    </div>
</header>

<!-- Mobile Sidebar -->
<div class="unique-mobile-sidebar" id="uniqueMobileSidebar">
    <div class="unique-sidebar-header">
        <div class="unique-sidebar-user">
            <i class="fa-regular fa-user-circle"></i>
            <div class="unique-user-details">
                @auth
                    <h4>{{ Auth::user()->name }}</h4>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: rgba(255,255,255,0.9); font-size: 13px; cursor: pointer; padding: 0;">
                            Logout
                        </button>
                    </form>
                @else
                    <h4>Welcome!</h4>
                    <a href="{{ route('login') }}">Sign In / Register</a>
                @endauth
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
            <a href="{{ route('wishlist.index') }}" class="unique-mobile-link">
                <i class="fa-regular fa-heart"></i>
                <span>Wishlist</span>
                <span class="unique-count-badge" id="mobileWishlistCount">0</span>
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
            <a href="tel:258326821485">
                <i class="fa-regular fa-phone"></i>
                <span>258 3268 21485</span>
            </a>
            <a href="mailto:info@uniquefoods.com">
                <i class="fa-regular fa-envelope"></i>
                <span>info@uniquefoods.com</span>
            </a>
        </div>
    </div>
</div>

<div class="unique-mobile-overlay" id="uniqueMobileOverlay"></div>

<!-- Include CSS & JS -->
<link rel="stylesheet" href="{{ asset('frontend/assets/css/header.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/cart-wishlist.css') }}">

@push('scripts')
<script src="{{ asset('frontend/assets/js/cart-wishlist.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu
    const menuBtn = document.getElementById('uniqueMobileMenu');
    const sidebar = document.getElementById('uniqueMobileSidebar');
    const sidebarClose = document.getElementById('uniqueSidebarClose');
    const overlay = document.getElementById('uniqueMobileOverlay');

    if (menuBtn) {
        menuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    if (sidebarClose) {
        sidebarClose.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    // Mobile search
    const searchBtn = document.getElementById('uniqueMobileSearch');
    const searchBar = document.getElementById('uniqueMobileSearchBar');

    if (searchBtn && searchBar) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            searchBar.classList.toggle('active');
            if (searchBar.classList.contains('active')) {
                const input = searchBar.querySelector('input');
                if (input) {
                    setTimeout(() => input.focus(), 100);
                }
            }
        });
    }

    // Prevent sidebar close when clicking inside it
    if (sidebar) {
        sidebar.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // ⚡ Load cart and wishlist counts on page load
    if (typeof window.updateCartUI === 'function') {
        window.updateCartUI();
    }
    if (typeof window.updateWishlistUI === 'function') {
        window.updateWishlistUI();
    }
});
</script>
@endpush
