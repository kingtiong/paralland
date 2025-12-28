<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Step 1 — Basics
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @include('orders.wizard._progress', ['order' => $order, 'step' => 1])

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('orders.wizard.step1.save', $order) }}" class="space-y-6">
                    @csrf

                    @php
                        $s1 = $order->wizard_step1 ?? [];
                    @endphp

                    <div>
                        <div class="text-sm font-medium text-gray-700">Website type</div>
                        <div class="mt-2 flex flex-wrap gap-3">
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="website_type" value="company" class="text-violet-600 focus:ring-violet-500" @checked(old('website_type', $s1['website_type'] ?? 'company') === 'company')>
                                <span class="text-sm text-gray-900">Company</span>
                            </label>
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="website_type" value="personal" class="text-violet-600 focus:ring-violet-500" @checked(old('website_type', $s1['website_type'] ?? '') === 'personal')>
                                <span class="text-sm text-gray-900">Personal</span>
                            </label>
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('website_type')" />
                    </div>

                    <div>
                        <x-input-label for="purpose" value="Purpose of website" />
                        <x-text-input id="purpose" name="purpose" type="text" class="mt-1 block w-full" value="{{ old('purpose', $s1['purpose'] ?? '') }}" placeholder="e.g. company profile, lead generation, ecommerce" />
                        <x-input-error class="mt-2" :messages="$errors->get('purpose')" />
                    </div>

                    <div>
                        <x-input-label for="title" value="Website title" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" value="{{ old('title', $s1['title'] ?? $order->title) }}" />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Short description" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm">{{ old('description', $s1['description'] ?? $order->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="contact_phone" value="Contact number" />
                            <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full" value="{{ old('contact_phone', $s1['contact_phone'] ?? '') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('contact_phone')" />
                        </div>
                        <div>
                            <x-input-label for="industry" value="Industry" />
                            <x-text-input id="industry" name="industry" type="text" class="mt-1 block w-full" value="{{ old('industry', $s1['industry'] ?? '') }}" placeholder="e.g. restaurant, construction, beauty" />
                            <x-input-error class="mt-2" :messages="$errors->get('industry')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="address" value="Address" />
                        <textarea id="address" name="address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm">{{ old('address', $s1['address'] ?? '') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                    </div>

                    <div>
                        <x-input-label for="products_services" value="Products / services (one per line)" />
                        @php
                            $lines = old('products_services');
                            if ($lines === null) {
                                $ps = $s1['products_services'] ?? [];
                                $lines = is_array($ps) ? implode("\n", $ps) : '';
                            }
                        @endphp
                        <textarea id="products_services" name="products_services" rows="5" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm" placeholder="Product A&#10;Service B">{{ $lines }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('products_services')" />
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        <x-primary-button>Continue</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

