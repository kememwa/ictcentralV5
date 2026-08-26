<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingDocument extends Model
{
    /** @use HasFactory<\Database\Factories\OnboardingDocumentsFactory> */
    use HasFactory;

    protected $fillable = [
        'onboarding_id',
        'document_id',
        'document_path',
        'submitted',
        'submitted_at',
        'submitted_by',
        'hr_approved',
        'approved_at',
        'approved_by',
    ];

    public function onboarding()
    {
        return $this->belongsTo(Onboarding::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
