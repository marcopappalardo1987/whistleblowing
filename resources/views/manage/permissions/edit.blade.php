<x-app-layout>

    <x-slot name="header">
        {{ __('Edit Permission') }}
    </x-slot>

    @include('layouts.alert-message')  

    @include('layouts.navigation-roles-and-permissions')    

    <div class="content-page">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-3">{{ __('Edit Permission') }}</h3>
                        
                        <form method="POST" action="{{ route('permissions.update') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Permission Name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $permissionName) }}" required autofocus>
                                <input type="hidden" name="permission_id" value="{{ request()->query('permission_id') }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Permission') }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>