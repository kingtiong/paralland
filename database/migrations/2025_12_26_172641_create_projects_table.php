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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('proposal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_user_id')->constrained('users')->cascadeOnDelete();

            // proposal_accepted | in_development | delivered | client_revision_requested | client_rejected | client_accepted
            $table->string('status')->default('proposal_accepted')->index();

            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('client_accepted_at')->nullable();
            $table->timestamp('client_rejected_at')->nullable();

            // Date the deployment is considered successful (starts maintenance schedule).
            $table->date('deployment_success_date')->nullable()->index();

            // Maintenance configuration (can be changed later if required).
            $table->decimal('maintenance_fee_usdt', 10, 2)->default(20.00);
            $table->unsignedSmallInteger('maintenance_offset_days')->default(20);

            // Fee split (percentages should total 100).
            $table->unsignedSmallInteger('maintenance_server_pct')->default(40);
            $table->unsignedSmallInteger('maintenance_team_pct')->default(20);
            $table->unsignedSmallInteger('maintenance_lp_pct')->default(40);
            $table->timestamps();

            $table->unique('proposal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
