<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin: Orders
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Order</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Client</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Work</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Payment</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-gray-900 hover:text-violet-700">
                                            #{{ $order->id }} — {{ $order->title }}
                                        </a>
                                        <div class="mt-1 text-xs text-gray-500">
                                            Dev: {{ number_format((float) ($order->estimated_total_usdt ?? 0), 2) }} · Monthly: {{ number_format((float) ($order->estimated_monthly_usdt ?? 0), 2) }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ $order->user?->email ?? 'unknown' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800">
                                            {{ $order->work_status }}
                                        </span>
                                        @if ($order->expected_completion_date)
                                            <div class="mt-1 text-xs text-gray-500">ETA: {{ $order->expected_completion_date->format('Y-m-d') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800">
                                            {{ $order->payment_status }}
                                        </span>
                                        @if ($order->paid_total_usdt !== null)
                                            <div class="mt-1 text-xs text-gray-500">Paid: {{ number_format((float) $order->paid_total_usdt, 2) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-600">
                                        {{ $order->created_at->format('Y-m-d H:i') }}
                                    </td>
                                </tr>
                            @endforeach
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
