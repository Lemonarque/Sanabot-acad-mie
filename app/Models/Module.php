<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Module extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
        'is_paid',
        'price',
        'duration_minutes',
        'counts_in_average',
        'required_for_cert',
        'required_for_final_eval',
        'min_score',
        'max_attempts',
        'content_types',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'counts_in_average' => 'boolean',
        'required_for_cert' => 'boolean',
        'required_for_final_eval' => 'boolean',
        'content_types' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isPaidByUser(?User $user): bool
    {
        if (! $this->is_paid) {
            return true;
        }
        if (! $user) {
            return false;
        }
        return $this->payments()
            ->where('user_id', $user->id)
            ->where('status', 'paid')
            ->exists();
    }
}
