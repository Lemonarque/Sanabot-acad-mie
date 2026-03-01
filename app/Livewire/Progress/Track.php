<?php

namespace App\Livewire\Progress;

use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Track extends Component
{
    public $enrollments;
    public $summary = [
        'total_courses' => 0,
        'in_progress_courses' => 0,
        'completed_courses' => 0,
        'not_started_courses' => 0,
        'average_progress' => 0,
        'certificates_count' => 0,
    ];
    public $courseCards = [];

    public function mount()
    {
        $user = Auth::user();

        $this->enrollments = Enrollment::with([
            'course.modules.lessons',
            'course.modules.quizzes',
            'course.finalQuizzes',
            'progress',
            'lessonProgress',
            'finalQuizResults',
            'certificate',
        ])
            ->where('user_id', $user->id)
            ->get();

        $this->buildProgressData();
    }

    private function buildProgressData(): void
    {
        $cards = [];

        foreach ($this->enrollments as $enrollment) {
            $course = $enrollment->course;

            if (! $course) {
                continue;
            }

            $orderedModules = $course->modules->sortBy('order')->values();
            $lessonIds = $orderedModules
                ->flatMap(fn ($module) => $module->lessons->pluck('id'))
                ->values();
            $finalQuizIds = $course->finalQuizzes->pluck('id')->values();

            $totalModules = $orderedModules->count();
            $validatedModules = $enrollment->progress
                ->whereIn('module_id', $orderedModules->pluck('id')->all())
                ->where('validated', true)
                ->count();

            $totalLessons = $lessonIds->count();
            $validatedLessons = $enrollment->lessonProgress
                ->whereIn('lesson_id', $lessonIds->all())
                ->where('validated', true)
                ->count();

            $totalFinalQuizzes = $finalQuizIds->count();
            $passedFinalQuizzes = $enrollment->finalQuizResults
                ->whereIn('quiz_id', $finalQuizIds->all())
                ->where('passed', true)
                ->count();

            $totalTrackItems = max(1, $totalModules + $totalLessons + $totalFinalQuizzes);
            $validatedTrackItems = $validatedModules + $validatedLessons + $passedFinalQuizzes;
            $progressPercent = (int) round(($validatedTrackItems / $totalTrackItems) * 100);

            $nextLesson = null;
            foreach ($orderedModules as $module) {
                $orderedLessons = $module->lessons->sortBy('order')->values();
                foreach ($orderedLessons as $lesson) {
                    $isValidated = $enrollment->lessonProgress
                        ->where('lesson_id', $lesson->id)
                        ->where('validated', true)
                        ->isNotEmpty();

                    if (! $isValidated) {
                        $nextLesson = [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'module_title' => $module->title,
                        ];
                        break 2;
                    }
                }
            }

            $cards[] = [
                'course_id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'presentation_image_url' => $course->presentation_image_url,
                'progress_percent' => $progressPercent,
                'total_modules' => $totalModules,
                'validated_modules' => $validatedModules,
                'total_lessons' => $totalLessons,
                'validated_lessons' => $validatedLessons,
                'total_final_quizzes' => $totalFinalQuizzes,
                'passed_final_quizzes' => $passedFinalQuizzes,
                'certificate_available' => (bool) $enrollment->certificate,
                'certificate_pdf_path' => $enrollment->certificate?->pdf_path,
                'next_lesson' => $nextLesson,
            ];
        }

        $this->courseCards = $cards;

        $totalCourses = count($cards);
        $completedCourses = collect($cards)->where('progress_percent', 100)->count();
        $notStartedCourses = collect($cards)->where('progress_percent', 0)->count();
        $inProgressCourses = max(0, $totalCourses - $completedCourses - $notStartedCourses);
        $averageProgress = $totalCourses > 0
            ? (int) round(collect($cards)->avg('progress_percent'))
            : 0;
        $certificatesCount = collect($cards)->where('certificate_available', true)->count();

        $this->summary = [
            'total_courses' => $totalCourses,
            'in_progress_courses' => $inProgressCourses,
            'completed_courses' => $completedCourses,
            'not_started_courses' => $notStartedCourses,
            'average_progress' => $averageProgress,
            'certificates_count' => $certificatesCount,
        ];
    }

    public function render()
    {
        return view('components.progress.⚡track');
    }
}
