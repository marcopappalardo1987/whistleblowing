<x-app-layout>
    
    <x-slot name="header">
        {{ __('Aggiungi Prodotto') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation.products')

    <div class="content-page mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-3">{{__('Aggiungi Prodotto')}}</h3>

                        <form action="{{ route('product.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <x-input-label for="name" :value="__('Nome')" />
                                <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="invalid-feedback" />
                            </div>

                            <div class="mb-3">
                                <x-input-label for="description" :value="__('Descrizione')" />
                                <x-textarea-input id="description" name="description" class="form-control" rows="5" required :value="old('description')" />
                                <x-input-error :messages="$errors->get('description')" class="invalid-feedback" />
                            </div>

                            <div class="mb-3">
                                <x-input-label for="price" :value="__('Prezzo in €')" />
                                <x-text-input id="price" class="form-control" type="number" step="0.01" name="price" :value="old('price')" />
                                <x-input-error :messages="$errors->get('price')" class="invalid-feedback" />
                            </div>

                            <div class="mb-3">
                                <x-input-label for="type" :value="__('Tipo di Pagamento')" />
                                <x-select-input id="type" name="type" :options="['subscription' => __('Abbonamento'), 'one_time' => __('Una Tantum')]" :selected="old('type')" class="form-select" onchange="toggleSubscriptionInterval()" />
                            </div>

                            <div class="mb-3" id="subscription_interval_div" style="display: none;">
                                <x-input-label for="subscription_interval" :value="__('Intervallo di Abbonamento')" />
                                <x-select-input id="subscription_interval" name="subscription_interval" :options="['' => __('Nessuno'), 'day' => __('Giornaliero'), 'week' => __('Settimanale'), 'month' => __('Mensile'), 'year' => __('Annuale')]" :selected="old('subscription_interval')" class="form-select" />
                            </div>

                            <div class="mb-3" id="features_section">
                                <h4 class="fw-bold mb-3">{{__('Caratteristiche')}}</h4>
                                <div id="features_container">
                                    <!-- Caratteristiche dinamiche verranno aggiunte qui -->
                                </div>
                                <button type="button" class="btn btn-success mt-2" onclick="addFeature()">{{__('Aggiungi Caratteristica')}}</button>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">{{__('Crea Prodotto')}}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSubscriptionInterval() {
            const type = document.getElementById('type').value;
            const subscriptionIntervalDiv = document.getElementById('subscription_interval_div');
            const subscriptionIntervalSelect = document.getElementById('subscription_interval');
            
            if (type === 'subscription') {
                subscriptionIntervalDiv.style.display = 'block';
                if (!subscriptionIntervalSelect.value) {
                    subscriptionIntervalSelect.value = 'month';
                }
            } else {
                subscriptionIntervalDiv.style.display = 'none';
                subscriptionIntervalSelect.value = '';
            }
        }

        function addFeature() {
            const container = document.getElementById('features_container');
            const featureIndex = container.children.length;
            const featureDiv = document.createElement('div');
            featureDiv.classList.add('mb-2', 'p-2', 'border', 'rounded');
            
            featureDiv.innerHTML = `
                <div class="row align-items-center">
                    <div class="col">
                        <input type="text" 
                               name="features[${featureIndex}][name]" 
                               placeholder="{{__('Nome Caratteristica')}}" 
                               class="form-control" 
                               required />
                    </div>
                    <div class="col-auto">
                        <button type="button" 
                                onclick="removeFeature(this)" 
                                class="btn btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(featureDiv);
        }

        function removeFeature(button) {
            button.closest('.mb-2').remove();
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleSubscriptionInterval();
        });
    </script>
</x-app-layout>