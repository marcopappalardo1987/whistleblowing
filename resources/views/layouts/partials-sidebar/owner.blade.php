<!-- Menu Items -->
@role('owner')
<div class="nav flex-column" id="sidebarAccordion">
    <!-- Dashboard -->
    <a href="{{ route('dashboard') }}" class="nav-link">
        <x-heroicon-o-home />
        <span>{{__('Dashboard')}}</span>
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
            <span>{{__('Prodotti')}}</span>
            <x-heroicon-o-chevron-right class="ms-auto submenu-arrow" />
        </a>
        <div class="collapse submenu" id="productsSubmenu">
            <a href="{{ route('products') }}" class="nav-link">
                <x-heroicon-o-rectangle-stack />
                <span>{{__('Lista Prodotti')}}</span>
            </a>
            @can('publish products')
            <a href="{{ route('product.add') }}" class="nav-link">
                <x-heroicon-o-plus-circle />
                <span>{{__('Aggiungi Prodotto')}}</span>
            </a>
            @endcan
        </div>
    </div>

    <!-- Form Builder -->
    <div class="nav-item">
        <a href="#formBuilderSubmenu" 
        class="nav-link collapsed"
        data-bs-toggle="collapse"
        data-bs-parent="#sidebarAccordion"
        role="button"
        aria-expanded="false"
        aria-controls="formBuilderSubmenu">
            <x-fluentui-form-48-o />
            <span>{{__('Form Builder')}}</span>
            <x-fluentui-form-48-o class="ms-auto submenu-arrow"/>
        </a>
        <div class="collapse submenu" id="formBuilderSubmenu">
            <a href="{{ route('form.builder.new') }}" class="nav-link">
                <x-heroicon-o-plus-circle />
                <span>{{__('Crea Nuovo Form')}}</span>
            </a>
            <a href="{{ route('form.builder.list') }}" class="nav-link">
                <x-heroicon-o-document />
                <span>{{__('Elenco Form')}}</span>
            </a>
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
            <span>{{__('Gestione APP')}}</span>
            <x-heroicon-o-chevron-right class="ms-auto submenu-arrow" />
        </a>
        <div class="collapse submenu" id="appSubmenu">
            <a href="{{ route('users') }}" class="nav-link">
                <x-heroicon-o-users />
                <span>{{__('Utenti')}}</span>
            </a>
            <a href="{{ route('roles') }}" class="nav-link">
                <x-heroicon-o-user-group />
                <span>{{__('Ruoli')}}</span>
            </a>
            <a href="{{ route('permissions') }}" class="nav-link">
                <x-heroicon-o-shield-check />
                <span>{{__('Permessi')}}</span>
            </a>
            <a href="{{ route('subscriptions.all') }}" class="nav-link">
                <x-heroicon-o-credit-card />
                <span>{{__('Abbonamenti')}}</span>
            </a>
            <a href="{{ route('affiliate.settings.commissions') }}" class="nav-link">
                <x-heroicon-o-banknotes />
                <span>{{__('Commissioni')}}</span>
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
            <span>{{__('Logs')}}</span>
            <x-heroicon-o-chevron-right class="ms-auto submenu-arrow" />
        </a>
        <div class="collapse submenu" id="logsSubmenu">
            <a href="{{ route('logs.laravel') }}" class="nav-link">
                <x-heroicon-o-document-chart-bar />
                <span>{{__('Logs Laravel')}}</span>
            </a>
            <a href="{{ route('logs.worker') }}" class="nav-link">
                <x-heroicon-o-cpu-chip />
                <span>{{__('Logs Worker')}}</span>
            </a>
        </div>
    </div>

</div>
@endcan