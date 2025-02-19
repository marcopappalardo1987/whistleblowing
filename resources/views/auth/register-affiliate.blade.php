<x-app-frontend 
    title="Registrati come Affiliato - {{ config('app.name') }}"
    metaDescription="Registrati come affiliato per guadagnare commissioni sui nostri servizi premium."
    ogTitle="Registrati come Affiliato - {{ config('app.name') }}"
    ogDescription="Unisciti al nostro programma di affiliazione e inizia a guadagnare oggi stesso."
    ogImage="{{ asset('images/register-og.jpg') }}"  {{-- Se hai un'immagine specifica --}}
    canonicalUrl="{{ url()->current() }}"
>
    @include('layouts.alert-message')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12">
                @include('layouts.alert-message') 
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Registrati come affiliato</h2>

                        <form method="POST" action="{{ route('register.affiliate.store') }}">
                            @csrf

                            <!-- Nome -->
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Nome e Cognome') }}</label>
                                <input id="name" type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    required 
                                    autofocus 
                                    autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input id="email" type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    name="password" 
                                    required 
                                    autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Conferma Password -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    {{ __('Conferma Password') }}
                                </label>
                                <input id="password_confirmation" type="password" 
                                    class="form-control"
                                    name="password_confirmation" 
                                    required 
                                    autocomplete="new-password">
                            </div>

                            <input type="hidden" name="referral_id" value="{{ request()->get('referral_id') }}">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <a class="text-decoration-none" href="{{ route('login') }}">
                                    {{ __('Hai già un account?') }}
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    {{ __('Registrati') }}
                                </button>
                            </div>
                        </form>

                        @if(session('intended_url'))
                            <hr class="my-4">
                            <div class="text-center">
                                <p class="text-muted small">
                                    <i class="bi bi-info-circle"></i>
                                    Dopo la registrazione potrai completare il tuo acquisto
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
        if (isset($_GET['referral_id'])) {
            // Memorizza referral_id nei cookie per 1 anno con un nome personalizzato
            setcookie('wbt_referral_id', $_GET['referral_id'], time() + (525600 * 60), "/"); // 525600 minuti = 1 anno
        }
    ?>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .card {
            border: none;
            border-radius: 10px;
        }
        .btn-outline-primary:hover {
            background-color: #f8f9fa;
        }
    </style>
    @endpush
</x-app-frontend>