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
                    <i class="bi bi-person me-2"></i>{{__('Profilo')}}
                </a>
            </li>
            @role('azienda')
            <li>
                <a class="dropdown-item" href="{{ route('profile.subscription') }}">
                    <i class="bi bi-credit-card me-2"></i>{{__('Abbonamento')}}
                </a>
            </li>
            @endrole
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item text-danger" type="submit">
                        <i class="bi bi-box-arrow-right me-2"></i>{{__('Logout')}}
                    </button>
                </form>
            </li>
        </ul>
    </li>

    <li class="nav-item dropdown me-3 d-flex align-items-center">
        <a class="nav-link d-flex align-items-center" href="#"  role="button" data-bs-toggle="dropdown">
            <img src="{{ asset('images/flags/'.app()->getLocale().'.svg') }}" alt="{{ strtoupper(app()->getLocale()) }}" class="flag-icon" style="width: 20px;">
        </a>
        <ul class="dropdown-menu dropdown-menu-end p-0" style="min-width: 20px;">
            @foreach(['it', 'en', 'es', 'fr'] as $locale)
                @if($locale !== app()->getLocale())

                    @php
                        $url = url()->full();
                        $url = preg_replace('/([&?])locale=[^&]*/', '$1locale=' . $locale, $url);
                        if (!str_contains($url, 'locale=')) {
                            $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'locale=' . $locale;
                        }
                    @endphp

                    <li>
                        <a class="dropdown-item p-0 text-center" href="{{ $url }}">
                            <img src="{{ asset('images/flags/'.$locale.'.svg') }}" alt="{{ strtoupper($locale) }}" class="flag-icon p-2" style="width: 40px;">
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </li>

</ul>