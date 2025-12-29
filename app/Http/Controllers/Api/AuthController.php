<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\EthereumPersonalSign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function nonce(Request $request)
    {
        $data = $request->validate([
            'wallet' => ['required', 'string', 'regex:/^0x[a-fA-F0-9]{40}$/'],
        ]);

        $wallet = strtolower($data['wallet']);
        $nonce = Str::random(32);
        $issuedAt = now()->toIso8601String();

        Cache::put($this->cacheKey($wallet), [
            'nonce' => $nonce,
            'issued_at' => $issuedAt,
        ], now()->addMinutes(10));

        return response()->json([
            'wallet' => $wallet,
            'nonce' => $nonce,
            'message' => $this->buildMessage($wallet, $nonce, $issuedAt),
            'expires_in_seconds' => 600,
        ]);
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'wallet' => ['required', 'string', 'regex:/^0x[a-fA-F0-9]{40}$/'],
            'nonce' => ['required', 'string'],
            'signature' => ['required', 'string'],
        ]);

        $wallet = strtolower($data['wallet']);
        $cached = Cache::get($this->cacheKey($wallet));

        if (!is_array($cached) || !isset($cached['nonce'], $cached['issued_at'])) {
            throw ValidationException::withMessages([
                'nonce' => 'Nonce expired or not found. Request a new nonce.',
            ]);
        }

        if (!hash_equals((string) $cached['nonce'], (string) $data['nonce'])) {
            throw ValidationException::withMessages([
                'nonce' => 'Invalid nonce.',
            ]);
        }

        $message = $this->buildMessage($wallet, (string) $cached['nonce'], (string) $cached['issued_at']);

        try {
            $ok = EthereumPersonalSign::verify($message, (string) $data['signature'], $wallet);
        } catch (\Throwable $e) {
            $ok = false;
        }

        if (!$ok) {
            throw ValidationException::withMessages([
                'signature' => 'Invalid signature.',
            ]);
        }

        Cache::forget($this->cacheKey($wallet));

        $user = User::firstOrCreate(
            ['wallet_address' => $wallet],
            ['role' => 'member']
        );

        // For simplicity: one active token per wallet.
        $user->tokens()->delete();
        $token = $user->createToken('wallet')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'wallet_address' => $user->wallet_address,
                'role' => $user->role,
            ],
        ]);
    }

    public function me(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'wallet_address' => $user->wallet_address,
            'role' => $user->role,
        ]);
    }

    public function logout(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $user->currentAccessToken()?->delete();

        return response()->json(['ok' => true]);
    }

    private function cacheKey(string $wallet): string
    {
        return 'wallet_login:'.strtolower($wallet);
    }

    private function buildMessage(string $wallet, string $nonce, string $issuedAt): string
    {
        $appUrl = (string) config('app.url');

        return "Paralland Login\n"
            ."Wallet: {$wallet}\n"
            ."Nonce: {$nonce}\n"
            ."Issued At: {$issuedAt}\n"
            ."URI: {$appUrl}\n";
    }
}

