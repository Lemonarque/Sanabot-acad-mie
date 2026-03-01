<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionUserCourseAccess extends Model
{
    protected $fillable = [
        'institution_id',
        'user_id',
        'course_id',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
