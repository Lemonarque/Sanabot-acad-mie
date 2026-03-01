<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionRequest extends Model
{
    protected $fillable = [
        'institution_id',
        'requested_seats',
        'approved_seats',
        'status',
        'note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'requested_seats' => 'integer',
        'approved_seats' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
