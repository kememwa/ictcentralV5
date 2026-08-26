<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Division;


class Designation extends Model
{
    /** @use HasFactory<\Database\Factories\DesignationFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'division_id',
        'status',
    ];

    //; a designation belongs to a division
    public function division(){
        return $this->belongsTo(Division::class);
    }
}
