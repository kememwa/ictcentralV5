<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toner extends Model
{
    /** @use HasFactory<\Database\Factories\TonerFactory> */
    use HasFactory;

    protected $fillable = [
        'brand_name',
        'model',
        'color',
        'quantity',
    ];

    public function printers()
    {
        return $this->belongsToMany(Printer::class);
    }
}
