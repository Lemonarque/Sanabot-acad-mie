<?php

namespace App\Livewire\Institution;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Institution;
use App\Models\InstitutionCourseAccess;
use App\Models\InstitutionCourseAccessRequest;
use App\Models\InstitutionCoursePriceRequest;
use App\Models\InstitutionInvitation;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Reporting extends Component
{
    public ?Institution $institution = null;
    public Collection $courseRows;

    public int $learnersCount = 0;
    public int $enabledCoursesCount = 0;
    public int $blockedLinksCount = 0;
    public int $enrollmentsCount = 0;
    public int $pendingAccessRequestsCount = 0;
    public int $approvedAccessRequestsCount = 0;
    public int $rejectedAccessRequestsCount = 0;
    public int $pendingPriceRequestsCount = 0;
    public int $approvedPriceRequestsCount = 0;
    public int $rejectedPriceRequestsCount = 0;
    public int $sentInvitationsCount = 0;
    public int $failedInvitationsCount = 0;

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

        $this->loadReportData();
    }

    private function loadReportData(): void
    {
        $learners = User::query()
            ->where('institution_id', $this->institution->id)
            ->whereHas('role', fn ($query) => $query->where('name', 'apprenant'))
            ->get(['id']);

        $learnerIds = $learners->pluck('id')->all();
        $this->learnersCount = count($learnerIds);

        $courses = Course::query()
            ->whereIn('status', ['approved', 'validated'])
            ->where('is_active', true)
            ->orderBy('title')
            ->get(['id', 'title', 'category_id', 'is_paid', 'price']);

        $institutionAccesses = InstitutionCourseAccess::query()
            ->where('institution_id', $this->institution->id)
            ->get()
            ->keyBy('course_id');

        $enrollmentsByCourse = Enrollment::query()
            ->when(! empty($learnerIds), fn ($query) => $query->whereIn('user_id', $learnerIds), fn ($query) => $query->whereRaw('1 = 0'))
            ->selectRaw('course_id, COUNT(*) as total')
            ->groupBy('course_id')
            ->pluck('total', 'course_id');

        $this->courseRows = $courses->map(function (Course $course) use ($institutionAccesses, $enrollmentsByCourse) {
            $institutionAccess = $institutionAccesses->get($course->id);

            if (! $course->is_paid || (float) $course->price <= 0) {
                $institutionEnabled = true;
                $allowed = $this->learnersCount;
                $blocked = 0;
            } else {
                $institutionEnabled = $institutionAccess ? (bool) $institutionAccess->is_enabled : false;
                $allowed = $institutionEnabled ? $this->learnersCount : 0;
                $blocked = $institutionEnabled ? 0 : $this->learnersCount;
            }

            $enrolled = (int) ($enrollmentsByCourse[$course->id] ?? 0);
            $conversion = $allowed > 0 ? (int) round(($enrolled / $allowed) * 100) : 0;

            return [
                'course_id' => $course->id,
                'title' => $course->title,
                'is_paid' => (bool) $course->is_paid,
                'public_price' => $course->price,
                'institution_enabled' => $institutionEnabled,
                'adjusted_price' => $institutionAccess?->adjusted_price,
                'allowed_count' => $allowed,
                'blocked_count' => $blocked,
                'enrolled_count' => $enrolled,
                'conversion' => $conversion,
            ];
        });

        $this->enabledCoursesCount = $this->courseRows
            ->where('is_paid', true)
            ->where('institution_enabled', true)
            ->count();
        $this->blockedLinksCount = (int) $this->courseRows->sum('blocked_count');
        $this->enrollmentsCount = (int) $this->courseRows->sum('enrolled_count');

        $this->pendingAccessRequestsCount = InstitutionCourseAccessRequest::where('institution_id', $this->institution->id)->where('status', 'pending')->count();
        $this->approvedAccessRequestsCount = InstitutionCourseAccessRequest::where('institution_id', $this->institution->id)->where('status', 'approved')->count();
        $this->rejectedAccessRequestsCount = InstitutionCourseAccessRequest::where('institution_id', $this->institution->id)->where('status', 'rejected')->count();

        $this->pendingPriceRequestsCount = InstitutionCoursePriceRequest::where('institution_id', $this->institution->id)->where('status', 'pending')->count();
        $this->approvedPriceRequestsCount = InstitutionCoursePriceRequest::where('institution_id', $this->institution->id)->where('status', 'approved')->count();
        $this->rejectedPriceRequestsCount = InstitutionCoursePriceRequest::where('institution_id', $this->institution->id)->where('status', 'rejected')->count();

        $this->sentInvitationsCount = InstitutionInvitation::where('institution_id', $this->institution->id)->where('status', 'sent')->count();
        $this->failedInvitationsCount = InstitutionInvitation::where('institution_id', $this->institution->id)->where('status', 'failed')->count();
    }

    public function render()
    {
        return view('components.institution.reporting');
    }
}
