<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeConfirmation extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeConfirmationFactory> */
    use HasFactory;

     protected $fillable = [
        'offboarding_process_id',
        'next_of_kin',
        'relationship',
        'next_of_kin_telephone',
        'employee_name',
        'payroll_number',
        'confirmation_date',
        'confirmed_by',
        'signature_path'
    ];

    public function offboardingProcess()
    {
        return $this->belongsTo(OffboardingProcess::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }
}
