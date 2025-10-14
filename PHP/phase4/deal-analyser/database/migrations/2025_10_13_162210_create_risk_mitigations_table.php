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
        Schema::create('risk_mitigations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('deal_id')->constrained('deals')->onDelete('cascade');
            $table->string('category')->nullable();
            $table->text('risk');
            $table->decimal('likelihood', 3, 2)->nullable(); // 0–1
            $table->decimal('impact', 3, 2)->nullable();     // 0–1
            $table->text('mitigation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_mitigations');
    }
};
