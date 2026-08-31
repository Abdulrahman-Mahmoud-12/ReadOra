@props([
    'variant' => 'default',
    'size' => 'md',
])

@php
$variants = [
    'default' => 'bg-gray-100 text-gray-800 dark:bg-navy-800 dark:text-gray-300 border-gray-200 dark:border-navy-700',
    'primary' => 'bg-navy-100 text-navy-800 dark:bg-navy-800 dark:text-gold-300 border-navy-200 dark:border-navy-700',
    'gold' => 'bg-gold-100 text-gold-800 dark:bg-gold-900/40 dark:text-gold-300 border-gold-200 dark:border-gold-800/50',
    'success' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/50',
    'warning' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 border-amber-200 dark:border-amber-800/50',
    'danger' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300 border-rose-200 dark:border-rose-800/50',
    'available' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
    'borrowed' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
    'reserved' => 'bg-sky-50 text-sky-700 dark:bg-sky-950/50 dark:text-sky-300 border-sky-200 dark:border-sky-800/60',
    'maintenance' => 'bg-gray-100 text-gray-700 dark:bg-navy-800 dark:text-gray-400 border-gray-300 dark:border-navy-700',
];

$sizes = [
    'sm' => 'px-2 py-0.5 text-xs font-medium',
    'md' => 'px-2.5 py-1 text-xs font-semibold',
    'lg' => 'px-3 py-1.5 text-sm font-semibold',
];

$variantClass = $variants[$variant] ?? $variants['default'];
$sizeClass = $sizes[$size] ?? $sizes['md'];
$classes = 'inline-flex max-w-full items-center gap-1.5 rounded-full border leading-tight ' . $variantClass . ' ' . $sizeClass . ' transition-colors';
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
