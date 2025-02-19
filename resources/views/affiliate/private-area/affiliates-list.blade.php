<x-app-layout>
    
    <x-slot name="header">
        {{ __('I miei Affiliati') }}
    </x-slot>

    @include('layouts.alert-message')

    
    <div class="content-page mt-4">
        <div class="row">
            <div class="col-12">
                @if($affiliates->isEmpty())
                    <div class="alert alert-info">
                        {{ __('Non ancora hai affiliati. Dopo aver generato un nuovo affiliato, potrai visualizzare la lista dei tuoi affiliati qui.') }}
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Data di Registrazione</th>
                                    </tr>
                                </thead>
                            <tbody>
                                @foreach($affiliates as $affiliate)
                                    <tr>
                                        <td>{{ $affiliate->name }}</td>
                                        <td>{{ $affiliate->email }}</td>
                                        <td>{{ $affiliate->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>    
                            </table>
                        </div>
                    </div>
                @endif
                
                <!-- Aggiungi i controlli di paginazione -->
                <div class="d-flex justify-content-center">
                        {{ $affiliates->links() }}
                    </div>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>