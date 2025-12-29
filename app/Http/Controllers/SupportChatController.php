<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Services\TelegramBot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        // Optional: notify admin via Telegram (admin replies with tag to route back).
        try {
            $u = $request->user();
            $base = rtrim((string) config('app.url', ''), '/');
            $memberUrl = $base ? "{$base}/admin/members/{$u->id}" : null;

            $text = "🔔 New SUPPORT message\n"
                ."From: {$u->name} <{$u->email}> (user_id={$u->id})\n"
                .($memberUrl ? "Open: {$memberUrl}\n" : '')
                ."Reply tag: [SUPPORT#{$u->id}]\n"
                ."\n"
                ."Message:\n{$data['message']}";

            app(TelegramBot::class)->sendToAdmin($text);
        } catch (\Throwable $e) {
            Log::warning('Telegram notify failed (support chat)', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('status', 'Message sent.');
    }
}

