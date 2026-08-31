<x-layouts.admin title="Book Copies — ReadOra Admin" header="Physical Shelf Copies Inventory">
    {{-- Add Copy Form Card --}}
    <div class="mb-8 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900">
        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4">+ Add Physical Inventory Copy</h3>
        <form method="POST" action="{{ route('admin.copies.store') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            @csrf
            <div>
                <label class="block text-[11px] font-bold uppercase text-gray-500 mb-1">Target Book *</label>
                <select name="book_id" required class="w-full px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white">
                    <option value="">Select book...</option>
                    @foreach($books as $b)
                        <option value="{{ $b->id }}" {{ $bookId === $b->id ? 'selected' : '' }}>{{ $b->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase text-gray-500 mb-1">Barcode *</label>
                <input type="text" name="barcode" placeholder="READ-CLEAN-001-03" required class="w-full px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white font-mono" />
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase text-gray-500 mb-1">Shelf Location</label>
                <input type="text" name="location" placeholder="Main Stacks, Shelf B-4" class="w-full px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white" />
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase text-gray-500 mb-1">Condition</label>
                <div class="flex gap-2">
                    <select name="condition" class="flex-1 px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white">
                        <option value="new">New</option>
                        <option value="good" selected>Good</option>
                        <option value="fair">Fair</option>
                        <option value="damaged">Damaged</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                    <button type="submit" class="px-4 py-1.5 bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold rounded-lg text-xs shadow-sm transition-colors shrink-0">
                        Add Copy
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Filters --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('admin.copies.index') }}" class="flex flex-wrap gap-2">
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search barcode, location, book..."
                class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-900 text-xs text-gray-900 dark:text-white"
            />
            <select name="status" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-900 text-xs text-gray-900 dark:text-white">
                <option value="">All Statuses</option>
                <option value="available" {{ $status === 'available' ? 'selected' : '' }}>Available</option>
                <option value="borrowed" {{ $status === 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                <option value="maintenance" {{ $status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                <option value="damaged" {{ $status === 'damaged' ? 'selected' : '' }}>Damaged</option>
            </select>
            <button type="submit" class="px-3 py-1.5 bg-navy-900 dark:bg-gold-500 text-white dark:text-navy-950 font-bold rounded-lg text-xs hover:opacity-90">
                Filter
            </button>
        </form>
    </div>

    {{-- Copies Table --}}
    <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-navy-800 dark:bg-navy-900 overflow-hidden">
        @if($copies->isEmpty())
            <div class="p-8 text-center text-gray-400 text-xs">No physical copies found.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 dark:bg-navy-950 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-4 py-3">Barcode</th>
                            <th class="px-4 py-3">Book Title</th>
                            <th class="px-4 py-3">Location</th>
                            <th class="px-4 py-3">Condition</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Active Loan</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-navy-800">
                        @foreach($copies as $copy)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-navy-800/40">
                                <td class="px-4 py-3 font-mono font-bold text-gray-900 dark:text-white">
                                    {{ $copy->barcode }}
                                </td>
                                <td class="px-4 py-3 max-w-xs truncate">
                                    <a href="{{ route('books.show', $copy->book->slug) }}" target="_blank" class="text-navy-600 dark:text-gold-400 hover:underline">
                                        {{ $copy->book->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                    {{ $copy->location ?? 'Main Stacks' }}
                                </td>
                                <td class="px-4 py-3 uppercase text-[10px] font-semibold text-gray-500">
                                    {{ $copy->condition }}
                                </td>
                                <td class="px-4 py-3">
                                    <x-badge :variant="$copy->status === 'available' ? 'available' : ($copy->status === 'borrowed' ? 'borrowed' : 'danger')" size="sm">
                                        {{ ucfirst($copy->status) }}
                                    </x-badge>
                                </td>
                                <td class="px-4 py-3">
                                    @if($copy->activeBorrowing)
                                        <span class="text-gray-900 dark:text-white font-medium block">{{ $copy->activeBorrowing->user->name }}</span>
                                        <span class="text-[10px] text-gray-400">Due {{ $copy->activeBorrowing->due_at->format('M d') }}</span>
                                    @else
                                        <span class="text-gray-400 text-[11px]">On Shelf</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    @if($copy->status !== 'borrowed')
                                        <form method="POST" action="{{ route('admin.copies.destroy', $copy) }}" onsubmit="return confirm('Remove copy {{ $copy->barcode }}?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">
                                                Remove
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-gray-400">In Use</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100 dark:border-navy-800">
                {{ $copies->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
