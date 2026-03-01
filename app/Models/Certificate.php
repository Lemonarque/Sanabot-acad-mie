<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'enrollment_id',
        'pdf_path',
        'verification_code',
        'issued_at',
        'qr_code_path',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
