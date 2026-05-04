<?php

namespace App\Models;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\StudentPerformance;
use App\Models\TopicPerformance;
use App\Models\Recommendation;
use App\Models\CourseEnrollment;
class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'is_published',
        'difficulty_level',
        'rating',
    ];
    public function category()
{
    return $this->belongsTo(Category::class);
}
public function quizzes(): HasMany
{
    return $this->hasMany(Quiz::class);
}
public function reviews()
{
    return $this->hasMany(Review::class);
}

public function updateAverageRating()
{
    $average = $this->reviews()->avg('rating') ?? 0;

    $this->update([
        'rating' => round($average, 1),
    ]);
}

// prerequisites of this course
public function prerequisites()
{
    return $this->belongsToMany(
        Course::class,
        'course_prerequisite',
        'course_id',
        'prerequisite_id'
    );
}

// courses that depend on this course
public function dependentCourses()
{
    return $this->belongsToMany(
        Course::class,
        'course_prerequisite',
        'prerequisite_id',
        'course_id'
    );
}
public function studentPerformances()
{
    return $this->hasMany(StudentPerformance::class);
}

public function topicPerformances()
{
    return $this->hasMany(TopicPerformance::class);
}

public function recommendations()
{
    return $this->hasMany(Recommendation::class);
}
public function enrollments()
{
    return $this->hasMany(CourseEnrollment::class);
}
}