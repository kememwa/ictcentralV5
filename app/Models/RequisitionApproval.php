<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequisitionApproval extends Model
{
    protected $fillable = [
        'requisition_id',
        'approver_id',
        'approval_stage',
        'status',
        'comments',
        'approval_cycle',
        'data_snapshot',
        'acted_at',
    ];

    protected function casts(): array
    {
        return [
            'approval_cycle' => 'integer',
            'data_snapshot' => 'array',
            'acted_at' => 'datetime',
        ];
    }

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}