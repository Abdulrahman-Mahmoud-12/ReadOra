<x-layouts.admin title="Circulation Desk — ReadOra Admin" header="Circulation & Loans Desk">
    {{-- Status Filter Tabs & Search --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.circulations.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ empty($status) ? 'bg-navy-900 text-white dark:bg-gold-500 dark:text-navy-950' : 'bg-white dark:bg-navy-900 border border-gray-200 dark:border-navy-800 text-gray-600 dark:text-gray-300' }}">
                All Loans
            </a>
            <a href="{{ route('admin.circulations.index', ['status' => 'active']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'active' ? 'bg-navy-900 text-white dark:bg-gold-500 dark:text-navy-950' : 'bg-white dark:bg-navy-900 border border-gray-200 dark:border-navy-800 text-gray-600 dark:text-gray-300' }}">
                Active ({{ $activeCount }})
            </a>
            <a href="{{ route('admin.circulations.index', ['status' => 'overdue']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'overdue' ? 'bg-rose-600 text-white' : 'bg-white dark:bg-navy-900 border border-gray-200 dark:border-navy-800 text-rose-600 dark:text-rose-400' }}">
                Overdue ({{ $overdueCount }})
            </a>
            <a href="{{ route('admin.circulations.index', ['status' => 'returned']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'returned' ? 'bg-navy-900 text-white dark:bg-gold-500 dark:text-navy-950' : 'bg-white dark:bg-navy-900 border border-gray-200 dark:border-navy-800 text-gray-600 dark:text-gray-300' }}">
                Returned ({{ $returnedCount }})
            </a>
        </div>

        <form method="GET" action="{{ route('admin.circulations.index') }}" class="flex gap-2">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search patron, title, barcode..."
                class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-900 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-gold-500"
            />
            <button type="submit" class="px-3 py-1.5 bg-navy-900 dark:bg-gold-500 text-white dark:text-navy-950 font-bold rounded-lg text-xs hover:opacity-90">
                Filter
            </button>
        </form>
    </div>

    {{-- Circulations Table --}}
    <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-navy-800 dark:bg-navy-900 overflow-hidden">
        @if($borrowings->isEmpty())
            <div class="p-8 text-center text-gray-400 text-xs">
                No circulation loans found matching your criteria.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 dark:bg-navy-950 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Patron</th>
                            <th class="px-4 py-3">Book & Barcode</th>
                            <th class="px-4 py-3">Borrowed At</th>
                            <th class="px-4 py-3">Due Date</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-navy-800">
                        @foreach($borrowings as $loan)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-navy-800/40 {{ $loan->isOverdue() ? 'bg-rose-50/40 dark:bg-rose-950/20' : '' }}">
                                <td class="px-4 py-3 font-mono text-gray-400">#{{ $loan->id }}</td>
                                <td class="px-4 py-3">
                                    <strong class="text-gray-900 dark:text-white block">{{ $loan->user->name }}</strong>
                                    <span class="text-[10px] text-gray-400 font-mono">{{ $loan->user->email }}</span>
                                </td>
                                <td class="px-4 py-3 max-w-xs truncate">
                                    <span class="font-medium text-gray-900 dark:text-white block truncate">{{ $loan->bookCopy->book->title }}</span>
                                    <span class="text-[10px] font-mono text-gold-600 dark:text-gold-400">{{ $loan->bookCopy->barcode }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                    {{ $loan->borrowed_at->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="{{ $loan->isOverdue() ? 'text-rose-600 dark:text-rose-400 font-bold' : 'text-gray-600 dark:text-gray-300' }}">
                                        {{ $loan->due_at->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <x-badge :variant="$loan->isOverdue() ? 'danger' : ($loan->status === 'active' ? 'available' : 'default')" size="sm">
                                        {{ $loan->isOverdue() ? 'Overdue' : ucfirst($loan->status) }}
                                    </x-badge>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    @if($loan->status === 'active')
                                        <div class="inline-flex gap-2">
                                            <form method="POST" action="{{ route('admin.circulations.renew', $loan) }}">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-navy-800 hover:bg-navy-900 text-white rounded text-[11px] font-semibold transition-colors">
                                                    +14d Renew
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.circulations.return', $loan) }}">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[11px] font-semibold transition-colors">
                                                    Check In
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-gray-400">Returned {{ $loan->returned_at?->format('M d') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100 dark:border-navy-800">
                {{ $borrowings->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
