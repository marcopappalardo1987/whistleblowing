<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h3 class="card-title h5 mb-3">{{ __('Logs') }}</h3>
        <div class="d-flex flex-wrap gap-2">
            @can('view ownerdata')
                <a href="{{ route('logs.laravel') }}" 
                   class="btn {{ request()->routeIs('logs.laravel') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-file-text me-2"></i>
                    {{ __('Laravel') }}
                </a>
            @endcan
            
            @can('view ownerdata')
                <a href="{{ route('logs.worker') }}" 
                   class="btn {{ request()->routeIs('logs.worker') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-gear-wide-connected me-2"></i>
                    {{ __('Worker') }}
                </a>
            @endcan
            
        </div>    
    </div>
</div>