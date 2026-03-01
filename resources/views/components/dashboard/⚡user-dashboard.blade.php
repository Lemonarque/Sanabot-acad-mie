<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="mb-8">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Espace apprenant</div>
                <h2 class="font-display text-4xl">Mon tableau de bord</h2>
                <p class="text-slate-600 mt-2">Retrouvez vos cours, votre progression et vos certificats.</p>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2 mb-8">
            <div class="glass-card rounded-2xl p-5">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Cours suivis</div>
                <div class="text-3xl font-display mt-2">{{ $enrollments->count() }}</div>
            </div>
            <div class="glass-card rounded-2xl p-5">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Certificats</div>
                <div class="text-3xl font-display mt-2">
                    {{ $enrollments->filter(fn($e) => $e->certificate)->count() }}
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="glass-card rounded-3xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-display text-2xl">Mes cours en cours</h3>
                    <span class="chip px-3 py-1 rounded-full text-xs">{{ $enrollments->count() }} cours</span>
                </div>
                <div class="space-y-5">
                    @forelse($enrollments as $enrollment)
                        @php
                            $total = $enrollment->progress->count();
                            $done = $enrollment->progress->where('validated', true)->count();
                            $percent = $total > 0 ? round(($done / $total) * 100) : 0;
                        @endphp
                        <div class="rounded-2xl bg-white/70 border border-slate-200 p-5">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div>
                                    <div class="font-semibold text-slate-900">{{ $enrollment->course->title }}</div>
                                    <div class="text-sm text-slate-600">{{ $enrollment->course->description }}</div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="chip px-3 py-1 rounded-full text-xs">Progression : {{ $percent }}%</span>
                                    <a href="{{ route('apprenant.courses.show', $enrollment->course->id) }}" class="px-4 py-2 rounded-full bg-slate-900 text-white font-semibold">Continuer</a>
                                </div>
                            </div>
                            <div class="mt-3 h-2 rounded-full bg-slate-200 overflow-hidden">
                                <div class="h-2 bg-gradient-to-r from-sky-500 via-teal-500 to-amber-500" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-slate-600">Aucun cours suivi pour le moment.</div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-6">
                <div class="glass-card rounded-3xl p-6">
                    <h3 class="font-display text-xl mb-3">Raccourcis</h3>
                    <div class="grid gap-3">
                        <a href="{{ route('apprenant.courses.catalogue') }}" class="rounded-2xl bg-white/70 border border-slate-200 p-4 hover:bg-white transition">
                            <div class="font-semibold">Explorer le catalogue</div>
                            <div class="text-sm text-slate-600">Découvrir de nouveaux cours</div>
                        </a>
                        <a href="{{ route('apprenant.progress') }}" class="rounded-2xl bg-white/70 border border-slate-200 p-4 hover:bg-white transition">
                            <div class="font-semibold">Voir ma progression</div>
                            <div class="text-sm text-slate-600">Suivi global des cours</div>
                        </a>
                        <a href="{{ route('apprenant.progress') }}" class="rounded-2xl bg-white/70 border border-slate-200 p-4 hover:bg-white transition">
                            <div class="font-semibold">Mes certificats</div>
                            <div class="text-sm text-slate-600">Disponibles depuis la progression</div>
                        </a>
                    </div>
                </div>

                <div class="glass-card rounded-3xl p-6">
                    <h3 class="font-display text-xl mb-3">Conseil pédagogique</h3>
                    <p class="text-slate-600">Visez 20-30 minutes par jour pour maintenir une progression régulière.</p>
                </div>
            </div>
        </div>
    </div>
</div>