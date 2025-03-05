<x-app-layout>

    <x-slot name="header">
        {{ __('Gestione Utenti') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation-users-manager')

    <div class="content-page mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="h5 card-title mb-3">{{ __('Aggiungi Nuovo Utente') }}</h3>
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <x-input-label for="name" value="{{ __('Nome') }}" />
                                    <x-text-input name="name" id="name" required />
                                    <x-input-error :messages="$errors->get('name')" />
                                </div>

                                <div class="col-md-6">
                                    <x-input-label for="email" value="{{ __('Email') }}" />
                                    <x-text-input type="email" name="email" id="email" required />
                                    <x-input-error :messages="$errors->get('email')" />
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <x-input-label for="password" value="{{ __('Password') }}" />
                                    <x-text-input type="password" name="password" id="password" required />
                                    <x-input-error :messages="$errors->get('password')" />
                                </div>

                                <div class="col-md-6">
                                    <x-input-label for="password_confirmation" value="{{ __('Conferma Password') }}" />
                                    <x-text-input type="password" name="password_confirmation" id="password_confirmation" required />
                                    <x-input-error :messages="$errors->get('password_confirmation')" />
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <x-input-label for="roles" value="{{ __('Ruoli') }}" />
                                    <x-select-input name="roles[]" id="roles" :options="$roles->pluck('name', 'name')" />
                                    <x-input-error :messages="$errors->get('roles')" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Crea Utente') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>