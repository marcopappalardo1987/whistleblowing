<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <div class="mb-4 text-muted small">
                            {{ __('Grazie per esserti registrato! Prima di iniziare, potresti verificare il tuo indirizzo email cliccando sul link che ti abbiamo appena inviato? Se non hai ricevuto l\'email, saremo felici di inviartene un\'altra.') }}
                        </div>

                        @if (session('status') == 'verification-link-sent')
                            <div class="mb-4 alert alert-success">
                                {{ __('Un nuovo link di verifica è stato inviato all\'indirizzo email che hai fornito durante la registrazione.') }}
                            </div>
                        @endif

                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf

                                <div>
                                    <x-primary-button>
                                        {{ __('Invia nuovamente l\'email di verifica') }}
                                    </x-primary-button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit" class="btn btn-link text-muted text-decoration-none small">
                                    {{ __('Disconnetti') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
