<?php

namespace App\Livewire\Resources;

use Livewire\Component;
use App\Models\Resource;
use App\Models\Lesson;

class Manage extends Component
{
    public $lesson;
    public $resources;
    public $title = '';
    public $url = '';
    public $type = '';
    public $editingId = null;

    public function mount($lessonId)
    {
        $this->lesson = Lesson::findOrFail($lessonId);
        $this->resources = $this->lesson->resources()->latest()->get();
    }

    public function save()
    {
        $this->validate([
            'title' => 'required',
            'url' => 'required|url',
            'type' => 'required',
        ]);

        if ($this->editingId) {
            // Mode édition
            $resource = Resource::findOrFail($this->editingId);
            $resource->update([
                'title' => $this->title,
                'url' => $this->url,
                'type' => $this->type,
            ]);
        } else {
            // Mode création
            $this->lesson->resources()->create([
                'title' => $this->title,
                'url' => $this->url,
                'type' => $this->type,
            ]);
        }

        $this->reset(['title', 'url', 'type', 'editingId']);
        $this->mount($this->lesson->id);
    }

    public function create()
    {
        $this->validate([
            'title' => 'required',
            'url' => 'required|url',
            'type' => 'required',
        ]);
        $this->lesson->resources()->create([
            'title' => $this->title,
            'url' => $this->url,
            'type' => $this->type,
        ]);
        $this->reset(['title', 'url', 'type']);
        $this->mount($this->lesson->id);
    }

    public function edit($id)
    {
        $resource = Resource::findOrFail($id);
        $this->editingId = $id;
        $this->title = $resource->title;
        $this->url = $resource->url;
        $this->type = $resource->type;
    }

    public function cancelEdit()
    {
        $this->reset(['title', 'url', 'type', 'editingId']);
    }

    public function update()
    {
        $this->validate([
            'title' => 'required',
            'url' => 'required|url',
            'type' => 'required',
        ]);
        $resource = Resource::findOrFail($this->editingId);
        $resource->update([
            'title' => $this->title,
            'url' => $this->url,
            'type' => $this->type,
        ]);
        $this->reset(['title', 'url', 'type', 'editingId']);
        $this->mount($this->lesson->id);
    }

    public function delete($id)
    {
        Resource::findOrFail($id)->delete();
        $this->mount($this->lesson->id);
    }

    public function render()
    {
        return view('components.resources.manage');
    }
}
