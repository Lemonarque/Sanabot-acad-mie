<?php

namespace App\Livewire\Courses;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Course;
use App\Models\Module;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Builder extends Component
{
    use WithFileUploads;

    private const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'svg', 'avif', 'heic', 'heif'];
    private const DEFAULT_PRESENTATION_IMAGE_PATH = 'course-images/default-no-image.svg';

    public $course;
    public $categories;
    
    // Course properties
    public $courseTitle = '';
    public $courseDescription = '';
    public $courseObjectives = '';
    public $courseCategory = null;
    public $courseLevel = 'beginner';
    public $language = 'fr';
    public $totalDurationMinutes = 0;
    public $targetAudience = '';
    public $isPaid = false;
    public $coursePrice = 0;
    public $courseImagePath = null;
    public $courseImageFile = null;
    
    // Modules
    public $modules = [];
    public $newModuleTitle = '';
    public $newModuleDescription = '';
    
    // Editing states
    public $editing = false;
    public $editingModuleId = null;
    public $editModuleTitle = '';
    public $editModuleDescription = '';

    public function mount($courseId = null)
    {
        $this->categories = Category::orderBy('name')->get();
        
        if ($courseId) {
            $this->course = Course::with('modules.lessons')->findOrFail($courseId);
            
            // Check ownership
            if ($this->course->creator_id !== Auth::id()) {
                abort(403);
            }
            
            $this->fillCourseFormFromModel();
            
            $this->loadModules();
        } else {
            // Mode création : formulaire ouvert par défaut
            $this->editing = true;
        }
    }

    private function loadModules()
    {
        $this->modules = $this->course->modules()
            ->with('lessons')
            ->orderBy('order')
            ->get()
            ->map(function ($module) {
                return [
                    'id' => $module->id,
                    'title' => $module->title,
                    'description' => $module->description,
                    'order' => $module->order,
                    'lessonsCount' => $module->lessons->count(),
                ];
            })
            ->toArray();
    }

    private function fillCourseFormFromModel(): void
    {
        if (! $this->course) {
            return;
        }

        $this->courseTitle = $this->course->title;
        $this->courseDescription = $this->course->description;
        $this->courseObjectives = $this->course->objectives;
        $this->courseCategory = $this->course->category_id;
        $this->courseLevel = $this->course->level ?? 'beginner';
        $this->language = $this->course->language ?? 'fr';
        $this->totalDurationMinutes = $this->course->total_duration_minutes ?? 0;
        $this->targetAudience = $this->course->target_audience ?? '';
        $this->isPaid = $this->course->is_paid ?? false;
        $this->coursePrice = $this->course->price ?? 0;
        $this->courseImagePath = $this->course->presentation_image;
    }

    public function startEditCourse(): void
    {
        $this->resetErrorBag();
        $this->editing = true;
    }

    public function cancelEditCourse(): void
    {
        $this->courseImageFile = null;
        $this->resetValidation();

        if ($this->course) {
            $this->course->refresh();
            $this->fillCourseFormFromModel();
            $this->editing = false;
            return;
        }

        $this->editing = true;
    }

    public function updatedCourseImageFile(): void
    {
        if (! $this->courseImageFile) {
            $this->resetValidation('courseImageFile');
            return;
        }

        $this->validateOnly('courseImageFile', $this->imageValidationRules());
    }

    public function updatedIsPaid($value): void
    {
        $this->isPaid = (bool) $value;

        if (! $this->isPaid) {
            $this->coursePrice = 0;
            $this->resetValidation('coursePrice');
        }
    }

    public function saveCourse()
    {
        $this->normalizeCourseFormData();

        $this->validate(
            $this->courseValidationRules(),
            $this->courseValidationMessages(),
            $this->courseValidationAttributes()
        );

        $legacyImagePath = $this->course?->presentation_image;
        if ($this->courseImageFile) {
            $legacyImagePath = $this->courseImageFile->store('course-images', 'public');
            $this->courseImagePath = $legacyImagePath;

            if (
                $this->course
                && $this->course->presentation_image
                && $this->course->presentation_image !== $legacyImagePath
            ) {
                Storage::disk('public')->delete($this->course->presentation_image);
            }
        }

        if (
            empty($legacyImagePath)
            && ! ($this->course && $this->course->getFirstMedia('course_presentation'))
        ) {
            $legacyImagePath = $this->ensureDefaultPresentationImagePath();
            $this->courseImagePath = $legacyImagePath;
        }

        $payload = [
            'title' => $this->courseTitle,
            'description' => $this->courseDescription,
            'objectives' => $this->courseObjectives,
            'category_id' => $this->courseCategory,
            'level' => $this->courseLevel,
            'language' => $this->language,
            'total_duration_minutes' => $this->totalDurationMinutes,
            'target_audience' => $this->targetAudience,
            'is_paid' => $this->isPaid,
            'price' => $this->isPaid ? $this->coursePrice : null,
            'presentation_image' => $legacyImagePath,
        ];

        if ($this->course) {
            $this->course->update($payload);

            $this->course->refresh();
            $this->fillCourseFormFromModel();
        } else {
            $this->course = Course::create(array_merge($payload, [
                'creator_id' => Auth::id(),
                'status' => 'pending',
            ]));

            $this->course->load('modules.lessons');
            $this->fillCourseFormFromModel();
            $this->loadModules();
        }

        if ($this->courseImageFile) {
            try {
                $this->course
                    ->addMediaFromDisk($legacyImagePath, 'public')
                    ->toMediaCollection('course_presentation');
            } catch (\Throwable $e) {
                report($e);
            }

            $this->course->refresh();
        }

        $this->courseImageFile = null;
        $this->resetValidation();
        $this->editing = false;
        session()->flash('success', 'Cours sauvegardé avec succès');
    }

    private function ensureDefaultPresentationImagePath(): ?string
    {
        if (Storage::disk('public')->exists(self::DEFAULT_PRESENTATION_IMAGE_PATH)) {
            return self::DEFAULT_PRESENTATION_IMAGE_PATH;
        }

        $sourcePath = public_path('images/presentation/default-no-image.svg');

        if (! file_exists($sourcePath)) {
            return null;
        }

        $sourceContent = file_get_contents($sourcePath);

        if ($sourceContent === false) {
            return null;
        }

        $directory = dirname(self::DEFAULT_PRESENTATION_IMAGE_PATH);

        if (! Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $saved = Storage::disk('public')->put(self::DEFAULT_PRESENTATION_IMAGE_PATH, $sourceContent);

        return $saved ? self::DEFAULT_PRESENTATION_IMAGE_PATH : null;
    }

    public function toggleEdit()
    {
        if ($this->editing) {
            $this->cancelEditCourse();
            return;
        }

        $this->startEditCourse();
    }

    private function imageValidationRules(): array
    {
        return [
            'courseImageFile' => [
                'nullable',
                'file',
                'max:5120',
                function (string $attribute, $value, \Closure $fail): void {
                    if (! $value) {
                        return;
                    }

                    $extension = Str::lower((string) $value->getClientOriginalExtension());
                    $mimeType = (string) ($value->getMimeType() ?? '');

                    if (in_array($extension, self::ALLOWED_IMAGE_EXTENSIONS, true)) {
                        return;
                    }

                    if (Str::startsWith($mimeType, 'image/')) {
                        return;
                    }

                    $fail('Le fichier sélectionné n\'est pas un format image supporté.');
                },
            ],
        ];
    }

    private function courseValidationRules(): array
    {
        return [
            'courseTitle' => ['required', 'string', 'min:3', 'max:255'],
            'courseDescription' => ['required', 'string', 'min:10'],
            'courseObjectives' => ['nullable', 'string'],
            'courseCategory' => ['nullable', 'exists:categories,id'],
            'courseLevel' => ['required', 'in:beginner,intermediate,advanced'],
            'language' => ['required', 'string', 'min:2', 'max:10'],
            'totalDurationMinutes' => ['nullable', 'integer', 'min:0'],
            'targetAudience' => ['nullable', 'string', 'max:255'],
            'isPaid' => ['boolean'],
            'coursePrice' => [
                'nullable',
                'numeric',
                'min:0',
                'required_if:isPaid,true',
                function (string $attribute, $value, \Closure $fail): void {
                    if ($this->isPaid && (float) $value < 100) {
                        $fail('Le prix minimum pour un cours payant est de 100 XOF.');
                    }
                },
            ],
            ...$this->imageValidationRules(),
        ];
    }

    private function courseValidationMessages(): array
    {
        return [
            'coursePrice.required_if' => 'Le prix est obligatoire quand le cours est payant.',
            'courseCategory.exists' => 'La catégorie sélectionnée est invalide.',
        ];
    }

    private function courseValidationAttributes(): array
    {
        return [
            'courseTitle' => 'titre du cours',
            'courseDescription' => 'description',
            'courseObjectives' => 'objectifs',
            'courseCategory' => 'catégorie',
            'courseLevel' => 'niveau',
            'language' => 'langue',
            'totalDurationMinutes' => 'durée totale',
            'targetAudience' => 'public cible',
            'coursePrice' => 'prix',
            'courseImageFile' => 'image de présentation',
        ];
    }

    private function normalizeCourseFormData(): void
    {
        $this->courseTitle = trim((string) $this->courseTitle);
        $this->courseDescription = trim((string) $this->courseDescription);
        $this->courseObjectives = trim((string) $this->courseObjectives);
        $this->language = strtolower(trim((string) $this->language));
        $this->targetAudience = trim((string) $this->targetAudience);

        $this->courseCategory = ($this->courseCategory === '' || $this->courseCategory === null)
            ? null
            : (int) $this->courseCategory;
        $this->totalDurationMinutes = max(0, (int) $this->totalDurationMinutes);
        $this->isPaid = (bool) $this->isPaid;
        $this->coursePrice = $this->isPaid ? (float) $this->coursePrice : 0;
    }

    public function addModule()
    {
        $this->validate([
            'newModuleTitle' => 'required|string|min:3',
            'newModuleDescription' => 'required|string|min:10',
        ]);

        if (!$this->course) {
            session()->flash('error', 'Veuillez d\'abord créer le cours');
            return;
        }

        $maxOrder = Module::where('course_id', $this->course->id)->max('order') ?? 0;
        
        $module = Module::create([
            'course_id' => $this->course->id,
            'title' => $this->newModuleTitle,
            'description' => $this->newModuleDescription,
            'order' => $maxOrder + 1,
        ]);

        $this->modules[] = [
            'id' => $module->id,
            'title' => $module->title,
            'description' => $module->description,
            'order' => $module->order,
            'lessonsCount' => 0,
        ];

        $this->newModuleTitle = '';
        $this->newModuleDescription = '';
        session()->flash('success', 'Section créée');
    }

    public function editModule($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        $this->editingModuleId = $moduleId;
        $this->editModuleTitle = $module->title;
        $this->editModuleDescription = $module->description;
    }

    public function updateModule()
    {
        $this->validate([
            'editModuleTitle' => 'required|string|min:3',
            'editModuleDescription' => 'required|string|min:10',
        ]);

        $module = Module::findOrFail($this->editingModuleId);
        $module->update([
            'title' => $this->editModuleTitle,
            'description' => $this->editModuleDescription,
        ]);

        $this->editingModuleId = null;
        $this->editModuleTitle = '';
        $this->editModuleDescription = '';
        $this->loadModules();
        session()->flash('success', 'Section mise à jour');
    }

    public function deleteModule($moduleId)
    {
        if ($this->course) {
            Module::findOrFail($moduleId)->delete();
            $this->loadModules();
            session()->flash('success', 'Section supprimée');
        }
    }

    public function moveModule($moduleId, $direction)
    {
        $module = Module::findOrFail($moduleId);
        $currentOrder = $module->order;
        
        if ($direction === 'up' && $currentOrder > 1) {
            $previous = Module::where('course_id', $this->course->id)
                ->where('order', '<', $currentOrder)
                ->orderBy('order', 'desc')
                ->first();
            
            if ($previous) {
                $module->order = $previous->order;
                $previous->order = $currentOrder;
                $module->save();
                $previous->save();
            }
        } elseif ($direction === 'down') {
            $next = Module::where('course_id', $this->course->id)
                ->where('order', '>', $currentOrder)
                ->orderBy('order')
                ->first();
            
            if ($next) {
                $module->order = $next->order;
                $next->order = $currentOrder;
                $module->save();
                $next->save();
            }
        }
        
        $this->loadModules();
    }

    public function getStats()
    {
        if (!$this->course) {
            return [
                'modules' => 0,
                'lessons' => 0,
                'quizzes' => 0,
                'resources' => 0,
                'completed' => 0,
            ];
        }

        $modules = $this->course->modules;
        $totalLessons = $modules->map(fn($m) => $m->lessons->count())->sum();
        $totalQuizzes = $modules->map(fn($m) => $m->quizzes->count())->sum()
            + $this->course->finalQuizzes()->count();
        $totalResources = $modules->map(fn($m) => $m->lessons->map(fn($l) => $l->resources->count())->sum())->sum();
        
        $hasTitle = !empty($this->courseTitle);
        $hasDescription = !empty($this->courseDescription);
        $hasModules = $modules->count() > 0;
        $hasLessons = $totalLessons > 0;
        
        $completed = collect([$hasTitle, $hasDescription, $hasModules, $hasLessons])
            ->filter(fn($item) => $item)
            ->count();

        return [
            'modules' => $modules->count(),
            'lessons' => $totalLessons,
            'quizzes' => $totalQuizzes,
            'resources' => $totalResources,
            'completed' => $completed,
            'completionPercent' => (int) ($completed / 4 * 100),
        ];
    }

    public function render()
    {
        return view('components.courses.builder', [
            'stats' => $this->getStats(),
        ]);
    }
}
