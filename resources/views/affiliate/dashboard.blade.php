<x-app-layout>
    
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    @include('layouts.alert-message')  


    <div class="card background-color3">
        <div class="card-body">
            <div class="commission-levels">
                <h4 class="text-2xl font-bold mb-3 mt-1 color-white">{{ __('Commissioni') }}</h4>
                <div class="row">
                    @foreach($commissions as $commission)
                    <div class="col-md-4 mb-3">
                        <div class="background-color-white rounded p-3 text-center">
                            <h3 class="text-lg font-semibold mb-0">{{ __('Livello') }}: {{ $commission->level }}</h3>
                            <p class="text-xl mb-0">{{ __('Commissione') }}: {{ number_format($commission->commission, 2) }}%</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
