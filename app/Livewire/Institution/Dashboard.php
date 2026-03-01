<?php

namespace App\Livewire\Institution;

use App\Models\Course;
use App\Models\Institution;
use App\Models\InstitutionCourseAccess;
use App\Models\InstitutionCourseAccessRequest;
use App\Models\InstitutionCoursePriceRequest;
use App\Models\InstitutionInvitation;
use App\Models\InstitutionRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Dashboard extends Component
{
    public ?Institution $institution = null;
    public int $learnersCount = 0;
    public int $availableSeats = 0;
    public int $pendingSeatRequestsCount = 0;
    public int $pendingInvitationsCount = 0;
    public int $authorizedPaidCoursesCount = 0;
    public int $pendingAccessRequestsCount = 0;
    public int $pendingPriceRequestsCount = 0;

    public $recentSeatRequests;
    public $recentAccessRequests;
    public $recentPriceRequests;

    public function mount()
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

    private function loadData(): void
    {
        $this->institution = Institution::findOrFail($this->institution->id);

        $this->learnersCount = User::where('institution_id', $this->institution->id)
            ->whereHas('role', fn ($query) => $query->where('name', 'apprenant'))
            ->count();

        $this->pendingInvitationsCount = InstitutionInvitation::where('institution_id', $this->institution->id)
            ->whereIn('status', ['sent', 'pending'])
            ->count();

        $this->availableSeats = max(
            0,
            ((int) $this->institution->approved_learner_quota) - ($this->learnersCount + $this->pendingInvitationsCount)
        );

        $this->pendingSeatRequestsCount = InstitutionRequest::where('institution_id', $this->institution->id)
            ->where('status', 'pending')
            ->count();

        $paidCourseIds = Course::query()
            ->whereIn('status', ['approved', 'validated'])
            ->where('is_active', true)
            ->where('is_paid', true)
            ->where('price', '>', 0)
            ->pluck('id');

        $this->authorizedPaidCoursesCount = InstitutionCourseAccess::where('institution_id', $this->institution->id)
            ->where('is_enabled', true)
            ->whereIn('course_id', $paidCourseIds)
            ->count();

        $this->pendingAccessRequestsCount = InstitutionCourseAccessRequest::where('institution_id', $this->institution->id)
            ->where('status', 'pending')
            ->count();

        $this->pendingPriceRequestsCount = InstitutionCoursePriceRequest::where('institution_id', $this->institution->id)
            ->where('status', 'pending')
            ->count();

        $this->recentSeatRequests = InstitutionRequest::where('institution_id', $this->institution->id)
            ->latest()
            ->limit(5)
            ->get();

        $this->recentAccessRequests = InstitutionCourseAccessRequest::with('course')
            ->where('institution_id', $this->institution->id)
            ->latest()
            ->limit(5)
            ->get();

        $this->recentPriceRequests = InstitutionCoursePriceRequest::with('course')
            ->where('institution_id', $this->institution->id)
            ->latest()
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('components.institution.dashboard');
    }
}
