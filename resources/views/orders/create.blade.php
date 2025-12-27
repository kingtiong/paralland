<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            New Order
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('orders.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="title" value="Project title" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" value="{{ old('title') }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Describe what you need" />
                        <textarea id="description" name="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div>
                        <div class="text-sm font-medium text-gray-700">Select modules</div>
                        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($modules as $m)
                                <label class="flex items-start gap-3 rounded-md border border-gray-200 p-3 hover:bg-gray-50">
                                    <input
                                        type="checkbox"
                                        name="modules[]"
                                        value="{{ $m['key'] }}"
                                        class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        @checked(in_array($m['key'], old('modules', [])))
                                    />
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $m['label'] }}</div>
                                        <div class="text-xs text-gray-500">Module key: {{ $m['key'] }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('modules')" />
                    </div>

                    <div>
                        <x-input-label for="requirements" value="Resources / notes (links, API keys, content, etc.)" />
                        <textarea id="requirements" name="requirements" rows="5" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Paste links to images/content, payment gateway docs, etc.">{{ old('requirements') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('requirements')" />
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        <x-primary-button>
                            Submit order
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
