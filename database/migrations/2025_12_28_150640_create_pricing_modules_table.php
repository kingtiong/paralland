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
        Schema::create('pricing_modules', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g. mlm, marketplace
            $table->string('label');
            $table->decimal('dev_cost_usdt', 12, 2)->default(0);
            $table->decimal('monthly_cost_usdt', 12, 2)->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_modules');
    }
};
