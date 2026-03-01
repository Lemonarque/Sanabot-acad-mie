<?php

namespace App\Livewire\Quiz;

use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\FinalQuizResult;
use App\Models\LessonProgress;
use App\Models\Progress;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TakeQuiz extends Component
{
    public $quiz;
    public $course;
    public $module;
    public $answers = [];
    public $score = null;
    public $scorePercent = null;
    public $submitted = false;
    public $message = '';
    public $courseProgress = 0;
    public $lessonValidationMap = [];
    public $isCourseFinalQuiz = false;

    public function mount($id, $moduleId = null, $courseId = null)
    {
        $this->quiz = Quiz::with([
            'questions.answers',
            'module.course.modules.lessons',
            'module.course.modules.quizzes',
            'course.modules.lessons',
            'course.modules.quizzes',
            'course.finalQuizzes',
        ])->findOrFail($id);

        $this->isCourseFinalQuiz = $this->quiz->module_id === null;
        $this->module = $this->quiz->module;
        $this->course = $this->quiz->course ?? $this->module?->course;

        if (! $this->course) {
            abort(404);
        }

        if (! $this->isCourseFinalQuiz && $moduleId !== null && (int) $moduleId !== (int) $this->module->id) {
            abort(404);
        }

        if ($this->isCourseFinalQuiz && $courseId !== null && (int) $courseId !== (int) $this->course->id) {
            abort(404);
        }

        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $this->course->id)
            ->first();
        if (! $enrollment) {
            abort(403);
        }

        foreach ($this->course->modules as $courseModule) {
            foreach ($courseModule->lessons as $courseLesson) {
                LessonProgress::firstOrCreate([
                    'enrollment_id' => $enrollment->id,
                    'lesson_id' => $courseLesson->id,
                ], [
                    'validated' => false,
                ]);
            }
        }

        $this->refreshCourseTracking($enrollment->id);
    }

    public function submit()
    {
        $score = 0;
        $questionCount = max(1, $this->quiz->questions->count());
        foreach ($this->quiz->questions as $question) {
            $correct = $question->answers->where('is_correct', true)->pluck('id')->map(fn ($id) => (int) $id)->toArray();
            $selectedMap = isset($this->answers[$question->id]) ? (array) $this->answers[$question->id] : [];
            $user = collect($selectedMap)
                ->filter(fn ($checked) => (bool) $checked)
                ->keys()
                ->map(fn ($id) => (int) $id)
                ->values()
                ->toArray();
            sort($correct);
            sort($user);
            if ($correct === $user) {
                $score++;
            }
        }
        $achievedPercent = (int) round(($score / $questionCount) * 100);
        $this->score = $score;
        $this->scorePercent = $achievedPercent;
        $this->submitted = true;
        $this->message = '';

        $user = Auth::user();
        if ($user) {
            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $this->course->id)
                ->first();

            if ($enrollment && $achievedPercent >= $this->quiz->min_score) {
                if ($this->isCourseFinalQuiz) {
                    FinalQuizResult::updateOrCreate([
                        'enrollment_id' => $enrollment->id,
                        'quiz_id' => $this->quiz->id,
                    ], [
                        'score_percent' => $achievedPercent,
                        'passed' => true,
                    ]);

                    $this->issueCertificateIfEligible($enrollment->id);
                    $this->message = 'Évaluation finale validée ! Cours validé, certificat disponible.';
                } else {
                    Progress::updateOrCreate([
                        'enrollment_id' => $enrollment->id,
                        'module_id' => $this->quiz->module->id,
                    ], [
                        'validated' => true,
                        'score' => $achievedPercent,
                    ]);

                    $this->issueCertificateIfEligible($enrollment->id);
                    $this->message = 'Section validée !';
                }
            } elseif ($enrollment) {
                if ($this->isCourseFinalQuiz) {
                    FinalQuizResult::updateOrCreate([
                        'enrollment_id' => $enrollment->id,
                        'quiz_id' => $this->quiz->id,
                    ], [
                        'score_percent' => $achievedPercent,
                        'passed' => false,
                    ]);
                }
                $this->message = 'Quiz non validé. Reessayez pour atteindre le seuil minimal.';
            }

            if ($enrollment) {
                $this->refreshCourseTracking($enrollment->id);
            }
        }
    }

    private function issueCertificateIfEligible(int $enrollmentId): void
    {
        $finalQuizIds = $this->course->finalQuizzes()->pluck('id')->all();

        if (! empty($finalQuizIds)) {
            $passedFinalQuiz = FinalQuizResult::where('enrollment_id', $enrollmentId)
                ->whereIn('quiz_id', $finalQuizIds)
                ->where('passed', true)
                ->exists();

            if (! $passedFinalQuiz) {
                return;
            }

            Certificate::firstOrCreate([
                'enrollment_id' => $enrollmentId,
            ], [
                'issued_at' => now(),
            ]);

            return;
        }

        $totalModules = $this->course->modules()->count();
        $validatedModules = Progress::where('enrollment_id', $enrollmentId)
            ->where('validated', true)
            ->count();

        if ($totalModules <= 0 || $validatedModules < $totalModules) {
            return;
        }

        Certificate::firstOrCreate([
            'enrollment_id' => $enrollmentId,
        ], [
            'issued_at' => now(),
        ]);
    }

    private function refreshCourseTracking(int $enrollmentId): void
    {
        $lessonProgressRows = LessonProgress::where('enrollment_id', $enrollmentId)->get();
        $allLessons = $this->course->modules->flatMap(fn ($courseModule) => $courseModule->lessons);
        $modulesWithQuiz = $this->course->modules->filter(fn ($courseModule) => $courseModule->quizzes->isNotEmpty());
        $totalLessons = $allLessons->count();
        $totalSectionQuizzes = $modulesWithQuiz->sum(fn ($courseModule) => $courseModule->quizzes->count());
        $totalFinalQuizzes = $this->course->finalQuizzes->count();
        $totalTrackItems = max(1, $totalLessons + $totalSectionQuizzes + $totalFinalQuizzes);

        $validatedLessons = $lessonProgressRows->where('validated', true)->count();
        $validatedSectionQuizzes = Progress::where('enrollment_id', $enrollmentId)
            ->where('validated', true)
            ->whereIn('module_id', $modulesWithQuiz->pluck('id')->all())
            ->count();
        $validatedFinalQuizzes = FinalQuizResult::where('enrollment_id', $enrollmentId)
            ->whereIn('quiz_id', $this->course->finalQuizzes->pluck('id')->all())
            ->where('passed', true)
            ->count();

        $this->courseProgress = (int) round((($validatedLessons + $validatedSectionQuizzes + $validatedFinalQuizzes) / $totalTrackItems) * 100);

        $this->lessonValidationMap = $lessonProgressRows
            ->mapWithKeys(fn ($row) => [$row->lesson_id => (bool) $row->validated])
            ->toArray();
    }

    public function render()
    {
        return view('components.quiz.take-quiz');
    }
}
