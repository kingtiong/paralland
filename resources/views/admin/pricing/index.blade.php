<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin — Pricing
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 shadow-sm sm:rounded-lg p-4">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-900 shadow-sm sm:rounded-lg p-4 text-sm">
                    <div class="font-semibold">Could not save pricing</div>
                    <div class="mt-1">Please fix the highlighted fields below and try again.</div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.pricing.save') }}" class="space-y-4">
                @csrf

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-semibold text-gray-900">Base prices</div>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <x-input-label for="base_dev_usdt" value="Base development (USDT)" />
                            <x-text-input id="base_dev_usdt" name="base_dev_usdt" type="number" step="0.01" min="0" class="mt-1 block w-full" value="{{ old('base_dev_usdt', $settings['base_dev_usdt']) }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('base_dev_usdt')" />
                        </div>
                        <div>
                            <x-input-label for="base_monthly_usdt" value="Base monthly server/support (USDT)" />
                            <x-text-input id="base_monthly_usdt" name="base_monthly_usdt" type="number" step="0.01" min="0" class="mt-1 block w-full" value="{{ old('base_monthly_usdt', $settings['base_monthly_usdt']) }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('base_monthly_usdt')" />
                        </div>
                        <div>
                            <x-input-label for="website_company_dev_usdt" value="Website (Company) dev (USDT)" />
                            <x-text-input id="website_company_dev_usdt" name="website_company_dev_usdt" type="number" step="0.01" min="0" class="mt-1 block w-full" value="{{ old('website_company_dev_usdt', $settings['website_company_dev_usdt']) }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('website_company_dev_usdt')" />
                        </div>
                        <div>
                            <x-input-label for="website_personal_dev_usdt" value="Website (Personal) dev (USDT)" />
                            <x-text-input id="website_personal_dev_usdt" name="website_personal_dev_usdt" type="number" step="0.01" min="0" class="mt-1 block w-full" value="{{ old('website_personal_dev_usdt', $settings['website_personal_dev_usdt']) }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('website_personal_dev_usdt')" />
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-gray-500">
                        These are the base costs. Module costs are added on top.
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="text-sm font-semibold text-gray-900">Modules</div>
                        <div class="mt-1 text-sm text-gray-600">Set development + monthly cost per module. Toggle active to show/hide in Step 3.</div>
                        <div class="mt-2 text-xs text-gray-500">
                            To delete a module, tick “Delete” and save. (Safe delete: it won’t remove past orders, it just hides the module.)
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Key</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Label</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Description</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Dev (USDT)</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Monthly (USDT)</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Active</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Delete</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($modules as $m)
                                    <tr>
                                        <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $m->key }}</td>
                                        <td class="px-4 py-3">
                                            <input
                                                name="modules[{{ $m->id }}][label]"
                                                type="text"
                                                class="block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm text-sm"
                                                value="{{ old("modules.{$m->id}.label", $m->label) }}"
                                            />
                                            <x-input-error class="mt-1" :messages="$errors->get(\"modules.{$m->id}.label\")" />
                                        </td>
                                        <td class="px-4 py-3">
                                            <textarea name="modules[{{ $m->id }}][description]" rows="2" class="block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm text-sm">{{ old("modules.{$m->id}.description", $m->description) }}</textarea>
                                            <x-input-error class="mt-1" :messages="$errors->get(\"modules.{$m->id}.description\")" />
                                        </td>
                                        <td class="px-4 py-3">
                                            <input
                                                name="modules[{{ $m->id }}][dev_cost_usdt]"
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                class="block w-40 rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm text-sm"
                                                value="{{ old("modules.{$m->id}.dev_cost_usdt", $m->dev_cost_usdt) }}"
                                            />
                                            <x-input-error class="mt-1" :messages="$errors->get(\"modules.{$m->id}.dev_cost_usdt\")" />
                                        </td>
                                        <td class="px-4 py-3">
                                            <input
                                                name="modules[{{ $m->id }}][monthly_cost_usdt]"
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                class="block w-40 rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm text-sm"
                                                value="{{ old("modules.{$m->id}.monthly_cost_usdt", $m->monthly_cost_usdt) }}"
                                            />
                                            <x-input-error class="mt-1" :messages="$errors->get(\"modules.{$m->id}.monthly_cost_usdt\")" />
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="hidden" name="modules[{{ $m->id }}][is_active]" value="0">
                                            <input type="checkbox" name="modules[{{ $m->id }}][is_active]" value="1" class="rounded border-gray-300 text-violet-600 focus:ring-violet-500" @checked((bool) old("modules.{$m->id}.is_active", $m->is_active))>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="hidden" name="modules[{{ $m->id }}][delete]" value="0">
                                            <input type="checkbox" name="modules[{{ $m->id }}][delete]" value="1" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-semibold text-gray-900">Add new module</div>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <x-input-label for="new_module_key" value="Key (a-z0-9_)" />
                            <x-text-input id="new_module_key" name="new_module_key" type="text" class="mt-1 block w-full" value="{{ old('new_module_key') }}" placeholder="custom_logic" />
                            <x-input-error class="mt-2" :messages="$errors->get('new_module_key')" />
                        </div>
                        <div>
                            <x-input-label for="new_module_label" value="Label" />
                            <x-text-input id="new_module_label" name="new_module_label" type="text" class="mt-1 block w-full" value="{{ old('new_module_label') }}" placeholder="Custom logic" />
                            <x-input-error class="mt-2" :messages="$errors->get('new_module_label')" />
                        </div>
                        <div class="lg:col-span-2">
                            <x-input-label for="new_module_description" value="Description (optional)" />
                            <textarea id="new_module_description" name="new_module_description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm text-sm">{{ old('new_module_description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('new_module_description')" />
                        </div>
                        <div>
                            <x-input-label for="new_module_dev_cost_usdt" value="Dev (USDT)" />
                            <x-text-input id="new_module_dev_cost_usdt" name="new_module_dev_cost_usdt" type="number" step="0.01" min="0" class="mt-1 block w-full" value="{{ old('new_module_dev_cost_usdt', 0) }}" />
                        </div>
                        <div>
                            <x-input-label for="new_module_monthly_cost_usdt" value="Monthly (USDT)" />
                            <x-text-input id="new_module_monthly_cost_usdt" name="new_module_monthly_cost_usdt" type="number" step="0.01" min="0" class="mt-1 block w-full" value="{{ old('new_module_monthly_cost_usdt', 0) }}" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <x-primary-button>Save pricing</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

