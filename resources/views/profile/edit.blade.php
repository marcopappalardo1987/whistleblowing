<x-app-layout>
    <x-slot name="header">
        <h1 class="h3 mb-0">{{ __('Profile') }}</h1>
    </x-slot>

    <div class="content-page py-4">
        <div class="row justify-content-center h-100">
            <div class="col-12 col-lg-6 d-flex">
                <div class="card shadow-sm mb-4 flex-fill">
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 d-flex">
                <div class="card shadow-sm mb-4 flex-fill">
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <div class="col-12 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <a href="{{ route('company.edit', ['user' => auth()->id()]) }}" class="btn btn-secondary">{{ __('Modifica Dati Azienda') }}</a>
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
