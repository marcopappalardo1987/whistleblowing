<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- {!! SEOMeta::generate() !!} --}}

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manrope:wght@200..800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/frontend/scss/app.scss'])
    </head>

    <body class="font-sans antialiased body">
        <div class="min-h-screen bg-gray-100">
            {{-- @include('frontend._partials.header') --}}

            <main>
                @yield('content')
            </main>
        </div>
        
        @vite(['resources/frontend/js/app.js'])
        @stack('scripts')

    </body>

    {{-- @include('frontend._partials.footer') --}}
</html>

