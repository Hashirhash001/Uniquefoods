<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Unique Foods">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Home')</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/assets/images/fav.png') }}">

    {{-- template css --}}
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">

    @stack('styles')
</head>
<body>

{{-- HEADER --}}
@include('frontend.partials.header')

{{-- PAGE CONTENT --}}
@yield('content')

{{-- FOOTER --}}
@include('frontend.partials.footer')

{{-- template js (plugins.js should have jQuery) --}}
<script src="{{ asset('frontend/assets/js/plugins.js') }}"></script>
<script src="{{ asset('frontend/assets/js/main.js') }}"></script>

{{-- Fallback: Load jQuery from CDN if not already loaded --}}
<script>
    if (typeof jQuery === 'undefined') {
        document.write('<script src="https://code.jquery.com/jquery-3.6.0.min.js"><\/script>');
    }
</script>

@stack('scripts')
</body>
</html>
