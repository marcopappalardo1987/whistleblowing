<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="https://marcopappalardo.it/wp-content/uploads/2023/10/Marco-Pappalardo-Logo.svg" alt="{{ config('app.name') }}" height="40">
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
                    <a class="nav-link {{ request()->is('plans') ? 'active' : '' }}" href="{{ url('/plans') }}">
                        Piani
                    </a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn btn-primary ms-lg-3" href="{{ route('login') }}">Accedi</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

