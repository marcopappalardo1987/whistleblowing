<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" height="40">
        </a>

        {{-- Hamburger Button --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menu Items --}}
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('plans') ? 'active' : '' }}" href="{{ route(app()->getLocale() . '.plans', ['locale' => app()->getLocale()]) }}">
                        Piani
                    </a>
                </li>
                @auth
                    @if(auth()->user()->can('view affiliate data') || auth()->user()->can('view ownerdata') || auth()->user()->hasRole('azienda'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link" style="text-decoration: none;">
                                    Logout
                                </button>
                            </form>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="btn btn-primary ms-lg-3" href="{{ route(app()->getLocale().'.login', ['locale' => app()->getLocale()]) }}">Accedi</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

