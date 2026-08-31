<x-layouts.admin title="Publishers — ReadOra Admin" header="Publishers Management">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Add Publisher Form --}}
        <div class="lg:col-span-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900 h-fit">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4">+ Add New Publisher</h3>
            <form method="POST" action="{{ route('admin.publishers.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold uppercase text-gray-500 mb-1">Publisher Name *</label>
                    <input type="text" name="name" required placeholder="e.g. MIT Press" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white" />
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase text-gray-500 mb-1">Website URL</label>
                    <input type="url" name="website" placeholder="https://mitpress.mit.edu" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white" />
                </div>
                <button type="submit" class="w-full py-2 bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold rounded-lg text-xs shadow-sm transition-colors">
                    Save Publisher
                </button>
            </form>
        </div>

        {{-- Publishers List --}}
        <div class="lg:col-span-8 rounded-lg border border-gray-200 bg-white shadow-sm dark:border-navy-800 dark:bg-navy-900 overflow-hidden">
            <div class="p-4 border-b border-gray-100 dark:border-navy-800 flex items-center justify-between gap-4">
                <form method="GET" action="{{ route('admin.publishers.index') }}" class="flex gap-2 w-full max-w-sm">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search publishers..." class="w-full px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-xs text-gray-900 dark:text-white" />
                    <button type="submit" class="px-3 py-1.5 bg-navy-900 dark:bg-gold-500 text-white dark:text-navy-950 font-bold rounded-lg text-xs">Search</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 dark:bg-navy-950 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-4 py-3">Publisher</th>
                            <th class="px-4 py-3">Website</th>
                            <th class="px-4 py-3">Published Books</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-navy-800">
                        @foreach($publishers as $pub)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-navy-800/40">
                                <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">
                                    {{ $pub->name }}
                                </td>
                                <td class="px-4 py-3 text-gray-500">
                                    @if($pub->website)
                                        <a href="{{ $pub->website }}" target="_blank" class="text-gold-600 dark:text-gold-400 hover:underline">{{ $pub->website }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-semibold text-navy-600 dark:text-gold-400">
                                    {{ $pub->books_count }} books
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    @if($pub->books_count === 0)
                                        <form method="POST" action="{{ route('admin.publishers.destroy', $pub) }}" onsubmit="return confirm('Delete publisher {{ $pub->name }}?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">
                                                Delete
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
                {{ $publishers->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
