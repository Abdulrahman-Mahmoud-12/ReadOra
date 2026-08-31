<x-layouts.admin title="Admin Dashboard — ReadOra" header="Library Administration Overview">
    {{-- Key Circulation & Inventory Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card
            title="Total Catalog Works"
            :value="number_format($totalBooks)"
            subtitle="Unique bibliographic records"
            variant="gold"
        >
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card
            title="Physical Shelf Copies"
            :value="number_format($totalCopies)"
            :subtitle="$availableCopies . ' available for loan'"
            variant="success"
        >
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card
            title="Active Circulation Loans"
            :value="number_format($activeLoansCount)"
            :subtitle="$overdueCount > 0 ? $overdueCount . ' overdue items!' : '0 overdue items'"
            :variant="$overdueCount > 0 ? 'danger' : 'default'"
        >
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card
            title="Registered Patrons"
            :value="number_format($totalUsers)"
            :subtitle="$activeBorrowers . ' active borrowers'"
            variant="purple"
        >
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    {{-- Activity & Audit Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Recent Circulation Activity --}}
        <div class="lg:col-span-8 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Recent Circulation Events</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Latest patron checkouts, returns, and loans.</p>
                </div>
                <a href="{{ route('admin.circulations.index') }}" class="text-xs font-semibold text-gold-600 dark:text-gold-400 hover:underline">
                    View All Circulations →
                </a>
            </div>

            @if($recentBorrowings->isEmpty())
                <p class="text-xs text-gray-400 py-8 text-center">No circulation activity recorded yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 dark:bg-navy-950 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                            <tr>
                                <th class="px-3 py-2.5 rounded-l-lg">Patron</th>
                                <th class="px-3 py-2.5">Book</th>
                                <th class="px-3 py-2.5">Due Date</th>
                                <th class="px-3 py-2.5">Status</th>
                                <th class="px-3 py-2.5 rounded-r-lg text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-navy-800">
                            @foreach($recentBorrowings as $loan)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-navy-800/40">
                                    <td class="px-3 py-2.5 font-medium text-gray-900 dark:text-white">
                                        {{ $loan->user->name }}
                                        <span class="text-[10px] text-gray-400 block">{{ $loan->user->email }}</span>
                                    </td>
                                    <td class="px-3 py-2.5 text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                        {{ $loan->bookCopy->book->title }}
                                        <span class="text-[10px] font-mono text-gray-400 block">{{ $loan->bookCopy->barcode }}</span>
                                    </td>
                                    <td class="px-3 py-2.5 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                        {{ $loan->due_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <x-badge :variant="$loan->isOverdue() ? 'danger' : ($loan->status === 'active' ? 'available' : 'default')" size="sm">
                                            {{ $loan->isOverdue() ? 'Overdue' : ucfirst($loan->status) }}
                                        </x-badge>
                                    </td>
                                    <td class="px-3 py-2.5 text-right whitespace-nowrap">
                                        @if($loan->status === 'active')
                                            <form method="POST" action="{{ route('admin.circulations.return', $loan) }}" class="inline-block">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[11px] font-semibold transition-colors">
                                                    Check In
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-[11px]">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Recent Audit Logs --}}
        <div class="lg:col-span-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900 flex flex-col justify-between">
            <div>
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Admin Audit Feed</h2>
                    <a href="{{ route('admin.audit-logs.index') }}" class="text-xs font-semibold text-navy-600 dark:text-gold-400 hover:underline">
                        All Logs →
                    </a>
                </div>

                @if($recentAuditLogs->isEmpty())
                    <p class="text-xs text-gray-400 py-6 text-center">No administrative actions logged yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($recentAuditLogs as $log)
                            <div class="p-3 bg-gray-50 dark:bg-navy-950 rounded-lg border border-gray-100 dark:border-navy-800 text-xs">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-bold text-navy-800 dark:text-gold-400 font-mono text-[11px]">
                                        {{ $log->action }}
                                    </span>
                                    <span class="text-[10px] text-gray-400">
                                        {{ $log->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">
                                    By <strong class="text-gray-900 dark:text-white">{{ $log->actor?->name ?? 'System' }}</strong>
                                    @if($log->entity_type)
                                        on {{ $log->entity_type }} #{{ $log->entity_id }}
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-navy-800">
                <a href="{{ route('admin.books.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold text-xs shadow-sm transition-colors">
                    + Add New Book to Library
                </a>
            </div>
        </div>
    </div>
</x-layouts.admin>
