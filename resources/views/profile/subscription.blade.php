<x-app-layout>
    
    <x-slot name="header">
        {{ __('Gestione Abbonamento') }}
    </x-slot>

    @include('layouts.alert-message')

    <div class="content-page py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="h4 mb-4">Dettaglio Abbonamento</h3>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-0"><strong>ID:</strong> {{ $subscriptionDetails['id'] }}</p>
                        <p class="mb-0"><strong>ID Stripe:</strong> {{ $subscriptionDetails['stripe_id'] }}</p>
                        <p class="mb-0"><strong>Nome Utente:</strong> {{ $subscriptionDetails['user_name'] }}</p>
                        <p class="mb-0"><strong>Email Utente:</strong> {{ $subscriptionDetails['user_email'] }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-0"><strong>Stato:</strong> <span class="badge {{ $subscriptionDetails['status'] === 'active' ? 'bg-success' : ($subscriptionDetails['status'] === 'trialing' ? 'bg-info' : 'bg-secondary') }}">{{ ucfirst($subscriptionDetails['status']) }}</span></p>
                        <p class="mb-0"><strong>Data Inizio:</strong> {{ \Carbon\Carbon::parse($subscriptionDetails['current_period_start'])->format('d/m/Y H:i') }}</p>
                        <p class="mb-0"><strong>Data Fine:</strong> {{ \Carbon\Carbon::parse($subscriptionDetails['current_period_end'])->format('d/m/Y H:i') }}</p>
                        <p class="mb-0"><strong>Data Creazione:</strong> {{ \Carbon\Carbon::parse($subscriptionDetails['created_at'])->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-page py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="h4 mb-4">Dettaglio Fatture</h3>
                <div class="mb-3">
                    <div class="row">
                        @foreach ($invoices as $invoice)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div><strong>Fattura ID:</strong> {{ $invoice->id }}</div>
                                        <div><strong>Data:</strong> {{ \Carbon\Carbon::parse($invoice->created)->format('d/m/Y H:i') }}</div>
                                        <div><strong>Importo:</strong> {{ number_format($invoice->amount_paid / 100, 2, ',', '.') . ' ' . config('cashier.currency_symbol', '€') }}</div>
                                        <div><strong>Stato:</strong> <span class="badge {{ $invoice->status === 'paid' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($invoice->status) }}</span></div>
                                        <a href="{{ $invoice->hosted_invoice_url }}" target="_blank" class="btn btn-dark mt-3">Visualizza Fattura</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-page">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="h4 mb-4">Opzioni di Modifica Abbonamento</h3>
                <div class="row mb-3">
                    <form action="{{ route('subscriptions.update', ['id' => $subscriptionDetails['id']]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                            <div class="form-group">
                                <label for="action" class="form-label">Azione:</label>
                                <select name="action" id="action" class="form-select" onchange="toggleFields()">
                                    <option value="">Seleziona un'azione</option>
                                    <option value="cancel">Annulla Abbonamento</option>
                                    <option value="resume">Riprendi Abbonamento</option>
                                    <!-- <option value="swap">Cambia Piano</option>
                                    <option value="update_payment">Modifica Metodo di Pagamento</option> -->
                                </select>
                            </div>

                            <div id="cancelFields" class="mb-3" style="display: none;">
                                <label for="cancelation_option" class="form-label">Opzione di Cancellazione:</label>
                                <select id="cancelation_option" name="cancelation_option" class="form-select">
                                    <option value="end_of_billing_cycle">Cancellazione a Fine Ciclo di Fatturazione</option>
                                    <option value="immediate">Cancellazione Immediata</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Esegui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleFields() {
            const action = document.getElementById('action').value;
            document.getElementById('cancelFields').style.display = action === 'cancel' ? 'block' : 'none';
            document.getElementById('swapFields').style.display = action === 'swap' ? 'block' : 'none';
            document.getElementById('paymentFields').style.display = action === 'update_payment' ? 'block' : 'none';
        }
    </script>
    @endpush

</x-app-layout>
