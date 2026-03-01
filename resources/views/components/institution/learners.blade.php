<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="mb-6">
            <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Institution</div>
            <h2 class="font-display text-4xl text-slate-900">Apprenants · {{ $institution->name }}</h2>
            <p class="text-slate-600 mt-2">Gérez votre quota et vos invitations apprenants.</p>
        </div>

        @include('components.institution._nav', ['active' => 'learners'])

        @if($message)
            <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 text-sm">{{ $message }}</div>
        @endif
        @if($error)
            <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700 text-sm">{{ $error }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Quota accordé</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $institution->approved_learner_quota }}</div>
            </div>
            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Places utilisées</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $this->usedSeats }}</div>
            </div>
            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Invitations en attente</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $this->pendingInvites }}</div>
            </div>
            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Places disponibles</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $this->availableSeats }}</div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2 mb-8">
            <div class="glass-card rounded-3xl p-6">
                <h3 class="font-display text-2xl text-slate-900 mb-4">Demande de quota</h3>
                <form wire:submit.prevent="submitSeatRequest" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nombre d'apprenants à demander</label>
                        <input type="number" min="1" wire:model.defer="requestedSeats" class="mt-1 w-full rounded-xl border-slate-200 px-4 py-2" required>
                    </div>
                    <button type="submit" class="px-6 py-3 rounded-full text-white font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Envoyer la demande</button>
                </form>

                <div class="mt-6">
                    <div class="text-sm font-semibold text-slate-700 mb-2">Historique des demandes</div>
                    <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                        @forelse($requests as $request)
                            <div class="rounded-xl border border-slate-200 bg-white/70 p-3 text-sm flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-slate-900 font-semibold">{{ $request->requested_seats }} place(s)</div>
                                    <div class="text-slate-500 text-xs">{{ $request->created_at?->format('d/m/Y H:i') }}</div>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $request->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($request->status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>
                        @empty
                            <div class="text-sm text-slate-500">Aucune demande pour le moment.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-3xl p-6">
                <h3 class="font-display text-2xl text-slate-900 mb-4">Ajouter des apprenants</h3>
                <form wire:submit.prevent="inviteLearners" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Emails des apprenants</label>
                        <textarea wire:model.defer="inviteEmails" rows="6" class="mt-1 w-full rounded-xl border-slate-200 px-4 py-2" placeholder="email1@exemple.com&#10;email2@exemple.com&#10;..." required></textarea>
                        <p class="text-xs text-slate-500 mt-1">Séparez par ligne, virgule ou point-virgule.</p>
                    </div>
                    <button type="submit" class="px-6 py-3 rounded-full text-white font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Inviter les apprenants</button>
                </form>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="glass-card rounded-3xl p-6">
                <h3 class="font-display text-2xl text-slate-900 mb-4">Apprenants liés</h3>
                <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                    @forelse($learners as $learner)
                        <div class="rounded-xl border border-slate-200 bg-white/70 p-3 flex items-center justify-between">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $learner->name }}</div>
                                <div class="text-sm text-slate-600">{{ $learner->email }}</div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">Lié</span>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">Aucun apprenant lié actuellement.</div>
                    @endforelse
                </div>
            </div>

            <div class="glass-card rounded-3xl p-6">
                <h3 class="font-display text-2xl text-slate-900 mb-4">Invitations récentes</h3>
                <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                    @forelse($invitations as $invitation)
                        <div class="rounded-xl border border-slate-200 bg-white/70 p-3 flex items-center justify-between gap-3">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $invitation->email }}</div>
                                <div class="text-xs text-slate-500">{{ $invitation->invited_at?->format('d/m/Y H:i') }}</div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $invitation->status === 'sent' ? 'bg-emerald-100 text-emerald-700' : ($invitation->status === 'failed' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ $invitation->status }}
                            </span>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">Aucune invitation envoyée.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
