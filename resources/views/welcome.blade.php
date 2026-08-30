<x-layouts.app :title="'Welcome'">
    {{-- Hero Section --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-navy-900 via-navy-800 to-navy-950"></div>
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="0.5" class="text-gold-400" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 lg:py-40">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 bg-gold-500/10 border border-gold-500/20 rounded-full px-4 py-1.5 mb-8">
                    <span class="w-2 h-2 bg-gold-400 rounded-full animate-pulse"></span>
                    <span class="text-sm font-medium text-gold-300">AI-Powered Library Management</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight">
                    Discover, Borrow & <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-gold-300 to-gold-500">Explore Knowledge</span>
                </h1>

                <p class="mt-6 text-lg text-gray-300 max-w-2xl mx-auto">
                    ReadOra is your modern digital library. Browse thousands of books, get personalized
                    recommendations, and interact with an intelligent AI assistant — all in one place.
                </p>

                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <x-button href="/register" size="lg" variant="primary">
                        Get Started
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </x-button>
                    <x-button href="/books" size="lg" variant="outline" class="border-gray-500 text-gray-300 hover:bg-white/10 dark:border-gray-500 dark:hover:bg-white/10">
                        Browse Library
                    </x-button>
                </div>
            </div>
        </div>

        {{-- Decorative bottom wave --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 80L60 68C120 56 240 32 360 24C480 16 600 24 720 36C840 48 960 64 1080 68C1200 72 1320 64 1380 60L1440 56V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" class="fill-gray-50 dark:fill-navy-950" />
            </svg>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-20 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                    Everything You Need in a <span class="text-gold-600 dark:text-gold-400">Modern Library</span>
                </h2>
                <p class="mt-4 text-gray-600 dark:text-gray-400">
                    Powerful features designed for readers, researchers, and library administrators.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="group p-6 bg-white dark:bg-navy-900 rounded-2xl border border-gray-200 dark:border-navy-800 hover:border-gold-300 dark:hover:border-gold-600 transition-all duration-300 hover:shadow-lg">
                    <div class="w-12 h-12 bg-gold-100 dark:bg-gold-900/30 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-gold-600 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Book Discovery</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Search, filter, and explore our curated collection of books across multiple categories and authors.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="group p-6 bg-white dark:bg-navy-900 rounded-2xl border border-gray-200 dark:border-navy-800 hover:border-gold-300 dark:hover:border-gold-600 transition-all duration-300 hover:shadow-lg">
                    <div class="w-12 h-12 bg-navy-100 dark:bg-navy-800 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-navy-600 dark:text-navy-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Easy Borrowing</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Borrow books with a single click. Track due dates, manage returns, and view your complete borrowing history.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="group p-6 bg-white dark:bg-navy-900 rounded-2xl border border-gray-200 dark:border-navy-800 hover:border-gold-300 dark:hover:border-gold-600 transition-all duration-300 hover:shadow-lg">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Smart Recommendations</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Get personalized book suggestions based on your reading history, favorites, and interests.</p>
                </div>

                {{-- Feature 4 --}}
                <div class="group p-6 bg-white dark:bg-navy-900 rounded-2xl border border-gray-200 dark:border-navy-800 hover:border-gold-300 dark:hover:border-gold-600 transition-all duration-300 hover:shadow-lg">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">AI Assistant</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Chat with our intelligent AI assistant for book recommendations, library help, and personalized guidance.</p>
                </div>

                {{-- Feature 5 --}}
                <div class="group p-6 bg-white dark:bg-navy-900 rounded-2xl border border-gray-200 dark:border-navy-800 hover:border-gold-300 dark:hover:border-gold-600 transition-all duration-300 hover:shadow-lg">
                    <div class="w-12 h-12 bg-rose-100 dark:bg-rose-900/30 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Favorites Collection</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Save your favorite books to revisit later. Build your personal reading list and track what interests you.</p>
                </div>

                {{-- Feature 6 --}}
                <div class="group p-6 bg-white dark:bg-navy-900 rounded-2xl border border-gray-200 dark:border-navy-800 hover:border-gold-300 dark:hover:border-gold-600 transition-all duration-300 hover:shadow-lg">
                    <div class="w-12 h-12 bg-sky-100 dark:bg-sky-900/30 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Analytics Dashboard</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Administrators get powerful analytics with borrowing trends, popular books, and user engagement insights.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="py-16 bg-navy-900 dark:bg-navy-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl sm:text-4xl font-bold text-gold-400">200+</div>
                    <div class="mt-2 text-sm text-gray-400">Curated Books</div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-bold text-gold-400">50+</div>
                    <div class="mt-2 text-sm text-gray-400">Categories</div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-bold text-gold-400">24/7</div>
                    <div class="mt-2 text-sm text-gray-400">AI Assistance</div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-bold text-gold-400">100%</div>
                    <div class="mt-2 text-sm text-gray-400">Real Book Data</div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative bg-gradient-to-r from-navy-800 to-navy-900 rounded-3xl p-8 sm:p-12 lg:p-16 overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gold-400/5 rounded-full blur-2xl"></div>

                <div class="relative text-center max-w-2xl mx-auto">
                    <h2 class="text-3xl sm:text-4xl font-bold text-white">
                        Ready to Start Reading?
                    </h2>
                    <p class="mt-4 text-gray-300">
                        Join ReadOra today and explore our extensive library. Create your account in seconds.
                    </p>
                    <div class="mt-8">
                        <x-button href="/register" size="lg" variant="primary">
                            Create Free Account
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
