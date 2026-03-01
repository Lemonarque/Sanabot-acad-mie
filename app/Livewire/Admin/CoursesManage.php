<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Course;
use Livewire\Component;

class CoursesManage extends Component
{
    public $courses;
    public $categories;
    public $search = '';
    public $status = 'all';

    public $editingId = null;
    public $category_id = null;
    public $title = '';
    public $description = '';
    public $short_description = '';
    public $detailed_description = '';
    public $objectives = '';
    public $target_audience = '';
    public $level = '';
    public $language = '';
    public $total_duration_minutes = null;
    public $certification_enabled = true;
    public $min_average = 75;
    public $final_evaluation_mode = 'optional';
    public $manual_validation = false;
    public $payment_mode = 'module';
    public $courseStatus = 'pending';
    public $isPaid = false;
    public $coursePrice = 0;
    public $showOnHomeCarousel = false;
    public $homeCarouselOrder = null;

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
        $this->loadCourses();
    }

    public function loadCourses()
    {
        $query = Course::query()
            ->with(['creator', 'category'])
            ->withCount(['modules', 'enrollments']);

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        $this->courses = $query->latest()->get();
    }

    public function searchCourses()
    {
        $this->loadCourses();
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $this->editingId = $id;
        $this->category_id = $course->category_id;
        $this->title = $course->title;
        $this->description = $course->description;
        $this->short_description = $course->short_description;
        $this->detailed_description = $course->detailed_description;
        $this->objectives = $course->objectives;
        $this->target_audience = $course->target_audience;
        $this->level = $course->level;
        $this->language = $course->language;
        $this->total_duration_minutes = $course->total_duration_minutes;
        $this->certification_enabled = (bool) $course->certification_enabled;
        $this->min_average = $course->min_average;
        $this->final_evaluation_mode = $course->final_evaluation_mode;
        $this->manual_validation = (bool) $course->manual_validation;
        $this->payment_mode = $course->payment_mode;
        $this->courseStatus = $course->status;
        $this->isPaid = (bool) $course->is_paid;
        $this->coursePrice = $course->price ?? 0;
        $this->showOnHomeCarousel = (bool) $course->show_on_home_carousel;
        $this->homeCarouselOrder = $course->home_carousel_order;
    }

    public function update()
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
            'homeCarouselOrder' => 'nullable|integer|min:1|max:99',
        ]);

        $course = Course::findOrFail($this->editingId);
        $carouselOrder = $this->showOnHomeCarousel ? $this->homeCarouselOrder : null;

        $course->update([
            'category_id' => $this->category_id,
            'title' => $this->title,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'detailed_description' => $this->detailed_description,
            'objectives' => $this->objectives,
            'target_audience' => $this->target_audience,
            'level' => $this->level,
            'language' => $this->language,
            'total_duration_minutes' => $this->total_duration_minutes,
            'certification_enabled' => $this->certification_enabled,
            'min_average' => $this->min_average,
            'final_evaluation_mode' => $this->final_evaluation_mode,
            'manual_validation' => $this->manual_validation,
            'payment_mode' => $this->payment_mode,
            'status' => $this->courseStatus,
            'is_paid' => $this->isPaid,
            'price' => $this->isPaid ? $this->coursePrice : null,
            'show_on_home_carousel' => (bool) $this->showOnHomeCarousel,
            'home_carousel_order' => $carouselOrder,
        ]);

        $this->reset([
            'editingId',
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
            'courseStatus',
            'isPaid',
            'coursePrice',
            'showOnHomeCarousel',
            'homeCarouselOrder',
        ]);
        $this->loadCourses();
    }

    public function cancelEdit()
    {
        $this->reset([
            'editingId',
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
            'courseStatus',
            'showOnHomeCarousel',
            'homeCarouselOrder',
        ]);
    }

    public function toggleHomeCarousel($id)
    {
        $course = Course::findOrFail($id);

        if (! $course->show_on_home_carousel) {
            $nextOrder = (int) Course::where('show_on_home_carousel', true)->max('home_carousel_order');

            $course->update([
                'show_on_home_carousel' => true,
                'home_carousel_order' => $nextOrder + 1,
            ]);
        } else {
            $course->update([
                'show_on_home_carousel' => false,
                'home_carousel_order' => null,
            ]);
        }

        $this->loadCourses();
    }

    public function setStatus($id, $status)
    {
        $course = Course::findOrFail($id);
        $course->update(['status' => $status]);
        $this->loadCourses();
    }

    public function toggleActive($id)
    {
        $course = Course::findOrFail($id);
        $course->update(['is_active' => ! $course->is_active]);
        $this->loadCourses();
    }

    public function delete($id)
    {
        Course::findOrFail($id)->delete();
        $this->loadCourses();
    }

    public function render()
    {
        return view('components.admin.courses-manage');
    }
}
