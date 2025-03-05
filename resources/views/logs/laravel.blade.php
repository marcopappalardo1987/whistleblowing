<x-app-layout>

    <x-slot name="header">
        {{ __('Laravel Logs') }}
    </x-slot>
    
    @include('layouts.alert-message')

    @include('layouts.navigation-logs')

    <div class="mt-4">
        <form action="{{ route('logs.clear') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">
                {{ __('Cancella Log Laravel') }}
            </button>
        </form>
    </div>

    <div class="card bg-dark mt-4">
        <div class="card-body text-light">
            <h3 class="card-title h5 mb-4">{{ __('Contenuto del file Laravel Log') }}</h3>
            <pre class="bg-dark text-light p-3">
                {{ file_get_contents(storage_path('logs/laravel.log')) }}
            </pre>
        </div>
    </div>

</x-app-layout>
