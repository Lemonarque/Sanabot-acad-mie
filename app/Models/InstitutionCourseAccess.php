<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionCourseAccess extends Model
{
    protected $fillable = [
        'institution_id',
        'course_id',
        'is_enabled',
        'adjusted_price',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'adjusted_price' => 'decimal:2',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
