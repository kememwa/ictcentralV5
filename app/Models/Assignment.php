<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    /** @use HasFactory<\Database\Factories\AssignmentsFactory> */
    use HasFactory;

    protected $fillable = [
        'requisition_id',
        'casual_id',
        'status',
    ];  


    public function casual()
    {
        return $this->belongsTo(Casual::class, 'casual_id');
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }
}
