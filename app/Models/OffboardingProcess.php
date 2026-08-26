<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffboardingProcess extends Model {
    protected $casts = [
        'completed_steps' => 'array',
        'last_day_of_service' => 'date',
        'initiated_at' => 'datetime',
    'completed_at' => 'datetime',
     'employee_confirmation' => 'boolean',
        'employee_confirmation_date' => 'datetime',
    ];

    protected $fillable = [
        'employee_id', 'status', 'mode_of_exit', 'last_day_of_service',
        'completed_steps', 'current_step', 'comments', 'initiated_by', 'initiated_at',   'completed_at', 'employee_confirmation',
        'employee_confirmation_date'
    ];

    public function employee() {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function checklists() {
        return $this->hasMany(OffboardingChecklist::class);
    }

    public function initiatedBy() {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function getProgressPercentageAttribute() {
        $completed = $this->checklists()->whereNotNull('completed_at')->count();
        $total = $this->checklists()->count();
        return $total > 0 ? round(($completed / $total) * 100) : 0;
    }

    public function getChecklistByDepartment($department) {
        return $this->checklists()->where('department', $department)->first();
    }
public function managerConfirmation()
{
    return $this->hasOne(ManagerConfirmation::class);
}
    public function isChecklistCompleted($department) {
        $checklist = $this->getChecklistByDepartment($department);
        return $checklist && $checklist->completed_at !== null;
    }

    public function allChecklistsCompleted() {
        return $this->checklists()->whereNull('completed_at')->count() === 0;
    }
public function getProgressPercentage(): int
{
    $progress = 0;

    // Step 1 (Initiation) → 9%
    if ($this->isStepCompleted(1)) {
        $progress += 9;
    }

    // Steps 2–8 (Supervisor → Manager) → 13% each
    foreach (range(2, 8) as $step) {
        if ($this->isStepCompleted($step)) {
            $progress += 13;
        }
    }

    return $progress;
}
public function isStepCompleted(int $step): bool
{
    $stepMap = [
        1 => 'Initiation',
        2 => 'Immediate Supervisor',
        3 => 'Administration',
        4 => 'IT',
        5 => 'Finance',
        6 => 'HR',
        7 => 'Employee',
        8 => 'Manager',
    ];

    $dept = $stepMap[$step] ?? null;

    if (!$dept) {
        return false;
    }

    // Checklists (supervisor, admin, IT, finance, HR)
    if (in_array($dept, ['Immediate Supervisor', 'Administration', 'IT', 'Finance', 'HR'])) {
        return $this->isChecklistCompleted($dept);
    }

    // Initiation check
    if ($dept === 'Initiation') {
        return (bool) $this->initiated_at;
    }

    // Employee confirmation
    if ($dept === 'Employee') {
        return $this->employee_confirmation === true;
    }

    // Manager confirmation
    if ($dept === 'Manager') {
        return (bool) $this->managerConfirmation;
    }

    return false;

}

}