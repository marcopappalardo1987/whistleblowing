<x-app-layout>

    <x-slot name="header">
        {{ __('Errori DataForSEO') }}
    </x-slot>
    
    @include('layouts.navigation-logs')

    <div class="content-page mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{ __('Risultati Errori DataForSEO') }}</h2>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code>{{ json_encode($errors, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
