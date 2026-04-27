<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPerformance extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'average_score',
        'total_quizzes_attempted',
        'total_topics_analyzed',
        'weak_topics_count',
        'strong_topics_count',
        'recommended_difficulty',
        'study_minutes_per_day',
        'last_analyzed_at',
    ];

    protected $casts = [
        'average_score' => 'decimal:2',
        'last_analyzed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}