<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Metadata dinamici --}}
    <title>{{ $title ?? config('app.name') }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Default description' }}">
    
    {{-- Open Graph dinamici --}}
    <meta property="og:title" content="{{ $ogTitle ?? config('app.name') }}">
    <meta property="og:description" content="{{ $ogDescription ?? 'Default OG description' }}">
    <meta property="og:url" content="{{ $ogUrl ?? url()->current() }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/default-og.jpg') }}">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    {{-- Canonical URL dinamico --}}
    <link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">
    
    {{-- Custom metadata aggiuntivi --}}
    @yield('additional_metadata')
    
    {{-- CSS comuni --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/frontend.css') }}"> --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    {{-- Contenuto principale --}}
    <main>
        <div class="container">
            <div class="col-12 mt-5">
                @include('layouts.alert-message') 
            </div>
        </div>
        {{ $slot }}
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