<?php

namespace App\Livewire\Modules;

use Livewire\Component;
use App\Models\Module;
use App\Models\Course;

class Manage extends Component
{
    public $course;
    public $modules;
    public $title = '';
    public $description = '';
    public $editingId = null;

    public function mount($courseId)
    {
        $this->course = Course::findOrFail($courseId);
        $this->modules = $this->course->modules()->latest()->get();
    }

    public function save()
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($this->editingId) {
            // Mode édition
            $module = Module::findOrFail($this->editingId);
            $module->update([
                'title' => $this->title,
                'description' => $this->description,
            ]);
        } else {
            // Mode création
            $this->course->modules()->create([
                'title' => $this->title,
                'description' => $this->description,
            ]);
        }

        $this->reset(['title', 'description', 'editingId']);
        $this->mount($this->course->id);
    }

    public function create()
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $this->course->modules()->create([
            'title' => $this->title,
            'description' => $this->description,
        ]);
        $this->reset(['title', 'description']);
        $this->mount($this->course->id);
    }

    public function edit($id)
    {
        $module = Module::findOrFail($id);
        $this->editingId = $id;
        $this->title = $module->title;
        $this->description = $module->description;
    }

    public function cancelEdit()
    {
        $this->reset(['title', 'description', 'editingId']);
    }

    public function update()
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $module = Module::findOrFail($this->editingId);
        $module->update([
            'title' => $this->title,
            'description' => $this->description,
        ]);
        $this->reset(['title', 'description', 'editingId']);
        $this->mount($this->course->id);
    }

    public function delete($id)
    {
        Module::findOrFail($id)->delete();
        $this->mount($this->course->id);
    }

    public function render()
    {
        return view('components.modules.manage');
    }
}
