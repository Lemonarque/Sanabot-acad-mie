<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Models\Quiz;
use App\Models\Question;

class QuestionsManage extends Component
{
    public $quiz;
    public $questions;
    public $content = '';
    public $editingId = null;

    public function mount($quizId)
    {
        $this->quiz = Quiz::findOrFail($quizId);
        $this->questions = $this->quiz->questions()->latest()->get();
    }

    public function save()
    {
        $this->validate([
            'content' => 'required',
        ]);

        if ($this->editingId) {
            // Mode édition
            $question = Question::findOrFail($this->editingId);
            $question->update([
                'content' => $this->content,
            ]);
        } else {
            // Mode création
            $this->quiz->questions()->create([
                'content' => $this->content,
            ]);
        }

        $this->reset(['content', 'editingId']);
        $this->mount($this->quiz->id);
    }

    public function create()
    {
        $this->validate([
            'content' => 'required',
        ]);
        $this->quiz->questions()->create([
            'content' => $this->content,
        ]);
        $this->reset(['content']);
        $this->mount($this->quiz->id);
    }

    public function edit($id)
    {
        $question = Question::findOrFail($id);
        $this->editingId = $id;
        $this->content = $question->content;
    }

    public function cancelEdit()
    {
        $this->reset(['content', 'editingId']);
    }

    public function update()
    {
        $this->validate([
            'content' => 'required',
        ]);
        $question = Question::findOrFail($this->editingId);
        $question->update([
            'content' => $this->content,
        ]);
        $this->reset(['content', 'editingId']);
        $this->mount($this->quiz->id);
    }

    public function delete($id)
    {
        Question::findOrFail($id)->delete();
        $this->mount($this->quiz->id);
    }

    public function render()
    {
        return view('components.quiz.questions-manage');
    }
}
