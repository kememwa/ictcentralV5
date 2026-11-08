<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;


class Requisition extends Model
{
    /** @use HasFactory<\Database\Factories\RequisitionFactory> */
    use HasFactory;

    protected $fillable = [
        'ref_number',
        'requested_by',
        'department_id',
        'requested_date',
        'no_of_casuals',
        'start_date',
        'end_date',
        'reason',
        'duration',
        'hod_approval_status',
        'hod_id',
        'hod_approval_date',
        'daily_rate',
        'nssf_rate',
        'sha_rate',
        'total_amount',
        'hr_approval_status',
        'hr_rep_id',
        'hr_rep_approval_date',
        'hrm_approval_status',
        'hrm_id',
        'hrm_approval_date',
        'coo_approval_status',
        'casual_assignment_status',
        'coo_approval_date',
        'coo_id',
        'hr_id',
        'uuid',

        // Workflow tracking
        'status',
        'current_approval_stage',
        'return_to_stage',
        'returned_from_stage',
        'returned_by',
        'returned_at',
        'return_reason',
        'resubmitted_at',
        'approval_cycle',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'requested_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',

            'hod_approval_date' => 'datetime',
            'coo_approval_date' => 'datetime',
            'hr_rep_approval_date' => 'datetime',
            'hrm_approval_date' => 'datetime',

            'returned_at' => 'datetime',
            'resubmitted_at' => 'datetime',
            'completed_at' => 'datetime',

            'approval_cycle' => 'integer',
        ];
    }

    // Automatically generate a UUID when creating a new onboarding record
    protected static function booted()
    {
        static::creating(function ($requisition) {
            $requisition->uuid = (string) Str::uuid();
        });
    }

    public function hod()
    {
        return $this->belongsTo(User::class, 'hod_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function hrRep()
    {
        return $this->belongsTo(User::class, 'hr_rep_id');
    }

    public function hrm()
    {
        return $this->belongsTo(User::class, 'hrm_id');
    }

    public function coo()
    {
        return $this->belongsTo(User::class, 'coo_id');
    }

    public function casualAssignments()
    {
        return $this->hasMany(Assignment::class, 'requisition_id');
    }

    // New workflow relationships

    public function approvals(): HasMany
    {
        return $this->hasMany(RequisitionApproval::class);
    }

    public function returnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

}
