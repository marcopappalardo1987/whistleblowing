<x-app-frontend 
    title="Piani e Prezzi - {{ config('app.name') }}"
    metaDescription="Scopri i nostri piani e tariffe per i servizi offerti. Scegli il piano più adatto alle tue esigenze."
    ogTitle="Piani e Prezzi - {{ config('app.name') }}"
    ogDescription="Confronta i nostri piani e trova quello perfetto per te. Prezzi competitivi e servizi di qualità."
    ogImage="{{ asset('images/plans-og.jpg') }}"
    canonicalUrl="{{ url()->current() }}"
>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4 mb-3">{{__('I Nostri Piani')}}</h1>
            <p class="lead text-muted">{{__('Scegli il piano più adatto alle tue esigenze')}}</p>
        </div>

        <div class="row row-cols-1 row-cols-md-3 mb-3 text-center justify-content-center">
            @foreach ($products as $product)
                <div class="col">
                    <div class="card mb-4 rounded-3 shadow-sm border-0 {{ $loop->iteration === 2 ? 'border-primary' : '' }}">
                        <div class="card-header py-3 background-color1 border-0 {{ $loop->iteration === 2 ? 'text-bg-primary border-primary' : '' }}">
                            <h4 class="my-0 fw-normal text-white">{{ $product->name }}</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title pricing-card-title">
                                €{{ number_format($product->price, 2) }}
                                <small class="text-body-secondary fw-light">/{{ $product->subscription_interval === 'day' ? 'giorno' : ($product->subscription_interval === 'week' ? 'settimana' : ($product->subscription_interval === 'month' ? 'mese' : 'anno')) }}</small>
                            </h1>
                            
                            @if($product->description)
                                <p class="text-muted mt-3">{{ $product->description }}</p>
                            @endif

                            <ul class="list-unstyled mt-3 mb-4">
                                @foreach ($product->features as $feature)
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        {{ $feature->name }}
                                    </li>
                                @endforeach
                            </ul>

                            @auth
                                @if($product->has_variants)
                                    <button type="button" 
                                            class="w-100 btn btn-lg {{ $loop->iteration === 2 ? 'btn-primary' : 'btn-outline-primary' }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#variantsModal{{ $product->id }}">
                                        Scegli Piano
                                    </button>
                                @else
                                    <form action="{{ route('checkout') }}" method="GET">
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" 
                                                class="w-100 btn btn-lg {{ $loop->iteration === 2 ? 'btn-primary' : 'btn-outline-primary' }}">
                                            Abbonati Ora
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" 
                                   class="w-100 btn btn-lg {{ $loop->iteration === 2 ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Accedi per Abbonarti
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                @if($product->has_variants)
                    <!-- Modal per le varianti -->
                    <div class="modal fade" id="variantsModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Scegli la Variante - {{ $product->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="list-group">
                                        @foreach($product->variants as $variant)
                                            <form action="{{ route('checkout') }}" method="GET" class="mb-2">
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                                                <button type="submit" class="list-group-item list-group-item-action">
                                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                                        <h6 class="mb-1">{{ $variant->name }}</h6>
                                                        <strong>€{{ number_format($variant->price, 2) }}/{{ $product->subscription_interval === 'day' ? 'giorno' : ($product->subscription_interval === 'week' ? 'settimana' : ($product->subscription_interval === 'month' ? 'mese' : 'anno')) }}</strong>
                                                    </div>
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Sezione FAQ o caratteristiche aggiuntive -->
        {{-- <div class="row mt-5">
            <div class="col-12 text-center">
                <h2 class="mb-4">Domande Frequenti</h2>
            </div>
            <div class="col-md-6">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Posso cambiare piano in qualsiasi momento?
                            </button>
                        </h3>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Sì, puoi aggiornare o declassare il tuo piano in qualsiasi momento. Le modifiche verranno applicate al successivo periodo di fatturazione.
                            </div>
                        </div>
                    </div>
                    <!-- Aggiungi altre FAQ qui -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="accordion" id="faqAccordion2">
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Come funziona il periodo di prova?
                            </button>
                        </h3>
                        <div id="faq2" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion2">
                            <div class="accordion-body">
                                Offriamo un periodo di prova di 14 giorni per tutti i piani. Puoi annullare in qualsiasi momento durante questo periodo senza alcun addebito.
                            </div>
                        </div>
                    </div>
                    <!-- Aggiungi altre FAQ qui -->
                </div>
            </div>
        </div> --}}
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .pricing-card-title {
            font-size: 2.5rem;
        }
        .card {
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .list-group-item button {
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            padding: 0;
        }
    </style>
    @endpush
</x-app-frontend>
