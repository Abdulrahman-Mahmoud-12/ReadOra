@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-navy-900';

    $variants = [
        'primary' => 'bg-gold-500 hover:bg-gold-600 text-navy-900 focus:ring-gold-400 shadow-sm',
        'secondary' => 'bg-navy-800 hover:bg-navy-700 text-white focus:ring-navy-500 shadow-sm',
        'outline' => 'border-2 border-navy-300 dark:border-navy-600 text-navy-700 dark:text-gray-300 hover:bg-navy-50 dark:hover:bg-navy-800 focus:ring-navy-400',
        'ghost' => 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-navy-800 focus:ring-gray-400',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500 shadow-sm',
    ];

    $sizes = [
        'xs' => 'text-xs px-2.5 py-1.5',
        'sm' => 'text-sm px-3 py-2',
        'md' => 'text-sm px-4 py-2.5',
        'lg' => 'text-base px-6 py-3',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
