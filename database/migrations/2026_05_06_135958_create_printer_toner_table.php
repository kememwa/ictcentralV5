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
        Schema::create('printer_toner', function (Blueprint $table) {
            $table->id();

            $table->foreignId('printer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('toner_id')
                ->constrained()
                ->cascadeOnDelete();

            // Prevent duplicate combinations
            $table->unique(['printer_id', 'toner_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printer_toner');
    }
};
