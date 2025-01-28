<x-app-layout>

    <x-slot name="header">
        {{ __('Modifica Lista') }}
    </x-slot>

    @include('layouts.alert-message')  

    @include('layouts.navigation-scraper-lists')

    <div class="card mt-4">
        <div class="card-body">
            <h3 class="h4 mb-4">{{__('Modifica Lista')}}</h3>

            <form method="POST" action="{{ route('scraper.list.edit.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Nome Lista') }}</label>
                    <input type="text" class="form-control" id="name" name="list_name" value="{{ $list->list_name }}" required autofocus>
                    @error('list_name')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="text-end">
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                    <input type="hidden" name="list_id" value="{{ $list->id }}">
                    <button type="submit" class="btn btn-dark">
                        {{ __('Rinomina Lista') }}
                    </button>
                </div>
            </form>

        </div>
    </div>

</x-app-layout>