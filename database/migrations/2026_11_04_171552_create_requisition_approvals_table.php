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
        Schema::create('requisition_approvals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('requisition_id')
                ->constrained('requisitions')
                ->cascadeOnDelete();

            // The person who performed the approval or return.
            $table->foreignId('approver_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // hod, coo, hr_rep or hrm
            $table->string('approval_stage');

            // pending, approved or returned
            $table->string('status')->default('pending');

            $table->text('comments')->nullable();

            // Tracks which resubmission cycle this action belongs to.
            $table->unsignedInteger('approval_cycle')->default(1);

            // Preserve rate details and other relevant values at the time
            // an approval action takes place.
            $table->json('data_snapshot')->nullable();

            $table->timestamp('acted_at')->nullable();

            $table->timestamps();

            $table->index([
                'requisition_id',
                'approval_cycle',
                'approval_stage',
            ], 'req_approval_stage_cycle_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_approvals');
    }
};
