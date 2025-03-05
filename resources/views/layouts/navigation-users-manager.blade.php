<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h3 class="card-title h5 mb-3">{{ __('Gestione Utenti') }}</h3>
        <div class="d-flex flex-wrap gap-2">
            @can('publish users')
                <a href="{{ route('users.add') }}" 
                   class="btn {{ request()->routeIs('users.add') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-person-plus me-2"></i>
                    {{ __('Aggiungi Utente') }}
                </a>
            @endcan

            @can('view users')
                <a href="{{ route('users') }}" 
                   class="btn {{ request()->routeIs('users') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-people me-2"></i>
                    {{ __('Gestione Utenti') }}
                </a>
            @endcan
        </div>    
    </div>
</div>