<?php

namespace App\Livewire\Courses;

use App\Models\Course;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\FinalQuizResult;
use App\Models\InstitutionCourseAccess;
use App\Models\LessonProgress;
use App\Models\Progress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public $course;
    public $enrolled = false;
    public $enrollMessage = '';
    public $courseProgress = 0;
    public $lessonValidationMap = [];
    public $hasFinalEvaluation = false;
    public $finalQuizPassed = false;
    public $courseValidated = false;
    public $certificateAvailable = false;
    public $certificate = null;

    public function mount($id)
    {
        $this->course = Course::with(['modules.lessons', 'modules.quizzes', 'finalQuizzes', 'creator'])->findOrFail($id);
        $this->enrollMessage = session('course_notice', '');
        if (! in_array($this->course->status, ['approved', 'validated'], true) || ! $this->course->is_active) {
            abort(404);
        }

        $this->hasFinalEvaluation = $this->course->finalQuizzes->isNotEmpty();

        $user = Auth::user();
        if ($user) {
            if ($user->institution_id && ! $this->canUserAccessCourse((int) $user->institution_id)) {
                abort(403);
            }

            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $this->course->id)
                ->first();

            $this->enrolled = (bool) $enrollment;

            if ($enrollment) {
                foreach ($this->course->modules as $module) {
                    Progress::firstOrCreate([
                        'enrollment_id' => $enrollment->id,
                        'module_id' => $module->id,
                    ], [
                        'validated' => false,
                        'score' => null,
                    ]);

                    foreach ($module->lessons as $courseLesson) {
                        LessonProgress::firstOrCreate([
                            'enrollment_id' => $enrollment->id,
                            'lesson_id' => $courseLesson->id,
                        ], [
                            'validated' => false,
                        ]);
                    }
                }

                $lessonProgressRows = LessonProgress::where('enrollment_id', $enrollment->id)->get();
                $allLessons = $this->course->modules->flatMap(fn ($module) => $module->lessons);
                $modulesWithQuiz = $this->course->modules->filter(fn ($module) => $module->quizzes->isNotEmpty());
                $totalLessons = $allLessons->count();
                $totalSectionQuizzes = $modulesWithQuiz->sum(fn ($module) => $module->quizzes->count());
                $totalFinalQuizzes = $this->course->finalQuizzes->count();
                $totalTrackItems = max(1, $totalLessons + $totalSectionQuizzes + $totalFinalQuizzes);
                $validatedLessons = $lessonProgressRows->where('validated', true)->count();
                $validatedSectionQuizzes = Progress::where('enrollment_id', $enrollment->id)
                    ->where('validated', true)
                    ->whereIn('module_id', $modulesWithQuiz->pluck('id')->all())
                    ->count();
                $validatedFinalQuizzes = FinalQuizResult::where('enrollment_id', $enrollment->id)
                    ->whereIn('quiz_id', $this->course->finalQuizzes->pluck('id')->all())
                    ->where('passed', true)
                    ->count();
                $this->courseProgress = (int) round((($validatedLessons + $validatedSectionQuizzes + $validatedFinalQuizzes) / $totalTrackItems) * 100);

                $this->finalQuizPassed = $validatedFinalQuizzes > 0;
                $this->certificate = Certificate::where('enrollment_id', $enrollment->id)->latest('id')->first();
                $this->certificateAvailable = (bool) $this->certificate;

                if ($this->hasFinalEvaluation) {
                    $this->courseValidated = $this->finalQuizPassed;
                } else {
                    $totalModules = $this->course->modules->count();
                    $this->courseValidated = $totalModules > 0 && $validatedSectionQuizzes >= $totalModules;
                }

                $this->lessonValidationMap = $lessonProgressRows
                    ->mapWithKeys(fn ($row) => [$row->lesson_id => (bool) $row->validated])
                    ->toArray();
            }

            if ($this->enrolled) {
                $firstLesson = $this->course->modules
                    ->sortBy('order')
                    ->flatMap(fn ($module) => $module->lessons->sortBy('order'))
                    ->first();

                if ($firstLesson) {
                    $this->redirectRoute('apprenant.lessons.show', ['id' => $firstLesson->id], navigate: true);

                    return;
                }
            }
        }
    }

    public function enroll()
    {
        $this->enrollMessage = '';
        $user = Auth::user();
        if (! $user) {
            $this->enrollMessage = 'Vous devez etre connecte pour vous inscrire.';
            return;
        }
        if (! $user->role || $user->role->name !== 'apprenant') {
            $this->enrollMessage = 'Acces reserve aux apprenants.';
            return;
        }

        if ($user->institution_id && ! $this->canUserAccessCourse((int) $user->institution_id)) {
            $this->enrollMessage = 'Ce cours n\'est pas autorisé pour votre institution.';
            return;
        }

        // Si le cours est payant, rediriger vers la page de paiement
        if ($this->course->is_paid && $this->course->price > 0) {
            return redirect()->route('payment.checkout', $this->course->id);
        }

        // Cours gratuit : inscription directe
        $enrollment = Enrollment::firstOrCreate([
            'user_id' => $user->id,
            'course_id' => $this->course->id,
        ]);

        foreach ($this->course->modules as $module) {
            Progress::firstOrCreate([
                'enrollment_id' => $enrollment->id,
                'module_id' => $module->id,
            ], [
                'validated' => false,
                'score' => null,
            ]);
        }

        $this->enrolled = true;
        $this->enrollMessage = 'Inscription reussie.';
    }

    private function canUserAccessCourse(int $institutionId): bool
    {
        if (! $this->course->is_paid || (float) $this->course->price <= 0) {
            return true;
        }

        $institutionAccess = InstitutionCourseAccess::where('institution_id', $institutionId)
            ->where('course_id', $this->course->id)
            ->first();

        return $institutionAccess && (bool) $institutionAccess->is_enabled;
    }

    public function render()
    {
        return view('components.courses.show');
    }
}
