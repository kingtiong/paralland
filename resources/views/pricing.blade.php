<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pricing — {{ config('app.name', 'Paralland Quest') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-zinc-950 text-zinc-100">
        <div class="mx-auto max-w-5xl px-6 py-12">
            <div class="flex items-center justify-between gap-4">
                <a href="/" class="text-sm text-zinc-300 hover:text-zinc-100">← Back</a>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm text-zinc-300 hover:text-zinc-100">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-zinc-300 hover:text-zinc-100">Log in</a>
                        <a href="{{ route('register') }}" class="rounded-md bg-violet-600 px-3 py-2 text-sm font-semibold text-white hover:bg-violet-500">Start for USD 0.10</a>
                    @endauth
                </div>
            </div>

            <div class="mt-10">
                <h1 class="text-3xl font-semibold tracking-tight">Radically Low Entry. Sustainable Operation.</h1>
                <p class="mt-3 text-zinc-300">
                    Ideas should be cheap to start. Systems should be stable to run. Businesses should grow without financial pressure.
                </p>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2">
                <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6">
                    <div class="text-sm font-semibold text-zinc-100">Step 1: Start Building</div>
                    <div class="mt-2 text-3xl font-semibold text-zinc-100">USD 0.10</div>
                    <ul class="mt-4 space-y-2 text-sm text-zinc-300">
                        <li>- Create your project</li>
                        <li>- Access core system builder</li>
                        <li>- Start designing your workflow</li>
                        <li>- Enter the Paralland ecosystem</li>
                    </ul>
                    <div class="mt-4 text-sm text-zinc-400">This is your “Hello, World” moment.</div>
                </div>

                <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6">
                    <div class="text-sm font-semibold text-zinc-100">Step 2: Run It Properly</div>
                    <div class="mt-2 text-3xl font-semibold text-zinc-100">From USDT 20 / month</div>
                    <ul class="mt-4 space-y-2 text-sm text-zinc-300">
                        <li>- Server & hosting</li>
                        <li>- System maintenance</li>
                        <li>- Security updates</li>
                        <li>- Technical support</li>
                        <li>- Continuous improvements</li>
                    </ul>
                    <div class="mt-4 text-sm text-zinc-400">No hidden fees. No enterprise lock-in.</div>
                </div>
            </div>

            <div class="mt-10 rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6">
                <div class="text-sm font-semibold text-zinc-100">Why this model?</div>
                <div class="mt-3 grid gap-3 text-sm text-zinc-300 md:grid-cols-3">
                    <div class="rounded-xl border border-zinc-800 bg-zinc-950/40 p-4">Ideas should be cheap to start</div>
                    <div class="rounded-xl border border-zinc-800 bg-zinc-950/40 p-4">Systems should be stable to run</div>
                    <div class="rounded-xl border border-zinc-800 bg-zinc-950/40 p-4">Businesses should grow without pressure</div>
                </div>
            </div>

            <div class="mt-12 text-center text-sm text-zinc-500">
                In 1972, “Hello, World” taught us how to talk to machines. In Paralland, we learn how to build systems that serve people.
                <span class="text-zinc-400">This is not just development. This is a quest.</span>
            </div>
        </div>
    </body>
</html>

