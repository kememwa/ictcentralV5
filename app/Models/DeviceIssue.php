<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Device;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceIssue extends Model
{
    //
    protected $fillable = [
        'device_id',
        'comment',
        'status'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
