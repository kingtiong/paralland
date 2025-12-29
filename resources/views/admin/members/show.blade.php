<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin — Member: {{ $member->email }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="text-sm text-gray-500">Member</div>
                        <div class="mt-1 text-lg font-semibold text-gray-900">{{ $member->name }}</div>
                        <div class="text-sm text-gray-600">{{ $member->email }}</div>
                        <div class="mt-2 text-xs">
                            <span class="font-semibold text-gray-700">Email verified:</span>
                            @if ($member->email_verified_at)
                                <span class="text-emerald-700">Yes ({{ $member->email_verified_at->format('Y-m-d H:i') }})</span>
                            @else
                                <span class="text-rose-700">No</span>
                            @endif
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            Joined: {{ optional($member->created_at)->toDayDateTimeString() }}
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.members.index') }}" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">
                            Back to members
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center rounded-md bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-500">
                            Review orders
                        </a>
                        @if (! $member->email_verified_at)
                            <form method="POST" action="{{ route('admin.members.verify_email', $member) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                                    Mark verified
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="text-sm font-semibold text-gray-900">Orders</div>
                    <div class="mt-1 text-sm text-gray-600">Interact with the member by reviewing orders and leaving notes on each order.</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Order</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Payment</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($orders as $o)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $o->title }}</div>
                                        <div class="text-xs text-gray-500">#{{ $o->id }} · Step {{ $o->wizard_step }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800">
                                            {{ $o->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ $o->payment_status }} @if($o->estimated_total_usdt) · {{ number_format((float) $o->estimated_total_usdt, 2) }} USDT @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('admin.orders.show', $o) }}" class="text-violet-700 hover:text-violet-900 font-semibold">
                                            Open
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-4 py-6 text-gray-600" colspan="4">No orders yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

