<x-layouts.admin title="Audit Trail — ReadOra Admin" header="System Audit Trail & Security Logs">
    {{-- Search & Filter --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex flex-wrap gap-2">
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search actor, action, IP..."
                class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-900 text-xs text-gray-900 dark:text-white"
            />
            <select name="action" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-900 text-xs text-gray-900 dark:text-white">
                <option value="">All Actions</option>
                <option value="book" {{ str_contains($action, 'book') ? 'selected' : '' }}>Book Events</option>
                <option value="copy" {{ str_contains($action, 'copy') ? 'selected' : '' }}>Copy Events</option>
                <option value="circulation" {{ str_contains($action, 'circulation') ? 'selected' : '' }}>Circulation Events</option>
                <option value="user" {{ str_contains($action, 'user') ? 'selected' : '' }}>User Events</option>
            </select>
            <button type="submit" class="px-3 py-1.5 bg-navy-900 dark:bg-gold-500 text-white dark:text-navy-950 font-bold rounded-lg text-xs hover:opacity-90">
                Filter
            </button>
        </form>
    </div>

    {{-- Audit Logs Table --}}
    <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-navy-800 dark:bg-navy-900 overflow-hidden">
        @if($logs->isEmpty())
            <div class="p-8 text-center text-gray-400 text-xs">No audit logs recorded yet.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 dark:bg-navy-950 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-4 py-3">Timestamp</th>
                            <th class="px-4 py-3">Actor</th>
                            <th class="px-4 py-3">Action</th>
                            <th class="px-4 py-3">Entity</th>
                            <th class="px-4 py-3">Changes / Payload</th>
                            <th class="px-4 py-3 text-right">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-navy-800">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-navy-800/40">
                                <td class="px-4 py-3 whitespace-nowrap text-gray-500 font-mono text-[11px]">
                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($log->actor)
                                        <strong class="text-gray-900 dark:text-white block">{{ $log->actor->name }}</strong>
                                        <span class="text-[10px] text-gray-400 font-mono">{{ $log->actor->email }}</span>
                                    @else
                                        <span class="text-gray-400 font-mono">System</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded font-mono text-[11px] font-bold bg-navy-100 dark:bg-navy-800 text-navy-800 dark:text-gold-400">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                    @if($log->entity_type)
                                        {{ $log->entity_type }} #{{ $log->entity_id }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 max-w-sm">
                                    @if($log->new_values || $log->old_values)
                                        <details class="cursor-pointer text-[10px] text-gray-500">
                                            <summary class="font-semibold text-navy-600 dark:text-gold-400 hover:underline">View Payload</summary>
                                            <pre class="mt-1 p-2 bg-gray-50 dark:bg-navy-950 rounded border border-gray-100 dark:border-navy-800 font-mono overflow-x-auto text-[10px]">{{ json_encode(['old' => $log->old_values, 'new' => $log->new_values], JSON_PRETTY_PRINT) }}</pre>
                                        </details>
                                    @else
                                        <span class="text-gray-400 text-[11px]">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-mono text-[11px] text-gray-400">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100 dark:border-navy-800">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
