<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- DevExtreme theme -->
        <link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/23.2.5/css/dx.light.css">

        <!-- Styles -->
        @vite(['resources/sass/app.scss', 'resources/frontend/js/app.js'])
        
    </head>
    <body>
        <div class="wrapper">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Page Content -->
            <div class="content">
                <!-- Top Navigation -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        @if(isset($subscription))
                            @if($subscription)
                                <span class="nav-link text-muted abbonamento-attivo-top-nav">
                                    {{__('Abbonamento attivo')}}: {{ $subscription->product()->first()->name }}
                                    @if($subscription->stripe_status === 'trialing')
                                        <span class="text-muted">({!! __('in prova') !!})</span>
                                    @endif
                                </span>
                                @else
                                    <span class="nav-link text-muted abbonamento-attivo-top-nav">
                                        Abbonamento attivo: Nessun abbonamento attivo
                                    </span>
                            @endif
                        @endif
                        <button class="btn btn-link d-block d-md-none" id="sidebarToggle" style="max-width: 50px">
                            <i class="bi bi-list"></i>
                        </button>

                        <!-- Right Side -->
                        @include('layouts.navigation.user-profile')
                    </div>
                </nav>

                <!-- Main Content -->
                <main class="p-4">
                    @isset($header)
                        @include('layouts.alert-message-owner')
                        <h1 class="h3 mb-4">{{ $header }}</h1>
                    @endisset
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn3.devexpress.com/jslib/23.2.5/js/dx.all.js"></script>
        @vite(['resources/js/app.js'])
        
        <script>
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                document.querySelector('.wrapper').classList.toggle('toggled');
            });
        </script>

        @stack('scripts')
    </body>
</html>
