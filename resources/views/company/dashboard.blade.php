<x-app-layout>
    
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="row">
        <div class="col-md-12">
            @include('layouts.alert-message')  
        </div>
    </div>

    @if($steps)
        <div class="row">
                <div class="col-md-12">
                    <div class="background-color5 p-4 rounded-3">
                        <h5>{{ __('Benvenuto, ') }} {{ Auth::user()->name }}</h5>
                        <p>{{ __('Solo qualche suggerimento per iniziare ad utilizzare il nostro software per la gestione delle segnalazioni in modo corretto.') }}</p>

                        <div class="steps-container mt-3">
                            @foreach($steps as $index => $step)
                                <div class="step-item d-flex align-items-start mb-3">
                                    <div class="step-number background-color3 text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="step-content background-color6 py-3 px-4 rounded-3 flex-grow-1">
                                        {!! $step !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(!empty($data))
        <div class="row">
            <div class="col-md-12">
                <p>{{ __('Ciao') }} {{ Auth::user()->name }}, {{ __('qui puoi vedere un riepilogo di quanto hai fatto finora.') }}</p>
            </div>
            <div class="col-md-6">
                <div class="background-color1 p-4 text-center rounded-3 mb-3">
                    <h1 class="text-white mb-0">{{ $data['numero_investigatori'] }}</h1>
                    <div class="text-white">{{ $data['numero_investigatori'] === 1 ? __('Investigatori') : __('Investigatori') }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="background-color-danger p-4 text-center rounded-3 mb-3">
                    <h1 class="text-white mb-0">{{ $data['numero_branch'] }}</h1>
                    <div class="text-white">{{ $data['numero_branch'] === 1 ? __('Branch') : __('Branch') }}</div>
                </div>
            </div>
            {{-- <div class="col-md-3">
                <div class="background-color-warning p-4 text-center rounded-3 mb-3">
                    <h1 class="text-white mb-0">{{ $reports->where('status', 'read')->count()}}</h1>
                    <div class="text-white">{{ $reports->where('status', 'read')->count() === 1 ? __('In attesa di azione') : __('In attesa di azioni') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="background-color-success p-4 text-center rounded-3 mb-3">
                    <h1 class="text-white mb-0">{{ $reports->whereIn('status', ['under_review', 'investigation_ongoing', 'resolving'])->count() }}</h1>
                    <div class="text-white">{{ $reports->whereIn('status', ['under_review', 'investigation_ongoing', 'resolving'])->count() === 1 ? __('Azione in corso') : __('Azioni in corso') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="background-color2 p-4 text-center rounded-3 mb-3">
                    <h1 class="text-white mb-0">{{ $reports->where('status', 'submitted')->count()}}</h1>
                    <div class="text-white">{{ $reports->where('status', 'submitted')->count() === 1 ? __('Senza risposta') : __('Senza risposte') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="background-color4 p-4 text-center rounded-3 mb-3">
                    <h1 class="text-white mb-0">{{ $reports->whereIn('status', ['replied', 'user_replied'])->count() }}</h1>
                    <div class="text-white">{{ $reports->whereIn('status', ['replied', 'user_replied'])->count() === 1 ? __('In Comunicazione') : __('In Comunicazione') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="background-color-info p-4 text-center rounded-3 mb-3">
                    <h1 class="text-white mb-0">{{ $reports->whereIn('status', ['closed', 'canceled'])->count() }}</h1>
                    <div class="text-white">{{ $reports->whereIn('status', ['closed', 'canceled'])->count() === 1 ? __('Archiviato') : __('Archiviati') }}</div>
                </div>
            </div> --}}
        </div>
    @endif

</x-app-layout>
