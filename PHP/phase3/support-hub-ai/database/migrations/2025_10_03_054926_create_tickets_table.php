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
        Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('team_id')->constrained()->onDelete('cascade');
        $table->foreignId('department_id')->constrained()->onDelete('cascade');
        $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null');

        $table->string('title');
        $table->text('content');
        $table->string('status')->default('open'); // e.g., open, in_progress, closed
        $table->string('priority')->nullable(); // e.g., low, medium, high (for AI)
        $table->string('category')->nullable(); // e.g., billing, technical (for AI)
        $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
