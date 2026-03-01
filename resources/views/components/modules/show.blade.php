<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-4xl mx-auto px-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 mb-8 text-sm">
            <a href="{{ route('apprenant.courses.show', $module->course_id) }}" class="text-slate-600 hover:text-slate-900">{{ $module->course->title }}</a>
            <span class="text-slate-400">/</span>
            <span class="text-slate-900 font-semibold">{{ $module->title }}</span>
        </nav>

        <div class="glass-card rounded-3xl p-8 mb-6">
            <div class="flex items-start justify-between gap-6 mb-6">
                <div class="flex-1">
                    <div class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Section</div>
                    <h2 class="font-display text-4xl text-slate-900 mb-3">{{ $module->title }}</h2>
                    <p class="text-slate-600 leading-relaxed">{{ $module->description }}</p>
                    
                    <!-- Metadata du module -->
                    <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-slate-200">
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <span>📚</span>
                            <span><strong>{{ $module->lessons->count() }}</strong> chapitres</span>
                        </div>
                        @if($module->quizzes->isNotEmpty())
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <span>📝</span>
                                <span><strong>{{ $module->quizzes->count() }}</strong> quiz</span>
                            </div>
                        @endif
                    </div>
                </div>
                <a href="{{ route('apprenant.courses.show', $module->course_id) }}" class="inline-flex items-center gap-2 px-5 py-2 bg-white/80 hover:bg-white border border-slate-200 rounded-full text-sm font-medium text-slate-700 transition shadow-sm flex-shrink-0">
                    ← Retour au cours
                </a>
            </div>
        </div>

        @if(! $canAccess)
            <div class="rounded-3xl border border-slate-200 bg-white/80 p-8">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">🔒 Accès limité</div>
                <h3 class="font-display text-2xl mt-2 text-slate-900">Section payante</h3>
                <p class="text-slate-600 mt-2 mb-6">Cette section est payante. Effectuez le paiement pour accéder à tous les chapitres et au contenu exclusif.</p>
                <div class="flex items-center gap-3">
                    <span class="chip px-4 py-2 rounded-full text-sm font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint)); color: white;">
                        {{ number_format($module->price ?? 0, 0, ',', ' ') }} XOF
                    </span>
                    <button wire:click="payModule" class="px-6 py-2 rounded-full text-white font-semibold transition" style="background: linear-gradient(135deg, var(--teal), var(--mint));">
                        Débloquer la section
                    </button>
                </div>
            </div>
        @else
            <div class="space-y-4">
                <!-- Leçons -->
                <div class="glass-card rounded-3xl p-8">
                    <h3 class="font-display text-2xl text-slate-900 mb-6">Chapitres de la section</h3>
                    <ul class="space-y-3">
                        @forelse($module->lessons->sortBy('order') as $lesson)
                            <li>
                                <a href="{{ route('apprenant.lessons.show', ['id' => $lesson->id]) }}" class="rounded-2xl bg-white/70 border border-slate-200 hover:bg-white hover:border-slate-300 p-5 block transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-semibold text-slate-900">{{ $lesson->title }}</h4>
                                            @if($lesson->description)
                                                <p class="text-sm text-slate-600 mt-1">{{ $lesson->description }}</p>
                                            @endif
                                        </div>
                                        <div class="text-slate-400 flex-shrink-0 ml-4">→</div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="text-slate-600 text-center py-6">Aucun chapitre pour cette section</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Quiz du module -->
                @if($module->quizzes->isNotEmpty())
                    <div class="glass-card rounded-3xl p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-display text-2xl text-slate-900">📝 Quiz de la section</h3>
                            <span class="chip px-3 py-1 rounded-full text-xs bg-emerald-100/80 text-emerald-700">Évaluation</span>
                        </div>
                        <p class="text-slate-600 mb-4">Une fois que vous avez suivi tous les chapitres, testez vos connaissances :</p>
                        <a href="{{ route('apprenant.quiz.take', ['moduleId' => $module->id, 'id' => $module->quizzes->first()->id]) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-slate-900 text-white font-semibold hover:bg-slate-800 transition">
                            Commencer le quiz →
                        </a>
                    </div>
                @endif

                @if($module->course->finalQuizzes->isNotEmpty())
                    <div class="glass-card rounded-3xl p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-display text-2xl text-slate-900">🧪 Évaluation finale du cours</h3>
                            <span class="chip px-3 py-1 rounded-full text-xs bg-slate-100 text-slate-700">Certification</span>
                        </div>
                        <p class="text-slate-600 mb-4">Quand vous avez terminé vos sections, passez l’évaluation finale du cours.</p>
                        <a href="{{ route('apprenant.quiz.course.take', ['courseId' => $module->course->id, 'id' => $module->course->finalQuizzes->first()->id]) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-slate-900 text-white font-semibold hover:bg-slate-800 transition">
                            Passer l’évaluation finale →
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
