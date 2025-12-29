@php
    $client = $invoice->project?->client;
@endphp

<p>Hi{{ $client?->name ? ' '.$client->name : '' }},</p>

<p>
    This is a reminder that your monthly server/support payment is <strong>overdue</strong>.
</p>

<ul>
    <li><strong>Invoice:</strong> #{{ $invoice->id }}</li>
    <li><strong>Project:</strong> #{{ $invoice->project_id }}</li>
    <li><strong>Due date:</strong> {{ $invoice->due_date?->format('Y-m-d') }}</li>
    <li><strong>Amount:</strong> {{ number_format((float) $invoice->amount_usdt, 2) }} USDT</li>
</ul>

<p>
    Please log in and complete the payment to avoid service interruption.
</p>

<p>
    Thanks,<br>
    {{ $appName }}
</p>

