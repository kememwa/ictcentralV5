<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingStep extends Model
{
    /** @use HasFactory<\Database\Factories\OnboardingStepsFactory> */
    use HasFactory;

    protected $fillable = [
    'onboarding_id', 'step_number', 'step_name', 'status', 'updated_by'
    ];

    public function onboarding()
    {
        return $this->belongsTo(Onboarding::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
