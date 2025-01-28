<x-app-layout>
    
    <x-slot name="header">
        {{ __('Prodotti') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation.products')

    <div class="content-page mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-3">{{ __('Prodotti') }}</h3>

                        @if($products->isEmpty())
                            <p>{{ __('Nessun prodotto trovato.') }}</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Nome') }}</th>
                                            <th>{{ __('Prezzo') }}</th>
                                            <th>{{ __('Tipo') }}</th>
                                            <th>{{ __('Intervallo') }}</th>
                                            <th>{{ __('Credits') }}</th>
                                            <th>{{ __('Azioni') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->getFormattedPriceAttribute() }}</td>
                                                <td>{{ $product->type === 'subscription' ? __('Abbonamento') : __('Prodotto') }}</td>
                                                <td>{{ $product->subscription_interval ? match($product->subscription_interval) {
                                                    'day' => 'Giornaliero',
                                                    'week' => 'Settimanale', 
                                                    'month' => 'Mensile',
                                                    'year' => 'Annuale',
                                                    default => '-'
                                                } : '-' }}</td>
                                                <td>{{ $product->credits ? $product->credits : '-' }}</td>
                                                <td>
                                                    <div class="d-flex flex-wrap">
                                                        <a href="{{ route('product.edit', ['product' => $product->id]) }}" class="btn btn-link text-primary p-0 d-flex align-items-center me-3">
                                                            <x-gmdi-edit class="me-2" style="width: 1rem;" />{{ __('Modifica') }}
                                                        </a>
                                                        <a href="{{ route('product.delete', ['product' => $product->id]) }}" class="btn btn-link text-danger p-0 d-flex align-items-center">
                                                            <x-gmdi-delete class="me-2" style="width: 1rem;" />{{ __('Elimina') }}
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>