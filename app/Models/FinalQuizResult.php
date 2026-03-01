<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalQuizResult extends Model
{
    protected $fillable = [
        'enrollment_id',
        'quiz_id',
        'score_percent',
        'passed',
    ];

    protected $casts = [
        'passed' => 'boolean',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
