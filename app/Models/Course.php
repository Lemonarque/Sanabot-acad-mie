<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Course extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'short_description',
        'detailed_description',
        'objectives',
        'target_audience',
        'level',
        'language',
        'total_duration_minutes',
        'certification_enabled',
        'min_average',
        'final_evaluation_mode',
        'manual_validation',
        'payment_mode',
        'creator_id',
        'status',
        'is_active',
        'is_paid',
        'price',
        'presentation_image',
        'show_on_home_carousel',
        'home_carousel_order',
    ];

    public function getPresentationImageUrlAttribute(): ?string
    {
        if (! $this->exists) {
            return null;
        }

        if (! empty($this->presentation_image)) {
            $path = ltrim((string) $this->presentation_image, '/');

            if (Str::startsWith($path, 'images/') && file_exists(public_path($path))) {
                return '/' . $path;
            }

            if (Str::startsWith($path, 'public/')) {
                $path = Str::after($path, 'public/');
            }

            if (Str::startsWith($path, 'storage/')) {
                $path = Str::after($path, 'storage/');
            }

            if (Storage::disk('public')->exists($path)) {
                return '/storage/' . ltrim($path, '/');
            }
        }

        $media = $this->getFirstMedia('course_presentation');

        if ($media) {
            $mediaPath = ltrim((string) $media->getPathRelativeToRoot(), '/');

            if (Storage::disk($media->disk)->exists($mediaPath)) {
                return '/storage/' . ltrim($mediaPath, '/');
            }
        }

        return '/images/presentation/default-no-image.svg';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('course_presentation')
            ->singleFile();
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function finalQuizzes()
    {
        return $this->hasMany(Quiz::class)->whereNull('module_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function institutionAccesses()
    {
        return $this->hasMany(InstitutionCourseAccess::class);
    }

    public function institutionUserAccesses()
    {
        return $this->hasMany(InstitutionUserCourseAccess::class);
    }

    public function institutionAccessRequests()
    {
        return $this->hasMany(InstitutionCourseAccessRequest::class);
    }

    public function getPriceForUser(?User $user): ?float
    {
        if (! $this->is_paid || $this->price === null) {
            return null;
        }

        if (! $user || ! $user->institution_id) {
            return (float) $this->price;
        }

        $institutionAccess = $this->institutionAccesses()
            ->where('institution_id', $user->institution_id)
            ->first();

        if ($institutionAccess && $institutionAccess->adjusted_price !== null) {
            return (float) $institutionAccess->adjusted_price;
        }

        return (float) $this->price;
    }
}
