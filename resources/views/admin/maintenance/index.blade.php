<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin — Monthly Server Payments
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-wrap items-center gap-2 text-sm">
                    <a href="{{ route('admin.maintenance.index', ['filter' => 'all']) }}" class="rounded-md px-3 py-2 {{ $filter === 'all' ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">All</a>
                    <a href="{{ route('admin.maintenance.index', ['filter' => 'due']) }}" class="rounded-md px-3 py-2 {{ $filter === 'due' ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">Due ({{ $counts['due'] ?? 0 }})</a>
                    <a href="{{ route('admin.maintenance.index', ['filter' => 'pending_30']) }}" class="rounded-md px-3 py-2 {{ $filter === 'pending_30' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-900 hover:bg-amber-100' }}">Pending &gt; 30 days ({{ $counts['pending_30'] ?? 0 }})</a>
                    <a href="{{ route('admin.maintenance.index', ['filter' => 'pending_60']) }}" class="rounded-md px-3 py-2 {{ $filter === 'pending_60' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-900 hover:bg-rose-100' }}">Pending &gt; 60 days ({{ $counts['pending_60'] ?? 0 }})</a>
                    <a href="{{ route('admin.maintenance.index', ['filter' => 'overdue']) }}" class="rounded-md px-3 py-2 {{ $filter === 'overdue' ? 'bg-rose-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">Overdue ({{ $counts['overdue'] ?? 0 }})</a>
                    <a href="{{ route('admin.maintenance.index', ['filter' => 'paid']) }}" class="rounded-md px-3 py-2 {{ $filter === 'paid' ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">Paid</a>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Invoice</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Client</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Due date</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Amount</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Last reminder</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($invoices as $inv)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-900">#{{ $inv->id }}</div>
                                        <div class="text-xs text-gray-500">Project #{{ $inv->project_id }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ $inv->project?->client?->email ?? 'unknown' }}
                                        @if ($inv->project?->proposal)
                                            <div class="text-xs text-gray-500">Order #{{ $inv->project->proposal->id }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $inv->due_date?->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ number_format((float) $inv->amount_usdt, 2) }} USDT</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800">
                                            {{ $inv->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $inv->last_reminded_at?->format('Y-m-d H:i') ?? '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-gray-600">No invoices.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

