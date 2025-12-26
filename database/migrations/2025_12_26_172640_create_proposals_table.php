<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // High-level proposal info.
            $table->string('title')->nullable();
            $table->longText('description')->nullable();

            // Flexible structured requirements (content links, APIs, credentials, etc.).
            $table->json('requirements')->nullable();

            // Development price quoted/accepted in RBE (integer tokens; no decimals).
            $table->unsignedBigInteger('development_price_rbe')->default(2);

            // submitted | revision_requested | accepted | rejected
            $table->string('status')->default('submitted')->index();

            $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('review_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
