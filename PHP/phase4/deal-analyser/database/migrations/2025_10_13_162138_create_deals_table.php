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
        Schema::create('deals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->decimal('value_estimate', 15, 2)->nullable();
            $table->integer('duration_months')->nullable();
            $table->enum('status',['draft', 'review', 'validated', 'accepted',])->default('draft');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};




// {
//   "title": "Joint AI Research and Development Partnership",
//   "description": "Collaboration on developing a next-generation predictive modeling algorithm (Project Chimera), pooling resources and intellectual property.",
//   "value_estimate": 5000000.00,
//   "duration_months": 24,
//   "status": "validated",
//   "image_path": null,
  
//   // --- Party A (Synergy Tech Solutions Inc.) ---
//   "party_a_name": "Synergy Tech Solutions Inc.",
//   "party_a_contact_name": "Dr. Elias Vance (CEO)",
//   "party_a_contact_email": "elias.vance@synergytech.com",
//   "party_a_contribution_details": "$3M funding + 5 full-time engineers.",
  
//   // --- Party B (Global Data Insights Corp.) ---
//   "party_b_name": "Global Data Insights Corp.",
//   "party_b_contact_name": "Ms. Clara Jensen (CDO)",
//   "party_b_contact_email": "clara.jensen@gdi.com",
//   "party_b_contribution_details": "Proprietary Data ($2M est.) + 3 senior data scientists.",
  
//   // --- Additional Deal Details ---
//   "ip_ownership": "joint",
//   "confidentiality_duration_years": 5,
  
//   "created_at": "2025-10-16 00:00:00",
//   "updated_at": "2025-10-16 00:00:00"
// }