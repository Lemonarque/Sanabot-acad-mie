<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Payment;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public $pendingCourses;
    public $usersCount;
    public $coursesCount;
    public $activeCoursesCount;
    public $categoriesCount;
    public $modulesCount;
    public $lessonsCount;
    public $enrollmentsCount;
    public $paymentsCount;
    public $revenueTotal;
    public $certificatesCount;
    public $issuedCertificatesCount;
    public $formateursCount;
    public $apprenantsCount;
    public $recentUsers;
    public $recentPayments;
    public $recentCertificates;
    public $recentCourses;
    public $topCourses;
    public $paidCoursesCount;
    public $freeCoursesCount;
    public $totalRevenueMonth;
    public $courseRevenue;

    public function mount()
    {
        $this->pendingCourses = Course::where('status', 'pending')->latest()->get();
        $this->usersCount = User::count();
        $this->coursesCount = Course::count();
        $this->activeCoursesCount = Course::where('is_active', true)->count();
        $this->categoriesCount = Category::count();
        $this->modulesCount = Module::count();
        $this->lessonsCount = Lesson::count();
        $this->enrollmentsCount = Enrollment::count();
        $this->paymentsCount = Payment::count();
        $this->revenueTotal = Payment::whereIn('status', ['paid', 'completed'])->sum('amount');
        $this->certificatesCount = Certificate::count();
        $this->issuedCertificatesCount = Certificate::whereNotNull('issued_at')->count();

        $this->formateursCount = User::whereHas('role', function ($q) {
            $q->where('name', 'formateur');
        })->count();
        $this->apprenantsCount = User::whereHas('role', function ($q) {
            $q->where('name', 'apprenant');
        })->count();

        $this->recentUsers = User::latest()->take(5)->get();
        $this->recentPayments = Payment::with(['user', 'course', 'module.course'])->latest()->take(5)->get();
        $this->recentCertificates = Certificate::with(['enrollment.user', 'enrollment.course'])->latest()->take(5)->get();
        $this->recentCourses = Course::with('creator')->latest()->take(5)->get();
        $this->topCourses = Course::withCount('enrollments')->orderByDesc('enrollments_count')->take(5)->get();
        
        // Pricing stats
        $this->paidCoursesCount = Course::where('is_paid', true)->count();
        $this->freeCoursesCount = Course::where('is_paid', false)->orWhereNull('is_paid')->count();
        $this->totalRevenueMonth = Payment::whereIn('status', ['paid', 'completed'])
            ->whereDate('created_at', '>=', now()->startOfMonth())
            ->sum('amount');
        $this->courseRevenue = Payment::whereIn('status', ['paid', 'completed'])
            ->with(['course', 'module.course'])
            ->latest()
            ->take(10)
            ->get();
    }

    public function validateCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->status = 'approved';
        $course->save();
        $this->mount();
    }

    public function rejectCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->status = 'rejected';
        $course->save();
        $this->mount();
    }

    public function render()
    {
        return view('components.admin.dashboard');
    }
}
