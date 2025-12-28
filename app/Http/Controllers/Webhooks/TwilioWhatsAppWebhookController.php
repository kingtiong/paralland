<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class TwilioWhatsAppWebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        // Minimal shared-secret check (recommended to replace with Twilio signature verification).
        $expected = (string) env('TWILIO_WHATSAPP_WEBHOOK_TOKEN', '');
        if ($expected !== '' && $request->header('X-Webhook-Token') !== $expected) {
            abort(403);
        }

        $body = trim((string) $request->input('Body', ''));
        if ($body === '') {
            return response('ok', 200);
        }

        // Expected formats:
        // [ORDER#123] your message...
        // [SUPPORT#45] your message... (45 = user_id)
        $matched = [];
        if (!preg_match('/^\[(ORDER|SUPPORT)#(\d+)\]\s*(.+)$/s', $body, $matched)) {
            // Ignore messages that don't include a routing tag.
            return response('ok', 200);
        }

        $kind = $matched[1];
        $id = (int) $matched[2];
        $message = trim((string) $matched[3]);
        if ($message === '') {
            return response('ok', 200);
        }

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
                'message' => $message,
                'external_provider' => 'twilio',
                'external_message_id' => (string) $request->input('MessageSid', ''),
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
                'message' => $message,
                'external_provider' => 'twilio',
                'external_message_id' => (string) $request->input('MessageSid', ''),
            ]);

            $conversation->last_message_at = now();
            $conversation->save();
        }

        // Twilio accepts any 200 response; return plain text to avoid revealing internals.
        return response('ok', 200);
    }
}
