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
            // 5-step wizard data (stored as JSON so the form can evolve quickly).
            $table->unsignedTinyInteger('wizard_step')->default(1)->after('status');
            $table->json('wizard_step1')->nullable()->after('wizard_step');
            $table->json('wizard_step2')->nullable()->after('wizard_step1');
            $table->json('wizard_step4_estimate')->nullable()->after('wizard_step2');

            $table->decimal('estimated_total_usdt', 12, 2)->nullable()->after('wizard_step4_estimate');

            // Payment (USDT only, BEP20)
            $table->string('payment_chain')->default('BEP20')->after('estimated_total_usdt');
            $table->string('payment_status')->default('unpaid')->index()->after('payment_chain'); // unpaid|pending|paid
            $table->string('payment_to_address', 42)->nullable()->after('payment_status');
            $table->string('payment_from_address', 42)->nullable()->after('payment_to_address');
            $table->string('payment_tx_hash')->nullable()->index()->after('payment_from_address');
            $table->timestamp('submitted_at')->nullable()->after('payment_tx_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn([
                'wizard_step',
                'wizard_step1',
                'wizard_step2',
                'wizard_step4_estimate',
                'estimated_total_usdt',
                'payment_chain',
                'payment_status',
                'payment_to_address',
                'payment_from_address',
                'payment_tx_hash',
                'submitted_at',
            ]);
        });
    }
};
