<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramBot
{
    public function isConfigured(): bool
    {
        return (string) config('services.telegram.bot_token') !== ''
            && (string) config('services.telegram.admin_chat_id') !== '';
    }

    public function sendToAdmin(string $text): void
    {
        if (!$this->isConfigured()) {
            return;
        }

        $token = (string) config('services.telegram.bot_token');
        $chatId = (string) config('services.telegram.admin_chat_id');

        Http::asForm()
            ->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'disable_web_page_preview' => true,
            ])
            ->throw();
    }
}

