<x-layouts.guest title="Create Account — ReadOra">
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create Account</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Join ReadOra to explore, borrow, and track books</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input
                for="name"
                label="Full Name"
                id="name"
                name="name"
                type="text"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="Jane Doe"
                :error="$errors->first('name')"
            />
        </div>

        <div>
            <x-input
                for="email"
                label="Email Address"
                id="email"
                name="email"
                type="email"
                :value="old('email')"
                required
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
                autocomplete="new-password"
                placeholder="••••••••"
                :error="$errors->first('password')"
            />
        </div>

        <div>
            <x-input
                for="password_confirmation"
                label="Confirm Password"
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                placeholder="••••••••"
                :error="$errors->first('password_confirmation')"
            />
        </div>

        <x-button type="submit" variant="primary" class="w-full justify-center">
            Create Account
        </x-button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-gold-600 hover:text-gold-500 dark:text-gold-400">Sign in</a>
    </div>
</x-layouts.guest>
