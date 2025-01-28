@props(['disabled' => false, 'value' => null])

<textarea 
    {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-control']) !!}>@if($value !== null) {{ $value }} @endif
</textarea>