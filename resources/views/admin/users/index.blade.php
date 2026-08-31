<x-layouts.admin title="Users & Roles — ReadOra Admin" header="User Accounts & Role Management">
    {{-- Search & Filter --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ empty($role) ? 'bg-navy-900 text-white dark:bg-gold-500 dark:text-navy-950' : 'bg-white dark:bg-navy-900 border border-gray-200 dark:border-navy-800 text-gray-600 dark:text-gray-300' }}">
                All Users ({{ $totalUsers }})
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $role === 'admin' ? 'bg-navy-900 text-white dark:bg-gold-500 dark:text-navy-950' : 'bg-white dark:bg-navy-900 border border-gray-200 dark:border-navy-800 text-gray-600 dark:text-gray-300' }}">
                Admins ({{ $adminCount }})
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'user']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $role === 'user' ? 'bg-navy-900 text-white dark:bg-gold-500 dark:text-navy-950' : 'bg-white dark:bg-navy-900 border border-gray-200 dark:border-navy-800 text-gray-600 dark:text-gray-300' }}">
                Patrons ({{ $patronCount }})
            </a>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
            @if($role)
                <input type="hidden" name="role" value="{{ $role }}">
            @endif
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search name, email..."
                class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-navy-700 bg-white dark:bg-navy-900 text-xs text-gray-900 dark:text-white"
            />
            <button type="submit" class="px-3 py-1.5 bg-navy-900 dark:bg-gold-500 text-white dark:text-navy-950 font-bold rounded-lg text-xs hover:opacity-90">
                Filter
            </button>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-navy-800 dark:bg-navy-900 overflow-hidden">
        @if($users->isEmpty())
            <div class="p-8 text-center text-gray-400 text-xs">No users found.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 dark:bg-navy-950 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-4 py-3">User</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Active Loans</th>
                            <th class="px-4 py-3">Total Borrowings</th>
                            <th class="px-4 py-3">Favorites</th>
                            <th class="px-4 py-3">Registered</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-navy-800">
                        @foreach($users as $u)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-navy-800/40">
                                <td class="px-4 py-3">
                                    <strong class="text-gray-900 dark:text-white block">{{ $u->name }}</strong>
                                    <span class="text-[10px] text-gray-400 font-mono">{{ $u->email }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $u->isAdmin() ? 'bg-gold-500/20 text-gold-600 dark:text-gold-400 border border-gold-500/30' : 'bg-gray-100 dark:bg-navy-800 text-gray-600 dark:text-gray-300' }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($u->active_borrowings_count > 0)
                                        <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-300 text-[10px] font-bold">
                                            {{ $u->active_borrowings_count }} active
                                        </span>
                                    @else
                                        <span class="text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                    {{ $u->borrowings_count }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                    {{ $u->favorites_count }}
                                </td>
                                <td class="px-4 py-3 text-gray-400 whitespace-nowrap">
                                    {{ $u->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        {{-- Toggle Role --}}
                                        <form method="POST" action="{{ route('admin.users.role', $u) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="role" value="{{ $u->isAdmin() ? 'user' : 'admin' }}">
                                            <button type="submit" class="text-xs font-semibold text-navy-600 dark:text-gold-400 hover:underline">
                                                {{ $u->isAdmin() ? 'Demote to Patron' : 'Promote to Admin' }}
                                            </button>
                                        </form>

                                        {{-- Delete User --}}
                                        @if(auth()->id() !== $u->id)
                                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Permanently delete user {{ $u->name }}?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100 dark:border-navy-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
