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

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="text-sm font-semibold text-gray-900">Payment</div>
                <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="rounded-md border border-gray-200 p-4">
                        <div class="text-xs text-gray-500">Estimated</div>
                        <div class="mt-1 font-semibold text-gray-900">
                            {{ number_format((float) ($order->estimated_total_usdt ?? 0), 2) }} USDT
                        </div>
                        <div class="mt-1 text-xs text-gray-500">
                            Monthly: {{ number_format((float) ($order->estimated_monthly_usdt ?? 0), 2) }} USDT / month
                        </div>
                        <div class="mt-1 text-xs text-gray-500">Status: {{ $order->payment_status }}</div>
                    </div>
                    <div class="rounded-md border border-gray-200 p-4">
                        <div class="text-xs text-gray-500">Paid (verified)</div>
                        <div class="mt-1 font-semibold text-gray-900">
                            {{ $order->paid_total_usdt !== null ? number_format((float) $order->paid_total_usdt, 2) : '—' }} USDT
                        </div>
                        <div class="mt-1 text-xs text-gray-500">
                            Verified at: {{ $order->payment_verified_at?->format('Y-m-d H:i') ?? '—' }}
                        </div>
                    </div>
                </div>

                @if (is_array($order->wizard_step4_estimate) && !empty($order->wizard_step4_estimate['items']))
                    <div class="mt-4 rounded-md border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-4 py-2 text-xs font-semibold text-gray-700">Pricing breakdown</div>
                        <div class="divide-y divide-gray-200 text-sm">
                            @foreach (($order->wizard_step4_estimate['items'] ?? []) as $item)
                                <div class="flex items-center justify-between px-4 py-3">
                                    <div class="text-gray-800">{{ $item['label'] ?? ($item['key'] ?? '-') }}</div>
                                    <div class="text-right">
                                        <div class="font-semibold text-gray-900">{{ number_format((float) ($item['dev_usdt'] ?? 0), 2) }} dev</div>
                                        <div class="text-xs text-gray-500">{{ number_format((float) ($item['monthly_usdt'] ?? 0), 2) }} / month</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.orders.payment.verify', $order) }}" class="mt-4 flex flex-col sm:flex-row gap-3 sm:items-end">
                    @csrf
                    <div>
                        <x-input-label for="paid_total_usdt" value="Mark payment verified (USDT)" />
                        <x-text-input id="paid_total_usdt" name="paid_total_usdt" type="number" step="0.01" min="0" class="mt-1 block w-full" value="{{ old('paid_total_usdt', $order->estimated_total_usdt ?? 0) }}" />
                        <x-input-error class="mt-2" :messages="$errors->get('paid_total_usdt')" />
                    </div>
                    <x-primary-button>Verify payment</x-primary-button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="text-sm font-semibold text-gray-900">Work status</div>
                <div class="mt-2 text-sm text-gray-600">Track progress and expected completion date.</div>

                <form method="POST" action="{{ route('admin.orders.work.update', $order) }}" class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                    @csrf
                    <div>
                        <x-input-label for="work_status" value="Status" />
                        <select id="work_status" name="work_status" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm">
                            @foreach (['new' => 'New', 'in_progress' => 'In progress', 'waiting_client' => 'Waiting client', 'completed' => 'Completed'] as $k => $lbl)
                                <option value="{{ $k }}" @selected(old('work_status', $order->work_status) === $k)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('work_status')" />
                    </div>
                    <div>
                        <x-input-label for="expected_completion_date" value="Expected completion date" />
                        <x-text-input id="expected_completion_date" name="expected_completion_date" type="date" class="mt-1 block w-full" value="{{ old('expected_completion_date', $order->expected_completion_date?->format('Y-m-d')) }}" />
                        <x-input-error class="mt-2" :messages="$errors->get('expected_completion_date')" />
                        <div class="mt-1 text-xs text-gray-500">Completed at: {{ $order->completed_at?->format('Y-m-d H:i') ?? '—' }}</div>
                    </div>
                    <div class="flex justify-end">
                        <x-primary-button>Update</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-sm font-semibold text-gray-900">Chat</div>
                        <div class="mt-1 text-sm text-gray-600">Communicate with the member about this order.</div>
                    </div>
                    <div class="text-xs text-gray-500">Order #{{ $order->id }}</div>
                </div>

                <div class="mt-4 rounded-md border border-gray-200 bg-gray-50 p-4">
                    @php
                        $msgs = $conversation?->messages ?? collect();
                    @endphp

                    @if ($msgs->isEmpty())
                        <div class="text-sm text-gray-600">No messages yet.</div>
                    @else
                        <div class="space-y-3">
                            @foreach ($msgs as $m)
                                <div class="flex gap-3">
                                    <div class="w-28 shrink-0 text-xs text-gray-500">
                                        <div class="font-medium text-gray-700">
                                            {{ $m->sender_type === 'admin' ? 'Admin' : ($m->sender_type === 'system' ? 'System' : 'Member') }}
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
                        <x-input-label for="message" value="Message (admin)" />
                        <textarea id="message" name="message" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm" placeholder="Reply to member...">{{ old('message') }}</textarea>
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
