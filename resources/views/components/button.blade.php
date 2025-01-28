@props(['type' => 'submit', 'color' => 'primary', 'text' => '', 'customClass' => ''])

<button type="{{ $type }}" {{ $attributes->merge(['class' => "btn btn-{$color} {$customClass}"]) }}>
    {{ $text }}
</button>