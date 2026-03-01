<?php

namespace App\Livewire\Institution;

use App\Models\Course;
use App\Models\Institution;
use App\Models\InstitutionCourseAccess;
use App\Models\InstitutionCourseAccessRequest;
use App\Models\InstitutionCoursePriceRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CourseRequests extends Component
{
    use WithPagination;

    public ?Institution $institution = null;
    public $courses;
    public $courseAccesses;
    public $priceRequests;
    public $accessRequests;
    public array $pendingAccessCourseIds = [];
    public int $statusPerPage = 5;

    public ?int $selectedCourseForAccessRequest = null;
    public string $accessRequestNote = '';
    public ?int $selectedCourseForPriceRequest = null;
    public ?float $requestedPrice = null;
    public string $priceRequestNote = '';
    public string $message = '';
    public string $error = '';

    public function mount(): void
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (! $user || ! $user->hasRole('institution')) {
            abort(403);
        }

        $this->institution = Institution::firstOrCreate([
            'owner_user_id' => $user->id,
        ], [
            'name' => $user->organization ?: $user->name,
            'slug' => Str::slug(($user->organization ?: $user->name) . '-' . $user->id),
            'contact_email' => $user->email,
            'contact_phone' => $user->phone,
            'approved_learner_quota' => 0,
            'is_active' => true,
        ]);

        if ($user->institution_id !== $this->institution->id) {
            $user->institution_id = $this->institution->id;
            $user->save();
        }

        $this->loadData();
    }

    public function getAuthorizedPaidCoursesCountProperty(): int
    {
        return $this->courses
            ->filter(fn ($course) => $course->is_paid && (float) $course->price > 0)
            ->filter(function ($course) {
                $access = $this->courseAccesses->get($course->id);
                return $access && $access->is_enabled;
            })
            ->count();
    }

    public function getPendingAccessRequestsCountProperty(): int
    {
        return $this->accessRequests->where('status', 'pending')->count();
    }

    public function getPendingPriceRequestsCountProperty(): int
    {
        return $this->priceRequests->where('status', 'pending')->count();
    }

    public function submitCourseAccessRequest(): void
    {
        $this->error = '';
        $this->message = '';

        $this->validate([
            'selectedCourseForAccessRequest' => 'required|exists:courses,id',
            'accessRequestNote' => 'nullable|string|max:1000',
        ]);

        $course = Course::whereIn('status', ['approved', 'validated'])
            ->where('is_active', true)
            ->findOrFail($this->selectedCourseForAccessRequest);

        if (! $course->is_paid || (float) $course->price <= 0) {
            $this->error = 'Les cours gratuits sont accessibles à tous. Seuls les cours payants nécessitent une demande.';
            return;
        }

        $institutionAccess = InstitutionCourseAccess::where('institution_id', $this->institution->id)
            ->where('course_id', $course->id)
            ->first();

        if ($institutionAccess && $institutionAccess->is_enabled) {
            $this->error = 'Ce cours est déjà autorisé pour votre institution.';
            return;
        }

        $pendingExists = InstitutionCourseAccessRequest::where('institution_id', $this->institution->id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->exists();

        if ($pendingExists) {
            $this->error = 'Une demande d\'accès est déjà en attente pour ce cours.';
            return;
        }

        InstitutionCourseAccessRequest::create([
            'institution_id' => $this->institution->id,
            'course_id' => $course->id,
            'status' => 'pending',
            'note' => trim($this->accessRequestNote) ?: null,
        ]);

        $this->selectedCourseForAccessRequest = null;
        $this->accessRequestNote = '';
        $this->message = 'Demande d\'accès envoyée à l\'administration.';

        $this->loadData();
    }

    public function submitCoursePriceRequest(): void
    {
        $this->error = '';
        $this->message = '';

        $this->validate([
            'selectedCourseForPriceRequest' => 'required|exists:courses,id',
            'requestedPrice' => 'required|numeric|min:0',
            'priceRequestNote' => 'nullable|string|max:1000',
        ]);

        $course = Course::findOrFail($this->selectedCourseForPriceRequest);

        if (! $course->is_paid || (float) $course->price <= 0) {
            $this->error = 'Ce cours est gratuit. Aucun ajustement de prix nécessaire.';
            return;
        }

        if ((float) $this->requestedPrice >= (float) $course->price) {
            $this->error = 'Le prix demandé doit être inférieur au prix actuel du cours.';
            return;
        }

        $pendingExists = InstitutionCoursePriceRequest::where('institution_id', $this->institution->id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->exists();

        if ($pendingExists) {
            $this->error = 'Une demande de prix est déjà en attente pour ce cours.';
            return;
        }

        InstitutionCoursePriceRequest::create([
            'institution_id' => $this->institution->id,
            'course_id' => $course->id,
            'requested_price' => $this->requestedPrice,
            'status' => 'pending',
            'note' => trim($this->priceRequestNote) ?: null,
        ]);

        $this->selectedCourseForPriceRequest = null;
        $this->requestedPrice = null;
        $this->priceRequestNote = '';
        $this->message = 'Demande d\'ajustement tarifaire envoyée.';

        $this->loadData();
    }

    private function loadData(): void
    {
        $this->institution = Institution::findOrFail($this->institution->id);

        $this->courses = Course::with(['category'])
            ->whereIn('status', ['approved', 'validated'])
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        $this->courseAccesses = InstitutionCourseAccess::where('institution_id', $this->institution->id)
            ->get()
            ->keyBy('course_id');

        $this->accessRequests = InstitutionCourseAccessRequest::with('course')
            ->where('institution_id', $this->institution->id)
            ->latest()
            ->limit(30)
            ->get();

        $this->pendingAccessCourseIds = $this->accessRequests
            ->where('status', 'pending')
            ->pluck('course_id')
            ->unique()
            ->values()
            ->all();

        $this->priceRequests = InstitutionCoursePriceRequest::with('course')
            ->where('institution_id', $this->institution->id)
            ->latest()
            ->limit(30)
            ->get();
    }

    public function render()
    {
        $paginatedCourses = Course::with(['category'])
            ->whereIn('status', ['approved', 'validated'])
            ->where('is_active', true)
            ->orderBy('title')
            ->paginate($this->statusPerPage, pageName: 'statusPage');

        return view('components.institution.course-requests', [
            'paginatedCourses' => $paginatedCourses,
        ]);
    }
}
