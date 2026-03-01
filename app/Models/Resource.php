<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'type', 'url', 'lesson_id'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
