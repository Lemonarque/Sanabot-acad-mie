<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $fillable = [
        'enrollment_id', 'module_id', 'validated', 'score'
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
