<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h3 class="card-title h5 mb-3">Strumenti di Scraping</h3>
        <div class="d-flex flex-wrap gap-2">
            @can('view ownerdata')
                <a href="{{ route('scraper.google.maps') }}" 
                   class="btn {{ request()->routeIs('scraper.google.maps') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-map me-2"></i>
                    {{ __('Google Maps') }}
                </a>
            @endcan
        </div>    
    </div>
</div>