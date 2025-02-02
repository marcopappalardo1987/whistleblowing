<x-app-layout>
    
    <x-slot name="header">
        {{ __('Gestione Link di Referenza') }}
    </x-slot>

    @include('layouts.alert-message')

    
    <div class="content-page mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                    <h3 class="h5">{{__('Registrazione nuovo affiliato')}}</h3>
                    <div class="input-group mb-3">
                        <input type="text" id="referralLink" class="form-control" value="{{ route('register.affiliate', ['referral_id' => auth()->user()->id]) }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyReferralLink()">Copia Link</button>
                        <button class="btn btn-outline-success" type="button" onclick="shareOnWhatsApp()">Condividi su WhatsApp</button>
                    </div>
                    <script>
                        function copyReferralLink() {
                            var copyText = document.getElementById("referralLink");
                            copyText.select();
                            copyText.setSelectionRange(0, 99999); // Per dispositivi mobili
                            document.execCommand("copy");
                            alert("Link copiato: " + copyText.value);
                        }

                        function shareOnWhatsApp() {
                            var referralLink = document.getElementById("referralLink").value;
                            var introText = "Ecco il link per accedere al programma affiliati di whistlebowingtool.com";
                            var whatsappUrl = "https://api.whatsapp.com/send?text=" + encodeURIComponent(introText + " " + referralLink);
                            window.open(whatsappUrl, '_blank');
                        }
                    </script>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                    <h3 class="h5">{{__('Registrazione azienda')}}</h3>
                    <div class="input-group mb-3">
                        <input type="text" id="referralLinkCompany" class="form-control" value="{{ route('register', ['referral_id' => auth()->user()->id]) }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyReferralLinkCompany()">Copia Link</button>
                        <button class="btn btn-outline-success" type="button" onclick="shareCompanyOnWhatsApp()">Condividi su WhatsApp</button>
                    </div>
                    <script>
                        function copyReferralLinkCompany() {
                            var copyText = document.getElementById("referralLinkCompany");
                            copyText.select();
                            copyText.setSelectionRange(0, 99999); // Per dispositivi mobili
                            document.execCommand("copy");
                            alert("Link copiato: " + copyText.value);
                        }

                        function shareCompanyOnWhatsApp() {
                            var referralLink = document.getElementById("referralLinkCompany").value;
                            var introText = "Iscrivi la tua azienda al nostro programma di whistleblowing per garantire un ambiente di lavoro sicuro e responsabile. Ecco il link per registrarsi: ";
                            var whatsappUrl = "https://api.whatsapp.com/send?text=" + encodeURIComponent(introText + referralLink);
                            window.open(whatsappUrl, '_blank');
                        }
                    </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>