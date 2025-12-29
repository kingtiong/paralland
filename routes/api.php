<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProposalController;
use App\Http\Controllers\Api\Admin\ProposalController as AdminProposalController;
use App\Http\Controllers\Webhooks\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/auth/nonce', [AuthController::class, 'nonce']);
Route::post('/auth/verify', [AuthController::class, 'verify']);

Route::post('/webhooks/telegram', TelegramWebhookController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Client proposals
    Route::get('/proposals', [ProposalController::class, 'index']);
    Route::post('/proposals', [ProposalController::class, 'store']);
    Route::get('/proposals/{proposal}', [ProposalController::class, 'show']);

    // Admin review
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/proposals', [AdminProposalController::class, 'index']);
        Route::post('/proposals/{proposal}/review', [AdminProposalController::class, 'review']);
    });
});

