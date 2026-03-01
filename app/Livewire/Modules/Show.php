<?php

namespace App\Livewire\Modules;

use App\Models\Enrollment;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public $module;
    public $canAccess = false;

    public function mount($id)
    {
        $this->module = Module::with(['lessons', 'quizzes', 'course.finalQuizzes'])->findOrFail($id);
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }
        $enrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $this->module->course_id)
            ->exists();
        if (! $enrolled) {
            abort(403);
        }

        $this->canAccess = $this->module->isPaidByUser($user);
    }

    public function render()
    {
        return view('components.modules.show');
    }
}
