<header>
    <div class="header">
        <div class="container d-flex justify-content-between align-items-center py-3">
            <a class="navbar-brand" href="https://www.whistleblowingtool.com/{{ app()->getLocale() }}">
                <img class="logo" src="https://whistleblowingtool.com/images/whistleblowing-tool-logo-white.webp" alt="{{ __('Whistleblowing Tool Logo') }}">
            </a>
            <nav id="main-nav-desktop" class="navbar navbar-expand-lg d-none d-lg-block">
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav">
                        <li class="nav-item p-0 mx-2">
                            <a class="nav-link px-0 py-1" href="{{ route(app()->getLocale().'.frontend.features', ['locale' => app()->getLocale()]) }}">{{ __('Funzionalità') }}</a>
                        </li>
                        <li class="nav-item p-0 mx-2">
                            <a class="nav-link px-0 py-1" href="{{ route(app()->getLocale().'.frontend.blog', ['locale' => app()->getLocale()]) }}">{{ __('Da sapere') }}</a>
                        </li>
                        {{-- <li class="nav-item p-0 mx-2">
                            <a class="nav-link px-0 py-1" href="{{ route(app()->getLocale().'.frontend.demo', ['locale' => app()->getLocale()]) }}">{{ __('Richiedi una Demo') }}</a>
                        </li> --}}
                        <li class="nav-item p-0 mx-2">
                            <a class="nav-link px-0 py-1" href="{{ route(app()->getLocale().'.frontend.assistance', ['locale' => app()->getLocale()]) }}">{{ __('Assistenza') }}</a>
                        </li>
                        <li class="nav-item p-0 mx-2">
                            <a class="nav-link px-0 py-1" href="{{ route(app()->getLocale().'.frontend.affiliate', ['locale' => app()->getLocale()]) }}">{{ __('Affiliazione') }}</a>
                        </li>
                        <li class="nav-item p-0 mx-2">
                            <a class="nav-link px-0 py-1" href="{{ route(app()->getLocale().'.plans', ['locale' => app()->getLocale()]) }}">{{ __('Prezzi') }}</a>
                        </li>
                        @guest
                        <li class="nav-item p-0 mx-2">
                            <a class="nav-link px-0 py-1" href="{{ route(app()->getLocale().'.login', ['locale' => app()->getLocale()]) }}">{{ __('Login') }}</a>
                        </li>
                        @endguest
                        @auth
                            <li class="nav-item p-0 mx-2">
                                <a class="nav-link px-0 py-1" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endauth 
                        <li class="nav-item dropdown p-0 mx-2">
                            <a class="nav-link dropdown-toggle px-0 py-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('images/flags/'.app()->getLocale().'.svg') }}" alt="{{ strtoupper(app()->getLocale()) }}" class="flag-icon" style="width: 20px;">
                            </a>
                            <ul class="dropdown-menu language-dropdown dropdown-menu-end">
                                @foreach(['it', 'en'] as $locale)
                                    @if($locale !== app()->getLocale())
                                        <li>
                                            <a class="dropdown-item" href="{{ route(Route::currentRouteName(), array_merge(['locale' => $locale], request()->query())) }}" style="
                                                display: unset;
                                                background-color: #fff;
                                                border-radius: 7px;
                                                padding: 5px 15px 7px;
                                            ">
                                                <img src="{{ asset('images/flags/'.$locale.'.svg') }}" alt="{{ strtoupper($locale) }}" class="flag-icon">
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="d-lg-none">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu" aria-expanded="false" aria-label="Toggle navigation"style="width: 26px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 24" fill="currentColor"><path d="m2.61 0h22.431c1.441 0 2.61 1.168 2.61 2.61s-1.168 2.61-2.61 2.61h-22.431c-1.441 0-2.61-1.168-2.61-2.61s1.168-2.61 2.61-2.61z"></path><path d="m2.61 9.39h22.431c1.441 0 2.61 1.168 2.61 2.61s-1.168 2.61-2.61 2.61h-22.431c-1.441 0-2.61-1.168-2.61-2.61s1.168-2.61 2.61-2.61z"></path><path d="m2.61 18.781h22.431c1.441 0 2.61 1.168 2.61 2.61s-1.168 2.61-2.61 2.61h-22.431c-1.441 0-2.61-1.168-2.61-2.61s1.168-2.61 2.61-2.61z"></path></svg>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Off-canvas menu -->
<div class="offcanvas offcanvas-end text-center vh-100" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel" style="overflow-y: auto;">
    <div class="offcanvas-header">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column justify-content-center">
        <a class="navbar-brand mb-2" href="https://www.whistleblowingtool.com/{{ app()->getLocale() }}">
            <img class="logo" src="https://whistleblowingtool.com/images/whistleblowingtool-logo.webp" alt="{{ __('Whistleblowing Tool Logo') }}">
        </a>
        <ul class="navbar-nav">
            <li class="nav-item px-2 pb-1">
                <a class="nav-link p-0" href="{{ route(app()->getLocale().'.frontend.features', ['locale' => app()->getLocale()]) }}">{{ __('Funzionalità') }}</a>
            </li>
            <li class="nav-item px-2 pb-1">
                <a class="nav-link p-0" href="{{ route(app()->getLocale().'.frontend.blog', ['locale' => app()->getLocale()]) }}">{{ __('Da sapere') }}</a>
            </li>
            {{-- <li class="nav-item px-2 pb-1">
                <a class="nav-link p-0" href="{{ route(app()->getLocale().'.frontend.demo', ['locale' => app()->getLocale()]) }}">{{ __('Richiedi una Demo') }}</a>
            </li> --}}
            <li class="nav-item px-2 pb-1">
                <a class="nav-link p-0" href="{{ route(app()->getLocale().'.frontend.assistance', ['locale' => app()->getLocale()]) }}">{{ __('Assistenza') }}</a>
            </li>
            <li class="nav-item px-2 pb-1">
                <a class="nav-link p-0" href="{{ route(app()->getLocale().'.frontend.affiliate', ['locale' => app()->getLocale()]) }}">{{ __('Affiliazione') }}</a>
            </li>
            <li class="nav-item px-2 pb-1">
                <a class="nav-link px-0 py-1" href="{{ route(app()->getLocale().'.plans', ['locale' => app()->getLocale()]) }}">{{ __('Prezzi') }}</a>
            </li>
            @guest
            <li class="nav-item px-2 pb-1">
                <a class="nav-link px-0 py-1" href="{{ route(app()->getLocale().'.login', ['locale' => app()->getLocale()]) }}">{{ __('Login') }}</a>
            </li>
            @endguest
            <li class="nav-item px-2 pb-1">
            <li class="nav-item dropdown px-2 pb-1">
                <a class="nav-link dropdown-toggle p-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('images/flags/'.app()->getLocale().'.svg') }}" alt="{{ strtoupper(app()->getLocale()) }}" class="flag-icon" style="width: 20px;">
                </a>
                <ul class="dropdown-menu language-dropdown">
                    @foreach(['it', 'en'] as $locale)
                        @if($locale !== app()->getLocale())
                            <li>
                                <a class="dropdown-item" href="{{ route(Route::currentRouteName(), ['locale' => $locale]) }}">
                                    <img src="{{ asset('images/flags/'.$locale.'.svg') }}" alt="{{ strtoupper($locale) }}" class="flag-icon">
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        </ul>
    </div>
</div>
