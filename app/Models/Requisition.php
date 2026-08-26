<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Requisition extends Model
{
    /** @use HasFactory<\Database\Factories\RequisitionFactory> */
    use HasFactory;

    protected $fillable = [
        'requested_by',
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
        'hr_id',
    ];

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

}
