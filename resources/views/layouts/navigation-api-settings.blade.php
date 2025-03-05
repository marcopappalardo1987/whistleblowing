<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h3 class="card-title h5 mb-3">{{ __('API Settings') }}</h3>
        <div class="d-flex flex-wrap gap-2">
            @can('publish ownerdata')
                <a href="{{ route('api.settings.dataforseo') }}" 
                   class="btn {{ request()->routeIs('api.settings.dataforseo') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-gear-fill me-2"></i>
                    {{ __('DataForSEO') }}
                </a>
            @endcan
        </div>    
    </div>
</div>