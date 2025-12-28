<x-admin-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <div class="text-xl font-semibold text-zinc-100">Admin Sign In</div>
        <div class="mt-2 text-sm text-zinc-300">
            Manage orders, interact with members, and keep the system running smoothly.
        </div>
        <div class="mt-3 text-sm text-zinc-400">
            Not an admin?
            <a href="{{ route('login') }}" class="text-violet-300 hover:text-violet-200 underline underline-offset-4">
                Go to member login
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-zinc-700 bg-zinc-950/60 text-violet-500 shadow-sm focus:ring-violet-500" name="remember">
                <span class="ms-2 text-sm text-zinc-300">Remember me</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">Log In</x-primary-button>
        </div>
    </form>
</x-admin-guest-layout>

