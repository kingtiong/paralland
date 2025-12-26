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
        Schema::create('maintenance_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            $table->date('due_date')->index();
            $table->decimal('amount_usdt', 10, 2)->default(20.00);

            // due | paid | overdue | cancelled
            $table->string('status')->default('due')->index();

            $table->timestamp('paid_at')->nullable();
            $table->string('payment_tx_hash')->nullable()->index();
            $table->string('paid_wallet_address', 42)->nullable();

            // Recorded fee splits for this invoice (snapshot at payment time).
            $table->decimal('split_server_usdt', 10, 2)->nullable();
            $table->decimal('split_team_usdt', 10, 2)->nullable();
            $table->decimal('split_lp_usdt', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_invoices');
    }
};
