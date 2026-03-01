<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'title', 'description', 'content', 'video_url', 'module_id', 'order', 'is_free_preview'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}
