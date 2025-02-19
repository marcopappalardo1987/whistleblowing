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
            <div class="col-md-12 mb-5">
                <img src="{{ $settings->logo_url }}" style="max-width: 200px">
            </div>
        </div>
    </div>

    <div class="container mb-5"> 
        <div class="row">
            <div class="col-md-12">
                <h3 class="mb-0">{{ __('Dettagli Segnalazione') }}</h3>
                <h6 class="mb-0">{{ __('id: ' . $formData['report']['id']) }}</h6>
                <div class="col-md-4">
                    <strong>{{ __('Stato') }}:</strong> 
                    <span class="badge bg-primary">{{ $formData['report']['status'] }}</span>
                </div>
            </div>
        </div>
        <hr>
    </div>

    <div class="container">
        @foreach($formData['forms'] as $form)
            @php
                if ($form['writer'] == 'whistleblower') {
                    $class = 'message background-color1 investigator-message text-white';
                    $alignColumn = 'justify-content-end text-end';
                } else {
                    $class = 'message background-color6 whistleblower-message';
                    $alignColumn = '';
                }
            @endphp
            
            <div class="row {{ $alignColumn }}">
                <div class="col-md-10 mb-5">
                    <div class="{{ $class }} p-4">
                        <div class="message-writer">
                            <span class="badge background-color3">
                                {{ $form['writer'] != 'whistleblower' ? 'investigatore' : $form['writer'] }}
                            </span>
                        </div>
                        <div class="message-content py-3">
                            @foreach($form as $key => $value)
                                <div class="metadata-item mb-2">
                                    <strong>{{ $key }}:</strong><br>
                                    @php
                                        $metaValue = $value;
                                    @endphp
                                    <span>
                                        @if (str_contains($metaValue, 'storage/reports'))
                                            <a href="{{ $metaValue }}" target="_blank" class="btn btn-sm btn-secondary mt-1">Apri allegato</a>  
                                        @else
                                            <span>{{ $metaValue }}</span>
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                            <div class="date-message">
                                <span class="small">{{ $formData['report']['created_at'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h5>{{ __('Rispondi alla segnalazione') }}</h5>
                        <form method="POST" action="{{ route('whistleblower.report.reply', ['id' => $formData['report']['id']]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="message" class="form-label">{{ __('Il tuo messaggio') }}</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <div class="form-group col-md-12 mb-3">
                                <label for="inserisci_un_allegato">Allegato</label>
                                <input type="file" class="form-control-file" id="allegato" name="allegato">
                                <input type="hidden" name="allegato" value="Inserisci un allegato">
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Invia risposta') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-frontend-whistleblowing>