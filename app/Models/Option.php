<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\UserAnswer;

class Option extends Model
{
    /** @use HasFactory<\Database\Factories\OptionFactory> */
    use HasFactory;

        protected $fillable = [
        'question_id',
        'option_letter',
        'option_text',
        'is_correct',
    ];

    // Option belongs to a question
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Option selected in many user answers
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}
