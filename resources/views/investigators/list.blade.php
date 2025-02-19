<x-app-layout>
    
    <x-slot name="header">
        {{ __('Investigatori') }}
    </x-slot>

    @include('layouts.alert-message')  
    <div class="container-fluid">
        <div class="row">
            @if($investigators->count())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('Nome') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Branch') }}</th> 
                            <th>{{ __('Stato') }}</th>
                            <th>{{ __('Ultimo accesso') }}</th>
                            <th>{{ __('Azioni') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($investigators as $investigator)
                            <tr class="align-middle">
                                <td>{{ $investigator->name }}</td>
                                <td>{{ $investigator->email }}</td>
                                <td>{{ $investigator->branch->name }}</td>
                                <td>{{ $investigator->status }}</td>
                                <td>@if($lastLogin->last() !== null) {{ $lastLogin->last()->created_at }} @else {{ __('Nessun accesso') }} @endif</td>
                                <td>
                                    <a href="{{ route('investigator.edit', $investigator->id) }}" class="btn btn-primary">{{ __('Modifica') }}</a>
                                    <form action="{{ route('investigator.destroy', $investigator->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">{{ __('Elimina') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">
                    <p>{{ __('Non hai ancora un investigatore!') }}</p>
                    <a href="{{ route('investigator.invite') }}" class="btn btn-primary">{{ __('Invita il tuo primo investigatore') }}</a>
                </div>
            @endif
        </div>
    </div>
    
</x-app-layout>
