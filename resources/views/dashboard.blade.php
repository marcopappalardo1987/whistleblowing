<x-app-layout>
    
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    @include('layouts.alert-message')  
    
</x-app-layout>
