<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicPerformance extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'topic',
        'average_score',
        'attempt_count',
        'correct_answers',
        'wrong_answers',
        'success_rate',
        'weakness_flag',
        'recommended_difficulty',
        'last_attempt_id',
    ];

    protected $casts = [
        'average_score' => 'decimal:2',
        'success_rate' => 'decimal:2',
        'weakness_flag' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lastAttempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'last_attempt_id');
    }
}