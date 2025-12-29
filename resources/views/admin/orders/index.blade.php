<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin: Orders
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                    <div>
                        <div class="text-xs font-semibold text-gray-700">Work status</div>
                        <select name="work_status" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm text-sm">
                            <option value="">All</option>
                            @foreach (['new' => 'New', 'in_progress' => 'In progress', 'waiting_client' => 'Waiting client', 'completed' => 'Completed'] as $k => $label)
                                <option value="{{ $k }}" @selected(request('work_status') === $k)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-700">Payment status</div>
                        <select name="payment_status" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm text-sm">
                            <option value="">All</option>
                            @foreach (['unpaid' => 'Unpaid', 'pending' => 'Pending', 'paid' => 'Paid'] as $k => $label)
                                <option value="{{ $k }}" @selected(request('payment_status') === $k)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-700">Order status</div>
                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm text-sm">
                            <option value="">All</option>
                            @foreach (['draft' => 'Draft', 'submitted' => 'Submitted', 'accepted' => 'Accepted', 'revision_requested' => 'Revision requested', 'rejected' => 'Rejected'] as $k => $label)
                                <option value="{{ $k }}" @selected(request('status') === $k)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center rounded-md bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-500">
                            Filter
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

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
