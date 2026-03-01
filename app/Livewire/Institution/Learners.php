<?php

namespace App\Livewire\Institution;

use App\Models\Institution;
use App\Models\InstitutionInvitation;
use App\Models\InstitutionRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;

class Learners extends Component
{
    public ?Institution $institution = null;
    public $requests;
    public $learners;
    public $invitations;

    public int $requestedSeats = 0;
    public string $inviteEmails = '';
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

    public function getUsedSeatsProperty(): int
    {
        return User::where('institution_id', $this->institution->id)
            ->whereHas('role', fn ($query) => $query->where('name', 'apprenant'))
            ->count();
    }

    public function getPendingInvitesProperty(): int
    {
        return InstitutionInvitation::where('institution_id', $this->institution->id)
            ->whereIn('status', ['sent', 'pending'])
            ->count();
    }

    public function getAvailableSeatsProperty(): int
    {
        $capacity = (int) $this->institution->approved_learner_quota;
        $used = $this->usedSeats + $this->pendingInvites;

        return max(0, $capacity - $used);
    }

    public function submitSeatRequest(): void
    {
        $this->error = '';
        $this->message = '';

        $this->validate([
            'requestedSeats' => 'required|integer|min:1|max:10000',
        ]);

        InstitutionRequest::create([
            'institution_id' => $this->institution->id,
            'requested_seats' => $this->requestedSeats,
            'status' => 'pending',
        ]);

        $this->requestedSeats = 0;
        $this->message = 'Demande envoyée à l\'administration.';
        $this->loadData();
    }

    public function inviteLearners(): void
    {
        $this->error = '';
        $this->message = '';

        if (! trim($this->inviteEmails)) {
            $this->error = 'Renseignez au moins une adresse email.';
            return;
        }

        $emails = preg_split('/[\s,;]+/', strtolower(trim($this->inviteEmails)));
        $emails = array_values(array_unique(array_filter($emails, fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))));

        if (empty($emails)) {
            $this->error = 'Aucune adresse email valide détectée.';
            return;
        }

        $availableSeats = $this->availableSeats;
        if ($availableSeats <= 0) {
            $this->error = 'Quota atteint. Faites une nouvelle demande à l\'admin.';
            return;
        }

        $apprenantRole = Role::firstOrCreate(['name' => 'apprenant']);
        $invitedBy = Auth::id();
        $sent = 0;

        foreach ($emails as $email) {
            if ($sent >= $availableSeats) {
                break;
            }

            $alreadyPending = InstitutionInvitation::where('institution_id', $this->institution->id)
                ->where('email', $email)
                ->whereIn('status', ['pending', 'sent'])
                ->exists();

            if ($alreadyPending) {
                continue;
            }

            $user = User::where('email', $email)->first();
            if (! $user) {
                $user = User::create([
                    'name' => Str::title(str_replace(['.', '_', '-'], ' ', Str::before($email, '@'))),
                    'email' => $email,
                    'password' => Hash::make(Str::random(24)),
                    'role_id' => $apprenantRole->id,
                    'approval_status' => 'approved',
                    'institution_id' => $this->institution->id,
                ]);
            } else {
                if (! $user->role_id) {
                    $user->role_id = $apprenantRole->id;
                }
                $user->institution_id = $this->institution->id;
                $user->approval_status = 'approved';
                $user->save();
            }

            $status = Password::sendResetLink(['email' => $email]);

            InstitutionInvitation::create([
                'institution_id' => $this->institution->id,
                'user_id' => $user->id,
                'email' => $email,
                'status' => $status === Password::RESET_LINK_SENT ? 'sent' : 'failed',
                'invited_by' => $invitedBy,
                'invited_at' => now(),
                'error_message' => $status === Password::RESET_LINK_SENT ? null : $status,
            ]);

            if ($status === Password::RESET_LINK_SENT) {
                $sent++;
            }
        }

        if ($sent > 0) {
            $this->message = $sent . ' invitation(s) envoyée(s).';
            $this->inviteEmails = '';
        } else {
            $this->error = 'Aucune invitation envoyée (quota insuffisant ou emails déjà en attente).';
        }

        $this->loadData();
    }

    private function loadData(): void
    {
        $this->institution = Institution::findOrFail($this->institution->id);

        $this->requests = InstitutionRequest::where('institution_id', $this->institution->id)
            ->latest()
            ->get();

        $this->learners = User::with('role')
            ->where('institution_id', $this->institution->id)
            ->whereHas('role', fn ($query) => $query->where('name', 'apprenant'))
            ->latest()
            ->get();

        $this->invitations = InstitutionInvitation::where('institution_id', $this->institution->id)
            ->latest()
            ->limit(30)
            ->get();
    }

    public function render()
    {
        return view('components.institution.learners');
    }
}
