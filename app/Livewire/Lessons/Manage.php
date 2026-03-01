<?php

namespace App\Livewire\Lessons;

use App\Models\Lesson;
use App\Models\Module;
use Livewire\Component;

class Manage extends Component
{
    public $module;
    public $lessons;

    public function mount($moduleId)
    {
        $this->module = Module::findOrFail($moduleId);
        $this->lessons = $this->module->lessons()->with(['resources'])->latest()->get();
    }

    public function delete($id)
    {
        Lesson::findOrFail($id)->delete();
        $this->mount($this->module->id);
    }

    public function render()
    {
        return view('components.lessons.manage');
    }
}
