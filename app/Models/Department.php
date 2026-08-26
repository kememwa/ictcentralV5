<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Division;

class Department extends Model
{
    //
     use HasFactory;
     protected $fillable = [
        'name',
        'hod_id',
        'status',
     ];
//one department has one head of department
    public function hod(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hod_id', 'id');
    }

    // One department has many divisions
    public function divisions(): HasMany
    {
        return $this->hasMany(Division::class);
    }
   
}
