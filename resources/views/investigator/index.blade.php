<x-app-layout>
    
    <x-slot name="header">
        <div class="container"> 
            {{ __('Bentornato') }} 
        </div>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('layouts.alert-message')  
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>{{ __('Ciao') }} {{ $investigator->name }}, {{ __('qui puoi vedere un riepilogo dei report che hai ricevuto.') }}</p>
            </div>
            <div class="col-md-3">
                <div class="background-color1 p-4 text-center rounded-3 mb-3">
                    <h1 class="text-white mb-0">{{ $reports->count() }}</h1>
                    <div class="text-white">{{ $reports->count() === 1 ? __('Totale') : __('Totali') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="background-color-danger p-4 text-center rounded-3 mb-3">
                    <h1 class="text-white mb-0">{{ $reports->whereIn('status', ['waiting_for_information', 'expiring', 'expired', 'reopened'])->count() }}</h1>
                    <div class="text-white">{{ $reports->whereIn('status', ['waiting_for_information', 'expiring', 'expired', 'reopened'])->count() === 1 ? __('Stato particolare') : __('Stati Particolari') }}</div>
                </div>
            </div>
            <div class="col-md-3">
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
            </div>
        </div>
    </div>

     
</x-app-layout>
