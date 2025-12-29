<x-guest-layout>
    <div class="mb-6">
        <div class="text-xl font-semibold text-zinc-100">Start Your System for USD 0.10</div>
        <div class="mt-2 text-sm text-zinc-300">
            Every business system begins with a single step. Today, that step costs less than a cup of coffee.
        </div>
        <div class="mt-3 text-sm text-zinc-400">
            Already have an account?
            <a href="{{ route('login') }}" class="text-violet-300 hover:text-violet-200 underline underline-offset-4">
                Log in
            </a>
        </div>
    </div>

    <div class="mb-6 rounded-xl border border-zinc-800 bg-zinc-950/40 p-4 text-sm text-zinc-300">
        <div class="font-semibold text-zinc-100">What happens after registration?</div>
        <ul class="mt-2 space-y-1 text-zinc-300">
            <li>- Create your first project</li>
            <li>- Choose your system type</li>
            <li>- Access builder tools</li>
            <li>- Get advisory guidance</li>
            <li>- Upgrade only when ready</li>
        </ul>
        <div class="mt-3 text-xs text-zinc-400">No long-term lock-in. Cancel anytime.</div>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                Start My Quest
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
