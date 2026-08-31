<x-layouts.app title="Borrowing History — ReadOra">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        {{-- Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="mb-2 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <a href="{{ route('dashboard') }}" class="hover:text-navy-600 dark:hover:text-gold-400">Dashboard</a>
                    <span>/</span>
                    <span class="text-gray-900 dark:text-white font-medium">Borrowing</span>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Borrowing & Circulation History
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Track your active book loans, due dates, return statuses, and borrowing history.
                </p>
            </div>
            <x-button href="{{ route('books.index', ['availability' => 'available']) }}" variant="primary" size="sm">
                Borrow a Book
            </x-button>
        </div>

        {{-- Borrowing Policy Notice --}}
        <div class="mb-8 flex flex-col justify-between gap-4 rounded-lg border border-navy-100 bg-navy-50 p-4 dark:border-navy-800 dark:bg-navy-900/60 sm:flex-row sm:items-center">
            <div class="flex items-start gap-3">
                <span class="p-2 rounded-xl bg-navy-100 dark:bg-navy-800 text-navy-700 dark:text-gold-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </span>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Patron Circulation Guidelines</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Physical books are loaned for 14 calendar days. You can renew active loans before they become overdue.</p>
                </div>
            </div>
            <div class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-white dark:bg-navy-800 text-navy-800 dark:text-gold-300 border border-navy-200 dark:border-navy-700 text-center">
                {{ $activeBorrowings->count() }} / 5 Active Loans
            </div>
        </div>

        {{-- Active Loans Section --}}
        <div class="mb-12">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Active Loans ({{ $activeBorrowings->count() }})</h2>
            </div>

            @if($activeBorrowings->isEmpty())
                <x-empty-state
                    title="No active loans or borrowings"
                    description="You currently have 0 books checked out. Find books in the catalog and borrow them with your digital library card."
                    actionText="Browse Available Books"
                    :actionUrl="route('books.index', ['availability' => 'available'])"
                >
                    <x-slot:icon>
                        <svg class="w-7 h-7 text-navy-600 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </x-slot:icon>
                </x-empty-state>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($activeBorrowings as $loan)
                        @php
                            $book = $loan->bookCopy->book;
                            $isOverdue = $loan->isOverdue();
                            $daysRemaining = $loan->daysRemaining();
                        @endphp
                        <div class="rounded-lg border {{ $isOverdue ? 'border-rose-300 dark:border-rose-900 bg-rose-50/30 dark:bg-rose-950/20' : 'border-gray-200 dark:border-navy-800 bg-white dark:bg-navy-900' }} p-5 shadow-sm flex flex-col justify-between">
                            <div class="flex gap-4">
                                {{-- Mini cover --}}
                                <div class="w-16 h-24 flex-shrink-0 rounded overflow-hidden bg-navy-950 border border-navy-700 shadow relative">
                                    @if($book->cover_image_path)
                                        <img
                                            src="{{ $book->cover_image_path }}"
                                            alt="{{ $book->title }}"
                                            class="w-full h-full object-cover"
                                            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                                        />
                                    @endif
                                    <div class="{{ $book->cover_image_path ? 'hidden' : '' }} absolute inset-0 bg-gradient-to-br from-navy-950 to-navy-800 p-2 text-white flex flex-col justify-between">
                                        <span class="text-[8px] text-gold-400 font-mono">{{ $loan->bookCopy->barcode }}</span>
                                        <p class="text-[9px] font-bold leading-tight line-clamp-3">{{ $book->title }}</p>
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <a href="{{ route('books.show', $book->slug) }}" class="text-base font-bold text-gray-900 dark:text-white hover:text-navy-600 dark:hover:text-gold-400 transition-colors line-clamp-1">
                                                {{ $book->title }}
                                            </a>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                By {{ $book->authors->pluck('name')->join(', ') ?: 'Unknown' }}
                                            </p>
                                        </div>
                                        <x-badge :variant="$isOverdue ? 'danger' : 'available'" size="sm">
                                            {{ $isOverdue ? 'Overdue' : 'Active Loan' }}
                                        </x-badge>
                                    </div>

                                    <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                                        <div class="p-2 bg-gray-50 dark:bg-navy-950 rounded">
                                            <span class="text-gray-400 block text-[10px]">Borrowed</span>
                                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $loan->borrowed_at->format('M d, Y') }}</span>
                                        </div>
                                        <div class="p-2 {{ $isOverdue ? 'bg-rose-100 dark:bg-rose-900/40 text-rose-800 dark:text-rose-300' : 'bg-gray-50 dark:bg-navy-950 text-gray-800 dark:text-gray-200' }} rounded">
                                            <span class="text-gray-400 block text-[10px]">Due Date</span>
                                            <span class="font-semibold">{{ $loan->due_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-navy-800 flex items-center justify-between gap-2">
                                <span class="text-xs {{ $isOverdue ? 'text-rose-600 dark:text-rose-400 font-bold' : 'text-gray-500 dark:text-gray-400' }}">
                                    @if($isOverdue)
                                        Overdue by {{ abs($daysRemaining) }} {{ abs($daysRemaining) === 1 ? 'day' : 'days' }}!
                                    @else
                                        {{ $daysRemaining }} {{ $daysRemaining === 1 ? 'day' : 'days' }} remaining
                                    @endif
                                </span>

                                <div class="flex items-center gap-2">
                                    @if(! $isOverdue)
                                        <form method="POST" action="{{ route('borrowings.renew', $loan) }}">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="px-3 py-1.5 text-xs font-semibold rounded-md border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-navy-700 transition-colors"
                                            >
                                                Renew (+14d)
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('borrowings.return', $loan) }}">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="px-3 py-1.5 text-xs font-semibold rounded-md bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-colors"
                                        >
                                            Return Book
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Past Borrowing History Table --}}
        <div class="mb-12 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900">
            <div class="mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Borrowing History</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Past returned physical books and reading circulation archive.</p>
            </div>

            @if($pastBorrowings->isEmpty())
                <p class="text-xs text-gray-500 dark:text-gray-400 py-6 text-center">No past returned loans yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 dark:bg-navy-950 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                            <tr>
                                <th class="px-4 py-3 rounded-l-lg">Book Title</th>
                                <th class="px-4 py-3">Barcode</th>
                                <th class="px-4 py-3">Borrowed</th>
                                <th class="px-4 py-3">Returned</th>
                                <th class="px-4 py-3 rounded-r-lg text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-navy-800">
                            @foreach($pastBorrowings as $history)
                                @php $book = $history->bookCopy->book; @endphp
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-navy-800/40">
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                        <a href="{{ route('books.show', $book->slug) }}" class="hover:underline">
                                            {{ $book->title }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">
                                        {{ $history->bookCopy->barcode }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                        {{ $history->borrowed_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                        {{ $history->returned_at ? $history->returned_at->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <x-badge variant="default" size="sm">
                                            Returned
                                        </x-badge>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($pastBorrowings->hasPages())
                    <div class="mt-4">
                        {{ $pastBorrowings->links() }}
                    </div>
                @endif
            @endif
        </div>

        {{-- Available for Borrowing Showcase --}}
        @if($popularBooks->isNotEmpty())
            <div class="pt-8 border-t border-gray-200 dark:border-navy-800">
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Available in Stacks Now</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Popular works ready for immediate checkout.</p>
                    </div>
                    <a href="{{ route('books.index', ['availability' => 'available']) }}" class="text-xs font-semibold text-gold-600 dark:text-gold-400 hover:underline">
                        View All Available →
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($popularBooks as $popular)
                        <x-book-card :book="$popular" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
