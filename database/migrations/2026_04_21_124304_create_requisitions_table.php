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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();

            // Request originator
            $table->foreignId('requested_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->date('requested_date');
            $table->unsignedInteger('no_of_casuals');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason');
            $table->unsignedInteger('duration');

            /*
            |--------------------------------------------------------------------------
            | HOD Approval
            |--------------------------------------------------------------------------
            */
            $table->boolean('hod_approval_status')->default(false);
            $table->foreignId('hod_id')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();
            $table->timestamp('hod_approval_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | HR Approval
            |--------------------------------------------------------------------------
            */
            $table->boolean('hr_approval_status')->nullable();
            $table->foreignId('hr_rep_id')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();
            $table->timestamp('hr_rep_approval_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | HRM Approval
            |--------------------------------------------------------------------------
            */
            $table->string('hrm_approval_status')->default('pending');
            $table->foreignId('hrm_id')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();
            $table->timestamp('hrm_approval_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | COO Approval
            |--------------------------------------------------------------------------
            */
            $table->boolean('coo_approval_status')->default(false);
            $table->foreignId('coo_id')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            $table->boolean('casual_assignment_status')->default(false);
            $table->timestamp('coo_approval_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Rates
            |--------------------------------------------------------------------------
            */
            $table->decimal('daily_rate', 10, 2)->nullable();

            $table->decimal('total_amount', 15, 2)->nullable();
            

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};
