<x-app-layout>

    <x-slot name="header">
        {{ __('Aggiungi Lista') }}
    </x-slot>

    @include('layouts.alert-message')  

    @include('layouts.navigation-scraper-lists')

    <div class="card mt-4">
        <div class="card-body">
            <h3 class="h4 mb-4">{{__('Aggiungi nuova Lista')}}</h3>

            <form method="POST" action="{{ route('scraper.list.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Nome Lista') }}</label>
                    <input type="text" class="form-control" id="name" name="list_name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="text-end">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <button type="submit" class="btn btn-dark">
                        {{ __('Aggiungi Lista') }}
                    </button>
                </div>
            </form>

        </div>
    </div>

</x-app-layout>