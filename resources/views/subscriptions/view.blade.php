<x-app-layout>
    
    <x-slot name="header">
        {{ __('Abbonamento') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation.subscriptions')

    <div class="content-page py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="h4 mb-4">{{ __('Dettaglio Abbonamento') }}</h3>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-0"><strong>{{ __('ID') }}:</strong> {{ $subscriptionDetails['id'] }}</p>
                        <p class="mb-0"><strong>{{ __('ID Stripe') }}:</strong> {{ $subscriptionDetails['stripe_id'] }}</p>
                        <p class="mb-0"><strong>{{ __('Nome Utente') }}:</strong> {{ $subscriptionDetails['user_name'] }}</p>
                        <p class="mb-0"><strong>{{ __('Email Utente') }}:</strong> {{ $subscriptionDetails['user_email'] }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-0"><strong>{{ __('Stato') }}:</strong> <span class="badge {{ $subscriptionDetails['status'] === 'active' ? 'bg-success' : ($subscriptionDetails['status'] === 'trialing' ? 'bg-info' : 'bg-secondary') }}">{{ ucfirst($subscriptionDetails['status']) }}</span></p>
                        <p class="mb-0"><strong>{{ __('Data Inizio') }}:</strong> {{ \Carbon\Carbon::parse($subscriptionDetails['current_period_start'])->format('d/m/Y H:i') }}</p>
                        <p class="mb-0"><strong>{{ __('Data Fine') }}:</strong> {{ \Carbon\Carbon::parse($subscriptionDetails['current_period_end'])->format('d/m/Y H:i') }}</p>
                        <p class="mb-0"><strong>{{ __('Data Creazione') }}:</strong> {{ \Carbon\Carbon::parse($subscriptionDetails['created_at'])->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-page py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="h4 mb-4">{{ __('Dettaglio Fatture') }}</h3>
                <div class="mb-3">
                    <div class="row">
                        @foreach ($invoices as $invoice)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div><strong>{{ __('Fattura ID') }}:</strong> {{ $invoice->id }}</div>
                                        <div><strong>{{ __('Data') }}:</strong> {{ \Carbon\Carbon::parse($invoice->created)->format('d/m/Y H:i') }}</div>
                                        <div><strong>{{ __('Importo') }}:</strong> {{ number_format($invoice->amount_paid / 100, 2, ',', '.') . ' ' . config('cashier.currency_symbol', '€') }}</div>
                                        <div><strong>{{ __('Stato') }}:</strong> <span class="badge {{ $invoice->status === 'paid' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($invoice->status) }}</span></div>
                                        <a href="{{ $invoice->hosted_invoice_url }}" target="_blank" class="btn btn-dark mt-3">{{ __('Visualizza Fattura') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>