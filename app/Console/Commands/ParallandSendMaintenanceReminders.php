<?php

namespace App\Console\Commands;

use App\Mail\MaintenanceInvoiceOverdueReminderMail;
use App\Models\MaintenanceInvoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ParallandSendMaintenanceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paralland:send-maintenance-reminders {--dry-run : Do not send emails; just report}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark overdue maintenance invoices and send reminder emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $dryRun = (bool) $this->option('dry-run');

        // Mark "due" invoices as overdue once past due date.
        $marked = MaintenanceInvoice::query()
            ->where('status', 'due')
            ->whereDate('due_date', '<', $now->toDateString())
            ->update(['status' => 'overdue']);

        $this->info("Marked overdue: {$marked}");

        // Send reminders once per day per invoice.
        $overdue = MaintenanceInvoice::query()
            ->with(['project.client'])
            ->where('status', 'overdue')
            ->where(function ($q) use ($now) {
                $q->whereNull('last_reminded_at')
                    ->orWhere('last_reminded_at', '<', $now->copy()->subDay());
            })
            ->get();

        $sent = 0;
        foreach ($overdue as $inv) {
            $email = $inv->project?->client?->email;
            if (!$email) {
                continue;
            }

            if ($dryRun) {
                $this->line("Would remind invoice #{$inv->id} to {$email}");
                continue;
            }

            Mail::to($email)->send(new MaintenanceInvoiceOverdueReminderMail($inv));
            $inv->last_reminded_at = $now;
            $inv->save();
            $sent++;
        }

        $this->info("Reminders sent: {$sent}");
        return self::SUCCESS;
    }
}
