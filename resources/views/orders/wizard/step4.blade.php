<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Step 4 — Summary & Estimate
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @include('orders.wizard._progress', ['order' => $order, 'step' => 4])

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm font-semibold text-gray-900">Summary</div>
                        @php $s1 = $order->wizard_step1 ?? []; @endphp
                        <div class="mt-3 space-y-2 text-sm text-gray-700">
                            <div><span class="font-medium">Website:</span> {{ ($s1['need_website'] ?? true) ? 'Yes' : 'No (existing website)' }}</div>
                            <div><span class="font-medium">Type:</span> {{ $s1['website_type'] ?? '-' }}</div>
                            <div><span class="font-medium">Purpose:</span> {{ $s1['purpose'] ?? '-' }}</div>
                            <div><span class="font-medium">Industry:</span> {{ $s1['industry'] ?? '-' }}</div>
                            <div><span class="font-medium">Title:</span> {{ $order->title }}</div>
                        </div>

                        <div class="mt-4">
                            <div class="text-sm font-medium text-gray-900">Modules</div>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach (($order->requested_modules ?? []) as $m)
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">{{ $m }}</span>
                                @endforeach
                                @if (empty($order->requested_modules))
                                    <span class="text-xs text-gray-500">No extra modules selected.</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="text-sm font-semibold text-gray-900">Estimate (USDT, BEP20)</div>
                        <div class="mt-3 rounded-md border border-gray-200 overflow-hidden">
                            <div class="divide-y divide-gray-200">
                                @foreach ($estimate['items'] as $item)
                                    <div class="flex items-center justify-between px-4 py-3">
                                        <div class="text-sm text-gray-800">{{ $item['label'] }}</div>
                                        <div class="text-right">
                                            <div class="text-sm font-semibold text-gray-900">{{ number_format($item['dev_usdt'], 2) }} dev</div>
                                            <div class="text-xs text-gray-500">{{ number_format($item['monthly_usdt'], 2) }} / month</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex items-center justify-between px-4 py-3 bg-gray-50">
                                <div class="text-sm font-semibold text-gray-900">Total</div>
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-gray-900">{{ number_format($estimate['dev_total_usdt'], 2) }} USDT (dev)</div>
                                    <div class="text-xs text-gray-600">{{ number_format($estimate['monthly_total_usdt'], 2) }} USDT / month</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">{{ $estimate['note'] }}</div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between gap-3">
                    <a href="{{ route('orders.wizard.step3', $order) }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                    <form method="POST" action="{{ route('orders.wizard.step4.confirm', $order) }}">
                        @csrf
                        <x-primary-button>Continue to payment</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

