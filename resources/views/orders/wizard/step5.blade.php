<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Step 5 — Payment (USDT, BEP20)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @include('orders.wizard._progress', ['order' => $order, 'step' => 5])

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 gap-6">
                    <div class="rounded-md border border-amber-200 bg-amber-50 p-4 text-amber-900">
                        <div class="font-semibold">Important</div>
                        <div class="mt-1 text-sm">
                            Pay using <b>USDT (BEP20 / BSC)</b> only. Do not use ERC20 or other networks.
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-md border border-gray-200 p-4">
                            <div class="text-xs text-gray-500">Amount</div>
                            <div class="mt-1 text-lg font-semibold text-gray-900">
                                {{ number_format($order->estimated_total_usdt ?? 0, 2) }} USDT
                            </div>
                            <div class="mt-1 text-xs text-gray-500">Network: BEP20 (BSC)</div>
                        </div>
                        <div class="rounded-md border border-gray-200 p-4">
                            <div class="text-xs text-gray-500">Pay to address</div>
                            <div class="mt-1 text-sm font-mono break-all text-gray-900">
                                {{ $payTo ?: 'Set USDT_BEP20_TREASURY_ADDRESS in .env' }}
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                After payment, submit your transaction hash below.
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('orders.wizard.step5.submit', $order) }}" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="payment_from_address" value="Your wallet address (optional)" />
                                <x-text-input id="payment_from_address" name="payment_from_address" type="text" class="mt-1 block w-full" value="{{ old('payment_from_address') }}" placeholder="0x..." />
                                <x-input-error class="mt-2" :messages="$errors->get('payment_from_address')" />
                            </div>
                            <div>
                                <x-input-label for="payment_tx_hash" value="Transaction hash (optional)" />
                                <x-text-input id="payment_tx_hash" name="payment_tx_hash" type="text" class="mt-1 block w-full" value="{{ old('payment_tx_hash') }}" placeholder="0x..." />
                                <x-input-error class="mt-2" :messages="$errors->get('payment_tx_hash')" />
                            </div>
                        </div>

                        <label class="flex items-start gap-3">
                            <input type="checkbox" name="accept_terms" value="1" class="mt-1 rounded border-gray-300 text-violet-600 focus:ring-violet-500" @checked(old('accept_terms'))>
                            <span class="text-sm text-gray-700">
                                I agree to the terms and confirm I will pay with USDT on BEP20 (BSC).
                            </span>
                        </label>
                        <x-input-error class="mt-2" :messages="$errors->get('accept_terms')" />

                        <div class="flex items-center justify-between gap-3">
                            <a href="{{ route('orders.wizard.step4', $order) }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                            <x-primary-button>Submit payment</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

