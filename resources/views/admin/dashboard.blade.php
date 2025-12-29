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
                        <div class="text-xs text-gray-500">Incoming messages (24h)</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['messages_last_24h'] ?? 0 }}</div>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="text-xs text-gray-500">New / In progress</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900">
                            {{ $stats['orders_new'] ?? 0 }} / {{ $stats['orders_in_progress'] ?? 0 }}
                        </div>
                        <div class="mt-1 text-xs text-gray-500">Waiting client: {{ $stats['orders_waiting_client'] ?? 0 }}</div>
                    </div>
                </div>

                <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="text-xs text-gray-500">Pending payment</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['orders_pending_payment'] ?? 0 }}</div>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="text-xs text-gray-500">Unpaid</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['orders_unpaid'] ?? 0 }}</div>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="text-xs text-gray-500">Monthly payments</div>
                        <div class="mt-1 text-sm font-semibold text-gray-900">
                            Pending &gt; 30d: {{ $stats['maintenance_pending_30'] ?? 0 }} · Pending &gt; 60d: {{ $stats['maintenance_pending_60'] ?? 0 }}
                        </div>
                        <div class="mt-1 text-xs text-gray-500">Overdue: {{ $stats['maintenance_overdue'] ?? 0 }}</div>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 lg:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="text-sm font-semibold text-gray-900">Recent messages</div>
                        <div class="mt-3 space-y-3">
                            @forelse ($recentMessages ?? [] as $m)
                                <div class="rounded-md bg-gray-50 p-3">
                                    <div class="text-xs text-gray-500">
                                        {{ $m->created_at?->format('Y-m-d H:i') }}
                                        · {{ $m->sender_type }}
                                        @if ($m->user)
                                            · {{ $m->user->email }}
                                        @endif
                                    </div>
                                    <div class="mt-1 text-sm text-gray-800 whitespace-pre-wrap">{{ $m->message }}</div>
                                </div>
                            @empty
                                <div class="text-sm text-gray-600">No messages yet.</div>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-violet-700 hover:text-violet-800">Go to orders (chat inside each order)</a>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="text-sm font-semibold text-gray-900">Quick actions</div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <a href="{{ route('admin.orders.index', ['work_status' => 'new']) }}" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-xs font-semibold text-gray-800 hover:bg-gray-200">New orders</a>
                            <a href="{{ route('admin.orders.index', ['work_status' => 'in_progress']) }}" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-xs font-semibold text-gray-800 hover:bg-gray-200">In progress</a>
                            <a href="{{ route('admin.orders.index', ['payment_status' => 'pending']) }}" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-xs font-semibold text-gray-800 hover:bg-gray-200">Pending payment</a>
                            <a href="{{ route('admin.maintenance.index', ['filter' => 'overdue']) }}" class="inline-flex items-center rounded-md bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-900 hover:bg-rose-100">Overdue monthly payments</a>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('admin.members.index') }}" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">
                        Members
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center rounded-md bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-500">
                        Orders (Review)
                    </a>
                    <a href="{{ route('admin.pricing.index') }}" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">
                        Pricing
                    </a>
                    <a href="{{ route('admin.maintenance.index') }}" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">
                        Monthly payments
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

