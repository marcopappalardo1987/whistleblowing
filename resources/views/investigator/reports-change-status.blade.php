<x-app-layout>
    
    <x-slot name="header">
        <div class="container"> 
            {{ __('Cambia lo stato della segnalazione') }}<br>
            <h6>{{ __('id: ' . $report->id) }}</h6>
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
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h5>{{ __('Cambia lo stato della segnalazione') }}</h5>
                        <p>{{ __('Qui puoi cambiare lo stato della segnalazione. Seleziona lo stato che preferisci.') }}</p>
                        <form method="POST" action="{{ route('investigator.report.status-update', ['id' => $report->id]) }}">
                            @csrf
                        <div class="mb-3">
                            <label for="report_status" class="form-label">{{ __('Stato della segnalazione') }}</label>
                            <select class="form-select" name="report_status" id="report_status">
                                {{-- <option value="submitted" {{ $report->status == 'submitted' ? 'selected' : '' }}>📥 {{__('Segnalazione Inviata')}}</option> --}}
                                <option value="read" {{ $report->status == 'read' ? 'selected' : '' }}>📖 {{__('Letto')}}</option>
                                <option value="replied" {{ $report->status == 'replied' ? 'selected' : '' }}>🔄 {{__('Risposto')}}</option>
                                <option value="under_review" {{ $report->status == 'under_review' ? 'selected' : '' }}>🧐 {{__('In Revisione')}}</option>
                                <option value="investigation_ongoing" {{ $report->status == 'investigation_ongoing' ? 'selected' : '' }}>🔍 {{__('In Investigazione')}}</option>
                                <option value="resolving" {{ $report->status == 'resolving' ? 'selected' : '' }}>✅ {{__('In Risoluzione')}}</option>
                                <option value="closed" {{ $report->status == 'closed' ? 'selected' : '' }}>🔒 {{__('Chiuso')}}</option>
                                
                                <option value="waiting_for_information" {{ $report->status == 'waiting_for_information' ? 'selected' : '' }}>⏸️ {{__('In Pausa')}}</option>
                                <option value="reopened" {{ $report->status == 'reopened' ? 'selected' : '' }}>↩️ {{__('Riaperto')}}</option>
                                <option value="expired" {{ $report->status == 'expired' ? 'selected' : '' }}>🕒 {{__('Scaduto')}}</option>
                                <option value="canceled" {{ $report->status == 'canceled' ? 'selected' : '' }}>❌ {{__('Annullato')}}</option>
                            
                                <option value="expiring" {{ $report->status == 'expiring' ? 'selected' : '' }}>⌛ {{__('In Scadenza')}}</option>
                            
                                <option value="user_replied" {{ $report->status == 'user_replied' ? 'selected' : '' }}>🔄 {{__('Risposta del Whistleblower')}}</option>
                            </select>
                        </div>
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-primary me-md-2">{{ __('Cambia stato') }}</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">{{ __('Non voglio cambiare lo stato') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

     
</x-app-layout>
