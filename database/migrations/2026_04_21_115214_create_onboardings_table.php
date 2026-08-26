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
        Schema::create('onboardings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Linked once user is created
            $table->string('name');
            $table->string('email')->unique(); // Email captured at start
            $table->string('phone'); // Phone number
            $table->date('start_date');
            $table->string('position');
            $table->string('department');
            $table->enum('status', ['in_progress', 'completed', 'cancelled'])->default('in_progress');
            $table->integer('progress')->default(0); // percentage (0–100)
            $table->json('requirements')->nullable();
            $table->string('password');
            $table->boolean('completed')->default(false); // quick flag
            $table->uuid('uuid')->unique()->nullable();
            $table->boolean('hr_finished')->default(false);
            $table->boolean('it_finished')->default(false);
            $table->boolean('submit_score')->default(false);
            $table->integer('score')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboardings');
    }
};
