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
                <a href="{{ route('page-whistleblowing-form-segnalazioni', 
                    [
                        'slug' => $settings->slug, 
                        'branch_id' => request()->route('branch_id')
                    ]) }}" class="btn btn-primary w-100 p-3">
                    <div class="d-flex justify-content-center">
                        <x-heroicon-o-exclamation-circle class="me-2" style="max-width: 40px;" />
                    </div>
                    <div class="ms-2">
                        <strong>{{__('Invia Segnalazione')}}</strong>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md mb-3">
                <a href="{{ route('page-whistleblowing-cerca-segnalazioni', 
                    [
                        'slug' => $settings->slug, 
                        'branch_id' => request()->route('branch_id')
                    ]) }}" class="btn btn-danger w-100 p-3">
                    <div class="d-flex justify-content-center">
                        <x-heroicon-o-magnifying-glass class="me-2" style="max-width: 40px;" />
                    </div>
                    <div class="ms-2">
                        <strong>{{__('Cerca una Segnalazione')}}</strong>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md mb-3">
                <a href="{{ route('page-whistleblowing-richiedi-appuntamento', 
                    [
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

    @if($subscriptionIsActive)
        <div class="container form-builder report-send mb-5">
            <form action="{{ route('report.view', ['slug' => $settings->slug]) }}" method="post">
                @csrf
                <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="report_id" class="form-label">{{ __('Codice Segnalazione') }}</label>
                    <input type="text" id="report_id" name="report_id" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{__('Cerca Segnalazione')}}</button>
            </form>
        </div>
    @else
        <div class="row">
            <div class="col-12 text-center mt-2">
                {{__('Al momento questo servizio non è disponibile. Contatta l\'amministratore dell\'azienda.')}}
            </div>
        </div>
    @endif

</x-app-frontend-whistleblowing>

