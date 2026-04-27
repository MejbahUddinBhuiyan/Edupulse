<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\TopicPerformance;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id',
        'user_id',
        'attempt_number',
        'total_marks',
        'obtained_marks',
        'status',
        'started_at',
        'submitted_at',
        'graded_by',
        'is_overridden',
        'override_note',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'is_overridden' => 'boolean',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class);
    }
    public function topicPerformances()
{
    return $this->hasMany(TopicPerformance::class, 'last_attempt_id');
}
}