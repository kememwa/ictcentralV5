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
        Schema::create('casuals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('id_number')->unique();
            $table->string('nssf_number')->unique();
            $table->string('sha_number')->unique();
            $table->string('phone_number')->unique();
            $table->string('n_name');
            $table->string('n_phone');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casuals');
    }
};
