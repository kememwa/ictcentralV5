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
        Schema::create('login_activities', function (Blueprint $table) {
            $table->id();

            // Nullable so activity can still be retained if an account is deleted.
            $table->unsignedBigInteger('user_id')->nullable()->index();

            $table->string('email')->index();
            $table->string('guard')->default('web');
            $table->string('session_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamp('logged_in_at')->index();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('logged_out_at')->nullable();

            $table->string('status')->default('logged_in')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_activities');
    }

};
