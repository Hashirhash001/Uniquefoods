<!-- Modern Header -->
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
                        <a href="mailto:info@unique-food.co.uk" class="unique-toplink">
                            <i class="fa-regular fa-envelope"></i>
                            <span>info@unique-food.co.uk</span>
                        </a>
                        <span class="unique-divider">|</span>
                        <a href="tel:+44 7425 837716" class="unique-toplink">
                            <i class="fa-regular fa-phone"></i>
                            <span>+44 7425 837716</span>
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
                        <div class="unique-logo-info">
                            <img src="{{ asset('admin/assets/images/logo/unique food logo3.png') }}" alt="" style="max-width: 100px;">
                        </div>
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="unique-search-section">
                    <div class="unique-search-form-wrapper">
                        <div class="unique-search-field-wrapper">
                            <input type="text"
                                id="headerSearchInput"
                                placeholder="Search for products, brands, and more..."
                                class="unique-search-input"
                                autocomplete="off">
                            <button type="button" class="unique-search-button" id="headerSearchBtn">
                                <i class="fa-regular fa-magnifying-glass"></i>
                            </button>
                            <div class="unique-search-dropdown" id="headerSearchDropdown">
                                <div class="search-dropdown-content" id="headerSearchResults"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Header Actions -->
                <div class="unique-header-actions">

                    <!-- Wishlist -->
                    <a href="{{ route('wishlist.index') }}" class="unique-action-wrapper">
                        <div class="unique-action-trigger">
                            <i class="fa-regular fa-heart"></i>
                            <span class="unique-badge" id="wishlistCount">0</span>
                        </div>
                        <div class="unique-action-info">
                            <span class="unique-action-value">Wishlist</span>
                        </div>
                    </a>

                    <!-- Cart -->
                    <div class="unique-action-wrapper unique-cart-wrapper">
                        <button class="unique-action-trigger">
                            <i class="fa-regular fa-cart-shopping"></i>
                            <span class="unique-badge" id="cartCount">0</span>
                            <div class="unique-action-info">
                                <span class="unique-action-label">Cart</span>
                                <span class="unique-action-value">£<span id="cartTotal">0.00</span></span>
                            </div>
                        </button>
                        <!-- Cart Dropdown -->
                        <div class="unique-action-menu unique-cart-menu">
                            <div class="unique-menu-header">
                                <h4>Shopping Cart</h4>
                                <span class="unique-cart-total"><span id="cartItemCount">0</span> Items</span>
                            </div>
                            <div class="unique-cart-empty" id="emptyCartState">
                                <i class="fa-regular fa-cart-shopping"></i>
                                <p>Your cart is empty</p>
                                <a href="{{ route('shop') }}" class="unique-btn-signin">Start Shopping</a>
                            </div>
                            <div class="unique-cart-items" id="cartItemsContainer" style="display: none;"></div>
                            <div class="unique-cart-footer" id="cartFooter" style="display: none;">
                                <div class="unique-cart-subtotal">
                                    <span>Subtotal:</span>
                                    <strong>£<span id="cartSubtotal">0.00</span></strong>
                                </div>
                                <a href="{{ route('cart.index') }}" class="unique-btn-view-cart">View Cart</a>
                                <a href="{{ route('checkout.index') }}" class="unique-btn-checkout">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>

                    <!-- Account -->
                    <div class="unique-action-wrapper unique-account-wrapper">
                        <button class="unique-action-trigger">
                            @auth
                                <div class="unique-account-avatar">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                                    @else
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    @endif
                                </div>
                            @else
                                <i class="fa-regular fa-user"></i>
                            @endauth
                            <div class="unique-action-info">
                                <span class="unique-action-label">
                                    @auth My Account @else Welcome @endauth
                                </span>
                                <span class="unique-action-value">
                                    @auth
                                        {{ Str::limit(Auth::user()->name, 18) }}
                                    @else
                                        Sign In
                                    @endauth
                                </span>
                            </div>
                        </button>
                        <!-- Account Menu -->
                        <div class="unique-action-menu">
                            @auth
                                <div class="unique-menu-header unique-menu-header-user">
                                    <div class="unique-menu-user-avatar">
                                        @if(Auth::user()->avatar)
                                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                                        @else
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <h4>{{ Auth::user()->name }}</h4>
                                        <p style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                                <div class="unique-menu-body">
                                    <a href="{{ route('account.profile') }}" class="unique-menu-link">
                                        <i class="fa-regular fa-user"></i>
                                        <span>My Profile</span>
                                    </a>
                                    <a href="{{ route('orders.index') }}" class="unique-menu-link">
                                        <i class="fa-regular fa-receipt"></i>
                                        <span>My Orders</span>
                                    </a>
                                    <a href="{{ route('wishlist.index') }}" class="unique-menu-link">
                                        <i class="fa-regular fa-heart"></i>
                                        <span>Wishlist</span>
                                    </a>
                                    <a href="{{ route('account.addresses') }}" class="unique-menu-link">
                                        <i class="fa-regular fa-location-dot"></i>
                                        <span>Addresses</span>
                                    </a>
                                    {{-- <a href="#" class="unique-menu-link">
                                        <i class="fa-regular fa-gear"></i>
                                        <span>Settings</span>
                                    </a> --}}
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
                                <div class="unique-menu-header">
                                    <h4>Welcome!</h4>
                                    <p>Sign in to access your account</p>
                                </div>
                                <div class="unique-menu-footer">
                                    <a href="{{ route('login') }}" class="unique-btn-signin">Sign In</a>
                                    <a href="{{ route('register') }}" class="unique-btn-signup">Create Account</a>
                                </div>
                            @endauth
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

                <!-- All Categories -->
                <div class="unique-categories-container">
                    <button class="unique-categories-button">
                        <i class="fa-regular fa-bars"></i>
                        <span>All Categories</span>
                        <i class="fa-regular fa-chevron-down"></i>
                    </button>
                    <div class="unique-categories-dropdown">
                        @if(isset($categories) && $categories->count())
                            @foreach($categories as $cat)
                                <div class="unique-category-item">
                                    @php $hasChildren = $cat->activeChildren->isNotEmpty(); @endphp
                                    <a href="{{ route('shop') }}?categories[]={{ $cat->id }}" class="unique-category-link"
                                       onclick="event.preventDefault(); window.location.href='{{ route('shop') }}?categories[]={{ $cat->id }}';">
                                        @if($cat->image)
                                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="unique-cat-img">
                                        @else
                                            <i class="fa-regular fa-box unique-cat-icon"></i>
                                        @endif
                                        <span class="unique-cat-name">{{ $cat->name }}</span>
                                        @if($hasChildren)
                                            <i class="fa-regular fa-chevron-right unique-cat-arrow"></i>
                                        @endif
                                    </a>
                                    @if($hasChildren)
                                        <div class="unique-category-submenu">
                                            <div class="unique-submenu-title">{{ $cat->name }}</div>
                                            <div class="unique-submenu-items">
                                                @foreach($cat->activeChildren as $subCat)
                                                    <a href="{{ route('shop') }}?categories[]={{ $subCat->id }}" class="unique-subcat-link"
                                                       onclick="event.preventDefault(); window.location.href='{{ route('shop') }}?categories[]={{ $subCat->id }}';">
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

                <!-- Main Nav -->
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
                        <li class="unique-nav-item {{ request()->routeIs('orders.index') ? 'unique-active' : '' }}">
                            <a href="{{ route('orders.index') }}" class="unique-navlink">
                                <i class="fa-regular fa-newspaper"></i>
                                <span>Orders</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- Promo -->
                {{-- <div class="unique-promo-banner">
                    <i class="fa-solid fa-gift"></i>
                    <span>Free delivery on orders over £500</span>
                </div> --}}

            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════ -->
    <!-- MOBILE HEADER (top bar)                -->
    <!-- ═══════════════════════════════════════ -->
    <div class="unique-mobile-header">
        <div class="unique-mobile-top">
            <a href="{{ route('home') }}" class="unique-mobile-logo">
                <img src="{{ asset('admin/assets/images/logo/unique food logo3.png') }}" alt="Unique Foods" style="height: 65px; width: auto;">
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

        <!-- Mobile Search Bar -->
        <div class="unique-mobile-searchbar" id="uniqueMobileSearchBar">
            <div style="position: relative;">
                <form action="{{ route('shop') }}" method="GET" onsubmit="return false;">
                    <input type="text" name="q" id="mobileSearchInput"
                           placeholder="Search products..." autocomplete="off">
                    <button type="button" id="mobileSearchBtn" style="width: unset !important;">
                        <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                </form>
                <div class="unique-mobile-search-dropdown" id="mobileSearchDropdown">
                    <div class="search-dropdown-content" id="mobileSearchResults"></div>
                </div>
            </div>
        </div>
    </div>

</header>

    <!-- ═══════════════════════════════════════ -->
    <!-- ACCOUNT BOTTOM SHEET                   -->
    <!-- ═══════════════════════════════════════ -->
    <div class="unique-account-sheet" id="uniqueAccountSheet">
        <div class="unique-sheet-handle"></div>
        <div class="unique-sheet-header">
            @auth
                <div class="unique-sheet-user-info">
                    <div class="unique-sheet-avatar">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <strong>{{ Auth::user()->name }}</strong>
                        <span>{{ Auth::user()->email }}</span>
                    </div>
                </div>
            @else
                <div class="unique-sheet-user-info">
                    <div class="unique-sheet-avatar">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div>
                        <strong>Welcome!</strong>
                        <span>Sign in to your account</span>
                    </div>
                </div>
            @endauth
            <button class="unique-sheet-close" id="uniqueAccountSheetClose">
                <i class="fa-regular fa-xmark"></i>
            </button>
        </div>
        <div class="unique-sheet-body">
            @auth
                <a href="{{ route('account.profile') }}" class="unique-sheet-menu-item">
                    <i class="fa-regular fa-user"></i>
                    <span>My Profile</span>
                    <i class="fa-regular fa-chevron-right" style="margin-left:auto; color:#9ca3af; font-size:12px;"></i>
                </a>
                <a href="{{ route('orders.index') }}" class="unique-sheet-menu-item">
                    <i class="fa-regular fa-receipt"></i>
                    <span>My Orders</span>
                    <i class="fa-regular fa-chevron-right" style="margin-left:auto; color:#9ca3af; font-size:12px;"></i>
                </a>
                <a href="{{ route('wishlist.index') }}" class="unique-sheet-menu-item">
                    <i class="fa-regular fa-heart"></i>
                    <span>Wishlist</span>
                    <i class="fa-regular fa-chevron-right" style="margin-left:auto; color:#9ca3af; font-size:12px;"></i>
                </a>
                <a href="{{ route('cart.index') }}" class="unique-sheet-menu-item">
                    <i class="fa-regular fa-cart-shopping"></i>
                    <span>My Cart</span>
                    <i class="fa-regular fa-chevron-right" style="margin-left:auto; color:#9ca3af; font-size:12px;"></i>
                </a>
                <a href="{{ route('account.addresses') }}" class="unique-sheet-menu-item">
                    <i class="fa-regular fa-location-dot"></i>
                    <span>Addresses</span>
                    <i class="fa-regular fa-chevron-right" style="margin-left:auto; color:#9ca3af; font-size:12px;"></i>
                </a>
                {{-- <a href="#" class="unique-sheet-menu-item">
                    <i class="fa-regular fa-gear"></i>
                    <span>Settings</span>
                    <i class="fa-regular fa-chevron-right" style="margin-left:auto; color:#9ca3af; font-size:12px;"></i>
                </a> --}}
                {{-- <div class="unique-sheet-divider"></div> --}}
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="unique-sheet-menu-item unique-sheet-logout">
                        <i class="fa-regular fa-arrow-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="unique-sheet-menu-item">
                    <i class="fa-regular fa-right-to-bracket"></i>
                    <span>Sign In</span>
                    <i class="fa-regular fa-chevron-right" style="margin-left:auto; color:#9ca3af; font-size:12px;"></i>
                </a>
                <a href="{{ route('register') }}" class="unique-sheet-menu-item">
                    <i class="fa-regular fa-user-plus"></i>
                    <span>Create Account</span>
                    <i class="fa-regular fa-chevron-right" style="margin-left:auto; color:#9ca3af; font-size:12px;"></i>
                </a>
            @endauth
        </div>
    </div>

    <!-- Single overlay -->
    <div class="unique-mobile-overlay" id="uniqueMobileOverlay"></div>

    <!-- ═══════════════════════════════════════ -->
    <!-- MOBILE BOTTOM NAV (4 items)            -->
    <!-- ═══════════════════════════════════════ -->
    <nav class="unique-mobile-bottom-nav">
        <a href="{{ route('home') }}" class="unique-bottom-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fa-{{ request()->routeIs('home') ? 'solid' : 'regular' }} fa-house"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('shop') }}" class="unique-bottom-nav-item {{ request()->routeIs('shop') ? 'active' : '' }}">
            <i class="fa-{{ request()->routeIs('shop') ? 'solid' : 'regular' }} fa-store"></i>
            <span>Shop</span>
        </a>
        <a href="{{ route('orders.index') }}" class="unique-bottom-nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="fa-{{ request()->routeIs('orders.*') ? 'solid' : 'regular' }} fa-receipt"></i>
            <span>Orders</span>
        </a>
        <button type="button" class="unique-bottom-nav-item" id="uniqueBottomAccount">
            @auth
                <div class="unique-bottom-avatar">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>
            @else
                <i class="fa-regular fa-user"></i>
            @endauth
            <span>Account</span>
        </button>
    </nav>

<!-- CSS -->
<link rel="stylesheet" href="{{ asset('frontend/assets/css/header.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/cart-wishlist.css') }}">

@push('scripts')
<script src="{{ asset('frontend/assets/js/cart-wishlist.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const mobOverlay   = document.getElementById('uniqueMobileOverlay');
    const accountSheet = document.getElementById('uniqueAccountSheet');
    const accountBtn   = document.getElementById('uniqueBottomAccount');
    const accountClose = document.getElementById('uniqueAccountSheetClose');
    const mobileSearch    = document.getElementById('uniqueMobileSearch');
    const mobileSearchBar = document.getElementById('uniqueMobileSearchBar');

    // ── GUARANTEE sheet starts closed on every page load ──
    if (accountSheet) {
        accountSheet.classList.remove('open');
    }
    if (mobOverlay) {
        mobOverlay.classList.remove('active');
    }
    document.body.style.overflow = '';

    function openSheet() {
        if (!accountSheet) return;
        accountSheet.classList.add('open');
        mobOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSheet() {
        if (accountSheet) accountSheet.classList.remove('open');
        if (mobOverlay)   mobOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Toggle: tap Account button opens; if already open, closes
    if (accountBtn) {
        accountBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (accountSheet.classList.contains('open')) {
                closeSheet();
            } else {
                openSheet();
            }
        });
    }

    if (accountClose) accountClose.addEventListener('click', closeSheet);
    if (mobOverlay)   mobOverlay.addEventListener('click', closeSheet);

    // Close sheet on swipe down
    let touchStartY = 0;
    if (accountSheet) {
        accountSheet.addEventListener('touchstart', e => {
            touchStartY = e.touches[0].clientY;
        }, { passive: true });
        accountSheet.addEventListener('touchend', e => {
            if (e.changedTouches[0].clientY - touchStartY > 80) closeSheet();
        }, { passive: true });
    }

    // Mobile search toggle
    if (mobileSearch && mobileSearchBar) {
        mobileSearch.addEventListener('click', function(e) {
            e.preventDefault();
            mobileSearchBar.classList.toggle('active');
            if (mobileSearchBar.classList.contains('active')) {
                setTimeout(() => mobileSearchBar.querySelector('input')?.focus(), 100);
            }
        });
    }

});
</script>

<script>
$(document).ready(function() {

    // ===== DESKTOP SEARCH =====
    let searchTimeout;
    const searchInput    = $('#headerSearchInput');
    const searchDropdown = $('#headerSearchDropdown');
    const searchResults  = $('#headerSearchResults');
    const searchBtn      = $('#headerSearchBtn');

    searchInput.on('input', function() {
        const query = $(this).val().trim();
        clearTimeout(searchTimeout);

        if (query.length === 0) {
            searchDropdown.removeClass('show');
            searchResults.html('');
            return;
        }

        if (query.length < 2) { searchDropdown.removeClass('show'); return; }
        searchTimeout = setTimeout(() => performSearch(query), 300);
    });

    searchBtn.on('click', function() {
        const query = searchInput.val().trim();
        if (query.length >= 2) window.location.href = '{{ route("shop") }}?q=' + encodeURIComponent(query);
    });

    searchInput.on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            const query = $(this).val().trim();
            if (query.length >= 2) window.location.href = '{{ route("shop") }}?q=' + encodeURIComponent(query);
        }
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.unique-search-section').length) searchDropdown.removeClass('show');
    });

    function performSearch(query) {
        $.ajax({
            url: '{{ route("shop.search") }}',
            type: 'GET',
            data: { q: query },
            success: function(response) {
                if (response.success) displaySearchResults(response, query);
            },
            error: function() {
                searchResults.html('<div class="search-empty"><i class="fa-regular fa-triangle-exclamation"></i><p>Failed to search</p></div>');
                searchDropdown.addClass('show');
            }
        });
    }

    function displaySearchResults(data, query) {
        let html = '';
        if (data.products.length === 0 && data.categories.length === 0) {
            html = `<div class="search-empty"><i class="fa-regular fa-magnifying-glass"></i><p>No results found for "${query}"</p></div>`;
        } else {
            if (data.categories.length > 0) {
                html += '<div class="search-section-title">Categories</div>';
                data.categories.forEach(cat => {
                    html += `<a href="{{ route('shop') }}?categories[]=${cat.id}" class="search-item">
                        <div class="search-item-category"><i class="fa-regular fa-folder"></i></div>
                        <div class="search-item-info"><div class="search-item-name">${cat.name}</div></div>
                        <i class="fa-regular fa-arrow-right"></i></a>`;
                });
            }
            if (data.products.length > 0) {
                html += '<div class="search-section-title" style="margin-top:16px;">Products</div>';
                data.products.forEach(product => {
                    const stockText = product.stock > 0
                        ? `<div class="search-item-stock">In Stock</div>`
                        : `<div class="search-item-stock out">Out of Stock</div>`;
                    html += `<a href="/product/${product.slug}" class="search-item">
                        <img src="${product.image_url}" alt="${product.name}" class="search-item-image">
                        <div class="search-item-info">
                            <div class="search-item-name">${product.name}</div>
                            <div class="search-item-meta">${product.category || 'General'}</div>
                            ${stockText}
                        </div>
                        <div class="search-item-price">£${product.price}</div></a>`;
                });
            }
            html += `<a href="{{ route('shop') }}?q=${encodeURIComponent(query)}" class="search-view-all">View All Results <i class="fa-regular fa-arrow-right"></i></a>`;
        }
        searchResults.html(html);
        searchDropdown.addClass('show');
    }

    // ===== MOBILE SEARCH =====
    let mobileSearchTimeout;
    const mobileSearchInput    = $('#mobileSearchInput');
    const mobileSearchDropdown = $('#mobileSearchDropdown');
    const mobileSearchResults  = $('#mobileSearchResults');
    const mobileSearchBtn      = $('#mobileSearchBtn');

    mobileSearchInput.on('input', function() {
        const query = $(this).val().trim();
        clearTimeout(mobileSearchTimeout);

        if (query.length === 0) {
            mobileSearchDropdown.removeClass('show');
            mobileSearchResults.html(''); // ✅ clear stale results
            return;
        }

        if (query.length < 2) { mobileSearchDropdown.removeClass('show'); return; }
        mobileSearchTimeout = setTimeout(() => performMobileSearch(query), 300);
    });

    mobileSearchBtn.on('click', function() {
        const query = mobileSearchInput.val().trim();
        if (query.length >= 2) window.location.href = '{{ route("shop") }}?q=' + encodeURIComponent(query);
    });

    mobileSearchInput.on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            const query = $(this).val().trim();
            if (query.length >= 2) window.location.href = '{{ route("shop") }}?q=' + encodeURIComponent(query);
        }
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.unique-mobile-searchbar').length) mobileSearchDropdown.removeClass('show');
    });

    function performMobileSearch(query) {
        $.ajax({
            url: '{{ route("shop.search") }}',
            type: 'GET',
            data: { q: query },
            success: function(response) {
                if (response.success) displayMobileSearchResults(response, query);
            },
            error: function() {
                mobileSearchResults.html('<div class="search-empty"><i class="fa-regular fa-triangle-exclamation"></i><p>Failed to search</p></div>');
                mobileSearchDropdown.addClass('show');
            }
        });
    }

    function displayMobileSearchResults(data, query) {
        let html = '';
        if (data.products.length === 0 && data.categories.length === 0) {
            html = `<div class="search-empty"><i class="fa-regular fa-magnifying-glass"></i><p>No results found</p></div>`;
        } else {
            if (data.categories.length > 0) {
                html += '<div class="search-section-title">Categories</div>';
                data.categories.forEach(cat => {
                    html += `<a href="{{ route('shop') }}?categories[]=${cat.id}" class="search-item">
                        <div class="search-item-category"><i class="fa-regular fa-folder"></i></div>
                        <div class="search-item-info"><div class="search-item-name">${cat.name}</div></div></a>`;
                });
            }
            if (data.products.length > 0) {
                html += '<div class="search-section-title" style="margin-top:16px;">Products</div>';
                data.products.forEach(product => {
                    html += `<a href="/product/${product.slug}" class="search-item">
                        <img src="${product.image_url}" alt="${product.name}" class="search-item-image">
                        <div class="search-item-info"><div class="search-item-name">${product.name}</div></div>
                        <div class="search-item-price">£${product.price}</div></a>`;
                });
            }
            html += `<a href="{{ route('shop') }}?q=${encodeURIComponent(query)}" class="search-view-all">View All</a>`;
        }
        mobileSearchResults.html(html);
        mobileSearchDropdown.addClass('show');
    }

});
</script>
@endpush
