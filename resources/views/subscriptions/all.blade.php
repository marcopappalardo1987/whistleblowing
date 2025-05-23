<x-app-layout>
    
    <x-slot name="header">
        {{ __('Abbonamenti') }}
    </x-slot>

    @include('layouts.alert-message')

    <!-- @include('layouts.navigation.subscriptions') -->

    <div class="card mt-4">
        <div class="card-body">
            <div class="content-page">

                {{-- Filtri --}}
                <form action="{{ route('subscriptions.all') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="per_page" class="form-label">{{ __('Mostra:') }}</label>
                            <select name="per_page" id="per_page" class="form-select">
                                @foreach($perPageOptions as $option)
                                    <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>
                                        {{ $option }} {{ __('elementi') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="status" class="form-label">{{ __('Stato') }}:</label>
                            <select name="status" id="status" class="form-select">
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ $currentStatus == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="user_email" class="form-label">{{ __('Email') }}:</label>
                            <input type="text" name="user_email" id="user_email" class="form-control" 
                                   value="{{ $currentEmail }}" placeholder="{{ __('Filtra per email') }}">
                        </div>

                        <div class="col-md-2">
                            <label for="user_name" class="form-label">{{ __('Nome utente') }}:</label>
                            <input type="text" name="user_name" id="user_name" class="form-control" 
                                   value="{{ $currentName }}" placeholder="{{ __('Filtra per nome') }}">
                        </div>

                        <div class="col-md-2">
                            <label for="subscription_name" class="form-label">{{ __('Abbonamento') }}:</label>
                            <input type="text" name="subscription_name" id="subscription_name" class="form-control" 
                                   value="{{ $currentSubscriptionName }}" placeholder="{{ __('Filtra per abbonamento') }}">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">{{ __('Filtra') }}</button>
                            <a href="{{ route('subscriptions.all') }}" class="btn btn-secondary">{{ __('Reset') }}</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Nome Utente') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Abbonamento') }}</th>
                                <th>{{ __('Stato') }}</th>
                                <th>{{ __('Prezzo') }}</th>
                                <th>{{ __('Data Attivazione') }}</th>
                                <th>{{ __('Azioni') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscriptions as $subscription) 
                            <tr>
                                <td>{{ $subscription['user_name'] }}</td>
                                <td>{{ $subscription['user_email'] }}</td>
                                <td>{{ $subscription['subscription_name'] }}</td>
                                <td>
                                    @php
                                        $statusClass = match($subscription['stripe_status']) {
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
                                    <span class="badge {{ $statusClass }}">{{ $subscription['stripe_status'] }}</span>
                                </td>
                                <td>{{ $subscription['amount'] }}</td>
                                <td>{{ $subscription['created_at'] ? $subscription['created_at']->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('subscriptions.view', ['id' => $subscription['id']]) }}" class="btn btn-sm btn-info">{{ __('Visualizza') }}</a>
                                    <a href="{{ route('subscriptions.edit', ['id' => $subscription['id']]) }}" class="btn btn-sm btn-primary">{{ __('Modifica') }}</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-12">
                        {{ $listContent->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // Opzionale: submit automatico del form quando cambia la select
        document.getElementById('per_page').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
    @endpush
</x-app-layout>
