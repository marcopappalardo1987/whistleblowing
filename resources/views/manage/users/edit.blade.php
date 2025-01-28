<x-app-layout>

    <x-slot name="header">
        {{ __('Manage Users') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation-users-manager')

    <div class="mx-auto mt-4">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="font-semibold mb-2">{{ __('Edit User') }}</h3>
                <form action="{{ route('users.update', ['user_id' => $user->id]) }}" method="POST">
                    @csrf
                    @method('POST')
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                        <input type="text" name="name" id="name" value="{{ $user->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50" required>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                        <input type="email" name="email" id="email" value="{{ $user->email }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50" required>
                    </div>

                    <div class="mb-4">
                        <label for="roles" class="block text-sm font-medium text-gray-700">{{ __('Role') }}</label>
                        @if($user->roles->contains('name', 'owner'))
                            <p class="mt-1 block text-sm text-gray-500">{{ __('Owner (Cannot be modified)') }}</p>
                        @else
                            <select name="roles[]" id="roles" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->roles->contains($role) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                        <input type="password" name="password" id="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50" placeholder="{{ __('Leave blank to keep current password') }}">
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Update User') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>