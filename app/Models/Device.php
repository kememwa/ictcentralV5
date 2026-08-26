<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\AssignDeviceLog;
use App\Models\DeviceIssue;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Printer;

class Device extends Model
{
    /** @use HasFactory<\Database\Factories\DeviceFactory> */
    use HasFactory;
    use SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
  protected $fillable = [
        'user_id',
        'name',
        'type',
        'purchase_date',
        'cost',
        'model',
        'tag_number',
        'serial_number',
        'line_manager_approval',
        'user_accepted',
        'good_condition',
        'branch',
    ];

    //this device belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function assignDeviceLogs(): HasMany
    {
        return $this->hasMany(AssignDeviceLog::class);
    }

    public function issues()
    {
        return $this->hasMany(DeviceIssue::class);
    }

    public function printer()
    {
        return $this->hasOne(Printer::class);
    }

    public function latestAssignmentLog()
    {
        return $this->hasOne(AssignDeviceLog::class)
            ->latestOfMany('action_date');
    }
}
