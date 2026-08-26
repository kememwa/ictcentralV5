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
        Schema::create('onboarding_documents', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('onboarding_id')->constrained()->onDelete('cascade');
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');

            // Document metadata
            $table->string('document_path')->nullable(); // path/URL to the uploaded image or file
            $table->boolean('submitted')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete(); // who submitted

            // HR approval metadata
            $table->boolean('hr_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete(); // who approved

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_documents');
    }
};
