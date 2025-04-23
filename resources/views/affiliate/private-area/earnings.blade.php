<x-app-layout>
    
    <x-slot name="header">
        {{ __('Guadagni Affiliato') }}
    </x-slot>

    @include('layouts.alert-message')

    
    <div class="content-page">
        <div class="row">
            <div class="col-4">    
                <div class="total-earnings-col" role="alert">
                    <span class="total-earnings-label">{{ __('Totale Guadagni') }}: </span>
                    <span class="total-earnings-value">{{ $totalEarnings }} €</span>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('affiliate.private-area.earnings') }}" class="mb-3">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <x-input-label for="filter_user" value="{{ __('Filtra per utente') }}" />
                                    <x-text-input id="filter_user" name="filter_user" value="{{ request('filter_user') }}" />
                                </div>
                                <div class="col-md-4">
                                    <x-input-label for="start_date" value="{{ __('Data Inizio') }}" />
                                    <x-text-input id="start_date" name="start_date" type="date" value="{{ request('start_date', now()->startOfMonth()->toDateString()) }}" />
                                </div>
                                <div class="col-md-4">
                                    <x-input-label for="end_date" value="{{ __('Data Fine') }}" />
                                    <x-text-input id="end_date" name="end_date" type="date" value="{{ request('end_date', now()->endOfMonth()->toDateString()) }}" />
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Filtra') }}</button>
                        </form>

                        @if($earnings->isEmpty())
                            <div class="alert alert-warning" role="alert">
                                {{ __('Nessun risultato trovato per i criteri di ricerca selezionati.') }}
                            </div>
                        @else
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('Nome Utente') }}</th>
                                        <th><a href="{{ route('affiliate.private-area.earnings', ['sort_field' => 'commission', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">{{ __('Commissione') }}</a></th>
                                        <th><a href="{{ route('affiliate.private-area.earnings', ['sort_field' => 'created_at', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">{{ __('Data') }}</a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($earnings as $earning)
                                        <tr>
                                            <td>{{ $earning->user->name }}</td>
                                            <td>{{ $earning->commission }}</td>
                                            <td>{{ $earning->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-center">
                                {{ $earnings->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>