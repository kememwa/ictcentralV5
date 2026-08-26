<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; 
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\OnboardingDocuments;
use Illuminate\Support\Str;
use App\Models\UserAnswer;


class Onboarding extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\OnboardingFactory> */
    use HasFactory;
    use Notifiable;


    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'start_date',
        'position',
        'department',
        'status',
        'progress',
        'completed',
        'completed_at',
        'uuid',
        'password',
        'requirements',
        'hr_finished',
        'it_finished',
        'submit_score',
        'score',    

    ];

        protected $hidden = [
        'password',
    ];

// Automatically generate a UUID when creating a new onboarding record
    protected static function booted()
    {
        static::creating(function ($onboarding) {
            $onboarding->uuid = (string) Str::uuid();
        });
    }


    //onboarding has many user answers
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'user_id');
    }


    public function steps()
    {
        return $this->hasMany(OnboardingSteps::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(OnboardingDocuments::class);
    }

    // Override the method to send a custom password reset notification
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\OnboardingResetPassword($token));
    }
}
