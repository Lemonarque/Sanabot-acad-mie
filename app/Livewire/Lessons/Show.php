<?php

namespace App\Livewire\Lessons;

use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\FinalQuizResult;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Payment;
use App\Models\Progress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public $lesson;
    public $course;
    public $module;
    public $canAccess = false;
    public $paymentRequired = false;
    public $paymentMessage = '';
    public $courseProgress = 0;
    public $lessonValidated = false;
    public $lessonValidationMap = [];
    public $enrollmentId = null;
    public $certificateAvailable = false;
    public $certificatePdfPath = null;
    public $styledLessonContent = '';

    public function mount($id)
    {
        $this->lesson = Lesson::with(['resources', 'module.course.modules.lessons', 'module.course.modules.quizzes', 'module.course.finalQuizzes'])
            ->findOrFail($id);
        $this->course = $this->lesson->module->course;
        $this->module = $this->lesson->module;

        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        $enrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $this->course->id)
            ->first();
        if (! $enrolled) {
            session()->flash('course_notice', 'Veuillez vous inscrire s\'il vous plaît.');
            $this->redirectRoute('apprenant.courses.show', ['id' => $this->course->id], navigate: true);
            return;
        }

        $this->enrollmentId = $enrolled->id;

        $this->paymentRequired = $this->module->is_paid;
        $this->canAccess = $this->module->isPaidByUser($user);

        foreach ($this->course->modules as $courseModule) {
            Progress::firstOrCreate([
                'enrollment_id' => $this->enrollmentId,
                'module_id' => $courseModule->id,
            ], [
                'validated' => false,
                'score' => null,
            ]);

            foreach ($courseModule->lessons as $courseLesson) {
                LessonProgress::firstOrCreate([
                    'enrollment_id' => $this->enrollmentId,
                    'lesson_id' => $courseLesson->id,
                ], [
                    'validated' => false,
                ]);
            }
        }

    $this->refreshProgressState();
    }

    public function payModule()
    {
        $user = Auth::user();
        if (! $user) {
            $this->paymentMessage = 'Connexion requise.';
            return;
        }

        if (! $this->module->is_paid) {
            $this->canAccess = true;
            return;
        }

        if ($this->module->isPaidByUser($user)) {
            $this->canAccess = true;
            return;
        }

        Payment::create([
            'user_id' => $user->id,
            'module_id' => $this->module->id,
            'amount' => $this->module->price ?? 0,
            'currency' => 'XOF',
            'status' => 'paid',
            'provider' => 'manual',
            'reference' => 'MVP-' . now()->format('YmdHis') . '-' . $user->id,
            'paid_at' => now(),
        ]);

        $this->canAccess = true;
        $this->paymentMessage = 'Paiement validé. Section débloquée.';
    }

    public function validateCurrentLesson()
    {
        if (! $this->canAccess || ! $this->enrollmentId) {
            $this->paymentMessage = 'Accès requis pour valider ce chapitre.';
            return;
        }

        LessonProgress::updateOrCreate([
            'enrollment_id' => $this->enrollmentId,
            'lesson_id' => $this->lesson->id,
        ], [
            'validated' => true,
        ]);

        if ($this->module->quizzes->isEmpty()) {
            $moduleLessonIds = $this->module->lessons->pluck('id')->all();
            $validatedLessonsInModule = LessonProgress::where('enrollment_id', $this->enrollmentId)
                ->whereIn('lesson_id', $moduleLessonIds)
                ->where('validated', true)
                ->count();

            if ($validatedLessonsInModule >= count($moduleLessonIds)) {
                Progress::updateOrCreate([
                    'enrollment_id' => $this->enrollmentId,
                    'module_id' => $this->module->id,
                ], [
                    'validated' => true,
                    'score' => null,
                ]);
            }
        }

        $finalQuizIds = $this->course->finalQuizzes->pluck('id')->all();

        if (! empty($finalQuizIds)) {
            $passedFinalQuiz = FinalQuizResult::where('enrollment_id', $this->enrollmentId)
                ->whereIn('quiz_id', $finalQuizIds)
                ->where('passed', true)
                ->exists();

            if ($passedFinalQuiz) {
                Certificate::firstOrCreate([
                    'enrollment_id' => $this->enrollmentId,
                ], [
                    'issued_at' => now(),
                ]);
            }
        } else {
            $validatedCount = Progress::where('enrollment_id', $this->enrollmentId)
                ->where('validated', true)
                ->count();
            $totalModules = $this->course->modules()->count();

            if ($totalModules > 0 && $validatedCount >= $totalModules) {
                Certificate::firstOrCreate([
                    'enrollment_id' => $this->enrollmentId,
                ], [
                    'issued_at' => now(),
                ]);
            }
        }

        $this->paymentMessage = 'Chapitre validé avec succès.';
        $this->refreshProgressState();
    }

    public function saveStyledContent($content): void
    {
        if (! $this->canAccess || ! $this->enrollmentId || ! is_string($content)) {
            return;
        }

        $normalizedContent = trim($content);
        if ($normalizedContent === '') {
            $normalizedContent = (string) ($this->lesson->content ?? '');
        }

        if (mb_strlen($normalizedContent) > 200000) {
            $normalizedContent = mb_substr($normalizedContent, 0, 200000);
        }

        LessonProgress::updateOrCreate([
            'enrollment_id' => $this->enrollmentId,
            'lesson_id' => $this->lesson->id,
        ], [
            'styled_content' => $normalizedContent,
        ]);

        $this->styledLessonContent = $normalizedContent;
    }

    private function refreshProgressState(): void
    {
        if (! $this->enrollmentId) {
            $this->courseProgress = 0;
            $this->lessonValidated = false;
            $this->lessonValidationMap = [];
            $this->certificateAvailable = false;
            $this->certificatePdfPath = null;
            $this->styledLessonContent = (string) ($this->lesson->content ?? '');
            return;
        }

        $lessonProgressRows = LessonProgress::where('enrollment_id', $this->enrollmentId)->get();
        $allLessons = $this->course->modules->flatMap(fn ($courseModule) => $courseModule->lessons);
        $modulesWithQuiz = $this->course->modules->filter(fn ($courseModule) => $courseModule->quizzes->isNotEmpty());
        $totalLessons = $allLessons->count();
        $totalSectionQuizzes = $modulesWithQuiz->sum(fn ($courseModule) => $courseModule->quizzes->count());
        $totalFinalQuizzes = $this->course->finalQuizzes->count();
        $totalTrackItems = max(1, $totalLessons + $totalSectionQuizzes + $totalFinalQuizzes);
        $validatedLessons = $lessonProgressRows->where('validated', true)->count();
        $validatedSectionQuizzes = Progress::where('enrollment_id', $this->enrollmentId)
            ->where('validated', true)
            ->whereIn('module_id', $modulesWithQuiz->pluck('id')->all())
            ->count();
        $validatedFinalQuizzes = FinalQuizResult::where('enrollment_id', $this->enrollmentId)
            ->whereIn('quiz_id', $this->course->finalQuizzes->pluck('id')->all())
            ->where('passed', true)
            ->count();

        $this->courseProgress = (int) round((($validatedLessons + $validatedSectionQuizzes + $validatedFinalQuizzes) / $totalTrackItems) * 100);

        $currentLessonProgress = $lessonProgressRows->firstWhere('lesson_id', $this->lesson->id);
        $this->lessonValidated = (bool) ($currentLessonProgress?->validated ?? false);
        $this->styledLessonContent = (string) ($currentLessonProgress?->styled_content ?: $this->lesson->content ?? '');

        $this->lessonValidationMap = $lessonProgressRows
            ->mapWithKeys(fn ($row) => [$row->lesson_id => (bool) $row->validated])
            ->toArray();

        $certificate = Certificate::where('enrollment_id', $this->enrollmentId)->first();
        $this->certificateAvailable = (bool) $certificate;
        $this->certificatePdfPath = $certificate?->pdf_path;
    }

    public function render()
    {
        return view('components.lessons.show');
    }
}
