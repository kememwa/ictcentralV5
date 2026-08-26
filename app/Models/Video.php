<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\QuizAttempt;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category',
        'department',
        'video_type', // 'upload' or 'youtube'
        'video_path', // for uploaded videos
        'youtube_id', // for YouTube videos
        'youtube_url', // for YouTube videos
        'thumbnail_path', // for uploaded videos
        'thumbnail_url', // for YouTube videos
        'duration',
        'status', // 'processing', 'ready', 'failed'
        'user_id',
    ];

    protected $casts = [
        'duration' => 'integer',
    ];

    // A video has many questions
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    // A video has many quiz attempts
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // Get video URL
    public function getVideoUrlAttribute()
    {
        if ($this->video_type === 'youtube') {
            return $this->youtube_url;
        }
        
        return $this->video_path ? asset('storage/' . $this->video_path) : null;
    }

    // Get thumbnail URL
    public function getThumbnailUrlAttribute()
    {
        if ($this->video_type === 'youtube') {
            return $this->thumbnail_url;
        }
        
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : null;
    }

    // Get YouTube embed URL
    public function getEmbedUrlAttribute()
    {
        if ($this->video_type === 'youtube' && $this->youtube_id) {
            return "https://www.youtube.com/embed/{$this->youtube_id}";
        }
        
        return null;
    }
}