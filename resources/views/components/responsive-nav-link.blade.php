@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-forest text-start text-base font-medium text-forest bg-forest/5 focus:outline-none focus:text-forest focus:bg-forest/10 focus:border-forest transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-ink/60 hover:text-ink hover:bg-ink/5 hover:border-ink/20 focus:outline-none focus:text-ink focus:bg-ink/5 focus:border-ink/20 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
