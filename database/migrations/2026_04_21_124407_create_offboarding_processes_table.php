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
    Schema::create('offboarding_processes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
        $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
        $table->string('mode_of_exit');
        $table->date('last_day_of_service');
        $table->json('completed_steps')->nullable();
        $table->integer('current_step')->default(1);
        $table->text('comments')->nullable();
        $table->foreignId('initiated_by')->constrained('users')->onDelete('cascade');
        $table->timestamp('initiated_at')->nullable();
        $table->timestamp('completed_at')->nullable();
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('offboarding_processes');
    }
};