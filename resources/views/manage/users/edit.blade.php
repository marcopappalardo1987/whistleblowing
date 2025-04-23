<!-- Start of Selection -->
<x-app-layout>

    <x-slot name="header">
        {{ __('Gestione Utenti') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation-users-manager')

    <div class="content-page">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="h5 card-title mb-3">{{ __('Modifica Utente') }}</h3>
                        <form action="{{ route('users.update', ['user_id' => $user->id]) }}" method="POST">
                            @csrf
                            @method('POST')
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <x-input-label for="name" value="{{ __('Name') }}" />
                                    <x-text-input name="name" id="name" value="{{ $user->name }}" required />
                                    <x-input-error :messages="$errors->get('name')" />
                                </div>

                                <div class="col-md-6">
                                    <x-input-label for="email" value="{{ __('Email') }}" />
                                    <x-text-input type="email" name="email" id="email" value="{{ $user->email }}" required />
                                    <x-input-error :messages="$errors->get('email')" />
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <x-input-label for="roles" value="{{ __('Role') }}" />
                                    @if($user->roles->contains('name', 'owner'))
                                        <p class="mt-1 block text-sm text-gray-500">{{ __('Owner (Cannot be modified)') }}</p>
                                    @else
                                        <x-select-input name="roles[]" id="roles" :options="$roles->pluck('name', 'name')" :selected="$user->roles->pluck('name')->first()" />
                                    @endif
                                    <x-input-error :messages="$errors->get('roles')" />
                                </div>

                                <div class="col-md-6">
                                    <x-input-label for="password" value="{{ __('Password') }}" />
                                    <x-text-input type="password" name="password" id="password" placeholder="{{ __('Leave blank to keep current password') }}" />
                                    <x-input-error :messages="$errors->get('password')" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Aggiorna Utente') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
<!-- End of Selection -->