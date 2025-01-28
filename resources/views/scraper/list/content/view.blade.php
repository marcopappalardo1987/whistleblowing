<x-app-layout>
    <x-slot name="header">
        {{ __('Visualizza Lista') }}
    </x-slot>

    @include('layouts.alert-message')
    @include('layouts.navigation-scraper-lists')

    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="h4 mb-0">{{ $list->list_name }}</h3>
                <div class="d-flex gap-2">
                    <button type="button" id="select-all" class="btn btn-dark">
                        {{ __('Seleziona Tutto') }}
                    </button>
                    <a href="{{ route('scraper.list.content.add', ['list_id' => $list->id]) }}" class="btn btn-dark">
                        {{ __('Aggiungi Contenuto') }}
                    </a>
                    <a href="{{ route('scraper.list.content.exportCsv', ['list_id' => $list->id]) }}" class="btn btn-dark">
                        <x-fas-download class="me-1" style="width: 1rem"/> {{ __('CSV') }}
                    </a>
                </div>
            </div>
            <div class="mb-3">
                <span class="badge bg-light text-dark">{{ $numberOfContent . ' ' . __('Contatti') }}</span>
            </div>

            <div class="container-fluid px-0">
                <form action="{{ route('scraper.list.content.bulkActions') }}" method="POST">
                    @csrf
                    <input type="hidden" name="list_id" value="{{ $list->id }}">
                    @foreach($listContent as $content)
                        <div class="row mb-2 py-2 align-items-center company-data">
                            <div class="col-12 col-sm-3">
                                <input type="checkbox" name="selected_ids[]" value="{{ $content->id }}" class="me-1">
                                {{ $content->company }}
                            </div>
                            <div class="col-12 col-sm-3">
                                <input type="text" name="domains[{{$content->id}}]" value="{{ $content->domain }}" readonly class="form-control-plaintext p-0">
                            </div>
                            <div class="col-12 col-sm-3">
                                {{ $content->city }}
                            </div>
                            <div class="col-12 col-sm-3">
                                @foreach($content->email as $email)
                                    {{ $email }}<br>
                                @endforeach
                            </div>
                            <div class="col-12 actions-container d-none">
                                <div class="d-flex gap-3 ms-3">
                                    <a href="{{ route('scraper.list.content.edit', ['contentId' => $content->id]) }}" class="text-primary text-decoration-none">
                                        <x-gmdi-edit class="me-1" style="width: 1rem"/>
                                        {{ __('Modifica') }}
                                    </a>
                                    
                                    <a href="{{ route('scraper.list.content.delete', ['contentId' => $content->id]) }}" class="text-danger text-decoration-none" onclick="return confirm('{{ __('Sei sicuro di voler eliminare questo contenuto?') }}');">
                                        <x-gmdi-delete class="me-1" style="width: 1rem"/>
                                        {{ __('Elimina') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="row mb-3">
                        <div class="col-12">
                            <select name="action" class="form-select">
                                <option value="">{{ __('Seleziona azione') }}</option>
                                <option value="delete_all">{{ __('Elimina selezionati') }}</option>
                                <option value="scan_emails">{{ __('Scansiona email') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12 col-sm-3">
                            <button type="button" id="confirm-submit" class="btn btn-dark" message="{{ __('Sei sicuro di voler procedere con questa azione?') }}" message-at-least-one="{{ __('Seleziona almeno un elemento.') }}">
                                {{ __('Procedi') }}
                            </button>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-12">
                        {{ $listContent->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>