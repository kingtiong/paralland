@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge(['class' => 'w-full rounded-md bg-zinc-950/60 border border-zinc-800 text-zinc-100 placeholder-zinc-500 shadow-sm focus:border-violet-500 focus:ring-violet-500']) }}
>
