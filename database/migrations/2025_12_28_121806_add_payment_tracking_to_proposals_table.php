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
        Schema::table('proposals', function (Blueprint $table) {
            $table->decimal('paid_total_usdt', 12, 2)->nullable()->after('estimated_total_usdt');
            $table->json('paid_modules')->nullable()->after('paid_total_usdt');
            $table->timestamp('payment_verified_at')->nullable()->after('paid_modules');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn(['paid_total_usdt', 'paid_modules', 'payment_verified_at']);
        });
    }
};
