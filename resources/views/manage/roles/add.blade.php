<x-app-layout>

    <x-slot name="header">
        {{ __('Add Role') }}
    </x-slot>

    @include('layouts.alert-message')  

    @include('layouts.navigation-roles-and-permissions')    

    <div class="content-page">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-3">{{ __('Add new User Role') }}</h3>

                        <form method="POST" action="{{ route('roles.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Role Name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add Role') }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>