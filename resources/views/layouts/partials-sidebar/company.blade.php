@role('azienda')
    
    <!-- Investigatore -->
    <div class="nav-item">
        <a href="#investigatorSubmenu" 
        class="nav-link collapsed" 
        data-bs-toggle="collapse"
        data-bs-parent="#sidebarAccordion"
        role="button" 
        aria-expanded="false" 
        aria-controls="investigatorSubmenu">
            <x-heroicon-o-user-group />
            <span>{{__('Investigatori')}}</span>
            <x-heroicon-o-chevron-right class="ms-auto submenu-arrow" />
        </a>
        <div class="collapse submenu" id="investigatorSubmenu">
            <a href="{{ route('investigator.invite') }}" class="nav-link">
                <x-heroicon-o-plus-circle />
                <span>{{__('Invita Investigatore')}}</span>
            </a>
        </div>
        <div class="collapse submenu" id="investigatorSubmenu">
            <a href="{{ route('investigator.list') }}" class="nav-link">
                <x-heroicon-o-plus-circle />
                <span>{{__('Elenco Investigatori')}}</span>
            </a>
        </div>
    </div>

    <!-- Branch -->
    <div class="nav-item">
        <a href="#sectorSubmenu" 
        class="nav-link collapsed" 
        data-bs-toggle="collapse"
        data-bs-parent="#sidebarAccordion"
        role="button" 
        aria-expanded="false" 
        aria-controls="sectorSubmenu">
            <x-heroicon-o-building-office />
            <span>{{__('Branch')}}</span>
            <x-heroicon-o-chevron-right class="ms-auto submenu-arrow" />
        </a>
        <div class="collapse submenu" id="sectorSubmenu">
            <a href="{{ route('branch.add') }}" class="nav-link">
                <x-heroicon-o-plus-circle />
                <span>{{__('Nuovo Branch')}}</span>
            </a>
        </div> 
        <div class="collapse submenu" id="sectorSubmenu">
            <a href="{{ route('branch.list') }}" class="nav-link">
                <x-heroicon-o-plus-circle />
                <span>{{__('Elenco Branch')}}</span>
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="nav-item">
        <a href="#formSubmenu" 
        class="nav-link collapsed" 
        data-bs-toggle="collapse"
        data-bs-parent="#sidebarAccordion"
        role="button" 
        aria-expanded="false" 
        aria-controls="formSubmenu"> 
            <x-heroicon-o-document-text />
            <span>{{__('Form')}}</span>
            <x-heroicon-o-chevron-right class="ms-auto submenu-arrow" />
        </a>
        {{-- @if($user->canAddForm()) --}}
            <div class="collapse submenu" id="formSubmenu">
                <a href="{{ route('form.builder.new') }}" class="nav-link">
                    <x-heroicon-o-plus-circle />
                    <span>{{__('Nuovo Form')}}</span>
                </a>
            </div>
            <div class="collapse submenu" id="formSubmenu">
                <a href="{{ route('form.builder.list') }}" class="nav-link">
                    <x-heroicon-o-plus-circle />
                    <span>{{__('Elenco Form')}}</span>
                </a>
            </div>
        {{-- @endif --}}
        <div class="collapse submenu" id="formSubmenu">
            <a href="{{ route('company.users-forms-relations') }}" class="nav-link">
                <x-heroicon-o-plus-circle />
                <span>{{__('Seleziona Form')}}</span>
            </a>
        </div>
    </div>

    <!-- Impostazioni -->
    <div class="nav-item">
        <a href="{{ route('company.settings') }}" 
           class="nav-link" 
           role="button">
            <x-heroicon-o-cog-6-tooth />
            <span>{{__('Impostazioni')}}</span>
        </a>
    </div>

@endrole