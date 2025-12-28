<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin — Command Center
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="text-sm text-gray-600">
                    You’re not building alone — but as admin, you coordinate the build, support, and scaling for every member.
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="text-xs text-gray-500">Members</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['members'] ?? 0 }}</div>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="text-xs text-gray-500">Orders</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['orders_total'] ?? 0 }}</div>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="text-xs text-gray-500">Pending payment</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['orders_pending_payment'] ?? 0 }}</div>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="text-xs text-gray-500">Unpaid</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['orders_unpaid'] ?? 0 }}</div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('admin.members.index') }}" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">
                        Members
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center rounded-md bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-500">
                        Orders (Review)
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

