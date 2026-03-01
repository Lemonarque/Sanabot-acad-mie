<?php

namespace App\Livewire\Courses;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Catalogue extends Component
{
    public $search = '';
    public $categoryId = '';
    public $categories;
    public $courses;

    private function loadCourses(): void
    {
        $search = trim($this->search);
        $user = Auth::user();
        $institutionId = $user?->institution_id;

        $courses = Course::with(['creator', 'modules.lessons'])
            ->withCount('enrollments')
            ->whereIn('status', ['approved', 'validated'])
            ->where('is_active', true)
            ->when($institutionId, function ($query) use ($institutionId) {
                $query->with([
                    'institutionAccesses' => fn ($subQuery) => $subQuery->where('institution_id', $institutionId),
                ]);
            })
            ->when(!empty($this->categoryId), function ($query) {
                $query->where('category_id', $this->categoryId);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('modules', function ($moduleQuery) use ($search) {
                            $moduleQuery->where('title', 'like', '%' . $search . '%')
                                ->orWhereHas('lessons', function ($lessonQuery) use ($search) {
                                    $lessonQuery->where('title', 'like', '%' . $search . '%');
                                });
                        });
                });
            })
            ->latest()
            ->get();

        if ($institutionId && $user) {
            $courses = $courses->filter(function (Course $course) {
                if (! $course->is_paid || (float) $course->price <= 0) {
                    return true;
                }

                $institutionAccess = $course->institutionAccesses->first();

                return $institutionAccess && (bool) $institutionAccess->is_enabled;
            })->values();
        }

        $this->courses = $courses;
    }

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
        $this->loadCourses();
    }

    public function updatedSearch()
    {
        $this->loadCourses();
    }

    public function updatedCategoryId()
    {
        $this->loadCourses();
    }

    public function applyFilters()
    {
        $this->loadCourses();
    }

    public function render()
    {
        return view('components.courses.catalogue');
    }
}
