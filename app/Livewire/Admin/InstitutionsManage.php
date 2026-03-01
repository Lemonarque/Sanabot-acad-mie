<?php

namespace App\Livewire\Admin;

use App\Models\Institution;
use App\Models\InstitutionCourseAccess;
use App\Models\InstitutionCourseAccessRequest;
use App\Models\InstitutionCoursePriceRequest;
use App\Models\InstitutionRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InstitutionsManage extends Component
{
    public $requests;
    public $institutions;
    public $priceRequests;
    public $accessRequests;
    public array $approvalSeats = [];
    public array $approvalPrices = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->requests = InstitutionRequest::with(['institution.owner', 'reviewer'])
            ->latest()
            ->get();

        $this->priceRequests = InstitutionCoursePriceRequest::with(['institution.owner', 'course', 'reviewer'])
            ->latest()
            ->get();

        $this->accessRequests = InstitutionCourseAccessRequest::with(['institution.owner', 'course', 'reviewer'])
            ->latest()
            ->get();

        $this->institutions = Institution::with(['owner'])
            ->latest()
            ->get();
    }

    public function approveAccessRequest(int $requestId)
    {
        $request = InstitutionCourseAccessRequest::with(['institution', 'course'])->findOrFail($requestId);
        if ($request->status !== 'pending') {
            return;
        }

        $request->status = 'approved';
        $request->reviewed_by = Auth::id();
        $request->reviewed_at = now();
        $request->save();

        InstitutionCourseAccess::updateOrCreate([
            'institution_id' => $request->institution_id,
            'course_id' => $request->course_id,
        ], [
            'is_enabled' => true,
        ]);

        $this->loadData();
    }

    public function rejectAccessRequest(int $requestId)
    {
        $request = InstitutionCourseAccessRequest::findOrFail($requestId);
        if ($request->status !== 'pending') {
            return;
        }

        $request->status = 'rejected';
        $request->reviewed_by = Auth::id();
        $request->reviewed_at = now();
        $request->save();

        $this->loadData();
    }

    public function approveRequest(int $requestId)
    {
        $request = InstitutionRequest::with('institution')->findOrFail($requestId);
        if ($request->status !== 'pending') {
            return;
        }

        $approvedSeats = (int) ($this->approvalSeats[$requestId] ?? $request->requested_seats);
        if ($approvedSeats < 1) {
            $approvedSeats = 1;
        }

        $request->status = 'approved';
        $request->approved_seats = $approvedSeats;
        $request->reviewed_by = Auth::id();
        $request->reviewed_at = now();
        $request->save();

        $institution = $request->institution;
        $institution->approved_learner_quota = ((int) $institution->approved_learner_quota) + $approvedSeats;
        $institution->save();

        unset($this->approvalSeats[$requestId]);
        $this->loadData();
    }

    public function approvePriceRequest(int $requestId)
    {
        $request = InstitutionCoursePriceRequest::with(['institution', 'course'])->findOrFail($requestId);
        if ($request->status !== 'pending') {
            return;
        }

        $approvedPrice = (float) ($this->approvalPrices[$requestId] ?? $request->requested_price);
        $coursePrice = (float) ($request->course?->price ?? 0);

        if ($approvedPrice < 0 || ($coursePrice > 0 && $approvedPrice > $coursePrice)) {
            return;
        }

        $request->status = 'approved';
        $request->approved_price = $approvedPrice;
        $request->reviewed_by = Auth::id();
        $request->reviewed_at = now();
        $request->save();

        InstitutionCourseAccess::updateOrCreate([
            'institution_id' => $request->institution_id,
            'course_id' => $request->course_id,
        ], [
            'is_enabled' => true,
            'adjusted_price' => $approvedPrice,
        ]);

        unset($this->approvalPrices[$requestId]);
        $this->loadData();
    }

    public function rejectPriceRequest(int $requestId)
    {
        $request = InstitutionCoursePriceRequest::findOrFail($requestId);
        if ($request->status !== 'pending') {
            return;
        }

        $request->status = 'rejected';
        $request->approved_price = null;
        $request->reviewed_by = Auth::id();
        $request->reviewed_at = now();
        $request->save();

        unset($this->approvalPrices[$requestId]);
        $this->loadData();
    }

    public function rejectRequest(int $requestId)
    {
        $request = InstitutionRequest::findOrFail($requestId);
        if ($request->status !== 'pending') {
            return;
        }

        $request->status = 'rejected';
        $request->approved_seats = 0;
        $request->reviewed_by = Auth::id();
        $request->reviewed_at = now();
        $request->save();

        $this->loadData();
    }

    public function render()
    {
        return view('components.admin.institutions-manage');
    }
}
