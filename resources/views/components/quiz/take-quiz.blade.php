<div class="min-h-screen aurora-bg py-10">
    <style>
        .learner-quiz-layout {
            display: block;
        }

        @media (min-width: 1024px) {
            .learner-quiz-layout {
                display: grid !important;
                grid-template-columns: 320px minmax(0, 1fr) !important;
                gap: 2rem !important;
                align-items: start !important;
            }

            .learner-quiz-sidebar {
                position: sticky !important;
                top: 6rem !important;
                max-height: calc(100vh - 7rem) !important;
                overflow-y: auto !important;
            }
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="learner-quiz-layout">
            <aside class="learner-quiz-sidebar glass-card rounded-3xl p-5 h-fit">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Cours</div>
                        <h3 class="font-display text-lg mt-2 text-slate-900">{{ $course->title }}</h3>
                        @if($module)
                            <div class="text-sm text-slate-500 mt-1">Section : <span class="font-semibold text-slate-700">{{ $module->title }}</span></div>
                        @else
                            <div class="text-sm text-slate-500 mt-1">Type : <span class="font-semibold text-slate-700">Évaluation finale</span></div>
                        @endif
                    </div>
                    <a href="{{ route('apprenant.dashboard') }}" class="text-xs text-slate-500 hover:underline">Dashboard</a>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white/80 overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-200">
                        <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Table des matières</div>
                    </div>
                    <div class="p-3 space-y-3 lg:max-h-[calc(100vh-18rem)] lg:overflow-y-auto">
                        @foreach($course->modules->sortBy('order') as $m)
                            <div class="rounded-2xl border transition {{ $module && $m->id === $module->id ? 'border-slate-300 bg-white' : 'border-slate-200 bg-white/70' }} p-3">
                                <div class="font-semibold {{ $module && $m->id === $module->id ? 'text-slate-900' : 'text-slate-700' }}">{{ $m->title }}</div>
                                <div class="mt-2 space-y-2 text-sm">
                                    @foreach($m->lessons->sortBy('order') as $item)
                                        <a href="{{ route('apprenant.lessons.show', $item->id) }}"
                                           class="flex items-center rounded-xl px-3 py-2 border transition {{ ($lessonValidationMap[$item->id] ?? false) === true ? 'text-emerald-700' : 'text-slate-600' }} border-transparent hover:bg-white">
                                            @if(($lessonValidationMap[$item->id] ?? false) === true)
                                                <span class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold mr-2">✓</span>
                                            @endif
                                            {{ $item->title }}
                                        </a>
                                    @endforeach
                                    @if($m->quizzes->isNotEmpty())
                                        <a href="{{ route('apprenant.quiz.take', ['moduleId' => $m->id, 'id' => $m->quizzes->first()->id]) }}"
                                           class="block px-3 py-2 text-sm font-semibold rounded-xl border transition {{ $module && $m->id === $module->id ? 'bg-slate-900 text-white border-slate-900' : 'text-slate-900 hover:bg-white border-transparent hover:border-slate-200' }}">
                                            📝 Quiz de validation
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if($course->finalQuizzes->isNotEmpty())
                            <a href="{{ route('apprenant.quiz.course.take', ['courseId' => $course->id, 'id' => $course->finalQuizzes->first()->id]) }}"
                               class="block rounded-2xl border px-4 py-3 text-sm font-semibold transition {{ $module ? 'border-slate-200 bg-white/70 hover:bg-white text-slate-900' : 'bg-slate-900 text-white border-slate-900' }}">
                                🧪 Évaluation finale du cours
                            </a>
                        @endif
                    </div>
                </div>
            </aside>

            <main class="min-w-0">
                <div class="glass-card rounded-3xl p-8 mb-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="min-w-0">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Quiz</div>
                            <h2 class="font-display text-4xl xl:text-[3.1rem] text-slate-900 mb-3 leading-tight">{{ $quiz->title }}</h2>
                            @if($module)
                                <p class="text-slate-600 text-lg">Section : {{ $module->title }}</p>
                            @else
                                <p class="text-slate-600 text-lg">Évaluation finale du cours</p>
                            @endif
                            <p class="text-xs text-slate-500 mt-2">Seuil de validation : {{ $quiz->min_score }}%</p>
                        </div>

                        <div class="w-full lg:w-auto lg:min-w-[220px] flex flex-col items-stretch gap-3 lg:items-end">
                            @if($module && $module->lessons->isNotEmpty())
                                <a href="{{ route('apprenant.lessons.show', $module->lessons->sortBy('order')->first()->id) }}" class="inline-flex items-center justify-center gap-2 min-w-[200px] px-5 py-3 bg-white hover:bg-slate-50 border border-slate-300 rounded-full text-sm font-semibold text-slate-700 transition">
                                    ← Retour au chapitre
                                </a>
                            @else
                                <a href="{{ route('apprenant.courses.show', $course->id) }}" class="inline-flex items-center justify-center gap-2 min-w-[200px] px-5 py-3 bg-white hover:bg-slate-50 border border-slate-300 rounded-full text-sm font-semibold text-slate-700 transition">
                                    ← Retour au cours
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="submit" class="space-y-6">
                    @foreach($quiz->questions as $index => $question)
                        <div class="glass-card rounded-3xl p-6">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Question {{ $index + 1 }}</div>
                            <div class="font-semibold text-slate-900 text-lg">{{ $question->content }}</div>
                            <div class="mt-4 space-y-2">
                                @foreach($question->answers as $answer)
                                    <label class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white/70 px-3 py-2 text-slate-700 hover:bg-white transition">
                                        <input type="checkbox" class="mt-1" wire:model.defer="answers.{{ $question->id }}.{{ $answer->id }}">
                                        <span>{{ $answer->content }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="glass-card rounded-3xl p-4 flex flex-wrap gap-3 items-center justify-between">
                        <button type="submit" class="px-5 py-2 rounded-full text-white font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Valider le quiz</button>
                        <div class="flex items-center gap-3 text-sm">
                            @if($submitted)
                                <span class="text-slate-700">Score : <span class="font-semibold">{{ $score }} / {{ $quiz->questions->count() }}</span></span>
                                <span class="text-slate-700">({{ $scorePercent }}%)</span>
                            @endif
                            @if($message)
                                <span class="text-emerald-700 font-semibold">{{ $message }}</span>
                            @endif
                        </div>
                    </div>

                    @if($submitted && $isCourseFinalQuiz && $scorePercent >= $quiz->min_score)
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            ✅ Votre cours est validé. Retrouvez votre certificat directement dans la progression ou depuis la page du cours.
                        </div>
                    @endif
                </form>
            </main>
        </div>
    </div>
</div>
