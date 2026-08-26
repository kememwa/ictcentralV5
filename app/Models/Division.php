<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Department;

class Division extends Model
{
    /** @use HasFactory<\Database\Factories\DivisionFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'department_id',
    ];
  
    // a division belongs to a department
    public function department(){
        return $this->belongsTo(Department::class);
    }

    // a division has many designations
    public function designations(){
        return $this->hasMany(Designation::class);
    }
}
