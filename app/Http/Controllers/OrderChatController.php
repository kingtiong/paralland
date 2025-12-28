<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Proposal;
use App\Services\TelegramBot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderChatController extends Controller
{
    public function store(Request $request, Proposal $order): RedirectResponse
    {
        if ($order->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
        ]);

        $conversation = Conversation::firstOrCreate(
            ['type' => 'order', 'proposal_id' => $order->id, 'user_id' => $order->user_id],
            ['user_id' => $order->user_id],
        );

        ConversationMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => $request->user()->id,
            'sender_type' => $request->user()->isAdmin() ? 'admin' : 'user',
            'message' => $data['message'],
        ]);

        $conversation->last_message_at = now();
        $conversation->save();

        if (!$request->user()->isAdmin()) {
            // Optional: notify admin via Telegram (admin replies with tag to route back).
            try {
                $u = $request->user();
                $base = rtrim((string) config('app.url', ''), '/');
                $orderUrl = $base ? "{$base}/admin/orders/{$order->id}" : null;
                $memberUrl = $base ? "{$base}/admin/members/{$u->id}" : null;

                $text = "🔔 New ORDER message\n"
                    ."Order: #{$order->id} — {$order->title}\n"
                    ."From: {$u->name} <{$u->email}> (user_id={$u->id})\n"
                    .($orderUrl ? "Open order: {$orderUrl}\n" : '')
                    .($memberUrl ? "Open member: {$memberUrl}\n" : '')
                    ."Reply tag: [ORDER#{$order->id}]\n"
                    ."\n"
                    ."Message:\n{$data['message']}";

                app(TelegramBot::class)->sendToAdmin($text);
            } catch (\Throwable $e) {
                Log::warning('Telegram notify failed (order chat)', [
                    'order_id' => $order->id,
                    'user_id' => $request->user()->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return back()->with('status', 'Message sent.');
    }
}

