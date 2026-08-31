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

<div {{ $attributes->merge(['class' => 'group flex min-w-0 flex-col overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:border-gold-300 hover:shadow-xl dark:border-navy-800 dark:bg-navy-900 dark:hover:border-gold-500/50']) }}>
    {{-- Book Cover Header --}}
    <a href="{{ route('books.show', $book->slug) }}" class="relative block aspect-[4/3] bg-navy-950 overflow-hidden">
        @if($book->cover_image_path)
            <img
                src="{{ $book->cover_image_path }}"
                alt="{{ $book->title }}"
                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                loading="lazy"
                onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
            />
            {{-- Dark gradient overlay for text readability --}}
            <div class="absolute inset-0 bg-gradient-to-t from-navy-950/90 via-navy-950/40 to-transparent"></div>
        @endif

        {{-- Fallback Stylized Spine (shown if no cover or image fails to load) --}}
        <div class="{{ $book->cover_image_path ? 'hidden' : '' }} absolute inset-0 bg-gradient-to-br from-navy-900 via-navy-800 to-navy-950 p-5 flex flex-col justify-between">
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
            <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-gold-500/20 border-r border-gold-500/30"></div>
        </div>

        {{-- Badges & Title Overlay --}}
        <div class="absolute inset-0 p-4 flex flex-col justify-between z-10">
            <div class="flex flex-wrap items-start justify-between gap-2">
                @if($firstCategory)
                    <x-badge variant="gold" size="sm" class="max-w-[9rem] truncate whitespace-nowrap shadow-sm">
                        {{ $firstCategory->name }}
                    </x-badge>
                @else
                    <span></span>
                @endif

                <x-badge :variant="$isAvailable ? 'available' : 'borrowed'" size="sm" class="shrink-0 shadow-sm">
                    {{ $isAvailable ? "{$availableCopies} Avail" : 'Checked Out' }}
                </x-badge>
            </div>

            <div>
                <h4 class="text-base font-bold text-white line-clamp-2 leading-snug group-hover:text-gold-300 transition-colors drop-shadow-md">
                    {{ $book->title }}
                </h4>
                <p class="text-xs text-gray-200 line-clamp-1 mt-1 font-medium drop-shadow">
                    {{ $authorsString }}
                </p>
            </div>
        </div>
    </a>

    {{-- Book Card Body --}}
    <div class="p-5 flex-1 flex flex-col justify-between gap-4">
        <div>
            <div class="mb-2 flex flex-wrap items-center gap-x-1.5 gap-y-1 text-xs text-gray-500 dark:text-gray-400">
                @if($book->publication_year)
                    <span>{{ $book->publication_year > 0 ? $book->publication_year : abs($book->publication_year) . ' BCE' }}</span>
                    <span>•</span>
                @endif
                @if($book->page_count)
                    <span>{{ $book->page_count }} pages</span>
                    <span>•</span>
                @endif
                <span>{{ strtoupper($book->language) }}</span>
            </div>

            @if($book->description)
                <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">
                    {{ $book->description }}
                </p>
            @endif
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 pt-3 dark:border-navy-800">
            {{-- Rating --}}
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4 text-gold-400 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-xs font-bold text-gray-900 dark:text-white">{{ number_format($rating, 1) }}</span>
                @if($book->ratings_count > 0)
                    <span class="text-[10px] text-gray-400">({{ number_format($book->ratings_count) }})</span>
                @endif
            </div>

            <a href="{{ route('books.show', $book->slug) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-navy-600 dark:text-gold-400 group-hover:underline">
                View Details
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>
    </div>
</div>
