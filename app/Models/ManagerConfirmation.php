<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerConfirmation extends Model
{
    /** @use HasFactory<\Database\Factories\ManagerConfirmationFactory> */
    use HasFactory;
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerConfirmation extends Model
{
    /** @use HasFactory<\Database\Factories\ManagerConfirmationFactory> */
    use HasFactory;

        protected $casts=[
        'confirmed_at'=>'datetime'
    ];
    
    protected $fillable = [
        'offboarding_process_id',
        'confirmed_by',
        'confirmed_at',
        'signature_path',
    ];


 public function offboardingProcess()
    {
        return $this->belongsTo(OffboardingProcess::class);
    }

    // The manager (user) who confirmed
    public function manager()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }
}
