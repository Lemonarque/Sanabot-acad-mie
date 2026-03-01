<?php

namespace App\Livewire\Dashboard;

use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserDashboard extends Component
{
    public $enrollments;

    public function mount()
    {
        $user = Auth::user();
        $this->enrollments = Enrollment::with(['course', 'progress', 'certificate'])
            ->where('user_id', $user->id)
            ->get();
    }

    public function render()
    {
        return view('components.dashboard.⚡user-dashboard');
    }
}
