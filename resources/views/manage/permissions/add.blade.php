<x-app-layout>

    <x-slot name="header">
        {{ __('Add Permissions') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation-roles-and-permissions')

    <div class="content-page mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-3">{{ __('Add New Permission') }}</h3>

                        <form method="POST" action="{{ route('permissions.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Permission Name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add Permission') }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>