@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link active d-flex align-items-center'
            : 'nav-link d-flex align-items-center';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
