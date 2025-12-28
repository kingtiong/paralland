<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Your System Command Center
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-900 font-semibold">Welcome, {{ auth()->user()->name }}.</div>
                <div class="mt-2 text-sm text-gray-600">
                    Build → operate → scale. Start small, add modules only when you need them.
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('orders.wizard.start') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-md bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-500">
                            Start Building
                        </button>
                    </form>
                    <a href="{{ route('orders.index') }}" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">
                        Project Overview
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">
                        Pricing philosophy
                    </a>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-semibold text-gray-900">🧭 Project Overview</div>
                    <div class="mt-2 text-sm text-gray-600">
                        Active system type, current modules, and plan status will appear here.
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-semibold text-gray-900">🧩 Modules</div>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">MLM</span>
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">E‑Commerce</span>
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">Inventory</span>
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">Accounting</span>
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">HR</span>
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">Custom logic</span>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        Start small. Add modules only when you need them.
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-semibold text-gray-900">🛠 System Builder</div>
                    <div class="mt-2 text-sm text-gray-600">
                        Workflow design, rules & logic, permissions & roles (coming next).
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-semibold text-gray-900">🤝 Advisory Panel</div>
                    <div class="mt-2 text-sm text-gray-600">
                        Design recommendations, sustainability checks, and build-now vs build-later guidance (coming next).
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6 lg:col-span-2">
                    <div class="text-sm font-semibold text-gray-900">💳 Billing</div>
                    <div class="mt-2 text-sm text-gray-600">
                        Monthly server & maintenance fee, upgrade options, and payment history will appear here.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
