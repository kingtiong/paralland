@php
    /** @var \App\Models\Proposal $order */
    $step = (int) ($step ?? 1);
    $steps = [
        1 => 'Basics',
        2 => 'Content',
        3 => 'Functions',
        4 => 'Summary',
        5 => 'Payment',
    ];
@endphp

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div class="text-sm font-semibold text-gray-900">Order wizard</div>
        <div class="text-xs text-gray-500">Order #{{ $order->id }}</div>
    </div>

    <div class="mt-3 grid grid-cols-5 gap-2">
        @foreach ($steps as $i => $label)
            <div class="rounded-md border {{ $i <= $step ? 'border-violet-200 bg-violet-50' : 'border-gray-200 bg-white' }} p-2">
                <div class="text-xs font-semibold {{ $i <= $step ? 'text-violet-900' : 'text-gray-500' }}">Step {{ $i }}</div>
                <div class="text-xs {{ $i <= $step ? 'text-violet-900' : 'text-gray-700' }}">{{ $label }}</div>
            </div>
        @endforeach
    </div>
</div>

