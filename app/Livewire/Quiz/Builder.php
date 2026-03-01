<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class Builder extends Component
{
    public $module;
    public $course;
    public $quiz;
    public $isCourseEvaluation = false;
    
    // Quiz properties
    public $quizTitle = '';
    public $minScore = 0;
    public $maxAttempts = 3;
    
    // Questions array
    public $questions = [];
    public $newQuestion = '';
    
    // Current editing question
    public $editingQuestionId = null;
    
    // New answer for a question
    public $newAnswerText = '';
    public $newAnswerCorrect = false;

    public function mount($moduleId = null, $courseId = null)
    {
        if ($courseId !== null) {
            $this->isCourseEvaluation = true;
            $this->course = Course::findOrFail($courseId);

            if ((int) $this->course->creator_id !== (int) Auth::id()) {
                abort(403);
            }

            $this->quiz = Quiz::where('course_id', $this->course->id)
                ->whereNull('module_id')
                ->latest()
                ->first();
        } else {
            $this->module = Module::with('course')->findOrFail($moduleId);

            if ((int) $this->module->course->creator_id !== (int) Auth::id()) {
                abort(403);
            }

            $this->course = $this->module->course;
            $this->quiz = $this->module->quizzes()->latest()->first();
        }
        
        if ($this->quiz) {
            $this->quizTitle = $this->quiz->title;
            $this->minScore = $this->quiz->min_score;
            $this->maxAttempts = $this->quiz->max_attempts;
            
            // Load questions with answers
            $this->loadQuestions();
        }
    }

    private function loadQuestions()
    {
        $this->questions = $this->quiz->questions()
            ->with('answers')
            ->get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'content' => $question->content,
                    'answers' => $question->answers->map(function ($answer) {
                        return [
                            'id' => $answer->id,
                            'content' => $answer->content,
                            'is_correct' => (bool) $answer->is_correct,
                        ];
                    })->toArray(),
                ];
            })
            ->toArray();
    }

    public function saveQuiz()
    {
        $this->validate([
            'quizTitle' => 'required|string',
            'minScore' => 'required|integer|min:0|max:100',
            'maxAttempts' => 'required|integer|min:1',
        ]);

        if ($this->quiz) {
            $this->quiz->update([
                'title' => $this->quizTitle,
                'min_score' => $this->minScore,
                'max_attempts' => $this->maxAttempts,
            ]);
        } else {
            $payload = [
                'title' => $this->quizTitle,
                'min_score' => $this->minScore,
                'max_attempts' => $this->maxAttempts,
                'course_id' => $this->course?->id,
            ];

            if ($this->isCourseEvaluation) {
                $payload['module_id'] = null;
                $this->quiz = Quiz::create($payload);
            } else {
                $this->quiz = $this->module->quizzes()->create($payload);
            }
        }

        session()->flash('success', 'Quiz sauvegardé avec succès');
    }

    public function deleteQuiz()
    {
        if ($this->quiz) {
            $this->quiz->delete();
            $this->quiz = null;
            $this->quizTitle = '';
            $this->minScore = 0;
            $this->maxAttempts = 3;
            $this->questions = [];
            session()->flash('success', 'Quiz supprimé');
        }
    }

    public function addQuestion()
    {
        $this->validate([
            'newQuestion' => 'required|string|min:5',
        ]);

        if (!$this->quiz) {
            session()->flash('error', 'Veuillez d\'abord créer le quiz');
            return;
        }

        $question = $this->quiz->questions()->create([
            'content' => $this->newQuestion,
        ]);

        $this->questions[] = [
            'id' => $question->id,
            'content' => $question->content,
            'answers' => [],
        ];

        $this->newQuestion = '';
        session()->flash('success', 'Question ajoutée');
    }

    public function updateQuestion($questionIndex)
    {
        $this->validate([
            'questions.' . $questionIndex . '.content' => 'required|string',
        ]);

        $questionData = $this->questions[$questionIndex];
        Question::findOrFail($questionData['id'])->update([
            'content' => $questionData['content'],
        ]);

        session()->flash('success', 'Question mise à jour');
    }

    public function deleteQuestion($questionIndex)
    {
        $questionId = $this->questions[$questionIndex]['id'];
        Question::findOrFail($questionId)->delete();
        array_splice($this->questions, $questionIndex, 1);
        $this->editingQuestionId = null;
        session()->flash('success', 'Question supprimée');
    }

    public function setEditingQuestion($questionIndex)
    {
        $this->editingQuestionId = $questionIndex;
        $this->newAnswerText = '';
        $this->newAnswerCorrect = false;
    }

    public function clearEditingQuestion()
    {
        $this->editingQuestionId = null;
        $this->newAnswerText = '';
        $this->newAnswerCorrect = false;
    }

    public function addAnswer($questionIndex)
    {
        $this->validate([
            'newAnswerText' => 'required|string|min:2',
        ]);

        $questionId = $this->questions[$questionIndex]['id'];
        
        // Vérifier qu'il y a au moins une bonne réponse dans la question
        $hasCorrectAnswer = collect($this->questions[$questionIndex]['answers'])
            ->some(fn($answer) => $answer['is_correct']);
        
        if (!$this->newAnswerCorrect && !$hasCorrectAnswer) {
            session()->flash('warning', 'Vérifiez que votre question a au moins une bonne réponse');
        }

        $answer = Answer::create([
            'question_id' => $questionId,
            'content' => $this->newAnswerText,
            'is_correct' => $this->newAnswerCorrect,
        ]);

        $this->questions[$questionIndex]['answers'][] = [
            'id' => $answer->id,
            'content' => $answer->content,
            'is_correct' => (bool) $answer->is_correct,
        ];

        $this->newAnswerText = '';
        $this->newAnswerCorrect = false;
        session()->flash('success', 'Réponse ajoutée');
    }

    public function updateAnswer($questionIndex, $answerIndex)
    {
        $answerData = $this->questions[$questionIndex]['answers'][$answerIndex];
        Answer::findOrFail($answerData['id'])->update([
            'content' => $answerData['content'],
            'is_correct' => $answerData['is_correct'],
        ]);

        session()->flash('success', 'Réponse mise à jour');
    }

    public function deleteAnswer($questionIndex, $answerIndex)
    {
        $answerId = $this->questions[$questionIndex]['answers'][$answerIndex]['id'];
        Answer::findOrFail($answerId)->delete();
        array_splice($this->questions[$questionIndex]['answers'], $answerIndex, 1);
        session()->flash('success', 'Réponse supprimée');
    }

    public function toggleCorrectAnswer($questionIndex, $answerIndex)
    {
        $this->questions[$questionIndex]['answers'][$answerIndex]['is_correct'] = 
            !$this->questions[$questionIndex]['answers'][$answerIndex]['is_correct'];
    }

    public function render()
    {
        return view('components.quiz.builder');
    }
}
