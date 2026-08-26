<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casual extends Model
{
    /** @use HasFactory<\Database\Factories\CasualFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'id_number',
        'nssf_number',
        'is_active',
    ];
}
