<x-app-layout>

    <x-slot name="header">
        {{ __('Manage Roles') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation-roles-and-permissions')

    <div class="content-page">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-3">{{ __('Existing Roles') }}</h3>

                        @if($roles->isEmpty())
                            <p>{{ __('No roles found.') }}</p>
                        @else
                            <ul class="list-group">
                                @foreach($roles as $role)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $role->name }}
                                        <div class="d-flex flex-wrap">
                                            @can('edit ownerdata')
                                                <!-- Edit Form -->
                                                <form action="{{ route('roles.edit') }}" method="GET" class="me-3">
                                                    @method('PUT')
                                                    @csrf
                                                    <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                    <button type="submit" class="btn btn-link text-primary p-0 d-flex align-items-center">
                                                        <x-gmdi-edit class="me-2" style="width: 1rem;" />{{ __('Edit') }}
                                                    </button>
                                                </form>
                                            @endcan

                                            @can('remove ownerdata')
                                                <!-- Delete Form -->
                                                <form action="{{ route('roles.delete') }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                    <button type="submit" class="btn btn-link text-danger p-0 d-flex align-items-center">
                                                        <x-gmdi-delete class="me-2" style="width: 1rem;" />{{ __('Delete') }}
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @can('publish ownerdata')
                            <div class="mt-4">
                                <a href="{{ route('roles.add') }}" class="btn btn-primary">
                                    {{ __('Add New Role') }}
                                </a>
                            </div>
                        @endcan

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
