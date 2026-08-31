@props([
    'book',
])

@php
    $availableCopies = $book->availableCopiesCount();
    $isAvailable = $book->isAvailable();
    $authorsString = $book->authors->pluck('name')->join(', ') ?: 'Unknown Author';
    $firstCategory = $book->categories->first();
    $rating = (float) $book->average_rating;
@endphp

<div {{ $attributes->merge(['class' => 'group flex min-w-0 flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:border-gold-400 hover:shadow-xl dark:border-navy-800 dark:bg-navy-900 dark:hover:border-gold-500/50']) }}>
    {{-- Full Portrait Book Cover Container --}}
    <a href="{{ route('books.show', $book->slug) }}" class="relative block aspect-[2/3] w-full bg-gradient-to-b from-navy-950 via-navy-900 to-navy-950 overflow-hidden">
        @if($book->cover_image_path)
            <img
                src="{{ $book->cover_image_path }}"
                alt="{{ $book->title }}"
                class="w-full h-full object-contain object-center p-2 group-hover:scale-105 transition-transform duration-300 drop-shadow-md"
                loading="lazy"
                onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
            />
        @endif

        {{-- Fallback Stylized Spine Cover (shown if no image or fails to load) --}}
        <div class="{{ $book->cover_image_path ? 'hidden' : '' }} absolute inset-0 bg-gradient-to-br from-navy-950 via-navy-900 to-navy-800 p-6 flex flex-col justify-between">
            <div class="absolute inset-0 opacity-15">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="card-pattern-{{ $book->id }}" width="20" height="20" patternUnits="userSpaceOnUse">
                            <path d="M 20 0 L 0 0 0 20" fill="none" stroke="currentColor" stroke-width="0.5" class="text-gold-400" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#card-pattern-{{ $book->id }})" />
                </svg>
            </div>
            <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-gold-500/30 border-r border-gold-500/40"></div>

            <div class="relative pl-3">
                <h4 class="text-base font-bold text-white line-clamp-3 leading-snug">
                    {{ $book->title }}
                </h4>
                @if($book->subtitle)
                    <p class="text-xs text-gold-300 line-clamp-2 mt-1">
                        {{ $book->subtitle }}
                    </p>
                @endif
            </div>

            <div class="relative pl-3 border-t border-navy-800 pt-3">
                <p class="text-xs text-gray-300 font-medium line-clamp-1">
                    {{ $authorsString }}
                </p>
            </div>
        </div>

        {{-- Floating Badges over Cover --}}
        <div class="absolute top-2.5 left-2.5 right-2.5 flex items-start justify-between gap-1.5 pointer-events-none z-10">
            @if($firstCategory)
                <x-badge variant="gold" size="sm" class="max-w-[7.5rem] truncate whitespace-nowrap shadow-md backdrop-blur-sm bg-gold-500/90 text-navy-950 font-bold">
                    {{ $firstCategory->name }}
                </x-badge>
            @else
                <span></span>
            @endif

            <x-badge :variant="$isAvailable ? 'available' : 'borrowed'" size="sm" class="shrink-0 shadow-md backdrop-blur-sm">
                {{ $isAvailable ? "{$availableCopies} Avail" : 'Checked Out' }}
            </x-badge>
        </div>
    </a>

    {{-- Book Card Details Body --}}
    <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between gap-3 bg-white dark:bg-navy-900">
        <div>
            {{-- Title & Author --}}
            <a href="{{ route('books.show', $book->slug) }}" class="block group-hover:text-gold-600 dark:group-hover:text-gold-400 transition-colors">
                <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white line-clamp-2 leading-tight">
                    {{ $book->title }}
                </h3>
            </a>
            <p class="text-xs text-gold-700 dark:text-gold-400/90 font-medium line-clamp-1 mt-1">
                {{ $authorsString }}
            </p>

            {{-- Metadata Pills --}}
            <div class="mt-2.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] text-gray-500 dark:text-gray-400">
                @if($book->publication_year)
                    <span>{{ $book->publication_year > 0 ? $book->publication_year : abs($book->publication_year) . ' BCE' }}</span>
                    <span>•</span>
                @endif
                @if($book->page_count)
                    <span>{{ $book->page_count }} pages</span>
                    <span>•</span>
                @endif
                <span class="uppercase font-mono">{{ $book->language }}</span>
            </div>
        </div>

        {{-- Footer: Rating & Action --}}
        <div class="flex items-center justify-between gap-2 border-t border-gray-100 dark:border-navy-800 pt-3">
            {{-- Rating --}}
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4 text-gold-400 fill-current shrink-0" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-xs font-bold text-gray-900 dark:text-white">{{ number_format($rating, 1) }}</span>
                @if($book->ratings_count > 0)
                    <span class="text-[10px] text-gray-400">({{ number_format($book->ratings_count) }})</span>
                @endif
            </div>

            <a href="{{ route('books.show', $book->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold text-navy-700 dark:text-gold-400 hover:text-gold-600 transition-colors">
                Details
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>
    </div>
</div>
