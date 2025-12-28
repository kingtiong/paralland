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
            // Admin work tracking
            $table->string('work_status')->default('new')->index()->after('status'); // new | in_progress | waiting_client | completed
            $table->date('expected_completion_date')->nullable()->after('work_status');
            $table->timestamp('completed_at')->nullable()->after('expected_completion_date');

            // Pricing
            $table->decimal('estimated_monthly_usdt', 12, 2)->nullable()->after('estimated_total_usdt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn([
                'work_status',
                'expected_completion_date',
                'completed_at',
                'estimated_monthly_usdt',
            ]);
        });
    }
};
