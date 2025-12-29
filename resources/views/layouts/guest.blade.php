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
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-40 left-1/2 h-80 w-[42rem] -translate-x-1/2 rounded-full bg-violet-600/20 blur-3xl"></div>
                <div class="absolute -bottom-40 left-1/3 h-80 w-[42rem] rounded-full bg-indigo-500/10 blur-3xl"></div>
            </div>

            <div class="relative w-full max-w-md">
                <a href="/" class="flex items-center gap-3 justify-center">
                    <div class="h-10 w-10 rounded-xl bg-zinc-900 border border-zinc-800 flex items-center justify-center">
                        <x-application-logo class="h-6 w-6 fill-current text-zinc-200" />
                    </div>
                    <div class="text-lg font-semibold tracking-tight">
                        {{ config('app.name', 'Paralland') }}
                    </div>
                </a>

                <div class="mt-8 rounded-2xl border border-zinc-800 bg-zinc-900/60 shadow-xl shadow-black/20 backdrop-blur px-6 py-6">
                    {{ $slot }}
                </div>

                <div class="mt-6 text-center text-xs text-zinc-500">
                    © {{ date('Y') }} {{ config('app.name', 'Paralland') }}. All rights reserved.
                </div>
            </div>
        </div>
    </body>
</html>
