<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Printer extends Model
{
    /** @use HasFactory<\Database\Factories\PrinterFactory> */
    use HasFactory;

    protected $fillable = [
        'device_id',
        'office_location',
    ];

    public function toners()
    {
        return $this->belongsToMany(Toner::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
