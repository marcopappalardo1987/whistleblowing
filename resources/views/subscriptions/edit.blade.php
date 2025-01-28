<x-app-layout>
    
    <x-slot name="header">
        {{ __('Gestisci Abbonamento') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation.subscriptions')

    <div class="card mt-4">
        <div class="card-body">
            <div class="content-page">
                <h3 class="h4 mb-4">Dettagli Sottoscrizione</h3>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> {{ $subscriptionData['id'] }}</p>
                        <p><strong>ID Stripe:</strong> {{ $subscriptionData['stripe_id'] }}</p>
                        <p><strong>Nome Utente:</strong> {{ $subscriptionData->user->name }}</p>
                        <p><strong>Email Utente:</strong> {{ $subscriptionData->user->email }}</p>
                        <p><strong>Abbonamento:</strong> {{ $subscriptionData->product->name }}</p>
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
                        <p><strong>Stato:</strong> <span class="badge {{ $statusClass }}">{{ ucfirst($subscriptionData['stripe_status']) }}</span></p>
                        <p><strong>Prezzo:</strong> {{ $subscriptionData->product->price }} {{ config('cashier.currency_symbol', '€') }}</p>
                        <p><strong>Scadenza Prova Gratuita:</strong> {{ $subscriptionData->trial_ends_at ? $subscriptionData->trial_ends_at->format('d/m/Y H:i') : '-' }}</p>
                        <p><strong>Data Attivazione:</strong> {{ $subscriptionData['created_at'] ? $subscriptionData['created_at']->format('d/m/Y H:i') : '-' }}</p>
                        <p><strong>Data di Scadenza:</strong> {{ $subscriptionData['ends_at'] ? $subscriptionData['ends_at']->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="content-page">
                <h3 class="h4 mb-4">Opzioni di Modifica Abbonamento</h3>
                <div class="row mb-3">
                    <form action="{{ route('subscriptions.update', ['id' => $subscriptionData['id']]) }}" method="POST">
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

                            <!-- <div id="swapFields" class="mb-3" style="display: none;">
                                <label for="new_plan" class="form-label">Nuovo Piano:</label>
                                <select id="new_plan" name="new_plan" class="form-select">
                                    <option value="basic">Piano Base</option>
                                    <option value="premium">Piano Premium</option>
                                    <option value="pro">Piano Pro</option>
                                </select>
                            </div> -->

                            <!-- <div id="paymentFields" class="mb-3" style="display: none;">
                                <label for="payment_method" class="form-label">Metodo di Pagamento:</label>
                                <input type="text" name="payment_method" id="payment_method" class="form-control" placeholder="ID del metodo di pagamento">
                            </div> -->
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
