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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('type');
            $table->date('purchase_date')->nullable();
            $table->integer('cost')->nullable();
            $table->string('model')->nullable();
            $table->string('tag_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->boolean('line_manager_approval')->default(false);
            $table->boolean('user_accepted')->default(false);
            $table->boolean('good_condition')->default(true);
            $table->enum('branch', ['HQ', 'Tatu-city', 'Mombasa', 'FGS2','Wall-street'])->default('HQ');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
