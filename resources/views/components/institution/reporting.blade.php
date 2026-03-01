<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-8">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Institution</div>
                <h2 class="font-display text-4xl text-slate-900">Reporting · {{ $institution->name }}</h2>
                <p class="text-slate-600 mt-2">Suivi des accès cours payants, des inscriptions et des demandes institutionnelles.</p>
            </div>
        </div>

        @include('components.institution._nav', ['active' => 'reporting'])

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Apprenants liés</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $learnersCount }}</div>
            </div>
            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Cours payants autorisés</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $enabledCoursesCount }}</div>
            </div>
            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Accès payants bloqués</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $blockedLinksCount }}</div>
            </div>
            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Inscriptions actives</div>
                <div class="text-3xl font-bold text-slate-900 mt-2">{{ $enrollmentsCount }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="glass-card rounded-3xl p-6">
                <h3 class="font-display text-xl text-slate-900 mb-3">Demandes d'accès</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between"><span class="text-slate-600">En attente</span><span class="font-semibold text-amber-700">{{ $pendingAccessRequestsCount }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-slate-600">Approuvées</span><span class="font-semibold text-emerald-700">{{ $approvedAccessRequestsCount }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-slate-600">Rejetées</span><span class="font-semibold text-rose-700">{{ $rejectedAccessRequestsCount }}</span></div>
                </div>
            </div>
            <div class="glass-card rounded-3xl p-6">
                <h3 class="font-display text-xl text-slate-900 mb-3">Demandes tarifaires</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between"><span class="text-slate-600">En attente</span><span class="font-semibold text-amber-700">{{ $pendingPriceRequestsCount }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-slate-600">Approuvées</span><span class="font-semibold text-emerald-700">{{ $approvedPriceRequestsCount }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-slate-600">Rejetées</span><span class="font-semibold text-rose-700">{{ $rejectedPriceRequestsCount }}</span></div>
                </div>
            </div>
            <div class="glass-card rounded-3xl p-6">
                <h3 class="font-display text-xl text-slate-900 mb-3">Invitations</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between"><span class="text-slate-600">Envoyées</span><span class="font-semibold text-emerald-700">{{ $sentInvitationsCount }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-slate-600">En échec</span><span class="font-semibold text-rose-700">{{ $failedInvitationsCount }}</span></div>
                </div>
            </div>
            <div class="glass-card rounded-3xl p-6">
                <h3 class="font-display text-xl text-slate-900 mb-3">Couverture accès</h3>
                @php
                    $paidRows = $courseRows->filter(fn ($row) => $row['is_paid']);
                    $paidSlots = $learnersCount * $paidRows->count();
                    $coverage = $paidSlots > 0 ? (int) round((($paidSlots - $blockedLinksCount) / $paidSlots) * 100) : 100;
                @endphp
                <div class="text-3xl font-bold text-slate-900">{{ $coverage }}%</div>
                <p class="text-sm text-slate-600 mt-1">Part des accès autorisés sur l'ensemble apprenants × cours payants.</p>
            </div>
        </div>

        <div class="glass-card rounded-3xl p-6">
            <h3 class="font-display text-2xl text-slate-900 mb-4">Détail par cours</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs uppercase tracking-[0.2em] text-slate-400 border-b border-slate-200">
                            <th class="py-3 pr-4">Cours</th>
                            <th class="py-3 pr-4">Statut</th>
                            <th class="py-3 pr-4">Prix</th>
                            <th class="py-3 pr-4">Autorisés</th>
                            <th class="py-3 pr-4">Bloqués</th>
                            <th class="py-3 pr-4">Inscrits</th>
                            <th class="py-3">Conversion</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($courseRows as $row)
                            <tr>
                                <td class="py-3 pr-4">
                                    <div class="font-semibold text-slate-900">{{ $row['title'] }}</div>
                                </td>
                                <td class="py-3 pr-4">
                                    @if(! $row['is_paid'])
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Libre</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $row['institution_enabled'] ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                            {{ $row['institution_enabled'] ? 'Autorisé' : 'Bloqué' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 pr-4 text-sm text-slate-700">
                                    @if($row['is_paid'])
                                        Public: {{ number_format((float) $row['public_price'], 0, ',', ' ') }} XOF
                                        @if($row['adjusted_price'] !== null)
                                            <div class="text-emerald-700 font-semibold">Ajusté: {{ number_format((float) $row['adjusted_price'], 0, ',', ' ') }} XOF</div>
                                        @endif
                                    @else
                                        Gratuit
                                    @endif
                                </td>
                                <td class="py-3 pr-4 font-semibold text-slate-900">{{ $row['allowed_count'] }}</td>
                                <td class="py-3 pr-4 font-semibold text-slate-900">{{ $row['blocked_count'] }}</td>
                                <td class="py-3 pr-4 font-semibold text-slate-900">{{ $row['enrolled_count'] }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-sky-100 text-sky-700">{{ $row['conversion'] }}%</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-slate-600">Aucune donnée de cours disponible.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
