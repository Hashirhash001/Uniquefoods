<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Unique Foods">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Home')</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('admin/assets/images/logo/favicon.png') }}">

    {{-- template css --}}
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/global-loader.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">

    {{-- Header Auto-Hide CSS --}}
    <style>
        /* ================================================
           HEADER AUTO-HIDE - PROFESSIONAL FIX
           ================================================ */

        /* Ensure header is fixed and has proper z-index */
        .unique-modern-header,
        .unique-mobile-header {
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        box-shadow 0.3s ease;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Hidden state - slides up */
        .unique-modern-header.header-hidden,
        .unique-mobile-header.header-hidden {
            transform: translateY(-100%);
            box-shadow: none;
        }

        /* Visible state - normal position */
        .unique-modern-header.header-visible,
        .unique-mobile-header.header-visible {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Compact mode when scrolled */
        .unique-modern-header.header-compact,
        .unique-mobile-header.header-compact {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        /* Prevent content jump - body padding added via JS */
        body {
            padding-top: 0;
            transition: padding-top 0.3s ease;
        }

        /* Smooth transitions for all header children */
        .unique-modern-header *,
        .unique-mobile-header * {
            transition: inherit;
        }

        /* Optional: Reduce header height in compact mode */
        .unique-modern-header.header-compact .header-inner {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        /* Ensure content starts below header */
        main,
        .page-content,
        [role="main"] {
            position: relative;
        }

        /* Fix for sticky elements conflicting with header */
        .sticky-element {
            top: calc(var(--header-height, 80px) + 20px) !important;
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- INSTANT LOADER - Shows immediately before ANY content --}}
<div id="uniqueGlobalLoader" class="unique-global-loader unique-active">
    <div class="unique-loader-background"></div>
    <div class="unique-loader-container">
        <div class="unique-loader-brand">
            <img src="{{ asset('admin/assets/images/logo/favicon.png') }}" alt="" max-width="150px">
        </div>

        <div class="unique-loader-spinner-wrapper">
            <div class="unique-spinner-outer"></div>
            <div class="unique-spinner-middle"></div>
            <div class="unique-spinner-inner"></div>
        </div>

        <div class="unique-loader-dots">
            <div class="unique-dot"></div>
            <div class="unique-dot"></div>
            <div class="unique-dot"></div>
        </div>

        <div class="unique-loader-text">Loading...</div>
        <div class="unique-loader-subtitle">Please wait...</div>

        <div class="unique-loader-progress">
            <div class="unique-progress-bar"></div>
        </div>
    </div>
</div>

{{-- HEADER --}}
@include('frontend.partials.header')

{{-- PAGE CONTENT --}}
<main role="main" class="main-content">
    @yield('content')
</main>

{{-- FOOTER --}}
@include('frontend.partials.footer')

{{-- template js (plugins.js should have jQuery) --}}
<script src="{{ asset('frontend/assets/js/plugins.js') }}"></script>
<script src="{{ asset('frontend/assets/js/main.js') }}"></script>
<script src="{{ asset('frontend/assets/js/global-loader.js') }}"></script>

{{-- Fallback: Load jQuery from CDN if not already loaded --}}
<script>
    if (typeof jQuery === 'undefined') {
        document.write('<script src="https://code.jquery.com/jquery-3.6.0.min.js"><\/script>');
    }
</script>

{{-- Auto-Hide Header (MUST load after header is rendered) --}}
<script src="{{ asset('frontend/assets/js/header-autohide.js') }}"></script>

{{-- ✅ INSTANT HIDE on page load --}}
<script>
    // Hide loader as soon as DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const loader = document.getElementById('uniqueGlobalLoader');
            if (loader) {
                loader.classList.remove('unique-active');
            }
        }, 500); // Small delay for smooth UX
    });
</script>

@stack('scripts')
</body>
</html>
