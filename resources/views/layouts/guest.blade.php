<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Styles -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="bg-light">
        <div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center py-4 py-sm-5">
            <div class="mb-4">
                <a href="/" class="text-decoration-none">
                    <img src="https://marcopappalardo.it/wp-content/uploads/2023/10/Marco-Pappalardo-Logo.svg" 
                         alt="{{ config('app.name') }}" 
                         height="60">
                </a>
            </div>

            <div class="card shadow-sm border-0 p-4 p-sm-5" style="max-width: 450px; width: 100%;">
                {{ $slot }}
            </div>
            
            @if(Route::has('login'))
                <div class="mt-4 text-center text-muted">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-decoration-none">Accedi</a>
                        @if(Route::has('register'))
                            <span class="mx-2">·</span>
                            <a href="{{ route('register') }}" class="text-decoration-none">Registrati</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <!-- Scripts -->
        <script>
            // Aggiungi qui eventuali script personalizzati
        </script>
    </body>
</html>
