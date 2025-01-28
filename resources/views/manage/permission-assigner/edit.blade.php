<x-app-layout>

    <x-slot name="header">
        {{ __('Edit Permission') }}
    </x-slot>

    @include('layouts.alert-message')  

    @include('layouts.navigation-roles-and-permissions')    

    <div class="content-page mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-3">{{ __('Roles and Permissions') }}</h3>
                        
                        <form method="POST" action="{{ route('permissions-assigner.store') }}">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="bg-light" style="position: sticky; left: 0; z-index: 1;">{{ __('Permissions') }}</th>
                                            @foreach($roles as $role)
                                                <th class="bg-light">{{ $role->name }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $permission)
                                            <tr>
                                                <td class="bg-white" style="position: sticky; left: 0; z-index: 1;">{{ $permission->name }}</td>
                                                @foreach($roles as $role)
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" 
                                                                type="checkbox" 
                                                                name="permissions[{{ $role->id }}][]" 
                                                                value="{{ $permission->id }}" 
                                                                @if($role->hasPermissionTo($permission->name)) checked @endif>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>