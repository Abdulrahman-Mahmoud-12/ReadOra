<x-layouts.admin title="Circulation Reports & Analytics — ReadOra Admin" header="Circulation Reports, Analytics & Data Export">
    {{-- Quick Export Toolbar --}}
    <div class="mb-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Export Library Data (CSV)
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Download comprehensive datasets with UTF-8 BOM encoding for Excel, Google Sheets, or BI systems.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.reports.export.books') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-navy-900 dark:bg-navy-800 text-white dark:text-gold-300 text-xs font-semibold hover:bg-navy-800 transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    Books Catalog CSV
                </a>
                <a href="{{ route('admin.reports.export.circulations') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-navy-900 dark:bg-navy-800 text-white dark:text-gold-300 text-xs font-semibold hover:bg-navy-800 transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                    Circulation Loans CSV
                </a>
                <a href="{{ route('admin.reports.export.patrons') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-navy-900 dark:bg-navy-800 text-white dark:text-gold-300 text-xs font-semibold hover:bg-navy-800 transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Patrons CSV
                </a>
                <a href="{{ route('admin.reports.export.copies') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-navy-900 dark:bg-navy-800 text-white dark:text-gold-300 text-xs font-semibold hover:bg-navy-800 transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    Shelf Inventory CSV
                </a>
            </div>
        </div>
    </div>

    {{-- Key Circulation KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-navy-800 dark:bg-navy-900">
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Lifetime Loans</div>
            <div class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white">{{ number_format($totalLoansCount) }}</div>
            <div class="mt-1 text-[11px] text-gray-400">Circulation transactions recorded</div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-navy-800 dark:bg-navy-900">
            <div class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Active Borrowings</div>
            <div class="mt-2 text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ number_format($activeLoansCount) }}</div>
            <div class="mt-1 text-[11px] text-gray-400">Currently in patron hands</div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-navy-800 dark:bg-navy-900">
            <div class="text-xs font-semibold uppercase tracking-wider text-rose-600 dark:text-rose-400">Overdue Loans</div>
            <div class="mt-2 text-3xl font-extrabold text-rose-600 dark:text-rose-400">{{ number_format($overdueLoansCount) }}</div>
            <div class="mt-1 text-[11px] text-gray-400">Exceeded standard due date</div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-navy-800 dark:bg-navy-900">
            <div class="text-xs font-semibold uppercase tracking-wider text-gold-600 dark:text-gold-400">New Loans This Month</div>
            <div class="mt-2 text-3xl font-extrabold text-gold-600 dark:text-gold-400">{{ number_format($thisMonthLoansCount) }}</div>
            <div class="mt-1 text-[11px] text-gray-400">{{ now()->format('F Y') }} activity</div>
        </div>
    </div>

    {{-- 2-Column Analytics Breakdown --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Top Borrowed Titles --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4 flex items-center justify-between">
                <span>Top Borrowed Titles</span>
                <span class="text-xs text-gold-500 font-mono">Ranked by Loans</span>
            </h3>

            <div class="divide-y divide-gray-100 dark:divide-navy-800">
                @forelse($topBooks as $index => $book)
                    <div class="py-3 flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-6 h-6 rounded-full bg-navy-100 dark:bg-navy-800 text-navy-800 dark:text-gold-400 font-bold flex items-center justify-center text-xs shrink-0">
                                #{{ $index + 1 }}
                            </span>
                            <div class="min-w-0">
                                <a href="{{ route('books.show', $book->slug) }}" target="_blank" class="font-bold text-gray-900 dark:text-white hover:underline truncate block">
                                    {{ $book->title }}
                                </a>
                                <span class="text-[11px] text-gray-400 truncate block">
                                    {{ $book->authors->pluck('name')->join(', ') ?: 'Unknown Author' }}
                                </span>
                            </div>
                        </div>
                        <span class="font-mono font-bold text-navy-900 dark:text-gold-400 shrink-0 bg-gold-500/10 px-2.5 py-1 rounded-md">
                            {{ $book->borrowings_count }} loans
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 py-4 text-center">No loan data recorded yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Most Active Patrons --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4 flex items-center justify-between">
                <span>Most Active Patrons</span>
                <span class="text-xs text-gold-500 font-mono">Ranked by Lifetime Loans</span>
            </h3>

            <div class="divide-y divide-gray-100 dark:divide-navy-800">
                @forelse($topPatrons as $index => $patron)
                    <div class="py-3 flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-6 h-6 rounded-full bg-navy-100 dark:bg-navy-800 text-navy-800 dark:text-gold-400 font-bold flex items-center justify-center text-xs shrink-0">
                                #{{ $index + 1 }}
                            </span>
                            <div class="min-w-0">
                                <strong class="text-gray-900 dark:text-white truncate block">{{ $patron->name }}</strong>
                                <span class="text-[11px] text-gray-400 font-mono truncate block">{{ $patron->email }}</span>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="font-mono font-bold text-gray-900 dark:text-white block">{{ $patron->borrowings_count }} loans</span>
                            <span class="text-[10px] text-emerald-600 dark:text-emerald-400">{{ $patron->active_borrowings_count }} currently active</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 py-4 text-center">No patron activity recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Overdue Loans Management Table --}}
    @if($overdueLoans->isNotEmpty())
        <div class="rounded-xl border border-rose-200 bg-white p-6 shadow-sm dark:border-rose-950 dark:bg-navy-900 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-rose-700 dark:text-rose-400 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    Critical Attention: Overdue Borrowings ({{ $overdueLoans->count() }})
                </h3>
                <a href="{{ route('admin.circulations.index', ['status' => 'overdue']) }}" class="text-xs font-bold text-rose-600 dark:text-rose-400 hover:underline">
                    View in Circulation Desk →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-rose-50 dark:bg-rose-950/40 text-rose-900 dark:text-rose-300 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-4 py-2.5">Patron</th>
                            <th class="px-4 py-2.5">Book Title</th>
                            <th class="px-4 py-2.5">Barcode</th>
                            <th class="px-4 py-2.5">Due Date</th>
                            <th class="px-4 py-2.5 text-right">Overdue By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-rose-100 dark:divide-rose-950/60">
                        @foreach($overdueLoans as $loan)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                    {{ $loan->user->name }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $loan->bookCopy?->book?->title ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 font-mono text-gray-500">
                                    {{ $loan->bookCopy?->barcode ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-rose-600 dark:text-rose-400 font-semibold">
                                    {{ $loan->due_at->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-rose-600 dark:text-rose-400">
                                    {{ $loan->due_at->diffForHumans(['parts' => 1]) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-layouts.admin>
