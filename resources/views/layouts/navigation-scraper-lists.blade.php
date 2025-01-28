<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h3 class="card-title h5 mb-3">Gestione delle Liste</h3>
        <div class="d-flex flex-wrap gap-2">
            @can('publish ownerdata')
                <a href="{{ route('scraper.list.add') }}" 
                   class="btn {{ request()->routeIs('scraper.list.add') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ __('Nuova Lista') }}
                </a>
            @endcan

            @can('view ownerdata')
                <a href="{{ route('scraper.list.manage') }}" 
                   class="btn {{ request()->routeIs('scraper.list.manage') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-list-ul me-2"></i>
                    {{ __('Tutte le Liste') }}
                </a>
            @endcan
        </div>    
    </div>
</div>