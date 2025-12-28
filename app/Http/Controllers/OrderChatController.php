<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Proposal;
use App\Services\TwilioWhatsApp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
            // Optional: notify admin via WhatsApp (admin replies with tag to route back).
            try {
                app(TwilioWhatsApp::class)->sendToAdmin("[ORDER#{$order->id}] {$data['message']}");
            } catch (\Throwable $e) {
                // Don't block chat if WhatsApp is not configured or fails.
            }
        }

        return back()->with('status', 'Message sent.');
    }
}

