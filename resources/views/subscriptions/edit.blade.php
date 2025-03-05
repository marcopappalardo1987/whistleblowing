<x-app-layout>
    
    <x-slot name="header">
        {{ __('Gestisci Abbonamento') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation.subscriptions')

    <div class="card mt-4">
        <div class="card-body">
            <div class="content-page">
                <h3 class="h4 mb-4">{{ __('Dettagli Sottoscrizione') }}</h3>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>{{ __('ID') }}:</strong> {{ $subscriptionData['id'] }}</p>
                        <p><strong>{{ __('ID Stripe') }}:</strong> {{ $subscriptionData['stripe_id'] }}</p>
                        <p><strong>{{ __('Nome Utente') }}:</strong> {{ $subscriptionData->user->name }}</p>
                        <p><strong>{{ __('Email Utente') }}:</strong> {{ $subscriptionData->user->email }}</p>
                        <p><strong>{{ __('Abbonamento') }}:</strong> {{ $subscriptionData->product->name }}</p>
                    </div>
                    <div class="col-md-6">
                        @php
                            $statusClass = match($subscriptionData['stripe_status']) {
                                'active' => 'bg-success',
                                'trialing' => 'bg-info',
                                'past_due' => 'bg-warning',
                                'canceled' => 'bg-danger',
                                'unpaid' => 'bg-danger',
                                'incomplete' => 'bg-warning',
                                'incomplete_expired' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <p><strong>{{ __('Stato') }}:</strong> <span class="badge {{ $statusClass }}">{{ ucfirst($subscriptionData['stripe_status']) }}</span></p>
                        <p><strong>{{ __('Prezzo') }}:</strong> {{ $subscriptionData->product->price }} {{ config('cashier.currency_symbol', '€') }}</p>
                        <p><strong>{{ __('Scadenza Prova Gratuita') }}:</strong> {{ $subscriptionData->trial_ends_at ? $subscriptionData->trial_ends_at->format('d/m/Y H:i') : '-' }}</p>
                        <p><strong>{{ __('Data Attivazione') }}:</strong> {{ $subscriptionData['created_at'] ? $subscriptionData['created_at']->format('d/m/Y H:i') : '-' }}</p>
                        <p><strong>{{ __('Data di Scadenza') }}:</strong> {{ $subscriptionData['ends_at'] ? $subscriptionData['ends_at']->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="content-page">
                <h3 class="h4 mb-4">{{ __('Opzioni di Modifica Abbonamento') }}</h3>
                <div class="row mb-3">
                    <form action="{{ route('subscriptions.update', ['id' => $subscriptionData['id']]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                            <div class="form-group">
                                <label for="action" class="form-label">{{ __('Azione') }}:</label>
                                <select name="action" id="action" class="form-select" onchange="toggleFields()">
                                    <option value="">{{ __('Seleziona un\'azione') }}</option>
                                    <option value="cancel">{{ __('Annulla Abbonamento') }}</option>
                                    <option value="resume">{{ __('Riprendi Abbonamento') }}</option>
                                    <!-- <option value="swap">{{ __('Cambia Piano') }}</option>
                                    <option value="update_payment">{{ __('Modifica Metodo di Pagamento') }}</option> -->
                                </select>
                            </div>

                            <div id="cancelFields" class="mb-3" style="display: none;">
                                <label for="cancelation_option" class="form-label">{{ __('Opzione di Cancellazione') }}:</label>
                                <select id="cancelation_option" name="cancelation_option" class="form-select">
                                    <option value="end_of_billing_cycle">{{ __('Cancellazione a Fine Ciclo di Fatturazione') }}</option>
                                    <option value="immediate">{{ __('Cancellazione Immediata') }}</option>
                                </select>
                            </div>

                            <!-- <div id="swapFields" class="mb-3" style="display: none;">
                                <label for="new_plan" class="form-label">{{ __('Nuovo Piano') }}:</label>
                                <select id="new_plan" name="new_plan" class="form-select">
                                    <option value="basic">{{ __('Piano Base') }}</option>
                                    <option value="premium">{{ __('Piano Premium') }}</option>
                                    <option value="pro">{{ __('Piano Pro') }}</option>
                                </select>
                            </div> -->

                            <!-- <div id="paymentFields" class="mb-3" style="display: none;">
                                <label for="payment_method" class="form-label">{{ __('Metodo di Pagamento') }}:</label>
                                <input type="text" name="payment_method" id="payment_method" class="form-control" placeholder="{{ __('ID del metodo di pagamento') }}">
                            </div> -->
                            <button type="submit" class="btn btn-primary mt-3">{{ __('Esegui') }}</button>
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
