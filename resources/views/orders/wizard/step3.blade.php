<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Step 3 — Additional Functions
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @include('orders.wizard._progress', ['order' => $order, 'step' => 3])

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('orders.wizard.step3.save', $order) }}" class="space-y-6">
                    @csrf

                    <div class="text-sm text-gray-600">
                        Select any extra systems you need. You can add more later too.
                    </div>

                    @if ($order->paid_total_usdt !== null)
                        <div class="rounded-md border border-amber-200 bg-amber-50 p-4 text-amber-900">
                            <div class="font-semibold">Upgrades</div>
                            <div class="mt-1 text-sm">
                                You’ve already paid once. If you add new functions here, the system will ask you to pay the difference.
                                Content revisions (Step 1/2) don’t require extra payment.
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @if (empty($modules))
                            <div class="text-sm text-gray-600">
                                No modules configured yet. An admin can add modules in <b>Admin → Pricing</b>.
                            </div>
                        @endif
                        @foreach ($modules as $m)
                            @php
                                $checked = in_array($m['key'], old('modules', $order->requested_modules ?? []));
                            @endphp
                            <label class="flex items-start gap-3 rounded-md border border-gray-200 p-4 hover:bg-gray-50">
                                <input
                                    type="checkbox"
                                    name="modules[]"
                                    value="{{ $m['key'] }}"
                                    class="mt-1 rounded border-gray-300 text-violet-600 shadow-sm focus:ring-violet-500"
                                    @checked($checked)
                                />
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-gray-900">{{ $m['label'] }}</div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        Dev: ~{{ number_format($m['dev_usdt'], 2) }} USDT · Monthly: ~{{ number_format($m['monthly_usdt'], 2) }} USDT
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <x-input-error class="mt-2" :messages="$errors->get('modules')" />

                    <div class="flex items-center justify-between gap-3">
                        <a href="{{ route('orders.wizard.step2', $order) }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                        <x-primary-button>Continue</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

