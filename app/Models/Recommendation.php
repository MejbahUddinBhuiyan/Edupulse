<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'topic',
        'type',
        'title',
        'message',
        'recommended_difficulty',
        'content_type',
        'content_id',
        'priority',
        'is_read',
        'generated_from',
    ];

    protected $casts = [
        'is_read' => 'boolean',
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