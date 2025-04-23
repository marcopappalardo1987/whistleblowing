<x-app-frontend-whistleblowing 
    title="Segnalazione di Whistleblowing - {{ config('app.name') }}"
    metaDescription="Utilizza il nostro strumento di whistleblowing per segnalare comportamenti scorretti in modo sicuro e anonimo."
    ogTitle="Segnalazione di Whistleblowing - {{ config('app.name') }}"
    ogDescription="Scopri come segnalare in modo sicuro e anonimo. La tua voce conta!"
    ogImage="{{ asset('images/whistleblowing-og.jpg') }}"
    canonicalUrl="{{ url()->current() }}"
>

    <div class="container py-5">
        <div class="row">
            <div class="col-12 text-center mb-5 mt-5">
                <img id="logo-azienda" src="{{$settings->logo_url}}" style="max-width: 200px">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h1>{{__('Segnalazione inviata con successo')}}</h1>
            </div>
            <div class="col-12">
                <div class="alert alert-success mb-3">
                    <h4 class="alert-heading mb-3">{{__('Il report è stato inviato con successo!')}}</h4>
                    <p class="mb-2 "><span class="fw-bold">{{__('Cosa succede ora?')}}</span><br>
                        {{__('Un amministratore del sistema riceverà la segnalazione e la analizzerà.')}}<br>
                        {{__('Non appena la segnalazione sarà analizzata, riceverai una risposta con il risultato della segnalazione.')}}
                    </p>
                </div>
                <div class="background-color-danger text-white p-3 rounded">
                    <div class="mb-3">
                        <p class="mb-2 fw-bold">{{__('ATTENZIONE: L\'accesso al report è possibile solo tramite il codice di segnalazione e la password.')}}</p>
                    </div>
                    <div class="mb-3">
                        <strong>{{__('Codice di segnalazione:')}}</strong>
                        <span class="ms-2" id="reportId">{{ $reportId }}</span>
                        <button class="btn btn-secondary ms-2" onclick="copyToClipboard('reportId')">{{__('Copia Codice')}}</button>
                    </div>
                    <div class="mb-3">
                        <strong>{{__('Password:')}}</strong> 
                        <span class="ms-2" id="password">{{ $passwordNotHashed }}</span>
                        <button class="btn btn-secondary ms-2" onclick="copyToClipboard('password')">{{__('Copia Password')}}</button>
                    </div>
                    <script>
                        function copyToClipboard(elementId) {
                            var text = document.getElementById(elementId).innerText;
                            navigator.clipboard.writeText(text).then(function() {
                                alert('{{__('Copiato negli appunti!')}}');
                            }, function(err) {
                                console.error('Impossibile copiare il testo: ', err);
                            });
                        }
                    </script>
                    <div>
                        <p class="mt-3 mb-0"><small>{{__('Ti consigliamo di salvare queste informazioni in un luogo sicuro.')}}</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-frontend-whistleblowing>