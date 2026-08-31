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
            <x-button href="{{ route('books.index') }}" variant="primary" size="sm">
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
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Physical books are loaned for up to 14 calendar days. Automatic reminders are sent before due dates.</p>
                </div>
            </div>
            <div class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-white dark:bg-navy-800 text-navy-800 dark:text-gold-300 border border-navy-200 dark:border-navy-700 text-center">
                Max 5 Active Loans
            </div>
        </div>

        {{-- Active Loans Empty State --}}
        @if($borrowings->isEmpty())
            <div class="mb-12">
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
            </div>
        @endif

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
