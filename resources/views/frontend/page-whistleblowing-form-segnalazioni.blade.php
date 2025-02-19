<x-app-frontend-whistleblowing 
    title="Segnalazione di Whistleblowing - {{ config('app.name') }}"
    metaDescription="Utilizza il nostro strumento di whistleblowing per segnalare comportamenti scorretti in modo sicuro e anonimo."
    ogTitle="Segnalazione di Whistleblowing - {{ config('app.name') }}"
    ogDescription="Scopri come segnalare in modo sicuro e anonimo. La tua voce conta!"
    ogImage="{{ asset('images/whistleblowing-og.jpg') }}"
    canonicalUrl="{{ url()->current() }}"
>

    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <a href="{{ route('page-whistleblowing', [
                    'slug' => $settings->slug, 
                    'branch_id' => request()->route('branch_id')
                ]) }}">
                    <img src="{{$settings->logo_url}}" style="max-width: 200px">
                </a>
            </div>
        </div>
        @if((int)$countBranches > 1)
            <div class="row">
                <div class="col-12 text-center mt-2">
                    <h2>{{ $branch->name }}</h2>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-12 col-md mb-3">
                <a href="{{ route('page-whistleblowing-form-segnalazioni', [
                    'slug' => $settings->slug, 
                    'branch_id' => request()->route('branch_id')
                ]) }}" class="btn btn-danger w-100 p-3">
                    <div class="d-flex justify-content-center">
                        <x-heroicon-o-exclamation-circle class="me-2" style="max-width: 40px;" />
                    </div>
                    <div class="ms-2">
                        <strong>{{__('Invia Segnalazione')}}</strong>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md mb-3">
                <a href="{{ route('page-whistleblowing-cerca-segnalazioni', [
                    'slug' => $settings->slug,
                    'branch_id' => request()->route('branch_id')
                ]) }}" class="btn btn-primary w-100 p-3">
                    <div class="d-flex justify-content-center">
                        <x-heroicon-o-magnifying-glass class="me-2" style="max-width: 40px;" />
                    </div>
                    <div class="ms-2">
                        <strong>{{__('Cerca una Segnalazione')}}</strong>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md mb-3">
                <a href="{{ route('page-whistleblowing-richiedi-appuntamento', [
                    'slug' => $settings->slug, 
                    'branch_id' => request()->route('branch_id')
                ]) }}" class="btn btn-primary w-100 p-3">
                    <div class="d-flex justify-content-center">
                        <x-heroicon-o-calendar class="me-2" style="max-width: 40px;" />
                    </div>
                    <div class="ms-2">
                        <strong>{{__('Richiedi un appuntamento')}}</strong>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="container form-builder report-send mb-5">
        @if($subscriptionIsActive)
            <form action="{{ route('report-store', [
                'slug' => $settings->slug, 
                'branch_id' => request()->route('branch_id')
            ]) }}" 
            method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <h2>{{__('Invia una segnalazione')}}</h2>
                </div>
                @if($forms_notice_fields->count() > 0)
                    @include('components.form-builder', ['fields' => $forms_notice_fields])
                @else
                    <div class="col-12">
                        <p>{{__('Nessun campo disponibile. Se sei l\'amministratore, puoi configurare i campi nella sezione "Form", "Seleziona form" o ') }} <a class="text-decoration-none" href="{{ route('company.users-forms-relations') }}">{{__('clicca qui')}}</a> {{__('per configurare il form di segnalazione')}}</p>
                    </div>
                @endif
            </div>
            <input type="hidden" name="report_type" value="notice">
            <input type="hidden" name="writer" value="whistleblower">
                <button type="submit" class="btn btn-primary mt-3">{{__('Invia Segnalazione')}}</button>
            </form>
        @else
            <div class="row">
                <div class="col-12 text-center mt-2">
                    {{__('Al momento questo servizio non è disponibile. Contatta l\'amministratore dell\'azienda.')}}
                </div>
            </div>
        @endif
    </div>

</x-app-frontend-whistleblowing>

