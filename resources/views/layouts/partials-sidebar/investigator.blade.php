@role('investigatore')

    <!-- Dashboard -->
    <div class="nav-item">
        <a href="{{ route('dashboard') }}" class="nav-link">
            <x-heroicon-o-home />
            <span>{{__('Dashboard')}}</span>
        </a>
    </div>

    <div class="nav-item">
        <hr class="border-secondary my-2">
    </div>

    <!-- Nuove Segnalazioni -->
    <div class="nav-item">
        <a href="{{ route('investigator.reports-list', ['status' => 'submitted']) }}" 
           class="nav-link">
            <x-heroicon-o-inbox />
            <span>{{__('Nuove Segnalazioni')}}</span>
        </a>
    </div>

    <!-- Letto senza azioni -->
    <div class="nav-item">
        <a href="{{ route('investigator.reports-list', ['status' => 'read']) }}" class="nav-link">
            <x-heroicon-o-book-open />
            <span>{{__('Letto senza azioni')}}</span>
        </a>
    </div>

    <!-- Segnalazioni in Corso -->
    <div class="nav-item">
        <a href="#ongoingSubmenu" 
        class="nav-link collapsed" 
        data-bs-toggle="collapse"
        data-bs-parent="#sidebarAccordion"
        role="button" 
        aria-expanded="false" 
        aria-controls="ongoingSubmenu">
            <x-heroicon-o-clock />
            <span>{{__('In Corso')}}</span>
            <x-heroicon-o-chevron-right class="ms-auto submenu-arrow" />
        </a>
        <div class="collapse submenu" id="ongoingSubmenu">
            <a href="{{ route('investigator.reports-list', ['status' => 'under_review']) }}" class="nav-link">
                <x-heroicon-o-magnifying-glass />
                <span>{{__('In Revisione')}}</span>
            </a>
        </div>
        <div class="collapse submenu" id="ongoingSubmenu">
            <a href="{{ route('investigator.reports-list', ['status' => 'investigation_ongoing']) }}" class="nav-link">
                <x-heroicon-o-document-duplicate />
                <span>{{__('In Investigazione')}}</span>
            </a>
        </div>
        <div class="collapse submenu" id="ongoingSubmenu">
            <a href="{{ route('investigator.reports-list', ['status' => 'resolving']) }}" class="nav-link">
                <x-heroicon-o-check-circle />
                <span>{{__('In Risoluzione')}}</span>
            </a>
        </div>
    </div>

    <!-- Comunicazioni -->
    <div class="nav-item">
        <a href="#communicationSubmenu" 
        class="nav-link collapsed" 
        data-bs-toggle="collapse"
        data-bs-parent="#sidebarAccordion"
        role="button" 
        aria-expanded="false" 
        aria-controls="communicationSubmenu">
            <x-heroicon-o-chat-bubble-left-right />
            <span>{{__('Comunicazioni')}}</span>
            <x-heroicon-o-chevron-right class="ms-auto submenu-arrow" />
        </a>
        <div class="collapse submenu" id="communicationSubmenu">
            <a href="{{ route('investigator.reports-list', ['status' => 'replied']) }}" class="nav-link">
                <x-heroicon-o-chat-bubble-left />
                <span>{{__('Risposta Inviata')}}</span>
            </a>
        </div>
        <div class="collapse submenu" id="communicationSubmenu">
            <a href="{{ route('investigator.reports-list', ['status' => 'user_replied']) }}" class="nav-link">
                <x-heroicon-o-chat-bubble-bottom-center-text />
                <span>{{__('Risposta Ricevuta')}}</span>
            </a>
        </div>
    </div>

    <!-- Stati Particolari -->
    <div class="nav-item">
        <a href="#specialSubmenu" 
        class="nav-link collapsed" 
        data-bs-toggle="collapse"
        data-bs-parent="#sidebarAccordion"
        role="button" 
        aria-expanded="false" 
        aria-controls="specialSubmenu">
            <x-heroicon-o-exclamation-triangle />
            <span>{{__('Stati Particolari')}}</span>
            <x-heroicon-o-chevron-right class="ms-auto submenu-arrow" />
        </a>
        <div class="collapse submenu" id="specialSubmenu">
            <a href="{{ route('investigator.reports-list', ['status' => 'waiting_for_information']) }}" class="nav-link">
                <x-heroicon-o-pause />
                <span>{{__('In Pausa')}}</span>
            </a>
        </div>
        <div class="collapse submenu" id="specialSubmenu">
            <a href="{{ route('investigator.reports-list', ['status' => 'expiring']) }}" class="nav-link">
                <x-heroicon-o-clock />
                <span>{{__('In Scadenza')}}</span>
            </a>
        </div>
        <div class="collapse submenu" id="specialSubmenu">
            <a href="{{ route('investigator.reports-list', ['status' => 'expired']) }}" class="nav-link">
                <x-heroicon-o-clock />
                <span>{{__('Scaduto')}}</span>
            </a>
        </div>
        <div class="collapse submenu" id="specialSubmenu">
            <a href="{{ route('investigator.reports-list', ['status' => 'reopened']) }}" class="nav-link">
                <x-heroicon-o-arrow-path />
                <span>{{__('Riaperto')}}</span>
            </a>
        </div>
    </div>

    <!-- Archivio -->
    <div class="nav-item">
        <a href="#archiveSubmenu" 
        class="nav-link collapsed" 
        data-bs-toggle="collapse"
        data-bs-parent="#sidebarAccordion"
        role="button" 
        aria-expanded="false" 
        aria-controls="archiveSubmenu">
            <x-heroicon-o-archive-box />
            <span>{{__('Archivio')}}</span>
            <x-heroicon-o-chevron-right class="ms-auto submenu-arrow" />
        </a>
        <div class="collapse submenu" id="archiveSubmenu">
            <a href="{{ route('investigator.reports-list', ['status' => 'closed']) }}" class="nav-link">
                <x-heroicon-o-lock-closed />
                <span>{{__('Chiuse')}}</span>
            </a>
        </div>
        <div class="collapse submenu" id="archiveSubmenu">
            <a href="{{ route('investigator.reports-list', ['status' => 'canceled']) }}" class="nav-link">
                <x-heroicon-o-x-mark />
                <span>{{__('Annullate')}}</span>
            </a>
        </div>
    </div>

@endrole