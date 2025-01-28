<x-app-layout>
    <x-slot name="header">
        {{ __('Manage Users') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation-users-manager')

    <div class="content-page mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-3">{{ __('User List') }}</h3>

                        @if($users->isEmpty())
                            <p>{{ __('No users found.') }}</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Role') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if($user->roles->isNotEmpty())
                                                        @foreach($user->roles as $role)
                                                            {{ $role->name }}
                                                        @endforeach
                                                    @else
                                                        {{ __('No Role Assigned') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-wrap">
                                                        @can('edit ownerdata')
                                                            <a href="{{ route('users.edit', ['user_id' => $user->id]) }}" class="btn btn-link text-primary p-0 d-flex align-items-center me-3">
                                                                <x-gmdi-edit class="me-2" style="width: 1rem;" />{{ __('Edit') }}
                                                            </a>
                                                        @endcan 

                                                        @can('remove ownerdata')
                                                            <a href="{{ route('users.delete', ['user_id' => $user->id]) }}" class="btn btn-link text-danger p-0 d-flex align-items-center">
                                                                <x-gmdi-delete class="me-2" style="width: 1rem;" />{{ __('Delete') }}
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                {{ $users->links() }} <!-- Pagination links -->
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>