<x-layouts.guest title="Sign In — ReadOra">
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome Back</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Sign in to your ReadOra library account</p>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-lg bg-emerald-50 p-3 text-sm text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input
                for="email"
                label="Email Address"
                id="email"
                name="email"
                type="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="name@example.com"
                :error="$errors->first('email')"
            />
        </div>

        <div>
            <x-input
                for="password"
                label="Password"
                id="password"
                name="password"
                type="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                :error="$errors->first('password')"
            />
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 cursor-pointer text-gray-700 dark:text-gray-300">
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    class="rounded border-gray-300 dark:border-navy-600 text-gold-500 focus:ring-gold-400 dark:bg-navy-800"
                >
                <span>Remember me</span>
            </label>
        </div>

        <x-button type="submit" variant="primary" class="w-full justify-center">
            Sign In
        </x-button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-gold-600 hover:text-gold-500 dark:text-gold-400">Create one</a>
    </div>
</x-layouts.guest>
