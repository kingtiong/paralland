<?php

namespace App\Mail;

use App\Models\MaintenanceInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MaintenanceInvoiceOverdueReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public MaintenanceInvoice $invoice;

    /**
     * Create a new message instance.
     */
    public function __construct(MaintenanceInvoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $id = $this->invoice->id;
        return new Envelope(
            subject: "Monthly server payment overdue (Invoice #{$id})",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.maintenance.overdue',
            with: [
                'invoice' => $this->invoice,
                'appName' => config('app.name', 'Paralland'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
