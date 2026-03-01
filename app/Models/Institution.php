<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'contact_email',
        'contact_phone',
        'owner_user_id',
        'approved_learner_quota',
        'is_active',
    ];

    protected $casts = [
        'approved_learner_quota' => 'integer',
        'is_active' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function learners()
    {
        return $this->hasMany(User::class, 'institution_id');
    }

    public function requests()
    {
        return $this->hasMany(InstitutionRequest::class);
    }

    public function invitations()
    {
        return $this->hasMany(InstitutionInvitation::class);
    }

    public function courseAccesses()
    {
        return $this->hasMany(InstitutionCourseAccess::class);
    }

    public function userCourseAccesses()
    {
        return $this->hasMany(InstitutionUserCourseAccess::class);
    }

    public function coursePriceRequests()
    {
        return $this->hasMany(InstitutionCoursePriceRequest::class);
    }

    public function courseAccessRequests()
    {
        return $this->hasMany(InstitutionCourseAccessRequest::class);
    }
}
