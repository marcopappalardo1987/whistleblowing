@extends('layouts.app-frontend-whistleblowing-page')
@section('content')
    @include('layouts.alert-message')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="text-center mb-4">{{ __('Reimposta la Password') }}</h2>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-4">

                        <form method="POST" action="{{ route(app()->getLocale().'.password.store', ['locale' => app()->getLocale()]) }}">
                            @csrf

                            <!-- Token di reimpostazione della password -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <!-- Indirizzo Email -->
                            <div class="mb-3">
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Conferma Password -->
                            <div class="mb-3">
                                <x-input-label for="password_confirmation" :value="__('Conferma Password')" />
                                <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="text-end mt-4">
                                <x-primary-button>
                                    {{ __('Reimposta la Password') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
