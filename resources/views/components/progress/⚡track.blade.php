<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-8">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Apprenant</div>
                <h2 class="font-display text-4xl text-slate-900">Ma progression</h2>
                <p class="text-slate-600 mt-2">Vue complète de vos cours, chapitres validés et objectifs restants.</p>
            </div>
            <a href="{{ route('apprenant.dashboard') }}" class="chip px-4 py-2 rounded-full text-sm">Retour dashboard</a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-6 mb-6">
            <div class="glass-card rounded-2xl p-4 xl:col-span-2">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Progression moyenne</div>
                <div class="mt-2 text-3xl font-display text-slate-900">{{ $summary['average_progress'] }}%</div>
                <progress value="{{ (int) $summary['average_progress'] }}" max="100" class="learner-progress mt-2"></progress>
            </div>

            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Cours</div>
                <div class="mt-2 text-3xl font-display text-slate-900">{{ $summary['total_courses'] }}</div>
            </div>

            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">En cours</div>
                <div class="mt-2 text-3xl font-display text-amber-600">{{ $summary['in_progress_courses'] }}</div>
            </div>

            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Terminés</div>
                <div class="mt-2 text-3xl font-display text-emerald-600">{{ $summary['completed_courses'] }}</div>
            </div>

            <div class="glass-card rounded-2xl p-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Certificats</div>
                <div class="mt-2 text-3xl font-display text-sky-600">{{ $summary['certificates_count'] }}</div>
            </div>
        </div>

        <div class="glass-card rounded-3xl p-6">
            <div class="space-y-4">
                @forelse($courseCards as $card)
                    <div class="rounded-2xl bg-white/75 border border-slate-200 p-5">
                        <div class="flex flex-col xl:flex-row gap-4">
                            <div class="xl:w-64 shrink-0">
                                <div class="rounded-2xl border border-slate-200 overflow-hidden h-40 bg-white">
                                    @if(!empty($card['presentation_image_url']))
                                        <img
                                            src="{{ $card['presentation_image_url'] }}"
                                            alt="Illustration du cours {{ $card['title'] }}"
                                            class="w-full h-full object-cover"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-emerald-100 via-sky-50 to-amber-100 flex items-center justify-center text-slate-600 text-sm">
                                            Illustration du cours
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                    <div class="min-w-0">
                                        <h3 class="font-semibold text-slate-900 text-lg">{{ $card['title'] }}</h3>
                                        <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ $card['description'] }}</p>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        @if($card['certificate_available'])
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">🏅 Certificat</span>
                                        @endif
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-900 text-white text-xs font-semibold">{{ $card['progress_percent'] }}%</span>
                                    </div>
                                </div>

                                <progress value="{{ (int) $card['progress_percent'] }}" max="100" class="learner-progress mt-4"></progress>

                                <div class="mt-4 grid gap-2 sm:grid-cols-2 xl:grid-cols-4 text-xs text-slate-600">
                                    <div class="rounded-xl border border-slate-200 bg-white/70 px-3 py-2">
                                        Modules : <strong class="text-slate-900">{{ $card['validated_modules'] }}/{{ $card['total_modules'] }}</strong>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-white/70 px-3 py-2">
                                        Chapitres : <strong class="text-slate-900">{{ $card['validated_lessons'] }}/{{ $card['total_lessons'] }}</strong>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-white/70 px-3 py-2">
                                        Éval. finale : <strong class="text-slate-900">{{ $card['passed_final_quizzes'] }}/{{ $card['total_final_quizzes'] }}</strong>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-white/70 px-3 py-2">
                                        Statut :
                                        <strong class="text-slate-900">
                                            @if($card['progress_percent'] === 100)
                                                Terminé
                                            @elseif($card['progress_percent'] === 0)
                                                Non démarré
                                            @else
                                                En progression
                                            @endif
                                        </strong>
                                    </div>
                                </div>

                                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-3 border-t border-slate-200">
                                    <div class="text-sm text-slate-600">
                                        @if($card['next_lesson'])
                                            Prochain chapitre :
                                            <strong class="text-slate-900">{{ $card['next_lesson']['title'] }}</strong>
                                            <span class="text-slate-500">({{ $card['next_lesson']['module_title'] }})</span>
                                        @else
                                            Tous les chapitres sont validés.
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($card['certificate_available'] && !empty($card['certificate_pdf_path']))
                                            <a href="{{ asset($card['certificate_pdf_path']) }}" target="_blank" class="px-4 py-2 rounded-full bg-emerald-100 border border-emerald-200 text-sm font-semibold text-emerald-700 hover:bg-emerald-200/70 transition">
                                                📄 Certificat
                                            </a>
                                        @else
                                            <button
                                                type="button"
                                                onclick="showCertificateUnavailableToast()"
                                                class="px-4 py-2 rounded-full bg-slate-100 border border-slate-300 text-sm font-semibold text-slate-500 hover:bg-slate-200 transition"
                                            >
                                                Certificat indisponible
                                            </button>
                                        @endif
                                        @if($card['next_lesson'])
                                            <a href="{{ route('apprenant.lessons.show', $card['next_lesson']['id']) }}" class="px-4 py-2 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                                                Continuer
                                            </a>
                                        @endif
                                        <a href="{{ route('apprenant.courses.show', $card['course_id']) }}" class="px-4 py-2 rounded-full bg-white border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                                            Ouvrir le cours
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl bg-white/75 border border-slate-200 p-8 text-center text-slate-600">
                        Aucun suivi de progression disponible.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    (() => {
        window.showCertificateUnavailableToast = () => {
            const existing = document.getElementById('certificate-unavailable-toast');
            if (existing) {
                existing.remove();
            }

            const toast = document.createElement('div');
            toast.id = 'certificate-unavailable-toast';
            toast.textContent = 'Certificat indisponible pour le moment.';
            toast.style.position = 'fixed';
            toast.style.top = 'calc(var(--nav-height) + 1rem)';
            toast.style.right = '1.5rem';
            toast.style.zIndex = '9999';
            toast.style.background = '#0f172a';
            toast.style.color = '#ffffff';
            toast.style.padding = '0.9rem 1.1rem';
            toast.style.borderRadius = '0.9rem';
            toast.style.fontWeight = '600';
            toast.style.fontSize = '0.9rem';
            toast.style.boxShadow = '0 12px 28px rgba(15, 23, 42, 0.35)';
            toast.style.border = '1px solid rgba(148, 163, 184, 0.35)';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-8px)';
            toast.style.transition = 'all 0.2s ease';

            document.body.appendChild(toast);

            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            });

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-8px)';
            }, 2400);

            setTimeout(() => {
                toast.remove();
            }, 2700);
        };
    })();
</script>