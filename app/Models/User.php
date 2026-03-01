<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'institution_id',
        'admin_level',
        'approval_status',
        'approved_at',
        'approved_by',
        'phone',
        'organization',
        'position',
        'motivation',
        'date_of_birth',
        'gender',
        'city',
        'country',
        'address',
        'experience_years',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->role && $this->role->name === $role;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('admin') && $this->admin_level === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin') && in_array($this->admin_level, ['super_admin', 'admin']);
    }

    public function isModerator(): bool
    {
        return $this->hasRole('admin') && $this->admin_level === 'moderator';
    }

    public function canManageUsers(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canManageCourses(): bool
    {
        return $this->isAdmin() || $this->isModerator();
    }

    public function canManagePayments(): bool
    {
        return $this->isAdmin();
    }

    public function canManageSettings(): bool
    {
        return $this->isSuperAdmin();
    }

    public function getAdminLevelLabel(): string
    {
        return match($this->admin_level) {
            'super_admin' => 'Super Admin',
            'admin' => 'Administrateur',
            'moderator' => 'Modérateur',
            default => 'Admin',
        };
    }

    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'creator_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function institutionCourseAccesses()
    {
        return $this->hasMany(InstitutionUserCourseAccess::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'date_of_birth' => 'date',
            'password' => 'hashed',
        ];
    }
}
