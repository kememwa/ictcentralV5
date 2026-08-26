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
        Schema::create('employee_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offboarding_process_id')->constrained()->onDelete('cascade');
            $table->string('next_of_kin');
            $table->string('relationship');
            $table->string('next_of_kin_telephone');
            $table->string('employee_name');
            $table->string('payroll_number');
            $table->date('confirmation_date');
            $table->string('signature_path')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_confirmations');
    }
};