<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Models\Quiz;
use App\Models\Module;

class Manage extends Component
{
    public $module;
    public $quiz;
    public $title = '';
    public $editing = false;

    public function mount($moduleId)
    {
        $this->module = Module::findOrFail($moduleId);
        $this->quiz = $this->module->quizzes()->latest()->first();
        if ($this->quiz) {
            $this->title = $this->quiz->title;
            $this->editing = true;
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required',
        ]);
        if ($this->quiz) {
            $this->quiz->update(['title' => $this->title]);
        } else {
            $this->quiz = $this->module->quizzes()->create(['title' => $this->title]);
        }
        $this->editing = true;
    }

    public function deleteQuiz()
    {
        if ($this->quiz) {
            $this->quiz->delete();
            $this->quiz = null;
            $this->title = '';
            $this->editing = false;
        }
    }

    public function render()
    {
        return view('components.quiz.manage');
    }
}
