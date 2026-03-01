<?php

namespace App\Livewire\Certificate;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class FormateurManage extends Component
{
    use WithFileUploads;

    public $course;
    public $enrollments;
    public $selectedEnrollmentId = '';
    public $certificatePdf;
    public $message = '';

    public function mount($courseId): void
    {
        $this->course = Course::where('id', $courseId)
            ->where('creator_id', Auth::id())
            ->with('creator')
            ->firstOrFail();

        $this->loadEnrollments();
    }

    private function loadEnrollments(): void
    {
        $this->enrollments = Enrollment::with(['user', 'certificate'])
            ->where('course_id', $this->course->id)
            ->latest()
            ->get();
    }

    public function issueCertificate(): void
    {
        $validated = $this->validate([
            'selectedEnrollmentId' => ['required', 'integer'],
            'certificatePdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $enrollment = Enrollment::where('id', $validated['selectedEnrollmentId'])
            ->where('course_id', $this->course->id)
            ->first();

        if (! $enrollment) {
            $this->addError('selectedEnrollmentId', 'Apprenant invalide pour ce cours.');
            return;
        }

        $certificate = Certificate::firstOrNew([
            'enrollment_id' => $enrollment->id,
        ]);

        if ($this->certificatePdf) {
            if (! empty($certificate->pdf_path)) {
                $existingPath = str_starts_with($certificate->pdf_path, 'storage/')
                    ? substr($certificate->pdf_path, 8)
                    : $certificate->pdf_path;

                Storage::disk('public')->delete($existingPath);
            }

            $path = $this->certificatePdf->store('certificates', 'public');
            $certificate->pdf_path = 'storage/' . $path;
        }

        if (! $certificate->issued_at) {
            $certificate->issued_at = now();
        }

        $certificate->save();

        $this->selectedEnrollmentId = '';
        $this->certificatePdf = null;
        $this->message = 'Certificat ajouté avec succès.';

        $this->loadEnrollments();
    }

    public function revokeCertificate(int $certificateId): void
    {
        $certificate = Certificate::with('enrollment')
            ->where('id', $certificateId)
            ->first();

        if (! $certificate || ! $certificate->enrollment || $certificate->enrollment->course_id !== $this->course->id) {
            return;
        }

        if (! empty($certificate->pdf_path)) {
            $existingPath = str_starts_with($certificate->pdf_path, 'storage/')
                ? substr($certificate->pdf_path, 8)
                : $certificate->pdf_path;
            Storage::disk('public')->delete($existingPath);
        }

        $certificate->delete();
        $this->message = 'Certificat retiré.';

        $this->loadEnrollments();
    }

    public function render()
    {
        return view('components.certificate.formateur-manage');
    }
}
