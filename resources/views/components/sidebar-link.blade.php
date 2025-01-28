@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link active d-block w-100 ps-3 pe-4 py-2 border-start border-primary text-start fw-medium'
            : 'nav-link d-block w-100 ps-3 pe-4 py-2 border-start text-start fw-medium text-secondary hover:text-dark';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
