<x-app-layout>

    <x-slot name="header">
        {{ __('Edit Role') }}
    </x-slot>

    @include('layouts.alert-message')  

    @include('layouts.navigation-roles-and-permissions')    

    <div class="content-page">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-3">{{ __('Edit User Role') }}</h3>

                        <form method="POST" action="{{ route('roles.update') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Role Name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $roleName) }}" required autofocus>
                                <input type="hidden" name="role_id" value="{{ request()->query('role_id') }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Role') }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>