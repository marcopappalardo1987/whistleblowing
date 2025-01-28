@props(['disabled' => false, 'value' => ''])

<input 
    value="{{ $value }}"
    {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-control']) !!}>
