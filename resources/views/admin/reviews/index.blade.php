<x-layouts.admin title="Reviews Moderation — ReadOra Admin" header="Patron Reviews & Ratings Moderation">
    {{-- Filter Tabs & Search --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.reviews.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ empty($status) ? 'bg-navy-900 text-white dark:bg-gold-500 dark:text-navy-950' : 'bg-white dark:bg-navy-900 border border-gray-200 dark:border-navy-800 text-gray-600 dark:text-gray-300' }}">
                All Reviews ({{ $totalCount }})
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'approved' ? 'bg-emerald-600 text-white' : 'bg-white dark:bg-navy-900 border border-gray-200 dark:border-navy-800 text-emerald-600 dark:text-emerald-400' }}">
                Approved ({{ $approvedCount }})
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'pending' ? 'bg-amber-600 text-white' : 'bg-white dark:bg-navy-900 border border-gray-200 dark:border-navy-800 text-amber-600 dark:text-amber-400' }}">
                Pending ({{ $pendingCount }})
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'rejected' ? 'bg-rose-600 text-white' : 'bg-white dark:bg-navy-900 border border-gray-200 dark:border-navy-800 text-rose-600 dark:text-rose-400' }}">
                Rejected ({{ $rejectedCount }})
            </a>
        </div>

        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex gap-2">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search review title, text, user..."
                class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-900 text-xs text-gray-900 dark:text-white"
            />
            <button type="submit" class="px-3 py-1.5 bg-navy-900 dark:bg-gold-500 text-white dark:text-navy-950 font-bold rounded-lg text-xs hover:opacity-90">
                Search
            </button>
        </form>
    </div>

    {{-- Reviews Table --}}
    <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-navy-800 dark:bg-navy-900 overflow-hidden">
        @if($reviews->isEmpty())
            <div class="p-8 text-center text-gray-400 text-xs">No reviews found matching criteria.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 dark:bg-navy-950 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-4 py-3">Patron</th>
                            <th class="px-4 py-3">Book</th>
                            <th class="px-4 py-3">Rating</th>
                            <th class="px-4 py-3">Review & Content</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3 text-right">Moderation</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-navy-800">
                        @foreach($reviews as $rev)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-navy-800/40">
                                <td class="px-4 py-3">
                                    <strong class="text-gray-900 dark:text-white block">{{ $rev->user->name }}</strong>
                                    <span class="text-[10px] text-gray-400 font-mono">{{ $rev->user->email }}</span>
                                </td>
                                <td class="px-4 py-3 max-w-xs truncate">
                                    <a href="{{ route('books.show', $rev->book->slug) }}" target="_blank" class="font-medium text-navy-600 dark:text-gold-400 hover:underline">
                                        {{ $rev->book->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-bold text-gold-500 text-xs">{{ $rev->rating }} / 5 ★</span>
                                </td>
                                <td class="px-4 py-3 max-w-sm">
                                    @if($rev->title)
                                        <p class="font-bold text-gray-900 dark:text-white mb-0.5 truncate">{{ $rev->title }}</p>
                                    @endif
                                    @if($rev->content)
                                        <p class="text-gray-600 dark:text-gray-400 line-clamp-2 text-[11px]">{{ $rev->content }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <x-badge :variant="$rev->status === 'approved' ? 'available' : ($rev->status === 'pending' ? 'default' : 'danger')" size="sm">
                                        {{ ucfirst($rev->status) }}
                                    </x-badge>
                                </td>
                                <td class="px-4 py-3 text-gray-400 whitespace-nowrap text-[11px]">
                                    {{ $rev->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        @if($rev->status !== 'approved')
                                            <form method="POST" action="{{ route('admin.reviews.status', $rev) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[10px] font-bold">
                                                    Approve
                                                </button>
                                            </form>
                                        @endif

                                        @if($rev->status !== 'rejected')
                                            <form method="POST" action="{{ route('admin.reviews.status', $rev) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="px-2 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded text-[10px] font-bold">
                                                    Reject
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.reviews.destroy', $rev) }}" onsubmit="return confirm('Permanently delete this review?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded text-[10px] font-bold">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100 dark:border-navy-800">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
