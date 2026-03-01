<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div style="display:flex;gap:1.5rem;align-items:flex-start;">
            @include('components.admin.sidebar')

            <div class="flex-1">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
                    <div>
                        <div class="text-xs uppercase tracking-[0.25em] text-slate-500">Administration</div>
                        <h2 class="font-display text-4xl text-slate-900">Gestion des utilisateurs</h2>
                        <p class="text-slate-600 mt-2">Validez les inscriptions et gerez les comptes.</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2 bg-white/80 hover:bg-white border border-slate-200 rounded-full text-sm font-medium text-slate-700 transition shadow-sm">
                        ← Retour au dashboard
                    </a>
                </div>

                <div class="rounded-3xl p-6 mb-6 border border-blue-200" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.06), rgba(96, 165, 250, 0.04));">
                    <div class="flex flex-col md:flex-row md:items-center gap-3">
                        <input type="text" wire:model.defer="search" placeholder="Rechercher par nom ou email..." class="w-full md:flex-1 border-slate-200 rounded-full px-5 py-2 focus:ring-2 focus:ring-emerald-200" />
                        <select wire:model.defer="status" class="rounded-full px-4 py-2 border-slate-200">
                            <option value="all">Tous les statuts</option>
                            <option value="pending">En attente</option>
                            <option value="approved">Valide</option>
                            <option value="rejected">Refuse</option>
                        </select>
                        <button wire:click="searchUsers" class="px-5 py-2.5 rounded-full text-white font-semibold transition" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Filtrer</button>
                        <div class="text-sm text-slate-500">{{ $users->count() }} resultat(s)</div>
                    </div>
                </div>

                <div class="rounded-3xl p-6 border border-blue-200" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.06), rgba(96, 165, 250, 0.04));">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-display text-2xl text-slate-900">Liste des utilisateurs</h3>
                        <span class="text-xs uppercase tracking-[0.2em] text-slate-400">Comptes</span>
                    </div>
                    @if($users->isEmpty())
                        <p class="text-slate-600">Aucun utilisateur trouve.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-xs uppercase tracking-[0.2em] text-slate-400 border-b border-slate-200">
                                        <th class="py-3">Nom</th>
                                        <th class="py-3">Email</th>
                                        <th class="py-3">Role</th>
                                        <th class="py-3">Niveau Admin</th>
                                        <th class="py-3">Statut</th>
                                        <th class="py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="py-4 font-semibold text-slate-900">{{ $user->name }}</td>
                                            <td class="py-4 text-slate-600">{{ $user->email }}</td>
                                            <td class="py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background: rgba(53, 167, 178, 0.12); color: var(--teal);">
                                                    {{ $user->role?->name ?? '—' }}
                                                </span>
                                            </td>
                                            <td class="py-4">
                                                @if($user->role?->name === 'admin' && $user->admin_level)
                                                    @php
                                                        $levelBadge = match($user->admin_level) {
                                                            'super_admin' => ['bg' => 'rgba(239, 68, 68, 0.15)', 'color' => '#dc2626', 'label' => '⭐ Super Admin', 'icon' => '👑'],
                                                            'admin' => ['bg' => 'rgba(59, 130, 246, 0.15)', 'color' => '#3b82f6', 'label' => 'Admin', 'icon' => '🔑'],
                                                            'moderator' => ['bg' => 'rgba(168, 85, 247, 0.15)', 'color' => '#a855f7', 'label' => 'Modérateur', 'icon' => '🛡️'],
                                                            default => ['bg' => 'rgba(156, 163, 175, 0.15)', 'color' => '#6b7280', 'label' => 'Admin', 'icon' => '⚙️'],
                                                        };
                                                    @endphp
                                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold" style="background: {{ $levelBadge['bg'] }}; color: {{ $levelBadge['color'] }};">
                                                        <span>{{ $levelBadge['icon'] }}</span>
                                                        <span>{{ $levelBadge['label'] }}</span>
                                                    </span>
                                                @else
                                                    <span class="text-slate-400 text-xs">—</span>
                                                @endif
                                            </td>
                                            <td class="py-4">
                                                @php
                                                    $badge = match($user->approval_status) {
                                                        'approved' => ['bg' => 'rgba(123, 191, 100, 0.18)', 'color' => 'var(--mint)', 'label' => 'Valide'],
                                                        'rejected' => ['bg' => 'rgba(240, 142, 163, 0.18)', 'color' => 'var(--rose)', 'label' => 'Refuse'],
                                                        default => ['bg' => 'rgba(244, 197, 94, 0.18)', 'color' => 'var(--amber)', 'label' => 'En attente'],
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background: {{ $badge['bg'] }}; color: {{ $badge['color'] }};">
                                                    {{ $badge['label'] }}
                                                </span>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex flex-wrap justify-end gap-2">
                                                    <button wire:click="viewProfile({{ $user->id }})" class="px-4 py-2.5 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--sky));">Voir fiche</button>
                                                    @if($user->approval_status === 'pending')
                                                        <button wire:click="approve({{ $user->id }})" class="px-4 py-2.5 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Valider</button>
                                                        <button wire:click="reject({{ $user->id }})" class="px-4 py-2.5 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, #f08ea3, #f4c55e);">Refuser</button>
                                                    @endif
                                                    <button wire:click="edit({{ $user->id }})" class="px-4 py-2.5 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--sky));">Editer</button>
                                                    <button wire:click="delete({{ $user->id }})" class="px-4 py-2.5 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, #f08ea3, #f4c55e);" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                @if($editingId)
                    <div class="fixed inset-0 bg-slate-900/40 flex items-center justify-center z-50">
                        <div class="rounded-3xl p-8 w-full max-w-md border border-teal-300" style="background: linear-gradient(135deg, rgba(248, 250, 252, 0.95), rgba(241, 245, 249, 0.9)), linear-gradient(135deg, rgba(53, 167, 178, 0.08), rgba(123, 191, 100, 0.05));">
                            <h4 class="font-display text-2xl mb-4">Editer l'utilisateur</h4>
                            <form wire:submit.prevent="update" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium">Nom</label>
                                    <input type="text" wire:model.defer="name" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Email</label>
                                    <input type="email" wire:model.defer="email" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Role</label>
                                    <select wire:model.defer="role" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2">
                                        <option value="">Selectionner</option>
                                        <option value="admin">Admin</option>
                                        <option value="formateur">Formateur</option>
                                        <option value="apprenant">Apprenant</option>
                                    </select>
                                </div>
                                @if($role === 'admin')
                                    <div>
                                        <label class="block text-sm font-medium">Niveau Admin</label>
                                        <select wire:model.defer="adminLevel" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2">
                                            <option value="">Sélectionner un niveau</option>
                                            <option value="super_admin">👑 Super Admin (Accès complet)</option>
                                            <option value="admin">🔑 Administrateur (Gestion + Contenu)</option>
                                            <option value="moderator">🛡️ Modérateur (Contenu uniquement)</option>
                                        </select>
                                        <p class="mt-1 text-xs text-slate-500">
                                            @if($adminLevel === 'super_admin')
                                                Accès complet : gestion des utilisateurs, paramètres et contenus.
                                            @elseif($adminLevel === 'admin')
                                                Gestion des formations, paiements et inscriptions.
                                            @elseif($adminLevel === 'moderator')
                                                Gestion des formations et catégories uniquement.
                                            @else
                                                Définissez le niveau de permissions pour cet administrateur.
                                            @endif
                                        </p>
                                    </div>
                                @endif
                                <div class="flex gap-2 mt-4">
                                    <button type="submit" class="text-white px-4 py-2.5 rounded-full font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Enregistrer</button>
                                    <button type="button" wire:click="cancelEdit" class="bg-slate-200 text-slate-700 px-4 py-2.5 rounded-full font-semibold">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                @if($profileModal && $profileUser)
                    <div class="fixed inset-0 bg-slate-900/40 flex items-center justify-center z-50">
                        <div class="rounded-3xl p-8 w-full max-w-2xl border border-emerald-300" style="background: linear-gradient(135deg, rgba(248, 250, 252, 0.95), rgba(241, 245, 249, 0.9)), linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(52, 211, 153, 0.05));">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-display text-2xl">Fiche d'inscription</h4>
                                <button wire:click="closeProfile" class="text-slate-500">Fermer</button>
                            </div>
                            <div class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Nom</div>
                                    <div class="font-semibold text-slate-900">{{ $profileUser->name }}</div>
                                </div>
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Email</div>
                                    <div class="font-semibold text-slate-900">{{ $profileUser->email }}</div>
                                </div>
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Telephone</div>
                                    <div class="font-semibold text-slate-900">{{ $profileUser->phone ?? '—' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Role</div>
                                    <div class="font-semibold text-slate-900">{{ $profileUser->role?->name ?? '—' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Organisation</div>
                                    <div class="font-semibold text-slate-900">{{ $profileUser->organization ?? '—' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Poste</div>
                                    <div class="font-semibold text-slate-900">{{ $profileUser->position ?? '—' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Date de naissance</div>
                                    <div class="font-semibold text-slate-900">{{ $profileUser->date_of_birth?->format('d/m/Y') ?? '—' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Genre</div>
                                    <div class="font-semibold text-slate-900">{{ $profileUser->gender ?? '—' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Ville</div>
                                    <div class="font-semibold text-slate-900">{{ $profileUser->city ?? '—' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Pays</div>
                                    <div class="font-semibold text-slate-900">{{ $profileUser->country ?? '—' }}</div>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Adresse</div>
                                    <div class="font-semibold text-slate-900">{{ $profileUser->address ?? '—' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Expérience</div>
                                    <div class="font-semibold text-slate-900">{{ $profileUser->experience_years !== null ? $profileUser->experience_years . ' an(s)' : '—' }}</div>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Motivation</div>
                                    <div class="font-semibold text-slate-900">{{ $profileUser->motivation ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-6">
                                @if($profileUser->approval_status === 'pending')
                                    <button wire:click="approve({{ $profileUser->id }})" class="text-white px-4 py-2.5 rounded-full font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Valider</button>
                                    <button wire:click="reject({{ $profileUser->id }})" class="text-white px-4 py-2.5 rounded-full font-semibold" style="background: linear-gradient(135deg, #f08ea3, #f4c55e);">Refuser</button>
                                @endif
                                <button wire:click="closeProfile" class="bg-slate-200 text-slate-700 px-4 py-2.5 rounded-full font-semibold">Fermer</button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
