<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin: Order #{{ $order->id }}
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <div class="text-lg font-semibold text-gray-900">{{ $order->title }}</div>
                        <div class="mt-1 text-sm text-gray-600">
                            Client: {{ $order->user?->email ?? $order->user?->wallet_address ?? 'unknown' }}
                        </div>
                        <div class="mt-2 text-sm text-gray-500">Submitted: {{ $order->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Status</div>
                        <div class="mt-1 inline-flex rounded-md bg-gray-100 px-2 py-1 text-sm font-medium text-gray-800">
                            {{ $order->status }}
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="text-sm font-medium text-gray-700">Description</div>
                    <div class="mt-2 text-gray-900 whitespace-pre-wrap">{{ $order->description }}</div>
                </div>

                <div class="mt-6">
                    <div class="text-sm font-medium text-gray-700">Modules</div>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach (($order->requested_modules ?? []) as $m)
                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">{{ $m }}</span>
                        @endforeach
                    </div>
                </div>

                @if (!empty($order->requirements))
                    <div class="mt-6">
                        <div class="text-sm font-medium text-gray-700">Requirements</div>
                        <pre class="mt-2 rounded-md bg-gray-50 p-4 text-sm text-gray-800 overflow-auto">{{ json_encode($order->requirements, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                @endif
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="text-sm font-semibold text-gray-900">Review</div>

                <form method="POST" action="{{ route('admin.orders.review', $order) }}" class="mt-4 space-y-3">
                    @csrf

                    <div>
                        <label class="text-sm font-medium text-gray-700">Action</label>
                        <div class="mt-2 flex flex-wrap gap-3">
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="action" value="accept" class="text-emerald-600 focus:ring-emerald-500" required>
                                <span class="text-sm">Accept</span>
                            </label>
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="action" value="revision" class="text-amber-600 focus:ring-amber-500" required>
                                <span class="text-sm">Request revision</span>
                            </label>
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="action" value="reject" class="text-rose-600 focus:ring-rose-500" required>
                                <span class="text-sm">Reject</span>
                            </label>
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('action')" />
                    </div>

                    <div>
                        <x-input-label for="note" value="Note to client (optional)" />
                        <textarea id="note" name="note" rows="4" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">{{ old('note') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('note')" />
                    </div>

                    <div class="flex items-center justify-end">
                        <x-primary-button>Submit review</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
