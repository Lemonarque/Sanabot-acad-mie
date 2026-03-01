<div class="min-h-screen aurora-bg flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-5xl grid lg:grid-cols-[1.1fr_0.9fr] gap-8">
        <div class="glass-card rounded-3xl p-8 lg:p-10">
            <div class="text-xs uppercase tracking-[0.3em] text-slate-500">Louck's Health</div>
            <h1 class="font-display text-4xl text-slate-900 mt-3">Inscription</h1>
            <p class="text-slate-600 mt-2">Creez votre compte et soumettez votre fiche d'inscription.</p>

            @if ($error)
                <div class="mt-5 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 px-4 py-3 text-sm">{{ $error }}</div>
            @endif
            @if ($success)
                <div class="mt-5 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 text-sm">{{ $success }}</div>
            @endif

            <form wire:submit.prevent="register" class="mt-6 space-y-5">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nom complet</label>
                        <input type="text" wire:model.defer="name" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" required />
                        @error('name') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" wire:model.defer="email" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" required />
                        @error('email') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Telephone</label>
                        <input type="text" wire:model.defer="phone" placeholder="+229 01 90 00 00 00" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" required />
                        @error('phone') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Role</label>
                        <select wire:model.defer="role_id" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" required>
                            <option value="">Choisir un role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('role_id') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white/70 px-4 py-3 text-xs text-slate-600">
                    Informations complémentaires obligatoires pour les rôles <span class="font-semibold text-slate-700">Formateur</span> et <span class="font-semibold text-slate-700">Institution</span> :
                    <span class="font-semibold">Organisation</span>, <span class="font-semibold">Poste</span> et <span class="font-semibold">Motivation</span>.
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Organisation</label>
                        <input type="text" wire:model.defer="organization" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" />
                        @error('organization') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Poste</label>
                        <input type="text" wire:model.defer="position" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" />
                        @error('position') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Motivation</label>
                    <textarea wire:model.defer="motivation" rows="3" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200"></textarea>
                    @error('motivation') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Date de naissance (optionnel)</label>
                        <input type="date" wire:model.defer="date_of_birth" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" />
                        @error('date_of_birth') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Genre (optionnel)</label>
                        <select wire:model.defer="gender" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200">
                            <option value="">Sélectionner</option>
                            <option value="femme">Femme</option>
                            <option value="homme">Homme</option>
                            <option value="autre">Autre</option>
                        </select>
                        @error('gender') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Ville (optionnel)</label>
                        <input type="text" wire:model.defer="city" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" />
                        @error('city') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Pays (optionnel)</label>
                        <input type="text" wire:model.defer="country" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" />
                        @error('country') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Adresse (optionnel)</label>
                        <input type="text" wire:model.defer="address" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" />
                        @error('address') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Années d'expérience (optionnel)</label>
                        <input type="number" min="0" max="60" wire:model.defer="experience_years" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" />
                        @error('experience_years') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Mot de passe</label>
                        <input type="password" wire:model.defer="password" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" required />
                        @error('password') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Confirmer le mot de passe</label>
                        <input type="password" wire:model.defer="password_confirmation" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" required />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
                    <span class="text-slate-500">Vous avez deja un compte ?</span>
                    <a href="{{ route('login') }}" class="text-emerald-700 hover:text-emerald-800">Se connecter</a>
                </div>
                <button type="submit" class="w-full py-3 rounded-2xl text-white font-semibold shadow-sm transition" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Envoyer la demande</button>
            </form>
        </div>

        <div class="glass-card rounded-3xl p-8 lg:p-10 flex flex-col justify-between" style="background: linear-gradient(135deg, rgba(53, 167, 178, 0.16), rgba(126, 217, 180, 0.2));">
            <div>
                <img
                    src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1200&q=80"
                    alt="Inscription et accompagnement"
                    class="w-full h-44 object-cover rounded-2xl border border-white/40 mb-5"
                    loading="lazy"
                >
                <div class="text-xs uppercase tracking-[0.3em] text-emerald-700">SANABOT-ACADEMY</div>
                <h2 class="font-display text-3xl text-slate-900 mt-3">Validation par Louck's Health</h2>
                <p class="text-slate-600 mt-3">Chaque inscription est verifiee par l'administration avant activation du compte.</p>
            </div>
            <div class="mt-10 space-y-3 text-sm text-slate-700">
                <div class="flex items-center gap-3">
                    <span class="h-2.5 w-2.5 rounded-full" style="background: var(--teal);"></span>
                    <span>Analyse du profil et du role</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="h-2.5 w-2.5 rounded-full" style="background: var(--mint);"></span>
                    <span>Activation apres validation</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="h-2.5 w-2.5 rounded-full" style="background: var(--sky);"></span>
                    <span>Support Louck's Health</span>
                </div>
            </div>
        </div>
    </div>
</div>
