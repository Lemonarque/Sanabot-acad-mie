<?php

namespace App\Livewire\Courses;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Manage extends Component
{
    public $courses;
    public $categories;
    public $selectedCourseId = null;
    public $search = '';
    public $categoryId = '';

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
        $this->loadCourses();

        $this->selectedCourseId = $this->courses->first()?->id;
    }

    public function updatedSearch()
    {
        $this->loadCourses();
    }

    public function searchCourses()
    {
        $this->loadCourses();
    }

    public function updatedCategoryId()
    {
        $this->loadCourses();
    }

    private function loadCourses(): void
    {
        $query = Course::with(['modules', 'enrollments', 'category'])
            ->where('creator_id', Auth::id());

        if (! empty(trim($this->search))) {
            $searchTerm = trim($this->search);
            $query->where(function ($builder) use ($searchTerm) {
                $builder->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($searchTerm) {
                        $categoryQuery->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        if (! empty($this->categoryId)) {
            $query->where('category_id', $this->categoryId);
        }

        $this->courses = $query->latest()->get();
    }

    public function delete($id)
    {
        Course::findOrFail($id)->delete();
        $this->loadCourses();
        session()->flash('success', 'Cours supprimé');
    }

    public function render()
    {
        return view('components.courses.manage');
    }
}
