<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Services\TwilioWhatsApp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportChatController extends Controller
{
    public function show(Request $request): View
    {
        $conversation = Conversation::firstOrCreate(
            ['type' => 'support', 'proposal_id' => null, 'user_id' => $request->user()->id],
            ['user_id' => $request->user()->id],
        );

        $conversation->load(['messages.user']);

        return view('support.chat', [
            'conversation' => $conversation,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
        ]);

        $conversation = Conversation::firstOrCreate(
            ['type' => 'support', 'proposal_id' => null, 'user_id' => $request->user()->id],
            ['user_id' => $request->user()->id],
        );

        ConversationMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => $request->user()->id,
            'sender_type' => 'user',
            'message' => $data['message'],
        ]);

        $conversation->last_message_at = now();
        $conversation->save();

        // Optional: notify admin via WhatsApp (admin replies with tag to route back).
        try {
            app(TwilioWhatsApp::class)->sendToAdmin("[SUPPORT#{$request->user()->id}] {$data['message']}");
        } catch (\Throwable $e) {
            // Don't block chat if WhatsApp is not configured or fails.
        }

        return back()->with('status', 'Message sent.');
    }
}

