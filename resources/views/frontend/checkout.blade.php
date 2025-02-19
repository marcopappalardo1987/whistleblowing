<x-app-frontend 
    title="Checkout - {{ config('app.name') }}"
    metaDescription="Completa il tuo ordine per i nostri servizi premium."
    ogTitle="Checkout - {{ config('app.name') }}"
    ogDescription="Completa il tuo ordine per i nostri servizi premium."
    ogImage="{{ asset('images/checkout-og.jpg') }}"  {{-- Se hai un'immagine specifica --}}
    canonicalUrl="{{ url()->current() }}"
>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="card-header background-color1 border-0">
                        <h4 class="text-white mb-0">{{__('Completa il tuo ordine')}}</h4>
                    </div>
                    <div class="card-body border-0">
                        <div class="mb-4 mt-2">
                            <h5>{{__('Riepilogo Ordine')}}</h5>
                            <div class="border rounded p-3">
                                <h6>{{ $product->name }}</h6>
                                <p class="text-muted mb-1">{{ $product->description }}</p>
                                <div class="d-flex align-items-center">
                                    <span>{{__('Prezzo:')}} </span>
                                    <strong class="ms-1">€{{ number_format($product->price, 2) }}/{{ $product->subscription_interval === 'day' ? 'giorno' : ($product->subscription_interval === 'week' ? 'settimana' : ($product->subscription_interval === 'month' ? 'mese' : 'anno')) }}</strong>
                                </div>
                            </div>
                        </div>

                        <form id="payment-form" action="{{ route('checkout.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            {{-- @if($product->has_variants)
                                <div class="mb-3">
                                    <label class="form-label">{{__('Seleziona Variante')}}</label>
                                    <select name="variant_id" class="form-select" required>
                                        @foreach($product->variants as $variant)
                                            <option value="{{ $variant->id }}">
                                                {{ $variant->name }} - €{{ number_format($variant->price, 2) }}/{{ $product->subscription_interval === 'day' ? 'giorno' : ($product->subscription_interval === 'week' ? 'settimana' : ($product->subscription_interval === 'month' ? 'mese' : 'anno')) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif --}}

                            <div class="mb-3">
                                <label class="form-label">{{__('Dettagli Carta')}}</label>
                                <div id="card-element" class="form-control"></div>
                                <div id="card-errors" class="invalid-feedback" style="display: none;"></div>
                            </div>

                            <button type="submit" class="btn btn-primary mb-3" id="submit-button">
                                {{__('Completa Abbonamento')}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ $stripeKey }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const cardErrors = document.getElementById('card-errors');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            submitButton.disabled = true;

            const { setupIntent, error } = await stripe.confirmCardSetup(
                '{{ $intent->client_secret }}',
                {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            email: '{{ Auth::user()->email }}'
                        }
                    }
                }
            );

            if (error) {
                cardErrors.textContent = error.message;
                cardErrors.style.display = 'block';
                submitButton.disabled = false;
            } else {
                const paymentMethodInput = document.createElement('input');
                paymentMethodInput.setAttribute('type', 'hidden');
                paymentMethodInput.setAttribute('name', 'payment_method');
                paymentMethodInput.setAttribute('value', setupIntent.payment_method);
                form.appendChild(paymentMethodInput);

                form.submit();
            }
        });
    </script>
    @endpush
</x-app-frontend>