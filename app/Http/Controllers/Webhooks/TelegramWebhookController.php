<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TelegramWebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        // Simple shared-secret (recommended). Set TELEGRAM_WEBHOOK_TOKEN and send header.
        $expected = (string) config('services.telegram.webhook_token', '');
        if ($expected !== '' && $request->header('X-Webhook-Token') !== $expected) {
            abort(403);
        }

        $adminChatId = (string) config('services.telegram.admin_chat_id', '');

        $update = $request->all();
        $message = $update['message'] ?? $update['edited_message'] ?? null;
        if (!is_array($message)) {
            return response('ok', 200);
        }

        $chatId = isset($message['chat']['id']) ? (string) $message['chat']['id'] : '';
        if ($adminChatId !== '' && $chatId !== $adminChatId) {
            // Only accept admin chat input
            return response('ok', 200);
        }

        $text = trim((string) ($message['text'] ?? ''));
        if ($text === '') {
            return response('ok', 200);
        }

        // Expected formats:
        // [ORDER#123] reply...
        // [SUPPORT#45] reply... (45 = user_id)
        $matched = [];
        if (!preg_match('/^\[(ORDER|SUPPORT)#(\d+)\]\s*(.+)$/s', $text, $matched)) {
            return response('ok', 200);
        }

        $kind = $matched[1];
        $id = (int) $matched[2];
        $body = trim((string) $matched[3]);
        if ($body === '') {
            return response('ok', 200);
        }

        $externalMessageId = isset($message['message_id']) ? (string) $message['message_id'] : null;

        if ($kind === 'ORDER') {
            $order = Proposal::query()->find($id);
            if (!$order) {
                return response('ok', 200);
            }

            $conversation = Conversation::firstOrCreate(
                ['type' => 'order', 'proposal_id' => $order->id, 'user_id' => $order->user_id],
                ['user_id' => $order->user_id],
            );

            ConversationMessage::create([
                'conversation_id' => $conversation->id,
                'user_id' => null,
                'sender_type' => 'admin',
                'message' => $body,
                'external_provider' => 'telegram',
                'external_message_id' => $externalMessageId,
            ]);

            $conversation->last_message_at = now();
            $conversation->save();
        } else {
            $conversation = Conversation::firstOrCreate(
                ['type' => 'support', 'proposal_id' => null, 'user_id' => $id],
                ['user_id' => $id],
            );

            ConversationMessage::create([
                'conversation_id' => $conversation->id,
                'user_id' => null,
                'sender_type' => 'admin',
                'message' => $body,
                'external_provider' => 'telegram',
                'external_message_id' => $externalMessageId,
            ]);

            $conversation->last_message_at = now();
            $conversation->save();
        }

        return response('ok', 200);
    }
}

