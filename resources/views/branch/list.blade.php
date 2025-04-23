<x-app-layout>
    
    <x-slot name="header">
        {{ __('Elenco Branch') }}
    </x-slot>

<!-- Start Generation Here -->
<div class="content-page">
    <div class="row">
        <div class="col-12">
            @include('layouts.alert-message')
        </div>
        <div class="col-12">
            @if($branches->count() > 0 && $maxBranches > 0)
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Nome') }}</th>
                                    <th>{{ __('Azioni') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($branches as $branch)
                                <tr>
                                    <td>{{ $branch->name }}</td>
                                    <td>
                                        <a href="{{ route('branch.edit', $branch->id) }}" class="btn btn-primary">
                                            {{ __('Modifica') }}
                                        </a>
                                        <a href="{{ route('branch.delete', $branch->id) }}" class="btn btn-danger">
                                            {{ __('Elimina') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif($maxBranches == 0)
                <div class="alert alert-danger">
                    <p>{{ __('Il tuo piano non consente di creare branch.') }}</p>
                    <a href="{{ route(app()->getLocale() . '.plans', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">{{ __('Vedi i piani disponibili') }}</a>
                </div>
            @else
                <div class="alert alert-danger">
                    <p>{{ __('Non hai ancora aggiunto nessun branch.') }}</p>
                    <a href="{{ route('branch.add') }}" class="btn btn-primary">{{ __('Aggiungi il tuo primo Branch') }}</a>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- End Generation Here -->    
    
</x-app-layout>
