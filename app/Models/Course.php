<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}