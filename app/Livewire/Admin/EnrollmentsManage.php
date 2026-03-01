<?php

namespace App\Livewire\Admin;

use App\Models\Enrollment;
use Livewire\Component;

class EnrollmentsManage extends Component
{
    public $enrollments;
    public $search = '';

    public function mount()
    {
        $this->loadEnrollments();
    }

    public function loadEnrollments()
    {
        $query = Enrollment::query()->with(['user', 'course'])->latest();

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })->orWhereHas('course', function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%');
            });
        }

        $this->enrollments = $query->get();
    }

    public function searchEnrollments()
    {
        $this->loadEnrollments();
    }

    public function delete($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->progress()->delete();
        if ($enrollment->certificate) {
            $enrollment->certificate()->delete();
        }
        $enrollment->delete();
        $this->loadEnrollments();
    }

    public function render()
    {
        return view('components.admin.enrollments-manage');
    }
}
