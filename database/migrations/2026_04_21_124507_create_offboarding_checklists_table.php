<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up() {
    Schema::create('offboarding_checklists', function (Blueprint $table) {
        $table->id();
        $table->foreignId('offboarding_process_id')->constrained()->onDelete('cascade');
        $table->string('department');
        $table->json('checklist_items');
        $table->foreignId('completed_by')->nullable()->constrained('users');
        $table->string('signature_path')->nullable();
        $table->timestamp('completed_at')->nullable();
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('offboarding_checklists');
    }
};