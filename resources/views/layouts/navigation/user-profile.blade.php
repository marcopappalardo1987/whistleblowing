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
            @role('azienda')
            <li>
                <a class="dropdown-item" href="{{ route('profile.subscription') }}">
                    <i class="bi bi-credit-card me-2"></i>Abbonamento
                </a>
            </li>
            @endrole
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