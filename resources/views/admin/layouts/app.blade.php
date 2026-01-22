<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('admin/assets/images/fav.png') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/plugins.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    @stack('styles')
</head>

<body>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            overflow-x: hidden;
        }

        /* ===============================
        MAIN DASHBOARD LAYOUT
        ================================ */
        .main_dashboard {
            display: flex;
            min-height: 100vh;
            background: #f3f4f6;
        }

        /* ===============================
        SIDEBAR CONTAINER
        ================================ */
        .sidebar_left {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            padding: 24px 12px;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Minimized Sidebar (Desktop) */
        .sidebar_left.minimized {
            width: 80px;
        }

        .sidebar_left.minimized .sidebar-logo {
            font-size: 24px;
            padding: 8px 0;
        }

        .sidebar_left.minimized .sidebar-logo-text {
            display: none;
        }

        .sidebar_left.minimized .single-menu-item > a span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar_left.minimized .uf-arrow {
            display: none;
        }

        .sidebar_left.minimized .single-menu-item > a {
            justify-content: center;
            padding: 12px 8px;
            position: relative;
        }

        .sidebar_left.minimized .submenu {
            display: none !important;
        }

        /* Tooltip for minimized items */
        .sidebar_left.minimized .single-menu-item {
            position: relative;
        }

        .sidebar_left.minimized .single-menu-item > a::after {
            content: attr(data-title);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: #1f2937;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            z-index: 10001;
        }

        .sidebar_left.minimized .single-menu-item > a:hover::after {
            opacity: 1;
        }

        /* Hide popup submenu by default - only show when minimized */
        .submenu-popup {
            display: none !important;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        /* Submenu popup for minimized state ONLY */
        .sidebar_left.minimized .submenu-popup {
            display: none;
            position: fixed;
            left: 80px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 8px;
            min-width: 200px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            z-index: 10001;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }

        .sidebar_left.minimized .uf-submenu:hover .submenu-popup {
            display: block !important;
            opacity: 1;
            pointer-events: auto;
        }

        .sidebar_left.minimized .submenu-popup:hover {
            display: block !important;
            opacity: 1;
            pointer-events: auto;
        }

        .submenu-popup li {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .submenu-popup li a {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7280;
            padding: 9px 12px;
            margin: 2px 0;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .submenu-popup li a i {
            font-size: 12px;
            width: 14px;
            opacity: 0.7;
        }

        .submenu-popup li a:hover {
            color: #111827;
            background: #f9fafb;
        }

        .submenu-popup li a:hover i {
            opacity: 1;
        }

        .submenu-popup li.active a {
            color: #15803d;
            font-weight: 600;
            background: #f0fdf4;
        }

        .submenu-popup li.active a i {
            opacity: 1;
        }

        .sidebar_left::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar_left::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        /* ===============================
        MOBILE OVERLAY
        ================================ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* ===============================
        LOGO
        ================================ */
        .sidebar-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 20px;
            font-weight: 700;
            color: #22c55e;
            margin-bottom: 28px;
            letter-spacing: -0.3px;
            padding: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .sidebar-logo:hover {
            color: #16a34a;
        }

        .sidebar-logo-icon {
            font-size: 24px;
        }

        .sidebar-logo-text {
            transition: opacity 0.3s, width 0.3s;
        }

        /* ===============================
        MENU BASE
        ================================ */
        .rts-side-nav-area-left {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        /* ===============================
        MENU ITEM
        ================================ */
        .single-menu-item {
            margin-bottom: 2px;
            position: relative;
        }

        .single-menu-item > a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
        }

        /* Icons */
        .single-menu-item img.icon,
        .single-menu-item i.icon {
            width: 20px;
            height: 20px;
            font-size: 18px;
            opacity: 0.7;
            transition: opacity 0.2s;
            flex-shrink: 0;
        }

        .single-menu-item i.icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Hover */
        .single-menu-item > a:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .single-menu-item > a:hover img.icon,
        .single-menu-item > a:hover i.icon {
            opacity: 1;
        }

        /* Active parent */
        .single-menu-item.active > a,
        .single-menu-item.uf-open > a {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            color: #15803d;
            font-weight: 600;
        }

        .single-menu-item.active > a img.icon,
        .single-menu-item.active > a i.icon,
        .single-menu-item.uf-open > a img.icon,
        .single-menu-item.uf-open > a i.icon {
            opacity: 1;
        }

        /* ===============================
        SUBMENU ARROW
        ================================ */
        .uf-arrow {
            margin-left: auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.25s ease, opacity 0.3s;
            transform: rotate(0deg);
            opacity: 0.6;
            flex-shrink: 0;
        }

        .uf-submenu.uf-open > a .uf-arrow {
            transform: rotate(180deg);
            opacity: 1;
            color: #15803d;
        }

        /* Disable theme arrows */
        .with-plus::before,
        .with-plus::after,
        .submenu-trigger::before,
        .submenu-trigger::after {
            content: none !important;
            display: none !important;
        }

        /* ===============================
        SUBMENU - FIXED SPACING
        ================================ */
        .uf-submenu {
            margin-bottom: 2px;
        }

        .uf-submenu .submenu {
            list-style: none;
            border-left: 2px solid #e5e7eb;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.25s ease,
                        padding 0.25s ease;
        }

        .uf-submenu.uf-open .submenu {
            max-height: 500px;
            opacity: 1;
            padding: 8px 0 6px 44px;
        }

        .submenu li {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .submenu li a {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7280;
            padding: 9px 12px;
            margin: 2px 0;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .submenu li a i {
            font-size: 12px;
            width: 14px;
            opacity: 0.7;
        }

        .submenu li a:hover {
            color: #111827;
            background: #f9fafb;
        }

        .submenu li a:hover i {
            opacity: 1;
        }

        .submenu li.active a {
            color: #15803d;
            font-weight: 600;
            background: #f0fdf4;
        }

        .submenu li.active a i {
            opacity: 1;
        }

        /* ===============================
        RIGHT CONTENT AREA
        ================================ */
        .right-area-body-content {
            flex: 1;
            margin-left: 260px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar_left.minimized ~ .right-area-body-content {
            margin-left: 80px;
        }

        /* ===============================
        HEADER
        ================================ */
        .header-one {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .headerleft {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Desktop Sidebar Toggle Button in Header */
        .desktop-sidebar-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: none;
            background: #f3f4f6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            color: #6b7280;
        }

        .desktop-sidebar-toggle:hover {
            background: #e5e7eb;
            color: #111827;
        }

        .desktop-sidebar-toggle i {
            font-size: 16px;
            transition: transform 0.3s;
        }

        .sidebar_left.minimized ~ .right-area-body-content .desktop-sidebar-toggle i {
            transform: rotate(180deg);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            width: 40px;
            height: 40px;
            border: none;
            background: #f3f4f6;
            border-radius: 8px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .mobile-menu-toggle:hover {
            background: #e5e7eb;
        }

        .mobile-menu-toggle span {
            display: block;
            width: 20px;
            height: 2px;
            background: #374151;
            position: relative;
            transition: all 0.3s;
        }

        .mobile-menu-toggle span::before,
        .mobile-menu-toggle span::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 2px;
            background: #374151;
            left: 0;
            transition: all 0.3s;
        }

        .mobile-menu-toggle span::before {
            top: -6px;
        }

        .mobile-menu-toggle span::after {
            top: 6px;
        }

        .mobile-menu-toggle.active span {
            background: transparent;
        }

        .mobile-menu-toggle.active span::before {
            top: 0;
            transform: rotate(45deg);
        }

        .mobile-menu-toggle.active span::after {
            top: 0;
            transform: rotate(-45deg);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user_avatar__information {
            position: relative;
            cursor: pointer;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #e5e7eb;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user_information_main_wrapper {
            position: absolute;
            right: 0;
            top: calc(100% + 1px);
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            min-width: 200px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .user_avatar__information:hover .user_information_main_wrapper {
            display: block;
        }

        .user_header .title {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            margin: 0 0 4px 0;
        }

        .user_header .desig {
            font-size: 12px;
            color: #6b7280;
        }

        .popup-footer-btn {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
        }

        .popup-footer-btn button {
            width: 100%;
            padding: 8px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .popup-footer-btn button:hover {
            background: #dc2626;
        }

        /* ===============================
        BODY CONTENT
        ================================ */
        .body-root-inner {
            padding: 24px;
        }

        /* ===============================
        PROGRESS WRAP
        ================================ */
        .progress-wrap {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 46px;
            height: 46px;
            cursor: pointer;
            display: none;
            background: #22c55e;
            border-radius: 50%;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .progress-wrap.active-progress {
            opacity: 1;
            visibility: visible;
            display: block;
        }

        .progress-wrap svg {
            width: 100%;
            height: 100%;
        }

        .progress-wrap svg path {
            fill: none;
            stroke: #fff;
            stroke-width: 4;
        }

        /* ===============================
        MOBILE RESPONSIVE (Tablet & Below)
        ================================ */
        @media (max-width: 1024px) {
            .sidebar_left {
                transform: translateX(-100%);
                width: 260px !important;
            }

            .sidebar_left.mobile-active {
                transform: translateX(0);
            }

            .right-area-body-content {
                margin-left: 0 !important;
            }

            .mobile-menu-toggle {
                display: flex;
            }

            .desktop-sidebar-toggle {
                display: none;
            }

            .body-root-inner {
                padding: 16px;
            }

            .header-one {
                padding: 12px 16px;
            }

            .sidebar_left.minimized {
                width: 260px !important;
            }

            .sidebar_left.minimized .sidebar-logo-text,
            .sidebar_left.minimized .single-menu-item > a span {
                display: block !important;
                opacity: 1 !important;
                width: auto !important;
            }

            .sidebar_left.minimized .uf-arrow {
                display: flex !important;
            }

            .submenu-popup {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .sidebar_left {
                width: 280px !important;
            }

            .sidebar-logo {
                font-size: 18px;
                margin-bottom: 24px;
            }

            .body-root-inner {
                padding: 12px;
            }

            .user_information_main_wrapper {
                right: -50px;
            }
        }

        @media (max-width: 480px) {
            .sidebar_left {
                width: 100% !important;
                max-width: 320px;
            }

            .header-one {
                padding: 10px 12px;
            }

            .user_header .title {
                font-size: 13px;
            }

            .progress-wrap {
                bottom: 15px;
                right: 15px;
                width: 40px;
                height: 40px;
            }
        }

        @media (max-height: 500px) and (orientation: landscape) {
            .sidebar_left {
                padding: 16px 12px;
            }

            .sidebar-logo {
                margin-bottom: 20px;
                font-size: 16px;
            }

            .single-menu-item > a {
                padding: 8px 12px;
            }
        }

        /* Avatar Icon Styling */
        .avatar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #e5e7eb;
            transition: all 0.2s;
        }

        .avatar:hover {
            border-color: #22c55e;
            background: #f0fdf4;
        }

        /* Icon inside avatar */
        .avatar i {
            font-size: 24px;
            color: #6b7280;
        }

        .avatar:hover i {
            color: #22c55e;
        }

        /* Alternative: Avatar with initials */
        .avatar-initials {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
        }

    </style>

<div class="main_dashboard">

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ================= SIDEBAR ================= -->
    <aside class="sidebar_left" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
            {{-- <span class="sidebar-logo-icon">🍽️</span> --}}
            <span class="sidebar-logo-text">Unique Foods</span>
        </a>

        <ul class="rts-side-nav-area-left">

            <!-- Dashboard -->
            <li class="single-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" data-title="Dashboard">
                    <i class="fas fa-th-large icon"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Products -->
            <li class="single-menu-item uf-submenu {{ request()->routeIs('admin.products.*') ? 'uf-open' : '' }}">
                <a href="#" class="uf-submenu-trigger" data-title="Products">
                    <i class="fas fa-box-open icon"></i>
                    <span>Products</span>

                    <span class="uf-arrow">
                        <svg width="12" height="12" viewBox="0 0 24 24">
                            <path d="M7 10l5 5 5-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"/>
                        </svg>
                    </span>
                </a>

                <!-- Regular submenu (shown when expanded) -->
                <ul class="submenu">
                    <li class="{{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.products.create') }}">
                            <i class="fas fa-plus"></i> Create Product
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.products.index') }}">
                            <i class="fas fa-list"></i> Product List
                        </a>
                    </li>
                </ul>

                <!-- Popup submenu (shown when minimized on hover) -->
                <ul class="submenu-popup">
                    <li class="{{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.products.create') }}">
                            <i class="fas fa-plus"></i> Create Product
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.products.index') }}">
                            <i class="fas fa-list"></i> Product List
                        </a>
                    </li>
                </ul>
            </li>


            <!-- Categories -->
            <li class="single-menu-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                <a href="{{ route('admin.categories.index') }}" data-title="Categories">
                    <i class="fas fa-layer-group icon"></i>
                    <span>Categories</span>
                </a>
            </li>

            <!-- Brands -->
            <li class="single-menu-item {{ request()->routeIs('admin.brands.index') ? 'active' : '' }}">
                <a href="{{ route('admin.brands.index') }}" data-title="Brands">
                    <i class="fas fa-tags icon"></i>
                    <span>Brands</span>
                </a>
            </li>

            <!-- Orders -->
            <li class="single-menu-item">
                <a href="#" data-title="Orders">
                    <i class="fas fa-shopping-cart icon"></i>
                    <span>Orders</span>
                </a>
            </li>

            <!-- Transactions -->
            <li class="single-menu-item">
                <a href="#" data-title="Transactions">
                    <i class="fas fa-credit-card icon"></i>
                    <span>Transactions</span>
                </a>
            </li>

        </ul>
    </aside>
    <!-- ================= SIDEBAR END ================= -->

    <div class="right-area-body-content">

        <!-- ================= HEADER ================= -->
        <header class="header-one">
            <div class="headerleft">
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <span></span>
                </button>

                <!-- Desktop Sidebar Toggle -->
                <button class="desktop-sidebar-toggle" id="desktopSidebarToggle" title="Toggle Sidebar">
                    <i class="fas fa-angles-left"></i>
                </button>
            </div>

            <div class="header-right">
                <div class="single_action__haeader user_avatar__information">
                    <div class="avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>

                    <div class="user_information_main_wrapper slide-down__click">
                        <div class="user_header">
                            <h3 class="title">{{ auth()->user()->name ?? 'Admin' }}</h3>
                            <span class="desig">Administrator</span>
                        </div>

                        <div class="popup-footer-btn">
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="geex-content__header__popup__footer__link">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- ================= HEADER END ================= -->

        <!-- ================= PAGE CONTENT ================= -->
        <div class="body-root-inner">
            @yield('content')
        </div>
        <!-- ================= PAGE CONTENT END ================= -->

    </div>

</div>

<!-- JS -->
<script src="{{ asset('admin/assets/js/plugins.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('admin/assets/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Sidebar & Mobile Menu Script -->
<script>
(function () {
    const sidebar = document.getElementById('sidebar');
    const desktopSidebarToggle = document.getElementById('desktopSidebarToggle');

    // Submenu Toggle (only works when NOT minimized)
    document.querySelectorAll('.uf-submenu-trigger').forEach(trigger => {
        trigger.addEventListener('click', function (e) {
            e.preventDefault();

            // If sidebar is minimized, don't toggle submenu
            if (sidebar.classList.contains('minimized')) {
                return;
            }

            const parent = this.closest('.uf-submenu');

            // Close others
            document.querySelectorAll('.uf-submenu.uf-open').forEach(item => {
                if (item !== parent) {
                    item.classList.remove('uf-open');
                }
            });

            parent.classList.toggle('uf-open');
        });
    });

    // Position submenu popup dynamically in minimized state - FIXED
    document.querySelectorAll('.uf-submenu').forEach(submenu => {
        const popup = submenu.querySelector('.submenu-popup');

        if (popup) {
            // Show popup on parent hover
            submenu.addEventListener('mouseenter', function() {
                if (sidebar.classList.contains('minimized')) {
                    const rect = this.getBoundingClientRect();
                    popup.style.top = rect.top + 'px';
                    popup.style.display = 'block';
                    popup.style.opacity = '1';
                    popup.style.pointerEvents = 'auto';
                }
            });

            // Keep popup visible when hovering over it
            popup.addEventListener('mouseenter', function() {
                if (sidebar.classList.contains('minimized')) {
                    this.style.display = 'block';
                    this.style.opacity = '1';
                    this.style.pointerEvents = 'auto';
                }
            });

            // Hide popup when mouse leaves both parent and popup
            let hideTimeout;

            const hidePopup = function() {
                hideTimeout = setTimeout(() => {
                    if (sidebar.classList.contains('minimized')) {
                        popup.style.opacity = '0';
                        popup.style.pointerEvents = 'none';
                        setTimeout(() => {
                            if (popup.style.opacity === '0') {
                                popup.style.display = 'none';
                            }
                        }, 200);
                    }
                }, 100);
            };

            const cancelHide = function() {
                clearTimeout(hideTimeout);
            };

            submenu.addEventListener('mouseleave', hidePopup);
            popup.addEventListener('mouseenter', cancelHide);
            popup.addEventListener('mouseleave', hidePopup);
        }
    });


    // Desktop Sidebar Toggle (in Header)
    if (desktopSidebarToggle) {
        // Check localStorage for saved state
        const sidebarState = localStorage.getItem('sidebarMinimized');
        if (sidebarState === 'true') {
            sidebar.classList.add('minimized');
        }

        desktopSidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('minimized');

            // Close all open submenus when minimizing
            if (sidebar.classList.contains('minimized')) {
                document.querySelectorAll('.uf-submenu.uf-open').forEach(item => {
                    item.classList.remove('uf-open');
                });
            }

            // Save state to localStorage
            const isMinimized = sidebar.classList.contains('minimized');
            localStorage.setItem('sidebarMinimized', isMinimized);
        });
    }

    // Mobile Menu Toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function toggleMobileMenu() {
        sidebar.classList.toggle('mobile-active');
        sidebarOverlay.classList.toggle('active');
        mobileMenuToggle.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('mobile-active') ? 'hidden' : '';
    }

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', toggleMobileMenu);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', toggleMobileMenu);
    }

    // Close mobile menu on link click
    document.querySelectorAll('.sidebar_left a').forEach(link => {
        link.addEventListener('click', function(e) {
            if (window.innerWidth <= 1024 && !this.classList.contains('uf-submenu-trigger')) {
                setTimeout(() => {
                    if (sidebar.classList.contains('mobile-active')) {
                        toggleMobileMenu();
                    }
                }, 300);
            }
        });
    });

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('mobile-active');
                sidebarOverlay.classList.remove('active');
                if (mobileMenuToggle) {
                    mobileMenuToggle.classList.remove('active');
                }
                document.body.style.overflow = '';
            }
        }, 250);
    });

})();
</script>

@stack('scripts')
</body>
</html>
