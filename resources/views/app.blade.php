<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Paralland') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-950 text-zinc-100">
        <div class="mx-auto max-w-5xl px-6 py-10">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold">Paralland DApp (MVP)</h1>
                    <p class="mt-1 text-sm text-zinc-400">
                        Connect wallet → sign login → submit proposal → admin review.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button id="connectBtn" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium hover:bg-indigo-500">
                        Connect Wallet
                    </button>
                    <button id="logoutBtn" class="hidden rounded-md bg-zinc-800 px-3 py-2 text-sm font-medium hover:bg-zinc-700">
                        Logout
                    </button>
                </div>
            </div>

            <div class="mt-6 rounded-lg border border-zinc-800 bg-zinc-900 p-4">
                <div class="text-sm">
                    <div class="text-zinc-400">Status</div>
                    <div id="statusText" class="mt-1">Not connected</div>
                    <div id="walletText" class="mt-1 text-zinc-400"></div>
                    <div id="roleText" class="mt-1 text-zinc-400"></div>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="rounded-lg border border-zinc-800 bg-zinc-900 p-4">
                    <h2 class="text-lg font-semibold">Submit Proposal</h2>
                    <form id="proposalForm" class="mt-4 space-y-3">
                        <div>
                            <label class="text-sm text-zinc-300">Title</label>
                            <input id="proposalTitle" class="mt-1 w-full rounded-md border border-zinc-700 bg-zinc-950 px-3 py-2 text-sm" placeholder="Website + CRM + payment gateway" />
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Description</label>
                            <textarea id="proposalDescription" rows="4" class="mt-1 w-full rounded-md border border-zinc-700 bg-zinc-950 px-3 py-2 text-sm" placeholder="What you need, timeline, reference sites..."></textarea>
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Development price (RBE)</label>
                            <input id="proposalPrice" type="number" min="2" value="2" class="mt-1 w-full rounded-md border border-zinc-700 bg-zinc-950 px-3 py-2 text-sm" />
                            <div class="mt-1 text-xs text-zinc-500">Minimum is 2 RBE (configurable later).</div>
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Requirements (JSON or plain text)</label>
                            <textarea id="proposalRequirements" rows="6" class="mt-1 w-full rounded-md border border-zinc-700 bg-zinc-950 px-3 py-2 text-sm" placeholder='{"content_links":["..."],"payment_gateway":"Stripe","notes":"..."}'></textarea>
                        </div>
                        <button class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-medium hover:bg-emerald-500" type="submit">
                            Submit
                        </button>
                    </form>
                </div>

                <div class="rounded-lg border border-zinc-800 bg-zinc-900 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-lg font-semibold">My Proposals</h2>
                        <button id="refreshBtn" class="rounded-md bg-zinc-800 px-3 py-2 text-sm font-medium hover:bg-zinc-700">
                            Refresh
                        </button>
                    </div>
                    <div id="proposalsList" class="mt-4 space-y-3 text-sm text-zinc-200"></div>
                </div>
            </div>

            <div id="adminPanel" class="mt-8 hidden rounded-lg border border-zinc-800 bg-zinc-900 p-4">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-lg font-semibold">Admin: Pending Proposals</h2>
                    <button id="adminRefreshBtn" class="rounded-md bg-zinc-800 px-3 py-2 text-sm font-medium hover:bg-zinc-700">
                        Refresh
                    </button>
                </div>
                <div id="adminProposalsList" class="mt-4 space-y-3 text-sm"></div>
            </div>
        </div>
    </body>
</html>

