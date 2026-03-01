<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $error = '';

    public function login()
    {
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $user = Auth::user();
            if ($user && $user->hasRole('admin')) {
                session()->regenerate();
                return redirect()->intended('/dashboard');
            }
            if ($user && $user->approval_status && $user->approval_status !== 'approved') {
                Auth::logout();
                $this->error = $user->approval_status === 'rejected'
                    ? 'Votre inscription a ete refusee. Contactez l\'administration.'
                    : 'Votre inscription est en attente de validation.';
                return;
            }
            session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        $this->error = 'Identifiants invalides.';
    }

    public function render()
    {
        return view('components.auth.login');
    }
}
