@props([
    'label' => null,
    'type' => 'text',
    'error' => null,
])

<div>
    @if($label)
        <label {{ $attributes->whereStartsWith('for') }} class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
        </label>
    @endif

    <input
        type="{{ $type }}"
        {{ $attributes->merge(['class' => 'w-full rounded-lg border border-gray-300 dark:border-navy-600 bg-white dark:bg-navy-800 text-gray-900 dark:text-gray-100 px-4 py-2.5 text-sm placeholder-gray-400 dark:placeholder-gray-500 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 dark:focus:ring-gold-400/20 focus:outline-none transition-colors' . ($error ? ' border-red-500 dark:border-red-400' : '')]) }}
    />

    @if($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
