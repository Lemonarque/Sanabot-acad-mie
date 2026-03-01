<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FinalQuizResult;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id', 'course_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    public function finalQuizResults()
    {
        return $this->hasMany(FinalQuizResult::class);
    }
}
