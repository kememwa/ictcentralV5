<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Device;

class AssignDeviceLog extends Model
{
    /** @use HasFactory<\Database\Factories\AssignDeviceLogFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */protected $fillable = [
        'device_id',
        'user_id',
        'action_by',
        'action_type',
        'action_date',
        'reason',
        'comment',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class)->withTrashed();
    }
    public function user() {
    return $this->belongsTo(User::class);
    }
    public function actionByUser() {
        return $this->belongsTo(User::class, 'action_by');
    }

    public function previousUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
