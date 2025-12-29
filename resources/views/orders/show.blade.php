<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order: {{ $order->title }}
            </h2>
            <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <div class="text-sm text-gray-500">Status</div>
                        <div class="mt-1 inline-flex rounded-md bg-gray-100 px-2 py-1 text-sm font-medium text-gray-800">
                            {{ $order->status }}
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        Submitted: {{ $order->created_at->format('Y-m-d H:i') }}
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('orders.wizard.step1', $order) }}" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-xs font-semibold text-gray-800 hover:bg-gray-200">
                        Revise Step 1 (Basics)
                    </a>
                    <a href="{{ route('orders.wizard.step2', $order) }}" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-xs font-semibold text-gray-800 hover:bg-gray-200">
                        Revise Step 2 (Content)
                    </a>
                    <a href="{{ route('orders.wizard.step3', $order) }}" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-xs font-semibold text-gray-800 hover:bg-gray-200">
                        Update functions (Step 3)
                    </a>
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
                        <div class="text-sm font-medium text-gray-700">Requirements / Notes</div>
                        <pre class="mt-2 rounded-md bg-gray-50 p-4 text-sm text-gray-800 overflow-auto">{{ json_encode($order->requirements, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                @endif
            </div>

            @if ($order->status === 'revision_requested')
                <div class="bg-amber-50 border border-amber-200 shadow-sm sm:rounded-lg p-6">
                    <div class="font-semibold text-amber-900">Revision requested</div>
                    <div class="mt-2 text-sm text-amber-900 whitespace-pre-wrap">{{ $order->review_note }}</div>
                </div>
            @endif

            @if ($order->status === 'rejected')
                <div class="bg-rose-50 border border-rose-200 shadow-sm sm:rounded-lg p-6">
                    <div class="font-semibold text-rose-900">Rejected</div>
                    <div class="mt-2 text-sm text-rose-900 whitespace-pre-wrap">{{ $order->review_note }}</div>
                </div>
            @endif

            @if ($order->status === 'accepted' && $order->project)
                <div class="bg-emerald-50 border border-emerald-200 shadow-sm sm:rounded-lg p-6">
                    <div class="font-semibold text-emerald-900">Accepted</div>
                    <div class="mt-2 text-sm text-emerald-900">
                        Project created (ID: {{ $order->project->id }}). Next we’ll add chat + delivery tracking.
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-sm font-semibold text-gray-900">Chat</div>
                        <div class="mt-1 text-sm text-gray-600">Communicate with customer service about this order.</div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Order #{{ $order->id }}
                    </div>
                </div>

                <div class="mt-4 rounded-md border border-gray-200 bg-gray-50 p-4">
                    @php
                        $msgs = $conversation?->messages ?? collect();
                    @endphp

                    @if ($msgs->isEmpty())
                        <div class="text-sm text-gray-600">No messages yet. Send the first message to open the chat.</div>
                    @else
                        <div class="space-y-3">
                            @foreach ($msgs as $m)
                                <div class="flex gap-3">
                                    <div class="w-28 shrink-0 text-xs text-gray-500">
                                        <div class="font-medium text-gray-700">
                                            {{ $m->sender_type === 'admin' ? 'Admin' : ($m->sender_type === 'system' ? 'System' : 'You') }}
                                        </div>
                                        <div>{{ $m->created_at->format('Y-m-d H:i') }}</div>
                                    </div>
                                    <div class="flex-1 rounded-md bg-white border border-gray-200 p-3 text-sm text-gray-900 whitespace-pre-wrap">
                                        {{ $m->message }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('orders.chat.store', $order) }}" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <x-input-label for="message" value="Message" />
                        <textarea id="message" name="message" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm" placeholder="Type your message...">{{ old('message') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('message')" />
                    </div>
                    <div class="flex items-center justify-end">
                        <x-primary-button>Send</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
