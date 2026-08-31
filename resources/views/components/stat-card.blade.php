@props([
    'title',
    'value',
    'subtitle' => null,
    'variant' => 'default',
])

@php
$iconBg = [
    'default' => 'bg-navy-50 text-navy-600 dark:bg-navy-800 dark:text-gold-400',
    'gold' => 'bg-gold-50 text-gold-600 dark:bg-gold-900/30 dark:text-gold-400',
    'success' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400',
    'purple' => 'bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-navy-900 rounded-lg p-6 border border-gray-200 dark:border-navy-800 shadow-sm hover:shadow-md transition-shadow']) }}>
    <div class="flex items-start justify-between gap-3">
        <span class="min-w-0 text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</span>
        @if(isset($icon))
            <span class="shrink-0 p-2.5 rounded-lg {{ $iconBg[$variant] ?? $iconBg['default'] }}">
                {{ $icon }}
            </span>
        @endif
    </div>
    <div class="mt-4 flex flex-wrap items-baseline gap-x-2 gap-y-1">
        <span class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $value }}</span>
        @if($subtitle)
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $subtitle }}</span>
        @endif
    </div>
</div>
