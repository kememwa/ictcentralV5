<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffboardingChecklist extends Model {
    protected $casts = [
        'checklist_items' => 'array',
        'completed_at'=>'datetime'
    ];

    protected $fillable = [
        'offboarding_process_id', 'department', 'checklist_items',
        'completed_by', 'completed_at','signature_path'
    ];

    public function process() {
        return $this->belongsTo(OffboardingProcess::class);
    }

    public function completedBy() {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function getIsCompletedAttribute() {
        return !is_null($this->completed_at);
    }
}