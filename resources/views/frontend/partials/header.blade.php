{{-- Header Style Two --}}
<header class="header-style-two bg-primary-header">

    {{-- Top Bar --}}
    {{-- <div class="header-top-area bgprimary">
        <div class="container-2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="bwtween-area-header-top">
                        <div class="discount-area">
                            <p class="disc">
                                FREE delivery + 40% Discount for next 3 orders! Place your 1st order in.
                            </p>
                            <div class="countdown">
                                <div class="countDown" data-date="10/05/2025 10:20:00"></div>
                            </div>
                        </div>

                        <div class="contact-number-area">
                            <p>Need help? Call Us
                                <a href="tel:+258326821485">+258 3268 21485</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- Logo + Search + Category + Actions --}}
    <div class="search-header-area-main bgprimary">
        <div class="container-2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="logo-search-category-wrapper">

                        {{-- Logo --}}
                        <a href="{{ route('home') }}" class="logo-area">
                            {{-- <img src="{{ asset('frontend/assets/images/logo/logo-02.svg') }}" alt="logo-main" class="logo"> --}}
                            <span style="font-weight: 600; font-size: 25px; color: #fff;">Unique Foods</span>
                        </a>

                        {{-- Category + Search --}}
                        <div class="category-search-wrapper">
                            <div class="category-btn category-hover-header">
                                <img class="parent" src="{{ asset('frontend/assets/images/icons/bar-1.svg') }}" alt="icons">
                                <span>Categories</span>

                                {{-- Dynamic Categories with Subcategories --}}
                                <ul class="category-sub-menu" id="category-active-four">
                                    @if(isset($categories) && $categories->count())
                                        @foreach($categories as $cat)
                                            <li>
                                                <a href="{{ route('category.show', $cat->slug) }}" class="menu-item">
                                                    {{-- <img src="{{ asset('frontend/assets/images/icons/category.png') }}" alt="icon" style="max-width: 25px;"> --}}
                                                    <span>{{ $cat->name }}</span>

                                                    {{-- Show plus icon if has children --}}
                                                    @if($cat->activeChildren->count() > 0)
                                                        <i class="fa-regular fa-plus"></i>
                                                    @endif
                                                </a>

                                                {{-- Subcategories --}}
                                                @if($cat->activeChildren->count() > 0)
                                                    <ul class="submenu mm-collapse">
                                                        @foreach($cat->activeChildren as $subCat)
                                                            <li>
                                                                <a class="mobile-menu-link" href="{{ route('category.show', $subCat->slug) }}">
                                                                    {{ $subCat->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    @else
                                        <li>
                                            <a href="#">No categories found</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                            {{-- Search --}}
                            <form action="{{ route('shop') }}" method="GET" class="search-header">
                                <input type="text" name="q" placeholder="Search for products, categories" value="{{ request('q') }}">
                                <button type="submit" class="rts-btn btn-primary radious-sm with-icon">
                                    <div class="btn-text">Search</div>
                                    <div class="arrow-icon"><i class="fa-light fa-magnifying-glass"></i></div>
                                    <div class="arrow-icon"><i class="fa-light fa-magnifying-glass"></i></div>
                                </button>
                            </form>
                        </div>

                        {{-- Account / Wishlist / Cart --}}
                        <div class="accont-wishlist-cart-area-header">
                            <a href="#" class="btn-border-only account">
                                <i class="fa-light fa-user"></i> Account
                            </a>

                            <a href="#" class="btn-border-only wishlist">
                                <i class="fa-regular fa-heart"></i> Wishlist
                                <span class="number">0</span>
                            </a>

                            <div class="btn-border-only cart category-hover-header">
                                <i class="fa-sharp fa-regular fa-cart-shopping"></i>
                                <span class="text">My Cart</span>
                                <span class="number">0</span>

                                {{-- Mini cart dropdown --}}
                                <div class="category-sub-menu card-number-show">
                                    <h5 class="shopping-cart-number">Shopping Cart (0)</h5>

                                    <div class="cart-item-1 border-top">
                                        <p class="text-center py-4">Your cart is empty</p>
                                    </div>

                                    <div class="button-wrapper d-flex align-items-center justify-content-between">
                                        <a href="#" class="rts-btn btn-primary">View Cart</a>
                                        <a href="#" class="rts-btn btn-primary border-only">CheckOut</a>
                                    </div>

                                    <a href="#" class="overlink"></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main navigation (sticky) --}}
    <div class="rts-header-nav-area-one header--sticky">
        <div class="container-2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="nav-and-btn-wrapper">
                        <div class="nav-area">
                            <nav>
                                <ul class="parent-nav">
                                    <li class="parent {{ request()->routeIs('home') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                                    </li>

                                    {{-- <li class="parent">
                                        <a href="#" class="nav-link">About</a>
                                    </li> --}}

                                    <li class="parent {{ request()->routeIs('shop*') ? 'active' : '' }}">
                                        <a href="{{ route('shop') }}" class="nav-link">Shop</a>
                                    </li>

                                    {{-- <li class="parent">
                                        <a href="#" class="nav-link">Blog</a>
                                    </li> --}}

                                    <li class="parent">
                                        <a href="#" class="nav-link">Contact</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                    </div>

                    {{-- Mobile duplicate (for sticky mobile header) --}}
                    <div class="logo-search-category-wrapper">
                        <a href="{{ route('home') }}" class="logo-area">
                            <img src="{{ asset('frontend/assets/images/logo/logo-01.svg') }}" alt="logo-main" class="logo">
                        </a>

                        <div class="category-search-wrapper">
                            <div class="category-btn category-hover-header">
                                <img class="parent" src="{{ asset('frontend/assets/images/icons/bar-1.svg') }}" alt="icons">
                                <span>Categories</span>

                                <ul class="category-sub-menu">
                                    @if(isset($categories) && $categories->count())
                                        @foreach($categories as $cat)
                                            <li>
                                                <a href="{{ route('category.show', $cat->slug) }}" class="menu-item">
                                                    @if($cat->image)
                                                        <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}">
                                                    @else
                                                        <img src="{{ asset('frontend/assets/images/icons/01.svg') }}" alt="icon">
                                                    @endif
                                                    <span>{{ $cat->name }}</span>

                                                    @if($cat->activeChildren->count() > 0)
                                                        <i class="fa-regular fa-plus"></i>
                                                    @endif
                                                </a>

                                                @if($cat->activeChildren->count() > 0)
                                                    <ul class="submenu mm-collapse">
                                                        @foreach($cat->activeChildren as $subCat)
                                                            <li>
                                                                <a class="mobile-menu-link" href="{{ route('category.show', $subCat->slug) }}">
                                                                    {{ $subCat->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>

                            <form action="{{ route('shop') }}" method="GET" class="search-header">
                                <input type="text" name="q" placeholder="Search for products, categories" value="{{ request('q') }}">
                                <button type="submit" class="rts-btn btn-primary radious-sm with-icon">
                                    <div class="btn-text">Search</div>
                                    <div class="arrow-icon"><i class="fa-light fa-magnifying-glass"></i></div>
                                    <div class="arrow-icon"><i class="fa-light fa-magnifying-glass"></i></div>
                                </button>
                            </form>
                        </div>

                        <div class="main-wrapper-action-2 d-flex">
                            <div class="accont-wishlist-cart-area-header">
                                <a href="#" class="btn-border-only account">
                                    <i class="fa-light fa-user"></i> Account
                                </a>

                                <a href="#" class="btn-border-only wishlist">
                                    <i class="fa-regular fa-heart"></i> Wishlist
                                </a>

                                <div class="btn-border-only cart category-hover-header">
                                    <i class="fa-sharp fa-regular fa-cart-shopping"></i>
                                    <span class="text">My Cart</span>

                                    <div class="category-sub-menu card-number-show">
                                        <h5 class="shopping-cart-number">Shopping Cart (0)</h5>
                                        <div class="cart-item-1 border-top">
                                            <p class="text-center py-4">Your cart is empty</p>
                                        </div>
                                        <div class="button-wrapper d-flex align-items-center justify-content-between">
                                            <a href="#" class="rts-btn btn-primary">View Cart</a>
                                            <a href="#" class="rts-btn btn-primary border-only">CheckOut</a>
                                        </div>
                                        <a href="#" class="overlink"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="actions-area">
                                <div class="search-btn" id="search">
                                    <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.75 14.7188L11.5625 10.5312C12.4688 9.4375 12.9688 8.03125 12.9688 6.5C12.9688 2.9375 10.0312 0 6.46875 0C2.875 0 0 2.9375 0 6.5C0 10.0938 2.90625 13 6.46875 13C7.96875 13 9.375 12.5 10.5 11.5938L14.6875 15.7812C14.8438 15.9375 15.0312 16 15.25 16C15.4375 16 15.625 15.9375 15.75 15.7812C16.0625 15.5 16.0625 15.0312 15.75 14.7188ZM1.5 6.5C1.5 3.75 3.71875 1.5 6.5 1.5C9.25 1.5 11.5 3.75 11.5 6.5C11.5 9.28125 9.25 11.5 6.5 11.5C3.71875 11.5 1.5 9.28125 1.5 6.5Z" fill="#1F1F25"/>
                                    </svg>
                                </div>

                                <div class="menu-btn" id="menu-btn">
                                    <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect y="14" width="20" height="2" fill="#1F1F25"/>
                                        <rect y="7" width="20" height="2" fill="#1F1F25"/>
                                        <rect width="20" height="2" fill="#1F1F25"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</header>

{{-- Mobile Sidebar --}}
<div id="side-bar" class="side-bar header-two">
    <button class="close-icon-menu"><i class="far fa-times"></i></button>

    <form action="{{ route('shop') }}" method="GET" class="search-input-area-menu mt--30">
        <input type="text" name="q" placeholder="Search..." value="{{ request('q') }}">
        <button type="submit"><i class="fa-light fa-magnifying-glass"></i></button>
    </form>

    <div class="mobile-menu-nav-area tab-nav-btn mt--20">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Menu</button>
                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Category</button>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            {{-- Menu tab --}}
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                <div class="mobile-menu-main">
                    <nav class="nav-main mainmenu-nav mt--30">
                        <ul class="mainmenu metismenu" id="mobile-menu-active">
                            <li><a href="{{ route('home') }}" class="main">Home</a></li>
                            <li><a href="#" class="main">About</a></li>
                            <li><a href="{{ route('shop') }}" class="main">Shop</a></li>
                            <li><a href="#" class="main">Blog</a></li>
                            <li><a href="#" class="main">Contact Us</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            {{-- Category tab with subcategories --}}
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                <div class="category-btn category-hover-header menu-category">
                    <ul class="category-sub-menu" id="category-active-menu">
                        @if(isset($categories) && $categories->count())
                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('category.show', $cat->slug) }}" class="menu-item">
                                        @if($cat->image)
                                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}">
                                        @else
                                            <img src="{{ asset('frontend/assets/images/icons/01.svg') }}" alt="icon">
                                        @endif
                                        <span>{{ $cat->name }}</span>

                                        @if($cat->activeChildren->count() > 0)
                                            <i class="fa-regular fa-plus"></i>
                                        @endif
                                    </a>

                                    @if($cat->activeChildren->count() > 0)
                                        <ul class="submenu mm-collapse">
                                            @foreach($cat->activeChildren as $subCat)
                                                <li>
                                                    <a class="mobile-menu-link" href="{{ route('category.show', $subCat->slug) }}">
                                                        {{ $subCat->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="button-area-main-wrapper-menuy-sidebar mt--50">
        <div class="contact-area">
            <div class="phone">
                <i class="fa-light fa-headset"></i>
                <a href="tel:02345697871">02345697871</a>
            </div>
            <div class="phone">
                <i class="fa-light fa-envelope"></i>
                <a href="mailto:info@example.com">info@example.com</a>
            </div>
        </div>

        <div class="buton-area-bottom">
            <a href="#" class="rts-btn btn-primary">Sign In</a>
            <a href="#" class="rts-btn btn-primary">Sign Up</a>
        </div>
    </div>
</div>

<div id="anywhere-home" class="anywere"></div>
<style>
    .category-sub-menu .submenu {
        display: none;
        padding-left: 20px;
    }

    .category-sub-menu .submenu.mm-show {
        display: block;
    }

    .category-sub-menu .fa-plus,
    .category-sub-menu .fa-minus {
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .category-sub-menu .fa-minus {
        transform: rotate(180deg);
    }

</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle plus icon clicks for category dropdowns
    const plusIcons = document.querySelectorAll('.category-sub-menu .fa-plus');

    plusIcons.forEach(function(icon) {
        icon.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Find the parent menu item
            const menuItem = this.closest('.menu-item').parentElement;
            const submenu = menuItem.querySelector('.submenu');

            if (submenu) {
                // Toggle submenu visibility
                submenu.classList.toggle('mm-show');

                // Toggle icon rotation (optional visual feedback)
                this.classList.toggle('fa-plus');
                this.classList.toggle('fa-minus');
            }
        });
    });

    // Prevent parent link navigation when it has children
    const menuItemsWithChildren = document.querySelectorAll('.category-sub-menu li:has(.submenu) > .menu-item');

    menuItemsWithChildren.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const submenu = this.parentElement.querySelector('.submenu');

            if (submenu) {
                e.preventDefault();
                submenu.classList.toggle('mm-show');

                const icon = this.querySelector('.fa-plus, .fa-minus');
                if (icon) {
                    icon.classList.toggle('fa-plus');
                    icon.classList.toggle('fa-minus');
                }
            }
        });
    });
});
</script>
