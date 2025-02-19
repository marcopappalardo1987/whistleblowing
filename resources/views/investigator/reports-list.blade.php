<x-app-layout>
    
    <x-slot name="header">
        <div class="container"> 
            {{ __('Riepilogo Segnalazioni') }} 
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
            <div class="col-12">
                @if($reports->isEmpty())
                    <div class="alert alert-info">
                        {{ __('Non ci sono nuove segnalazioni da visualizzare.') }}
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('Stato') }}</th>
                                            <th>{{ __('Data') }}</th>
                                            <th>{{ _('Aggiornamento')}}</th>
                                            <th>{{ __('Azioni') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports as $report)
                                            <tr>
                                                <td>{{ $report->id }}</td>
                                                <td>
                                                    <span class="badge bg-warning">
                                                        {{ ucfirst($report->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $report->updated_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('investigator.report.view', ['id' => $report->id]) }}" 
                                                        class="btn btn-primary btn-sm align-items-center">
                                                        <x-heroicon-o-eye class="me-1" style="width: 20px; height: 20px;" />
                                                        {{ __('Visualizza') }}
                                                    </a>
                                                    <a href="{{ route('investigator.report.change-status', ['id' => $report->id]) }}"
                                                        class="btn btn-secondary btn-sm align-items-center ms-2">
                                                        <x-heroicon-o-arrow-path class="me-1" style="width: 20px; height: 20px;" />
                                                        {{ __('Cambia Stato') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

     
</x-app-layout>
