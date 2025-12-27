<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-900 font-semibold">Welcome, {{ auth()->user()->name }}.</div>
                <div class="mt-2 text-sm text-gray-600">
                    Start by creating an order and selecting the modules you want (website, MLM, payment, marketplace, admin, etc.).
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('orders.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                        Create new order
                    </a>
                    <a href="{{ route('orders.index') }}" class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">
                        View my orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
