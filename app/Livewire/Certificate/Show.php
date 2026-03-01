<?php

namespace App\Livewire\Certificate;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\Certificate;

class Show extends Component
{
    public $certificates;

    public function mount()
    {
        $user = Auth::user();
        $this->certificates = Certificate::with('enrollment.course')
            ->whereHas('enrollment', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->get();
    }

    public function render()
    {
        return view('components.certificate.⚡show');
    }
}

