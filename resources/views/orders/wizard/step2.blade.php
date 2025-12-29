<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Step 2 — Content + Uploads
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @include('orders.wizard._progress', ['order' => $order, 'step' => 2])

            @if (session('status'))
                <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="mb-4 text-sm text-gray-600">
                        Tip: You can use the suggestions on the right, then edit to match your real business.
                    </div>

                    <form method="POST" action="{{ route('orders.wizard.step2.save', $order) }}" class="space-y-6">
                        @csrf

                        <div>
                            <div class="flex items-center justify-between gap-3">
                                <x-input-label for="intro" value="Intro" />
                                <button type="button" class="text-xs text-violet-700 hover:text-violet-900 underline underline-offset-4"
                                    onclick="document.getElementById('intro').value = @js($suggestions['intro'] ?? '')">
                                    Use suggestion
                                </button>
                            </div>
                            <textarea id="intro" name="intro" rows="4" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm">{{ old('intro', $existing['intro'] ?? '') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('intro')" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-3">
                                <x-input-label for="business_nature" value="Business nature" />
                                <button type="button" class="text-xs text-violet-700 hover:text-violet-900 underline underline-offset-4"
                                    onclick="document.getElementById('business_nature').value = @js($suggestions['business_nature'] ?? '')">
                                    Use suggestion
                                </button>
                            </div>
                            <textarea id="business_nature" name="business_nature" rows="4" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm">{{ old('business_nature', $existing['business_nature'] ?? '') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('business_nature')" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-3">
                                <x-input-label for="advantages" value="Business advantages" />
                                <button type="button" class="text-xs text-violet-700 hover:text-violet-900 underline underline-offset-4"
                                    onclick="document.getElementById('advantages').value = @js($suggestions['advantages'] ?? '')">
                                    Use suggestion
                                </button>
                            </div>
                            <textarea id="advantages" name="advantages" rows="5" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm">{{ old('advantages', $existing['advantages'] ?? '') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('advantages')" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-3">
                                <x-input-label for="services" value="Products / services section" />
                                <button type="button" class="text-xs text-violet-700 hover:text-violet-900 underline underline-offset-4"
                                    onclick="document.getElementById('services').value = @js($suggestions['services'] ?? '')">
                                    Use suggestion
                                </button>
                            </div>
                            <textarea id="services" name="services" rows="5" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm">{{ old('services', $existing['services'] ?? '') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('services')" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-3">
                                <x-input-label for="team_members" value="Team members (one per line)" />
                                <button type="button" class="text-xs text-violet-700 hover:text-violet-900 underline underline-offset-4"
                                    onclick="document.getElementById('team_members').value = @js($suggestions['team_members'] ?? '')">
                                    Use suggestion
                                </button>
                            </div>
                            <textarea id="team_members" name="team_members" rows="5" class="mt-1 block w-full rounded-md border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm">{{ old('team_members', $existing['team_members'] ?? '') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('team_members')" />
                        </div>

                        <div class="flex items-center justify-between gap-3">
                            <a href="{{ route('orders.wizard.step1', $order) }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                            <x-primary-button>Continue</x-primary-button>
                        </div>
                    </form>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="font-semibold text-gray-900">Uploads</div>
                    <div class="mt-1 text-xs text-gray-500">
                        Allowed: JPG, PNG, WEBP. Stored privately.
                    </div>

                    <form method="POST" action="{{ route('orders.wizard.step2.upload', $order) }}" enctype="multipart/form-data" class="mt-4 space-y-3">
                        @csrf

                        <div>
                            <x-input-label for="kind" value="Kind (optional)" />
                            <x-text-input id="kind" name="kind" type="text" class="mt-1 block w-full" value="{{ old('kind', 'image') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('kind')" />
                        </div>

                        <div>
                            <x-input-label for="images" value="Select images" />
                            <input id="images" name="images[]" type="file" multiple accept=".jpg,.jpeg,.png,.webp" class="mt-1 block w-full text-sm">
                            <x-input-error class="mt-2" :messages="$errors->get('images')" />
                            <x-input-error class="mt-2" :messages="$errors->get('images.*')" />
                        </div>

                        <x-primary-button type="submit">Upload</x-primary-button>
                    </form>

                    <div class="mt-6">
                        <div class="text-sm font-medium text-gray-700">Files</div>
                        <div class="mt-3 space-y-2">
                            @forelse ($files as $f)
                                <div class="rounded-md border border-gray-200 p-3">
                                    <div class="text-xs text-gray-500">{{ $f->kind ?? 'file' }}</div>
                                    <div class="text-sm text-gray-900 break-all">{{ $f->original_name }}</div>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <a href="{{ route('orders.files.download', $f) }}" class="text-xs text-violet-700 hover:text-violet-900 underline underline-offset-4">Download</a>
                                        <form method="POST" action="{{ route('orders.files.destroy', $f) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 underline underline-offset-4">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-xs text-gray-500">No files uploaded yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

