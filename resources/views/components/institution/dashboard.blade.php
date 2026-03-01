<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="mb-6">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Institution</div>
                <h2 class="font-display text-4xl text-slate-900">Vue d'ensemble · {{ $institution->name }}</h2>
                <p class="text-slate-600 mt-2">Accédez à chaque fonctionnalité depuis les sous-pages dédiées.</p>
            </div>
        </div>

        @include('components.institution._nav', ['active' => 'dashboard'])

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Apprenants liés</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $learnersCount }}</div>
            </div>
            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Places disponibles</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $availableSeats }}</div>
            </div>
            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Demandes quota en attente</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $pendingSeatRequestsCount }}</div>
            </div>
            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Cours payants autorisés</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $authorizedPaidCoursesCount }}</div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3 mb-8">
            <div class="glass-card rounded-3xl p-6">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Demandes d'accès en attente</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $pendingAccessRequestsCount }}</div>
                <a href="{{ route('institution.course.requests') }}" class="inline-block mt-4 text-sm font-semibold text-slate-700">Gérer les demandes cours →</a>
            </div>

            <div class="glass-card rounded-3xl p-6">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Demandes prix en attente</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $pendingPriceRequestsCount }}</div>
                <a href="{{ route('institution.course.requests') }}" class="inline-block mt-4 text-sm font-semibold text-slate-700">Gérer les demandes tarifaires →</a>
            </div>

            <div class="glass-card rounded-3xl p-6">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Invitations en attente</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $pendingInvitationsCount }}</div>
                <a href="{{ route('institution.learners') }}" class="inline-block mt-4 text-sm font-semibold text-slate-700">Gérer les apprenants →</a>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="glass-card rounded-3xl p-6">
                <h3 class="font-display text-xl text-slate-900 mb-4">Dernières demandes quota</h3>
                <div class="space-y-2">
                    @forelse($recentSeatRequests as $request)
                        <div class="rounded-xl border border-slate-200 bg-white/70 p-3 text-sm">
                            <div class="font-semibold text-slate-900">{{ $request->requested_seats }} place(s)</div>
                            <div class="text-xs text-slate-500">{{ $request->created_at?->format('d/m/Y H:i') }} · {{ ucfirst($request->status) }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">Aucune demande.</div>
                    @endforelse
                </div>
            </div>

            <div class="glass-card rounded-3xl p-6">
                <h3 class="font-display text-xl text-slate-900 mb-4">Dernières demandes d'accès</h3>
                <div class="space-y-2">
                    @forelse($recentAccessRequests as $request)
                        <div class="rounded-xl border border-slate-200 bg-white/70 p-3 text-sm">
                            <div class="font-semibold text-slate-900">{{ $request->course?->title ?? 'Cours supprimé' }}</div>
                            <div class="text-xs text-slate-500">{{ $request->created_at?->format('d/m/Y H:i') }} · {{ ucfirst($request->status) }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">Aucune demande.</div>
                    @endforelse
                </div>
            </div>

            <div class="glass-card rounded-3xl p-6">
                <h3 class="font-display text-xl text-slate-900 mb-4">Dernières demandes tarifaires</h3>
                <div class="space-y-2">
                    @forelse($recentPriceRequests as $request)
                        <div class="rounded-xl border border-slate-200 bg-white/70 p-3 text-sm">
                            <div class="font-semibold text-slate-900">{{ $request->course?->title ?? 'Cours supprimé' }}</div>
                            <div class="text-xs text-slate-500">{{ $request->created_at?->format('d/m/Y H:i') }} · {{ ucfirst($request->status) }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">Aucune demande.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
