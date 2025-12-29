<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            // support | order
            $table->string('type')->index();

            // For order chats
            $table->foreignId('proposal_id')->nullable()->constrained('proposals')->cascadeOnDelete();

            // For support chats (and as owner for order chats)
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();

            $table->timestamp('last_message_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['type', 'proposal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};

