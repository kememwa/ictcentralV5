<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Video;
use App\Models\Option;
use App\Models\UserAnswer;

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;

        protected $fillable = [
        'video_id',
        'question',
    ];

        // Question belongs to a video
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    // Question has many options
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    // Question has many user answers
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}
