<x-layouts.app title="Patron Profile — ReadOra">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        {{-- Header --}}
        <div class="mb-8">
            <nav class="mb-2 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                <a href="{{ route('dashboard') }}" class="hover:text-navy-600 dark:hover:text-gold-400">Dashboard</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white font-medium">Profile</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Patron Profile & Settings
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Manage your personal information, digital library card, and account settings.
            </p>
        </div>

        {{-- Success Flash Banner --}}
        @if(session('status') === 'profile-updated')
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Your profile details have been successfully updated.</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Left Column: Digital Library Card --}}
            <div class="lg:col-span-5 flex flex-col gap-6">
                {{-- Digital Library Card --}}
                <div class="relative overflow-hidden rounded-lg border border-navy-800 bg-gradient-to-br from-navy-950 via-navy-900 to-navy-800 p-6 text-white shadow-2xl sm:p-8">
                    <div class="absolute top-0 right-0 p-6 opacity-20">
                        <x-brand-mark class="h-16 w-16" />
                    </div>

                    <div class="relative flex min-h-52 flex-col justify-between gap-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-2">
                                <x-brand-mark class="h-6 w-6 shrink-0" />
                                <span class="truncate text-sm font-bold tracking-wider text-gold-400">READORA PATRON</span>
                            </div>
                            <x-badge variant="gold" size="sm" class="shrink-0">
                                {{ ucfirst($user->role) }}
                            </x-badge>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 font-mono tracking-widest uppercase">Digital Card Number</p>
                            <p class="text-lg sm:text-xl font-mono font-bold tracking-widest text-white mt-0.5">
                                {{ sprintf('RO-%06d', $user->id) }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-3 border-t border-navy-700/60 pt-4 sm:flex-row sm:items-end sm:justify-between">
                            <div class="min-w-0">
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider">Cardholder</p>
                                <p class="break-words text-sm font-semibold text-white">{{ $user->name }}</p>
                            </div>
                            <div class="shrink-0 sm:text-right">
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider">Member Since</p>
                                <p class="text-xs text-gray-300 font-medium">{{ $user->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Account Summary Card --}}
                <div class="space-y-3 rounded-lg border border-gray-200 bg-white p-6 text-xs shadow-sm dark:border-navy-800 dark:bg-navy-900">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 py-2 dark:border-navy-800">
                        <span class="text-gray-500 dark:text-gray-400">Account Role</span>
                        <span class="font-semibold text-gray-900 dark:text-white capitalize">{{ $user->role }}</span>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 py-2 dark:border-navy-800">
                        <span class="text-gray-500 dark:text-gray-400">Primary Email</span>
                        <span class="break-all text-right font-semibold text-gray-900 dark:text-white">{{ $user->email }}</span>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 py-2 dark:border-navy-800">
                        <span class="text-gray-500 dark:text-gray-400">Card Status</span>
                        <x-badge variant="success" size="sm">Active</x-badge>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-2 py-2">
                        <span class="text-gray-500 dark:text-gray-400">Borrowing Privileges</span>
                        <span class="font-semibold text-emerald-600 dark:text-emerald-400">Good Standing</span>
                    </div>
                </div>
            </div>

            {{-- Right Column: Edit Profile Form --}}
            <div class="lg:col-span-7">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900 sm:p-8">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">
                        Edit Profile Information
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">
                        Update your patron account name and contact email address.
                    </p>

                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        {{-- Full Name --}}
                        <div>
                            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                                Full Name
                            </label>
                            <x-input
                                type="text"
                                name="name"
                                id="name"
                                :value="old('name', $user->name)"
                                required
                                class="w-full"
                            />
                            @error('name')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email Address --}}
                        <div>
                            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                                Email Address
                            </label>
                            <x-input
                                type="email"
                                name="email"
                                id="email"
                                :value="old('email', $user->email)"
                                required
                                class="w-full"
                            />
                            @error('email')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-3 border-t border-gray-100 pt-4 dark:border-navy-800 sm:flex-row sm:items-center sm:justify-between">
                            <span class="text-xs text-gray-400">All updates take effect immediately.</span>
                            <x-button type="submit" variant="primary" size="md">
                                Save Profile Changes
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
