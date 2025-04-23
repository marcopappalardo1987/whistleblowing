<x-app-layout>

    <x-slot name="header">
        {{ __('Manage Permissions') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation-roles-and-permissions')

    <div class="content-page">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-3">{{ __('Existing Permissions') }}</h3>

                        @if($permissions->isEmpty())
                            <p>{{ __('No permissions found.') }}</p>
                        @else
                            <ul class="list-group">
                                @foreach($permissions as $permission)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $permission->name }}
                                        <div class="d-flex flex-wrap">
                                            @can('edit ownerdata')
                                                <!-- Edit Form -->
                                                <form action="{{ route('permissions.edit') }}" method="GET" class="me-3">
                                                    @method('PUT')
                                                    @csrf
                                                    <input type="hidden" name="permission_id" value="{{ $permission->id }}">
                                                    <button type="submit" class="btn btn-link text-primary p-0 d-flex align-items-center">
                                                        <x-gmdi-edit class="me-2" style="width: 1rem;" />{{ __('Edit') }}
                                                    </button>
                                                </form>
                                            @endcan

                                            @can('remove ownerdata')
                                                <!-- Delete Form -->
                                                <form action="{{ route('permissions.delete') }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <input type="hidden" name="permission_id" value="{{ $permission->id }}">
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
                                <a href="{{ route('permissions.add') }}" class="btn btn-primary">
                                    {{ __('Add New Permission') }}
                                </a>
                            </div>
                        @endcan

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>