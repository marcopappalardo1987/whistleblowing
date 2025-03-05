<x-app-layout>

    <x-slot name="header">
        {{ __('Gestione Applicazione') }}
    </x-slot>
    
    @include('layouts.navigation-roles-and-permissions')

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6">
                @include('layouts.navigation-users-manager')
            </div>
            <div class="col-12 col-lg-6">
                @include('layouts.navigation-api-settings')
            </div>
        </div>
    </div>
    
</x-app-layout>
