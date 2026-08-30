<footer class="bg-navy-900 dark:bg-navy-950 text-gray-400 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <x-logo class="h-8" variant="dark" />
                <p class="mt-4 text-sm text-gray-500">Your modern digital library. Discover, borrow, and explore books with the power of AI.</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-300 uppercase tracking-wider">Quick Links</h3>
                <ul class="mt-4 space-y-2">
                    <li><a href="/books" class="text-sm text-gray-500 hover:text-gold-400 transition-colors">Browse Books</a></li>
                    <li><a href="/login" class="text-sm text-gray-500 hover:text-gold-400 transition-colors">Sign In</a></li>
                    <li><a href="/register" class="text-sm text-gray-500 hover:text-gold-400 transition-colors">Register</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-300 uppercase tracking-wider">About</h3>
                <ul class="mt-4 space-y-2">
                    <li><span class="text-sm text-gray-500">Built with Laravel & AI</span></li>
                    <li><span class="text-sm text-gray-500">ITI Graduation Project</span></li>
                </ul>
            </div>
        </div>
        <div class="mt-8 pt-8 border-t border-navy-800 text-center">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} ReadOra. All rights reserved.</p>
        </div>
    </div>
</footer>
