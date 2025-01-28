<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h3 class="card-title h5 mb-3">{{ __('Abbonamenti') }}</h3>
        <div class="d-flex flex-wrap gap-2">
            @can('view ownerdata')
                <a href="{{ route('subscriptions.all') }}" 
                   class="btn {{ request()->routeIs('subscriptions.all') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-eye me-2"></i>
                    {{ __('Visualizza Abbonamenti') }}
                </a>
            @endcan
        </div>    
    </div>
</div>