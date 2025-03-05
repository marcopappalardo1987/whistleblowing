<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h3 class="card-title h5 mb-3">{{ __('Roles and Permissions') }}</h3>
        <div class="d-flex flex-wrap gap-2">
            @can('publish ownerdata')
                <a href="{{ route('roles.add') }}" 
                   class="btn {{ request()->routeIs('roles.add') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ __('Add Role') }}
                </a>
            @endcan

            @can('view ownerdata')
                <a href="{{ route('roles') }}" 
                   class="btn {{ request()->routeIs('roles') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-pencil-square me-2"></i>
                    {{ __('Manage Role') }}
                </a>
            @endcan

            @can('publish ownerdata')
                <a href="{{ route('permissions.add') }}" 
                   class="btn {{ request()->routeIs('permissions.add') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ __('Add Permission') }}
                </a>
            @endcan

            @can('view ownerdata')
                <a href="{{ route('permissions') }}" 
                   class="btn {{ request()->routeIs('permissions') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-pencil-square me-2"></i>
                    {{ __('Manage Permissions') }}
                </a>
            @endcan

            @can('view ownerdata')
                <a href="{{ route('permissions-assigner') }}" 
                   class="btn {{ request()->routeIs('permissions-assigner') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-person-gear me-2"></i>
                    {{ __('Assign Permissions') }}
                </a>
            @endcan
        </div>    
    </div>
</div>