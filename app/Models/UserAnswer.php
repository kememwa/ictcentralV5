<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\Option;
use App\Models\Onboarding;

class UserAnswer extends Model
{
    /** @use HasFactory<\Database\Factories\UserAnswerFactory> */
    use HasFactory;


        protected $fillable = [
        'user_id',
        'question_id',
        'selected_option_id',
        'is_answered',
        'is_correct',
        'answered_at',
    ];



    // Belongs to a new user
    public function user()
    {
        return $this->belongsTo(Onboarding::class, 'user_id');
    }
    // Belongs to a question
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Belongs to selected option
    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
