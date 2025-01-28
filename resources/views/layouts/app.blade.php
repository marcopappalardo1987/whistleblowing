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

        <!-- DevExtreme theme -->
        <link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/23.2.5/css/dx.light.css">

        <!-- Styles -->
        @vite(['resources/sass/app.scss'])
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
                        <button class="btn btn-link d-block d-md-none" id="sidebarToggle" style="max-width: 50px">
                            <i class="bi bi-list"></i>
                        </button>

                        <!-- Right Side -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Notifications -->
                            {{-- <li class="nav-item dropdown me-3">
                                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-bell"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">Notifica 1</a></li>
                                    <li><a class="dropdown-item" href="#">Notifica 2</a></li>
                                </ul>
                            </li> --}}

                            <!-- Profile -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" 
                                         alt="Profile" 
                                         class="rounded-circle me-2" 
                                         width="32">
                                    <span>{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="bi bi-person me-2"></i>Profilo
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.subscription') }}">
                                            <i class="bi bi-credit-card me-2"></i>Abbonamento
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button class="dropdown-item text-danger" type="submit">
                                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- Main Content -->
                <main class="p-4">
                    @isset($header)
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
