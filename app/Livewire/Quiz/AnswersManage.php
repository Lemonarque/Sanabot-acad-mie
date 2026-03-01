<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Models\Question;
use App\Models\Answer;

class AnswersManage extends Component
{
    public $question;
    public $answers;
    public $content = '';
    public $is_correct = false;
    public $editingId = null;

    public function mount($questionId)
    {
        $this->question = Question::findOrFail($questionId);
        $this->answers = $this->question->answers()->latest()->get();
    }

    public function save()
    {
        $this->validate([
            'content' => 'required',
        ]);

        if ($this->editingId) {
            // Mode édition
            $answer = Answer::findOrFail($this->editingId);
            $answer->update([
                'content' => $this->content,
                'is_correct' => $this->is_correct,
            ]);
        } else {
            // Mode création
            $this->question->answers()->create([
                'content' => $this->content,
                'is_correct' => $this->is_correct,
            ]);
        }

        $this->reset(['content', 'is_correct', 'editingId']);
        $this->mount($this->question->id);
    }

    public function create()
    {
        $this->validate([
            'content' => 'required',
        ]);
        $this->question->answers()->create([
            'content' => $this->content,
            'is_correct' => $this->is_correct,
        ]);
        $this->reset(['content', 'is_correct']);
        $this->mount($this->question->id);
    }

    public function edit($id)
    {
        $answer = Answer::findOrFail($id);
        $this->editingId = $id;
        $this->content = $answer->content;
        $this->is_correct = $answer->is_correct;
    }

    public function cancelEdit()
    {
        $this->reset(['content', 'is_correct', 'editingId']);
    }

    public function update()
    {
        $this->validate([
            'content' => 'required',
        ]);
        $answer = Answer::findOrFail($this->editingId);
        $answer->update([
            'content' => $this->content,
            'is_correct' => $this->is_correct,
        ]);
        $this->reset(['content', 'is_correct', 'editingId']);
        $this->mount($this->question->id);
    }

    public function delete($id)
    {
        Answer::findOrFail($id)->delete();
        $this->mount($this->question->id);
    }

    public function render()
    {
        return view('components.quiz.answers-manage');
    }
}
