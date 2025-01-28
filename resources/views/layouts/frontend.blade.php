<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Metadata dinamici --}}
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Default description')">
    
    {{-- Open Graph dinamici --}}
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', 'Default OG description')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:image" content="@yield('og_image', asset('images/default-og.jpg'))">
    
    {{-- Canonical URL dinamico --}}
    <link rel="canonical" href="@yield('canonical_url', url()->current())">
    
    {{-- Custom metadata aggiuntivi --}}
    @yield('additional_metadata')
    
    {{-- CSS comuni --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/frontend.css') }}"> --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    {{-- Header comune --}}
    <header class="site-header">
        @include('frontend.partials.header')
    </header>

    {{-- Contenuto principale --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer comune --}}
    <footer class="site-footer">
        {{-- @include('frontend.partials.footer') --}}
    </footer>

    {{-- JavaScript comuni --}}
    {{-- <script src="{{ asset('js/frontend.js') }}"></script> --}}
    @stack('scripts')
</body>
</html> 