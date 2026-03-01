<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role_id = '';
    public $phone = '';
    public $organization = '';
    public $position = '';
    public $motivation = '';
    public $date_of_birth = '';
    public $gender = '';
    public $city = '';
    public $country = '';
    public $address = '';
    public $experience_years = '';
    public $error = '';
    public $success = '';

    public function register()
    {
        $this->error = '';
        $this->success = '';
        $this->name = trim($this->name);
        $this->email = strtolower(trim($this->email));
        $this->phone = trim($this->phone);
        $this->organization = trim($this->organization);
        $this->position = trim($this->position);
        $this->motivation = trim($this->motivation);
        $this->gender = trim($this->gender);
        $this->city = trim($this->city);
        $this->country = trim($this->country);
        $this->address = trim($this->address);

        $allowedRoleIds = Role::whereIn('name', ['apprenant', 'formateur', 'institution'])
            ->pluck('id')
            ->all();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|same:password_confirmation',
            'role_id' => ['required', Rule::in($allowedRoleIds)],
            'phone' => ['required', 'regex:/^\+?[0-9\s\-()]{8,20}$/'],
            'organization' => 'nullable|string|max:255|required_if:role_id,' . $this->getRoleIdByName('institution') . ',' . $this->getRoleIdByName('formateur'),
            'position' => 'nullable|string|max:255|required_if:role_id,' . $this->getRoleIdByName('institution') . ',' . $this->getRoleIdByName('formateur'),
            'motivation' => 'nullable|string|max:1000|required_if:role_id,' . $this->getRoleIdByName('institution') . ',' . $this->getRoleIdByName('formateur') . '|min:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => ['nullable', Rule::in(['femme', 'homme', 'autre'])],
            'city' => 'nullable|string|max:120',
            'country' => 'nullable|string|max:120',
            'address' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:60',
        ], [
            'role_id.in' => 'Le rôle sélectionné n\'est pas autorisé pour une auto-inscription.',
            'phone.regex' => 'Le numéro de téléphone doit contenir entre 8 et 20 caractères (chiffres, espaces, +, -, parenthèses).',
            'organization.required_if' => 'L\'organisation est obligatoire pour ce rôle.',
            'position.required_if' => 'Le poste est obligatoire pour ce rôle.',
            'motivation.required_if' => 'La motivation est obligatoire pour ce rôle.',
            'motivation.min' => 'La motivation doit contenir au moins 20 caractères.',
            'date_of_birth.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role_id' => $this->role_id,
            'phone' => $this->phone,
            'organization' => $this->organization,
            'position' => $this->position,
            'motivation' => $this->motivation,
            'date_of_birth' => $this->date_of_birth ?: null,
            'gender' => $this->gender ?: null,
            'city' => $this->city,
            'country' => $this->country,
            'address' => $this->address,
            'experience_years' => $this->experience_years !== '' ? (int) $this->experience_years : null,
            'approval_status' => 'pending',
        ]);
        $this->success = 'Inscription envoyée. Votre demande est en cours de validation.';
        $this->reset([
            'name',
            'email',
            'password',
            'password_confirmation',
            'role_id',
            'phone',
            'organization',
            'position',
            'motivation',
            'date_of_birth',
            'gender',
            'city',
            'country',
            'address',
            'experience_years',
        ]);
    }

    protected function getRoleIdByName(string $roleName): int
    {
        return (int) Role::where('name', $roleName)->value('id');
    }

    public function render()
    {
        $roles = Role::whereIn('name', ['apprenant', 'formateur', 'institution'])->orderBy('name')->get();
        return view('components.auth.register', compact('roles'));
    }
}
