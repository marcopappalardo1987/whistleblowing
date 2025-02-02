<x-app-layout>
    
    <x-slot name="header">
        {{ __('Tutte le commissioni') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('affiliate.partials.add-commission')

    @include('affiliate.partials.all-commissions')

</x-app-layout>