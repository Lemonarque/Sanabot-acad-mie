<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseImageController extends Controller
{
    public function __invoke(Course $course)
    {
        $media = $course->getFirstMedia('course_presentation');

        if ($media) {
            $mediaPath = $media->getPath();
            if (is_string($mediaPath) && file_exists($mediaPath)) {
                return response()->file($mediaPath);
            }
        }

        if (! empty($course->presentation_image)) {
            $path = ltrim($course->presentation_image, '/');

            if (Str::startsWith($path, 'public/')) {
                $path = Str::after($path, 'public/');
            }

            if (Str::startsWith($path, 'storage/')) {
                $path = Str::after($path, 'storage/');
            }

            if (Storage::disk('public')->exists($path)) {
                return response()->file(Storage::disk('public')->path($path));
            }
        }

        $fallback = public_path('images/presentation/default-no-image.svg');

        if (file_exists($fallback)) {
            return response()->file($fallback);
        }

        abort(404);
    }
}
