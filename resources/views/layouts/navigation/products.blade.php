<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h3 class="card-title h5 mb-3">Prodotti</h3>
        <div class="d-flex flex-wrap gap-2">
            @can('view products')
                <a href="{{ route('products') }}" 
                   class="btn {{ request()->routeIs('products') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-eye me-2"></i>
                    {{ __('Visualizza Prodotti') }}
                </a>
            @endcan
            
            @can('publish products')
                <a href="{{ route('product.add') }}" 
                   class="btn {{ request()->routeIs('product.add') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ __('Aggiungi Prodotto') }}
                </a>
            @endcan
        </div>    
    </div>
</div>