@extends('layouts.frontend')

@section('title', 'Registrati - ' . config('app.name'))

@section('meta_description')
    Crea il tuo account per accedere ai nostri servizi premium.
@endsection

@section('og_title', 'Registrati - ' . config('app.name'))

@section('og_description')
    Registrati per accedere a tutti i nostri servizi e funzionalità premium.
@endsection

{{-- @section('og_image', asset('images/register-og.jpg')) --}}

@section('additional_metadata')
    <meta name="keywords" content="registrazione, nuovo account, iscrizione">
    <meta name="robots" content="index, follow">
@endsection

@section('content')
@include('layouts.alert-message')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Registrati</h2>

                        <form method="POST" action="{{ route('register') }}">
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
@endsection