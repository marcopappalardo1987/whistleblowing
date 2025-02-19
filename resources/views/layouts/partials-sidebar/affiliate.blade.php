@role('affiliato')
    <div class="nav flex-column" id="sidebarAccordion">
        <a class="nav-link" href="{{ route('affiliate.private-area.earnings') }}">
            <x-ionicon-cash-outline class="me-2" />
            <span>{{__('Guadagni Generati')}}</span>
        </a>
        <a class="nav-link" href="{{ route('affiliate.private-area.affiliates-list') }}">
            <x-heroicon-o-users class="me-2" />
            <span>{{__('Elenco Affiliati')}}</span>
        </a>
        <a class="nav-link" href="{{ route('affiliate.private-area.links') }}">
            <x-heroicon-o-link class="me-2" />
            <span>{{__('Link di Affiliazione')}}</span>
        </a>
    </div>
@endrole