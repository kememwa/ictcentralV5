<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'guard',
        'session_id',
        'ip_address',
        'user_agent',
        'logged_in_at',
        'last_activity_at',
        'logged_out_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'logged_in_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'logged_out_at' => 'datetime',
        ];
    }
}
