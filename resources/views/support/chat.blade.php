<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Customer Service Chat</h2>
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="text-sm text-gray-600">
                    Message us anytime. For Telegram bridging, admins can optionally reply via Telegram when enabled.
                </div>

                <div class="mt-4 rounded-md border border-gray-200 bg-gray-50 p-4">
                    @php
                        $msgs = $conversation->messages ?? collect();
                    @endphp

                    @if ($msgs->isEmpty())
                        <div class="text-sm text-gray-600">No messages yet. Send the first message.</div>
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

                <form method="POST" action="{{ route('support.chat.store') }}" class="mt-4 space-y-3">
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

