<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Paralland Quest') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-zinc-950 text-zinc-100">
        <div class="mx-auto max-w-6xl px-6 py-10">
            <header class="flex items-center justify-between gap-4">
                <div class="text-sm font-semibold tracking-tight">{{ config('app.name', 'Paralland Quest') }}</div>
                <nav class="flex items-center gap-4 text-sm">
                    <a href="{{ route('pricing') }}" class="text-zinc-300 hover:text-zinc-100">Pricing</a>
                    <a href="#modules" class="text-zinc-300 hover:text-zinc-100">Explore</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-md bg-zinc-800 px-3 py-2 font-semibold text-zinc-100 hover:bg-zinc-700">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-zinc-300 hover:text-zinc-100">Log in</a>
                        <a href="{{ route('register') }}" class="rounded-md bg-violet-600 px-3 py-2 font-semibold text-white hover:bg-violet-500">Start Building (USD 0.10)</a>
                    @endauth
                </nav>
            </header>

            <section class="mt-14">
                <div class="rounded-3xl border border-zinc-800 bg-zinc-900/50 p-8 md:p-12">
                    <div class="text-sm text-zinc-400">
                        The Real Meaning of “Hello, World”
                    </div>

                    <div class="mt-3 space-y-4">
                        <p class="text-zinc-200">
                            In the beginning, “Hello, World” was the first step into coding. Today, the real challenge is not writing code — it’s turning ideas into working systems.
                        </p>
                        <p class="text-zinc-300">
                            Paralland Quest exists to bridge that gap. We help individuals and teams start real digital systems — from MLM platforms to e-commerce, inventory, accounting, and HR — with almost zero upfront cost, and professional guidance.
                        </p>
                        <p class="text-zinc-300">
                            This is the journey from learning → building → operating → scaling — all in one place.
                        </p>
                    </div>

                    <h1 class="mt-8 text-4xl font-semibold tracking-tight md:text-5xl">
                        From Hello, World to Real Systems.
                    </h1>
                    <p class="mt-4 max-w-3xl text-zinc-300">
                        Build your own business system — MLM, e-commerce, inventory, accounting, HR, and more — starting from USD 0.10, then scale with affordable monthly support.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        @auth
                            <form method="POST" action="{{ route('orders.wizard.start') }}">
                                @csrf
                                <button type="submit" class="rounded-md bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-500">
                                    Start Building (USD 0.10)
                                </button>
                            </form>
                        @else
                            <a href="{{ route('register') }}" class="rounded-md bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-500">
                                Start Building (USD 0.10)
                            </a>
                        @endauth
                        <a href="#modules" class="rounded-md bg-zinc-800 px-4 py-2 text-sm font-semibold text-zinc-100 hover:bg-zinc-700">
                            Explore the Quest
                        </a>
                    </div>
                </div>
            </section>

            <section class="mt-14 rounded-3xl border border-zinc-800 bg-zinc-900/50 p-8 md:p-12" id="modules">
                <h2 class="text-2xl font-semibold tracking-tight">Build What Your Business Actually Needs</h2>
                <p class="mt-3 text-zinc-300">
                    Paralland is not a template marketplace. It is a system-building platform designed to support real businesses.
                </p>
                <p class="mt-2 text-zinc-400">Start small. Add modules only when you need them.</p>

                <div class="mt-8 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-950/40 p-6">
                        <div class="text-sm font-semibold">MLM / Network Marketing Systems</div>
                        <div class="mt-2 text-sm text-zinc-300">Referral trees, commissions, ranks, payouts</div>
                    </div>
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-950/40 p-6">
                        <div class="text-sm font-semibold">E-Commerce Platforms</div>
                        <div class="mt-2 text-sm text-zinc-300">Products, orders, payments, customer management</div>
                    </div>
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-950/40 p-6">
                        <div class="text-sm font-semibold">Inventory Management</div>
                        <div class="mt-2 text-sm text-zinc-300">Stock tracking, suppliers, warehouses</div>
                    </div>
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-950/40 p-6">
                        <div class="text-sm font-semibold">Accounting & Finance</div>
                        <div class="mt-2 text-sm text-zinc-300">Income, expenses, reports, audits</div>
                    </div>
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-950/40 p-6">
                        <div class="text-sm font-semibold">Human Resources (HR)</div>
                        <div class="mt-2 text-sm text-zinc-300">Staff records, payroll logic, performance tracking</div>
                    </div>
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-950/40 p-6">
                        <div class="text-sm font-semibold">Custom Systems</div>
                        <div class="mt-2 text-sm text-zinc-300">Tailored workflows for your business model</div>
                    </div>
                </div>
            </section>

            <section class="mt-14 grid gap-6 md:grid-cols-2">
                <div class="rounded-3xl border border-zinc-800 bg-zinc-900/50 p-8">
                    <h2 class="text-2xl font-semibold tracking-tight">Radically Low Entry. Sustainable Operation.</h2>
                    <div class="mt-4 space-y-3 text-sm text-zinc-300">
                        <div class="rounded-xl border border-zinc-800 bg-zinc-950/40 p-4">
                            <div class="font-semibold text-zinc-100">Step 1: Start Building</div>
                            <div class="mt-1 text-zinc-300">USD 0.10</div>
                            <div class="mt-2 text-zinc-400">This is your “Hello, World” moment.</div>
                        </div>
                        <div class="rounded-xl border border-zinc-800 bg-zinc-950/40 p-4">
                            <div class="font-semibold text-zinc-100">Step 2: Run It Properly</div>
                            <div class="mt-1 text-zinc-300">From USDT 20 / month</div>
                            <div class="mt-2 text-zinc-400">No hidden fees. No enterprise lock-in.</div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('pricing') }}" class="inline-flex rounded-md bg-zinc-800 px-4 py-2 text-sm font-semibold text-zinc-100 hover:bg-zinc-700">
                            Read the full pricing philosophy
                        </a>
                    </div>
                </div>

                <div class="rounded-3xl border border-zinc-800 bg-zinc-900/50 p-8">
                    <h2 class="text-2xl font-semibold tracking-tight">You’re Not Building Alone</h2>
                    <p class="mt-3 text-zinc-300">
                        Most platforms give you tools. We give you thinking partners.
                    </p>
                    <p class="mt-3 text-zinc-300">
                        Paralland is backed by a strong advisory team with experience in business model design, MLM & incentive structures, financial flow & sustainability, system architecture, presentation & investor readiness, and regulatory and operational considerations.
                    </p>
                    <div class="mt-6 text-sm text-zinc-300">
                        <div class="font-semibold text-zinc-100">We help you answer:</div>
                        <ul class="mt-2 space-y-1 text-zinc-300">
                            <li>- How should your system flow?</li>
                            <li>- Is your model sustainable?</li>
                            <li>- How do you present it to users, partners, or investors?</li>
                            <li>- What should be built now vs later?</li>
                        </ul>
                        <div class="mt-4 text-zinc-400">Tools build systems. Advisors build businesses.</div>
                    </div>
                </div>
            </section>

            <footer class="mt-14 border-t border-zinc-800 pt-10 text-center text-sm text-zinc-500">
                In 1972, “Hello, World” taught us how to talk to machines. In Paralland, we learn how to build systems that serve people.
                <span class="text-zinc-300">This is not just development. This is a quest.</span>
            </footer>
        </div>
    </body>
</html>

