<nav class="sidebar bg-dark position-fixed h-100">
    <div class="d-flex flex-column h-100">

        <div class="nav flex-column" id="sidebarLogo">
            <!-- Logo -->
            <a href="javascript:void(0)" class="nav-link">
                <x-heroicon-o-squares-2x2 class="text-white" style="width: 20px" />
                <span>SeoPowerPlus</span>
            </a>
        </div>
        
        <!-- Menu Items -->
        @can('view ownerdata')
        <div class="nav flex-column" id="sidebarAccordion">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="nav-link">
                <x-heroicon-o-home />
                <span>Dashboard</span>
            </a>

            <div class="nav-item">
                <hr class="border-secondary my-2">
            </div>

            <!-- Prodotti -->
            <div class="nav-item">
                <a href="#productsSubmenu" 
                   class="nav-link collapsed" 
                   data-bs-toggle="collapse"
                   data-bs-parent="#sidebarAccordion"
                   role="button" 
                   aria-expanded="false" 
                   aria-controls="productsSubmenu">
                    <x-heroicon-o-shopping-cart />
                    <span>Prodotti</span>
                    <x-heroicon-o-chevron-right class="ms-auto submenu-arrow" />
                </a>
                <div class="collapse submenu" id="productsSubmenu">
                    <a href="{{ route('products') }}" class="nav-link">
                        <x-heroicon-o-rectangle-stack />
                        <span>Lista Prodotti</span>
                    </a>
                    @can('publish products')
                    <a href="{{ route('product.add') }}" class="nav-link">
                        <x-heroicon-o-plus-circle />
                        <span>Aggiungi Prodotto</span>
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Gestione APP -->
            <div class="nav-item">
                <a href="#appSubmenu" 
                   class="nav-link collapsed" 
                   data-bs-toggle="collapse" 
                   data-bs-parent="#sidebarAccordion"
                   role="button" 
                   aria-expanded="false" 
                   aria-controls="appSubmenu">
                    <x-heroicon-o-cog-6-tooth />
                    <span>Gestione APP</span>
                    <x-heroicon-o-chevron-right class="ms-auto submenu-arrow" />
                </a>
                <div class="collapse submenu" id="appSubmenu">
                    <a href="{{ route('users') }}" class="nav-link">
                        <x-heroicon-o-users />
                        <span>Utenti</span>
                    </a>
                    <a href="{{ route('roles') }}" class="nav-link">
                        <x-heroicon-o-user-group />
                        <span>Ruoli</span>
                    </a>
                    <a href="{{ route('permissions') }}" class="nav-link">
                        <x-heroicon-o-shield-check />
                        <span>Permessi</span>
                    </a>
                    <a href="{{ route('subscriptions.all') }}" class="nav-link">
                        <x-heroicon-o-credit-card />
                        <span>Abbonamenti</span>
                    </a>
                </div>
            </div>

            <!-- Logs -->
            <div class="nav-item">
                <a href="#logsSubmenu" 
                   class="nav-link collapsed"
                   data-bs-toggle="collapse"
                   data-bs-parent="#sidebarAccordion"
                   role="button"
                   aria-expanded="false"
                   aria-controls="logsSubmenu">
                    <x-heroicon-o-document-text />
                    <span>Logs</span>
                    <x-heroicon-o-chevron-right class="ms-auto submenu-arrow" />
                </a>
                <div class="collapse submenu" id="logsSubmenu">
                    <a href="{{ route('logs.laravel') }}" class="nav-link">
                        <x-heroicon-o-document-chart-bar />
                        <span>Logs Laravel</span>
                    </a>
                    <a href="{{ route('logs.worker') }}" class="nav-link">
                        <x-heroicon-o-cpu-chip />
                        <span>Logs Worker</span>
                    </a>
                </div>
            </div>
        </div>
        @endcan
    </div>
</nav>