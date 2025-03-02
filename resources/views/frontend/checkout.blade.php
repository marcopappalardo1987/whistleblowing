@extends('layouts.app-frontend')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="shadow border border-white rounded-3 overflow-hidden">
                    <div class="bg-color3 border-0 p-3">
                        <h4 class="text-white mb-0">{{__('Completa il tuo ordine')}}</h4>
                    </div>
                    <div class="border-0 p-4">
                        <div class="mb-4 mt-2">
                            <h5>{{__('Riepilogo Ordine')}}</h5>
                            <div class="rounded bg-colorWhite p-3">
                                <h6 class="color1">{{ __($product->name) }}</h6>
                                <p class="color1 mb-3">{{ __($product->description) }}</p>
                                <div class="d-flex align-items-center color1">
                                    <span>{{__('Prezzo:')}} </span>
                                    <strong class="ms-1">€{{ number_format($product->price, 2) }}/{{ $product->subscription_interval === 'day' ? 'giorno' : ($product->subscription_interval === 'week' ? 'settimana' : ($product->subscription_interval === 'month' ? 'mese' : 'anno')) }}</strong>
                                </div>
                            </div>
                        </div>

                        <form id="payment-form" action="{{ route(app()->getLocale().'.checkout.process', ['locale' => app()->getLocale()]) }}" method="POST">
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

                            <button type="submit" class="btn btn-white mb-3" id="submit-button">
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
    
@endsection