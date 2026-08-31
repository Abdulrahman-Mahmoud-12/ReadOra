@props([
    'title' => 'No items found',
    'description' => 'There are no items matching your criteria at this time.',
    'actionText' => null,
    'actionUrl' => null,
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-12 px-4 rounded-lg border border-dashed border-gray-200 dark:border-navy-800 bg-white/50 dark:bg-navy-900/40']) }}>
    <div class="w-14 h-14 mx-auto mb-4 rounded-lg bg-navy-50 dark:bg-navy-800/80 flex items-center justify-center text-navy-600 dark:text-gold-400">
        @if($icon)
            {{ $icon }}
        @else
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        @endif
    </div>

    <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
    <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">{{ $description }}</p>

    @if($actionText && $actionUrl)
        <div class="mt-6">
            <x-button :href="$actionUrl" variant="primary" size="sm">
                {{ $actionText }}
            </x-button>
        </div>
    @endif
</div>
