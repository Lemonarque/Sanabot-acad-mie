<div class="min-h-screen aurora-bg py-10">
    <style>
        @media (min-width: 1024px) {
            .learner-course-layout {
                display: grid;
                grid-template-columns: 300px minmax(0, 1fr);
                gap: 2rem;
                align-items: start;
            }

            .learner-course-sidebar {
                position: sticky;
                top: 6rem;
            }
        }
        .learner-course-layout {
            display: block;
        }

        @media (min-width: 1024px) {
            .learner-course-layout {
                display: grid !important;
                grid-template-columns: 300px minmax(0, 1fr) !important;
                gap: 2rem !important;
                align-items: start !important;
            }

            .learner-course-sidebar {
                position: sticky !important;
                top: 6rem !important;
            }
        }
    </style>
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="learner-course-layout">
            <aside class="learner-course-sidebar glass-card rounded-3xl p-5 h-fit">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Cours</div>
                        <h3 class="font-display text-lg mt-2 text-slate-900">{{ $course->title }}</h3>
                        <div class="text-sm text-slate-500 mt-1">Formateur : <span class="font-semibold text-slate-700">{{ $course->creator->name ?? 'N/A' }}</span></div>
                    </div>
                    <a href="{{ route('apprenant.dashboard') }}" class="text-xs text-slate-500 hover:underline">Dashboard</a>
                </div>

                <!-- Progress bar -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-xs font-semibold text-slate-600">Progression</div>
                        <div class="text-xs font-semibold text-slate-900">{{ $courseProgress ?? 0 }}%</div>
                    </div>
                    <progress value="{{ (int) ($courseProgress ?? 0) }}" max="100" class="w-full h-2 rounded-full overflow-hidden"></progress>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white/80">
                    <div class="px-4 py-3 border-b border-slate-200">
                        <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Table des matières</div>
                    </div>
                    <div class="p-3 space-y-3 max-h-96 overflow-y-auto">
                        @foreach($course->modules->sortBy('order') as $module)
                            <details class="rounded-2xl border border-slate-200 bg-white/70 p-3 hover:bg-white transition">
                                <summary class="cursor-pointer font-semibold text-slate-900">{{ $module->title }}</summary>
                                <div class="mt-2 space-y-2 text-sm">
                                    @foreach($module->lessons->sortBy('order') as $item)
                                        <a href="{{ route('apprenant.lessons.show', $item->id) }}"
                                           class="flex items-center rounded-xl px-3 py-2 border border-transparent text-slate-600 hover:bg-white hover:border-slate-200 transition {{ ($lessonValidationMap[$item->id] ?? false) === true ? 'text-emerald-700' : '' }}">
                                            @if(($lessonValidationMap[$item->id] ?? false) === true)
                                                <span class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold mr-2">✓</span>
                                            @endif
                                            {{ $item->title }}
                                        </a>
                                    @endforeach
                                    @if($module->quizzes->isNotEmpty())
                                        <a href="{{ route('apprenant.quiz.take', ['moduleId' => $module->id, 'id' => $module->quizzes->first()->id]) }}" class="block px-3 py-2 text-sm font-semibold text-slate-900 hover:bg-white rounded-xl border border-transparent hover:border-slate-200">📝 Quiz de validation</a>
                                    @endif
                                </div>
                            </details>
                        @endforeach

                        @if($course->finalQuizzes->isNotEmpty())
                            <a href="{{ route('apprenant.quiz.course.take', ['courseId' => $course->id, 'id' => $course->finalQuizzes->first()->id]) }}" class="block rounded-2xl border border-slate-200 bg-white/70 hover:bg-white px-4 py-3 text-sm font-semibold text-slate-900 transition">
                                🧪 Évaluation finale du cours
                            </a>
                        @endif
                    </div>
                </div>
            </aside>

            <main class="min-w-0">
                <!-- Header -->
                <div class="glass-card rounded-3xl p-8 mb-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="flex-1">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Présentation du cours</div>
                            <h2 class="font-display text-4xl text-slate-900 mb-3">{{ $course->title }}</h2>
                            <div class="rounded-2xl overflow-hidden border border-slate-200 mb-4">
                                @if($course->presentation_image_url)
                                    <img
                                        src="{{ $course->presentation_image_url }}"
                                        alt="Image de présentation du cours {{ $course->title }}"
                                        class="w-full h-72 lg:h-80 object-cover"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="w-full h-72 lg:h-80 bg-gradient-to-br from-emerald-100 via-sky-50 to-amber-100 border border-slate-200 flex items-center justify-center text-slate-600 text-sm">
                                        Illustration du cours
                                    </div>
                                @endif
                            </div>
                            <p class="text-slate-600 mb-4 leading-relaxed">{{ $course->description }}</p>
                            
                            <!-- Metadata du cours -->
                            <div class="flex flex-wrap gap-4 pt-4">
                                @if($course->level)
                                    <div class="flex items-center gap-2 text-sm text-slate-600">
                                        <span>📊</span>
                                        <span>Niveau : <strong>{{ ucfirst($course->level) }}</strong></span>
                                    </div>
                                @endif
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <span>📚</span>
                                    <span><strong>{{ $course->modules->count() }}</strong> sections</span>
                                </div>
                                @if($course->total_duration_minutes)
                                    <div class="flex items-center gap-2 text-sm text-slate-600">
                                        <span>⏱️</span>
                                        <span><strong>{{ $course->total_duration_minutes }}</strong> heures</span>
                                    </div>
                                @endif
                                @if($course->is_paid && $course->price > 0)
                                    <div class="flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold" style="background: linear-gradient(135deg, rgba(53, 167, 178, 0.15), rgba(122, 230, 210, 0.15)); color: var(--teal);">
                                        <span>💰</span>
                                        <span>{{ number_format($course->price, 0, ',', ' ') }} XOF</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 px-3 py-1 bg-emerald-100 rounded-full text-sm font-semibold text-emerald-700">
                                        <span>🎁</span>
                                        <span>Gratuit</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col items-start gap-2 lg:items-end">
                            @if($enrolled)
                                <span class="px-4 py-2 rounded-full bg-emerald-100/80 text-emerald-700 text-sm font-semibold">✓ Inscrit</span>
                            @else
                                @if($course->is_paid && $course->price > 0)
                                    <button wire:click="enroll" class="px-5 py-2 rounded-full text-white font-semibold transition hover:shadow-lg" style="background: linear-gradient(135deg, var(--teal), var(--mint));">
                                        💳 Payer {{ number_format($course->price, 0, ',', ' ') }} XOF
                                    </button>
                                @else
                                    <button wire:click="enroll" class="px-5 py-2 rounded-full bg-slate-900 text-white font-semibold hover:bg-slate-800 transition">
                                        S'inscrire gratuitement
                                    </button>
                                @endif
                            @endif
                            <a href="{{ route('apprenant.courses.catalogue') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 border border-slate-300 rounded-full text-sm font-semibold text-slate-700 transition">
                                ← Retour au catalogue
                            </a>
                        </div>
                    </div>
                </div>

                @if($enrollMessage)
                    <style>
                        @keyframes toast-shake {
                            0%, 100% { transform: translateX(0); }
                            20% { transform: translateX(-4px); }
                            40% { transform: translateX(4px); }
                            60% { transform: translateX(-3px); }
                            80% { transform: translateX(3px); }
                        }
                    </style>

                    <div id="enroll-toast" class="w-[calc(100vw-2rem)] md:w-auto md:min-w-[30rem] md:max-w-[42rem] px-7 py-6 bg-slate-900 text-white rounded-2xl shadow-2xl border border-slate-700/60 transition-all duration-300 opacity-100 translate-y-0" style="position: fixed; top: calc(var(--nav-height) + 1rem); right: 1.5rem; left: auto; z-index: 9999; animation: toast-shake 0.45s ease-in-out 1;">
                        <div class="flex items-start gap-3.5 text-lg font-bold leading-relaxed">
                            <span class="text-xl">ℹ️</span>
                            <span>{{ $enrollMessage }}</span>
                        </div>
                    </div>

                    <script>
                        (() => {
                            const toast = document.getElementById('enroll-toast');
                            if (!toast) return;

                            setTimeout(() => {
                                toast.classList.remove('opacity-100', 'translate-y-0');
                                toast.classList.add('opacity-0', '-translate-y-2');
                            }, 3500);

                            setTimeout(() => {
                                toast.remove();
                            }, 3900);
                        })();
                    </script>
                @endif

                @if($enrolled && $hasFinalEvaluation)
                    @if($courseValidated && $certificateAvailable)
                        <div class="mb-6 p-4 rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-sm">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <span>✅ Cours validé grâce au quiz final. Votre certificat est disponible.</span>
                                @if(!empty($certificate?->pdf_path))
                                    <a href="{{ asset($certificate->pdf_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                                        📄 Ouvrir le certificat
                                    </a>
                                @else
                                    <button type="button" onclick="showCertificateUnavailableToast()" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 border border-slate-300 text-sm font-semibold text-slate-500 hover:bg-slate-200 transition">
                                        Certificat indisponible
                                    </button>
                                @endif
                            </div>
                        </div>
                    @elseif($courseValidated)
                        <div class="mb-6 p-4 rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-sm">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <span>✅ Cours validé grâce au quiz final. Génération du certificat en cours.</span>
                                <button type="button" onclick="showCertificateUnavailableToast()" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 border border-slate-300 text-sm font-semibold text-slate-500 hover:bg-slate-200 transition">
                                    Certificat indisponible
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="mb-6 p-4 rounded-2xl border border-amber-200 bg-amber-50 text-amber-800 text-sm">
                            ⏳ Pour valider ce cours et obtenir votre certificat, vous devez réussir l’évaluation finale.
                        </div>
                    @endif
                @endif

                <!-- Contenu principale -->
                @if($enrolled)
                    <!-- Sections grid -->
                    <div class="glass-card rounded-3xl p-8">
                        <h3 class="font-display text-2xl text-slate-900 mb-6">Sections du cours</h3>
                        <div class="space-y-4">
                            @foreach($course->modules->sortBy('order') as $module)
                                <a href="{{ route('apprenant.modules.show', $module->id) }}" class="rounded-2xl border border-slate-200 bg-white/70 hover:bg-white hover:border-slate-300 p-6 transition flex items-center justify-between group">
                                    <div>
                                        <h4 class="font-semibold text-slate-900 mb-1">{{ $module->title }}</h4>
                                        <p class="text-sm text-slate-600">{{ $module->lessons->count() }} chapitre(s)</p>
                                    </div>
                                    <div class="text-slate-400 group-hover:text-slate-600 transition">→</div>
                                </a>
                            @endforeach
                        </div>

                        @if($course->finalQuizzes->isNotEmpty())
                            <div class="mt-6 pt-6 border-t border-slate-200">
                                <a href="{{ route('apprenant.quiz.course.take', ['courseId' => $course->id, 'id' => $course->finalQuizzes->first()->id]) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-slate-900 text-white font-semibold hover:bg-slate-800 transition">
                                    Passer l’évaluation finale →
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </main>
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
            toast.style.padding = '0.95rem 1.15rem';
            toast.style.borderRadius = '0.9rem';
            toast.style.fontWeight = '600';
            toast.style.fontSize = '0.92rem';
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
