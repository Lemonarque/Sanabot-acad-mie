<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InstitutionLogin extends Component
{
    public string $email = '';
    public string $password = '';
    public string $error = '';

    public function login()
    {
        $this->error = '';

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->error = 'Identifiants invalides.';
            return;
        }

        /** @var User|null $user */
        $user = Auth::user();
        if (! $user || ! $user->hasRole('institution')) {
            Auth::logout();
            $this->error = 'Ce compte n\'est pas un compte institution.';
            return;
        }

        session()->regenerate();

        return redirect()->intended(route('institution.dashboard'));
    }

    public function render()
    {
        return view('components.auth.institution-login');
    }
}
