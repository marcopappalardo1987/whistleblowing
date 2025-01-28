@props(['disabled' => false, 'options' => [], 'selected' => ''])

<select 
    {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-select form-control']) !!}>
    @foreach($options as $optionValue => $optionLabel)
        <option value="{{ $optionValue }}" {{ $selected == $optionValue ? 'selected' : '' }}>{{ $optionLabel }}</option>
    @endforeach
</select>