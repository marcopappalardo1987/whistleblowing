<x-app-layout>

    <x-slot name="header">
        {{ __('Worker Laravel Logs') }}
    </x-slot>
    
    @include('layouts.alert-message')

    @include('layouts.navigation-logs')

    <div class="mt-4">
        <form action="{{ route('logs.worker.clear') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">
                {{ __('Cancella Log Worker') }}
            </button>
        </form>
    </div>

    <div class="card bg-dark mt-4">
        <div class="card-body text-light">
            <h3 class="card-title h5 mb-4">Contenuto del file Worker Log</h3>
            <pre class="bg-dark text-light p-3">
                {{ file_get_contents(storage_path('logs/worker.log')) }}
            </pre>
        </div>
    </div>

</x-app-layout>
