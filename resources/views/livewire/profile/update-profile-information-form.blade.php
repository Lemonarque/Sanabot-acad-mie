<?php

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $organization = '';
    public string $position = '';
    public string $date_of_birth = '';
    public string $gender = '';
    public string $city = '';
    public string $country = '';
    public string $address = '';
    public string $experience_years = '';
    public string $motivation = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->organization = $user->organization ?? '';
        $this->position = $user->position ?? '';
        $this->date_of_birth = $user->date_of_birth?->format('Y-m-d') ?? '';
        $this->gender = $user->gender ?? '';
        $this->city = $user->city ?? '';
        $this->country = $user->country ?? '';
        $this->address = $user->address ?? '';
        $this->experience_years = $user->experience_years !== null ? (string) $user->experience_years : '';
        $this->motivation = $user->motivation ?? '';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'organization' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:homme,femme,autre,prefer_not_to_say'],
            'city' => ['nullable', 'string', 'max:120'],
            'country' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:500'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:80'],
            'motivation' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['experience_years'] = $validated['experience_years'] === '' ? null : $validated['experience_years'];

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user instanceof MustVerifyEmail) {
            return;
        }

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informations du profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Mettez à jour vos informations personnelles, professionnelles et vos coordonnées.') }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="name" :value="__('Nom complet')" />
                <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="phone" :value="__('Téléphone')" />
                <x-text-input wire:model="phone" id="phone" name="phone" type="text" class="mt-1 block w-full" autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="organization" :value="__('Organisation')" />
                <x-text-input wire:model="organization" id="organization" name="organization" type="text" class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('organization')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="position" :value="__('Poste')" />
                <x-text-input wire:model="position" id="position" name="position" type="text" class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('position')" />
            </div>

            <div>
                <x-input-label for="experience_years" :value="__('Années d\'expérience')" />
                <x-text-input wire:model="experience_years" id="experience_years" name="experience_years" type="number" min="0" max="80" class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('experience_years')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="date_of_birth" :value="__('Date de naissance')" />
                <x-text-input wire:model="date_of_birth" id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
            </div>

            <div>
                <x-input-label for="gender" :value="__('Genre')" />
                <select wire:model="gender" id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Sélectionner</option>
                    <option value="homme">Homme</option>
                    <option value="femme">Femme</option>
                    <option value="autre">Autre</option>
                    <option value="prefer_not_to_say">Préfère ne pas répondre</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="city" :value="__('Ville')" />
                <x-text-input wire:model="city" id="city" name="city" type="text" class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('city')" />
            </div>

            <div>
                <x-input-label for="country" :value="__('Pays')" />
                <x-text-input wire:model="country" id="country" name="country" type="text" class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('country')" />
            </div>
        </div>

        <div>
            <x-input-label for="address" :value="__('Adresse')" />
            <textarea wire:model="address" id="address" name="address" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div>
            <x-input-label for="motivation" :value="__('Objectifs / Motivation')" />
            <textarea wire:model="motivation" id="motivation" name="motivation" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
            <x-input-error class="mt-2" :messages="$errors->get('motivation')" />
        </div>

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
