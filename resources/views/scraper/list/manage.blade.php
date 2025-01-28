<x-app-layout>
    <x-slot name="header">
        {{ __('Gestisci Liste') }}
    </x-slot>

    @include('layouts.alert-message')  

    @include('layouts.navigation-scraper-lists')

    <div class="content-page mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="fw-semibold mb-3">{{ __('Liste Create') }}</h3>
                        
                        @if($lists->isEmpty())
                            <p class="text-muted">{{ __('Nessuna lista trovata.') }}</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Nome') }}</th>
                                            <th>{{ __('Azioni') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lists as $list)
                                            <tr>
                                                <td>{{ $list->getAttributes()['list_name'] }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @can('view ownerdata')
                                                            <a href="{{ route('scraper.list.content.view', ['list_id' => $list->getAttributes()['id']]) }}" 
                                                               class="btn btn-outline-secondary btn-sm">
                                                                <i class="bi bi-eye-fill me-1"></i>
                                                                {{ __('Visualizza') }}
                                                            </a>
                                                        @endcan

                                                        @can('publish ownerdata')
                                                            <a href="{{ route('scraper.list.content.add', ['list_id' => $list->getAttributes()['id']]) }}" 
                                                               class="btn btn-outline-success btn-sm">
                                                                <i class="bi bi-plus-circle-fill me-1"></i>
                                                                {{ __('Aggiungi alla lista') }}
                                                            </a>
                                                        @endcan

                                                        @can('edit ownerdata')
                                                            <a href="{{ route('scraper.list.edit', ['list_id' => $list->id]) }}" 
                                                               class="btn btn-outline-primary btn-sm">
                                                                <i class="bi bi-pencil-fill me-1"></i>
                                                                {{ __('Rinomina') }}
                                                            </a>
                                                        @endcan 

                                                        @can('remove ownerdata')
                                                            <a href="{{ route('scraper.list.delete', ['list_id' => $list->id]) }}" 
                                                               class="btn btn-outline-danger btn-sm">
                                                                <i class="bi bi-trash-fill me-1"></i>
                                                                {{ __('Elimina') }}
                                                            </a>
                                                        @endcan
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
