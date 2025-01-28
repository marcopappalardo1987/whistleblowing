@props(['name', 'value'])

<input type="hidden" name="{{ $name }}" value="{{ $value }}" {!! $attributes->merge(['class' => 'form-control']) !!}>