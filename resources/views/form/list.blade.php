<x-app-layout>
    
    <x-slot name="header">
        {{ __('Elenco Form') }}
    </x-slot>

    <!-- Start Generation Here -->
    <div class="content-page mt-4">
        <div class="row">
            <div class="col-12">
                @include('layouts.alert-message')
                @if($forms->count() > 0 && $maxForms > 0)
                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{__('ID')}}</th>
                                        <th>{{__('Nome')}}</th>
                                        <th>{{__('Creato il')}}</th>
                                        <th>{{__('Modificato il')}}</th>
                                        <th>{{__('Azioni')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($forms as $form)
                                        <tr>
                                            <td>{{ $form->id }}</td>
                                            <td>{{ $form->title }}</td>
                                            <td>{{ $form->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $form->updated_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('form.builder.edit', $form->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> {{__('Modifica')}}
                                                </a>
                                                <form action="{{ route('form.builder.destroy', $form->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questo form?')">
                                                        <i class="fas fa-trash"></i> {{__('Elimina')}}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @elseif($maxForms == 0)
                    <div class="alert alert-danger">
                        <p>{{__('Il tuo piano non consente di creare form personalizzati.')}}</p>
                        <a href="{{ route('plans') }}" class="btn btn-primary">{{__('Vedi i piani disponibili')}}</a>
                    </div>
                @elseif($maxForms - $formsCount <= 0)
                    <div class="alert alert-danger">
                        <p>{{__('Hai raggiunto il numero massimo di form consentito.')}}</p>
                        <a href="{{ route('plans') }}" class="btn btn-primary">{{__('Vedi i piani disponibili')}}</a>
                    </div>
                @elseif($formsCount == 0)
                    <div class="alert alert-info">
                        <p>{{__('Non hai ancora creato nessun form.')}}</p>
                        <a href="{{ route('form.builder.new') }}" class="btn btn-primary">{{__('Crea il tuo primo form')}}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- End Generation Here -->

    
    
</x-app-layout>
