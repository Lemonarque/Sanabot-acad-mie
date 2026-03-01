<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionCoursePriceRequest extends Model
{
    protected $fillable = [
        'institution_id',
        'course_id',
        'requested_price',
        'approved_price',
        'status',
        'note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'requested_price' => 'decimal:2',
        'approved_price' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
