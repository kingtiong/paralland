<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ drawerOpen: false }" class="min-h-screen bg-gray-100">
            <!-- Mobile overlay -->
            <div
                x-show="drawerOpen"
                x-transition.opacity
                class="fixed inset-0 bg-black/40 z-40 lg:hidden"
                @click="drawerOpen = false"
            ></div>

            <!-- Drawer / Sidebar -->
            <aside
                class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-200 transform transition-transform lg:translate-x-0 lg:static lg:inset-auto"
                :class="{ '-translate-x-full': !drawerOpen, 'translate-x-0': drawerOpen }"
            >
                <div class="h-16 px-4 flex items-center justify-between border-b border-gray-100">
                    <a href="{{ route('dashboard') }}" class="font-semibold text-gray-900">
                        {{ config('app.name', 'Paralland') }}
                    </a>
                    <button class="lg:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100" @click="drawerOpen = false" aria-label="Close menu">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-4">
                    <div class="text-xs uppercase tracking-wide text-gray-500">Menu</div>
                    <nav class="mt-3 space-y-1">
                        <a href="{{ route('dashboard') }}" class="block rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('orders.index') }}" class="block rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('orders.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                            My Orders
                        </a>
                        <form method="POST" action="{{ route('orders.wizard.start') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                New Order
                            </button>
                        </form>

                        @if (auth()->user()?->isAdmin())
                            <div class="pt-3 text-xs uppercase tracking-wide text-gray-500">Admin</div>
                            <a href="{{ route('admin.dashboard') }}" class="block rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                Admin Dashboard
                            </a>
                            <a href="{{ route('admin.members.index') }}" class="block rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.members.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                Members
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="block rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                Orders (Review)
                            </a>
                            <a href="{{ route('admin.pricing.index') }}" class="block rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.pricing.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                Pricing
                            </a>
                            <a href="{{ route('admin.maintenance.index') }}" class="block rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.maintenance.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                Monthly payments
                            </a>
                        @endif
                    </nav>
                </div>

                <div class="absolute bottom-0 left-0 right-0 border-t border-gray-100 p-4">
                    <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-xs font-medium text-gray-800 hover:bg-gray-200">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-xs font-medium text-gray-800 hover:bg-gray-200">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main area -->
            <div class="lg:pl-72">
                <!-- Top bar -->
                <div class="h-16 flex items-center gap-3 px-4 sm:px-6 lg:px-8 border-b border-gray-200 bg-white">
                    <button class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:bg-gray-100" @click="drawerOpen = true" aria-label="Open menu">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="text-sm text-gray-600">
                        {{ request()->routeIs('dashboard') ? 'Dashboard' : '' }}
                    </div>
                </div>

                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
