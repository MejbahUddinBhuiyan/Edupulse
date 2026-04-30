<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\StudentPerformance;
use App\Models\TopicPerformance;
use App\Models\Recommendation;
use App\Models\CourseEnrollment;
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isAdminOrTeacher()
    {
        return in_array($this->role, ['admin', 'teacher']);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function createdQuizzes(): HasMany
{
    return $this->hasMany(Quiz::class, 'created_by');
}

public function quizAttempts(): HasMany
{
    return $this->hasMany(QuizAttempt::class, 'user_id');
}

public function gradedAttempts(): HasMany
{
    return $this->hasMany(QuizAttempt::class, 'graded_by');
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