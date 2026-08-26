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
        Schema::create('assign_device_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('device_id')->constrained();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();      // The target user
            $table->foreignId('action_by')->constrained('users')->onDelete('cascade'); // Who performed the action

            $table->enum('action_type', ['assign', 'unassign','delete']);
            $table->timestamp('action_date')->useCurrent();

            // New fields
            $table->string('reason')->nullable();   // e.g., 'replacement', 'maintenance', etc.
            $table->text('comment')->nullable();    // optional comment or explanation

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assign_device_logs');
    }
};
