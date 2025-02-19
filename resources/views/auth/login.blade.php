<x-app-frontend 
    title="Accedi - {{ config('app.name') }}"
    metaDescription="Accedi al tuo account per gestire i tuoi servizi e le tue impostazioni."
    ogTitle="Accedi - {{ config('app.name') }}"
    ogDescription="Accedi in modo sicuro al tuo account personale."
    ogImage="{{ asset('images/plans-og.jpg') }}"  {{-- Se hai un'immagine specifica --}}
    canonicalUrl="{{ url()->current() }}"
>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">{{__('Accedi')}}</h2>
                        
                        @if (session('status'))
                            <div class="alert alert-success mb-4">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Ricordami -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                    <label class="form-check-label" for="remember_me">
                                        {{ __('Ricordami') }}
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none color3" href="{{ route('password.request') }}">
                                        {{ __('Password dimenticata?') }}
                                    </a>
                                @endif

                                <button type="submit" class="btn btn-primary">
                                    {{ __('Accedi') }}
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-0">Non hai ancora un account?</p>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary mt-2">
                                {{ __('Registrati ora') }}
                            </a>
                            @if(session('intended_url'))
                                <p class="mt-3 text-muted small">
                                    <i class="bi bi-info-circle"></i>
                                    {{__('Dopo la registrazione potrai completare il tuo acquisto')}}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
