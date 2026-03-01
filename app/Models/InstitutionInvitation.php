<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionInvitation extends Model
{
    protected $fillable = [
        'institution_id',
        'user_id',
        'email',
        'status',
        'invited_by',
        'invited_at',
        'error_message',
    ];

    protected $casts = [
        'invited_at' => 'datetime',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
