<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Orders
            </h2>
            <a href="{{ route('orders.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                New order
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if ($orders->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-700">
                    No orders yet. Create your first order.
                </div>
            @endif

            @foreach ($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="block bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-lg font-semibold text-gray-900">{{ $order->title }}</div>
                            <div class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $order->description }}</div>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach (($order->requested_modules ?? []) as $m)
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">{{ $m }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-xs text-gray-500">Status</div>
                            <div class="mt-1 inline-flex rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800">
                                {{ $order->status }}
                            </div>
                            <div class="mt-2 text-xs text-gray-500">Wizard step: {{ $order->wizard_step ?? 1 }}/5</div>
                            <div class="mt-2 text-xs text-gray-500">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
