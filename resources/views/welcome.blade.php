<x-layouts.app title="ReadOra">
    <section class="mx-auto flex min-h-screen w-full max-w-7xl flex-col px-6 py-6 sm:px-8 lg:px-10">
        <header class="flex items-center justify-between gap-4">
            <x-brand-mark />

            <div class="flex items-center gap-2 rounded-md border border-readora-navy/15 bg-white/70 p-1 text-xs font-semibold text-readora-navy shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-readora-paper">
                <button type="button" class="rounded px-3 py-2 hover:bg-readora-paper dark:hover:bg-white/10" onclick="window.readoraTheme.set('light')">Light</button>
                <button type="button" class="rounded px-3 py-2 hover:bg-readora-paper dark:hover:bg-white/10" onclick="window.readoraTheme.set('dark')">Dark</button>
                <button type="button" class="rounded px-3 py-2 hover:bg-readora-paper dark:hover:bg-white/10" onclick="window.readoraTheme.set('system')">System</button>
            </div>
        </header>

        <main class="grid flex-1 items-center gap-10 py-12 lg:grid-cols-[1.05fr_0.95fr] lg:py-16">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-normal text-readora-forest dark:text-readora-amber">Library management with guarded AI</p>
                <h1 class="mt-5 text-4xl font-semibold leading-tight text-readora-ink dark:text-readora-paper sm:text-5xl lg:text-6xl">
                    ReadOra
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-700 dark:text-slate-300">
                    A Laravel foundation for book discovery, circulation workflows, administration, recommendations, and a role-aware assistant built around secure server-side authorization.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#foundation" class="rounded-md bg-readora-navy px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-readora-midnight dark:bg-readora-amber dark:text-readora-midnight dark:hover:bg-readora-gold">
                        View Foundation
                    </a>
                    <a href="{{ url('/up') }}" class="rounded-md border border-readora-navy/20 px-5 py-3 text-sm font-semibold text-readora-navy hover:bg-white/70 dark:border-white/15 dark:text-readora-paper dark:hover:bg-white/10">
                        Health Check
                    </a>
                </div>
            </div>

            <div class="rounded-lg border border-readora-navy/15 bg-white/80 p-6 shadow-xl shadow-readora-navy/10 dark:border-white/10 dark:bg-white/5 dark:shadow-black/20">
                <div class="grid gap-4">
                    <div class="rounded-md bg-readora-navy p-5 text-white dark:bg-readora-paper dark:text-readora-midnight">
                        <p class="text-sm font-semibold text-readora-amber dark:text-readora-forest">Phase 1</p>
                        <h2 class="mt-2 text-2xl font-semibold">Laravel Foundation</h2>
                        <p class="mt-3 text-sm leading-6 text-white/75 dark:text-readora-midnight/75">
                            Framework scaffold, environment defaults, theme shell, documentation structure, and smoke-testable routes.
                        </p>
                    </div>

                    <div id="foundation" class="grid gap-3 text-sm text-slate-700 dark:text-slate-300">
                        <div class="flex items-center justify-between rounded-md border border-slate-200 bg-white px-4 py-3 dark:border-white/10 dark:bg-white/5">
                            <span>Laravel application</span>
                            <span class="font-semibold text-readora-forest dark:text-readora-amber">Ready</span>
                        </div>
                        <div class="flex items-center justify-between rounded-md border border-slate-200 bg-white px-4 py-3 dark:border-white/10 dark:bg-white/5">
                            <span>Theme preference</span>
                            <span class="font-semibold text-readora-forest dark:text-readora-amber">Light / Dark / System</span>
                        </div>
                        <div class="flex items-center justify-between rounded-md border border-slate-200 bg-white px-4 py-3 dark:border-white/10 dark:bg-white/5">
                            <span>AI provider config</span>
                            <span class="font-semibold text-readora-forest dark:text-readora-amber">Abstracted</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </section>
</x-layouts.app>
