<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TwilioWhatsApp
{
    public function isConfigured(): bool
    {
        return (string) config('services.twilio.account_sid') !== ''
            && (string) config('services.twilio.auth_token') !== ''
            && (string) config('services.twilio.from_whatsapp') !== ''
            && (string) config('services.twilio.admin_whatsapp') !== '';
    }

    public function sendToAdmin(string $text): void
    {
        if (!$this->isConfigured()) {
            return;
        }

        $sid = (string) config('services.twilio.account_sid');
        $token = (string) config('services.twilio.auth_token');

        $from = (string) config('services.twilio.from_whatsapp');
        $to = (string) config('services.twilio.admin_whatsapp');

        Http::asForm()
            ->withBasicAuth($sid, $token)
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                'From' => $from,
                'To' => $to,
                'Body' => $text,
            ])
            ->throw();
    }
}

