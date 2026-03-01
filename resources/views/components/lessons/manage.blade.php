<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Formateur</div>
                <h2 class="font-display text-4xl">Chapitres</h2>
                <p class="text-slate-600 mt-2">Section : {{ $module->title }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('lessons.create', ['moduleId' => $module->id]) }}" class="inline-flex items-center gap-2 px-5 py-2 bg-slate-900 text-white rounded-full text-sm font-semibold hover:bg-slate-800 transition shadow-sm">
                    + Ajouter un chapitre
                </a>
                <a href="{{ route('modules.manage', ['courseId' => $module->course_id]) }}" class="inline-flex items-center gap-2 px-5 py-2 bg-white/80 hover:bg-white border border-slate-200 rounded-full text-sm font-medium text-slate-700 transition shadow-sm">
                    ← Retour aux sections
                </a>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="space-y-6">
                <div id="lessons-create" class="glass-card rounded-3xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-display text-2xl">Créer ou modifier un chapitre</h3>
                            <p class="text-sm text-slate-600 mt-1">L'éditeur s'ouvre sur une page dediee pour recharger proprement le contenu.</p>
                        </div>
                        <a href="{{ route('lessons.create', ['moduleId' => $module->id]) }}" class="px-5 py-2.5 bg-slate-900 text-white rounded-full text-sm font-semibold hover:bg-slate-800 transition shadow-sm">
                            + Ajouter un chapitre
                        </a>
                    </div>
                </div>

                <div id="lessons-list" class="glass-card rounded-3xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-display text-2xl">Liste des chapitres</h3>
                        <span class="chip px-3 py-1 rounded-full text-xs bg-sky-100/80 text-sky-700">{{ $lessons->count() }} chapitre(s)</span>
                    </div>
                    @if($lessons->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">📚</div>
                            <p class="text-slate-600 font-medium">Aucun chapitre pour cette section</p>
                            <p class="text-sm text-slate-500 mt-2">Commencez par ajouter votre premier chapitre ci-dessus</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($lessons as $index => $lesson)
                                <div class="rounded-2xl border border-slate-200 bg-white/70 p-5 hover:shadow-md transition">
                                    <div class="flex flex-col lg:flex-row gap-4">
                                        <!-- Main Content -->
                                        <div class="flex-1">
                                            <div class="flex items-start gap-3 mb-3">
                                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-sm font-bold">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-slate-900 text-lg mb-1">{{ $lesson->title }}</h4>
                                                    <div class="text-sm text-slate-600 line-clamp-2">{!! Str::limit(strip_tags($lesson->content), 120) !!}</div>
                                                </div>
                                            </div>

                                            <!-- Indicators -->
                                            <div class="flex flex-wrap gap-2 ml-11">
                                                @if($lesson->video_url)
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-medium">
                                                        🎥 Vidéo
                                                    </span>
                                                @endif
                                                @if($lesson->resources->count() > 0)
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-sky-100 text-sky-700 rounded-full text-xs font-medium">
                                                        📎 {{ $lesson->resources->count() }} ressource(s)
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex flex-col gap-2 lg:w-auto">
                                            <div class="flex flex-wrap gap-2">
                                                <a 
                                                    href="{{ route('lessons.edit', ['lessonId' => $lesson->id]) }}"
                                                    class="px-4 py-2 rounded-full bg-amber-400 text-white text-sm font-semibold hover:bg-amber-500 transition"
                                                >
                                                    ✏️ Éditer
                                                </a>
                                                <a 
                                                    href="{{ route('resources.manage', ['lessonId' => $lesson->id]) }}" 
                                                    class="px-4 py-2 rounded-full bg-sky-500 text-white text-sm font-semibold hover:bg-sky-600 transition"
                                                >
                                                    📎 Ressources
                                                </a>
                                            </div>
                                            <div class="flex flex-wrap gap-2">
                                                <a 
                                                    href="{{ route('quiz.manage', ['moduleId' => $module->id]) }}" 
                                                    class="px-4 py-2 rounded-full bg-indigo-500 text-white text-sm font-semibold hover:bg-indigo-600 transition"
                                                >
                                                    ✏️ Quiz
                                                </a>
                                                <button 
                                                    wire:click="delete({{ $lesson->id }})" 
                                                    onclick="return confirm('Supprimer ce chapitre ?')"
                                                    class="px-4 py-2 rounded-full bg-rose-500/10 text-rose-700 text-sm font-semibold hover:bg-rose-500/20 transition"
                                                >
                                                    🗑️
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="glass-card rounded-3xl p-6">
                    <h3 class="font-display text-xl mb-3">Roadmap</h3>
                    <div class="space-y-3 text-sm">
                        <a href="{{ route('courses.builder.edit', ['courseId' => $module->course_id]) }}" class="rounded-2xl border border-slate-200 bg-white/70 p-4 hover:bg-white transition block">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Etape 1</div>
                            <div class="font-semibold text-slate-900">Infos du cours</div>
                            <div class="text-slate-600">Titre, description, objectifs.</div>
                        </a>
                        <a href="{{ route('modules.manage', ['courseId' => $module->course_id]) }}" class="rounded-2xl border border-slate-200 bg-white/70 p-4 hover:bg-white transition block">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Etape 2</div>
                            <div class="font-semibold text-slate-900">Sections</div>
                            <div class="text-slate-600">Structure du cours.</div>
                        </a>
                        <div class="rounded-2xl border border-slate-200 bg-white/70 p-4">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Etape 3</div>
                            <div class="font-semibold text-slate-900">Chapitres ou Quiz</div>
                            <div class="text-slate-600">Ajoutez un chapitre ou un quiz directement.</div>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <a href="{{ route('lessons.create', ['moduleId' => $module->id]) }}" class="px-3 py-2 rounded-full text-xs font-semibold border border-slate-200 hover:bg-white transition">Ajouter un chapitre</a>
                                @if($lessons->isNotEmpty())
                                    <a href="{{ route('quiz.manage', ['moduleId' => $module->id]) }}" class="px-3 py-2 rounded-full text-xs font-semibold border border-slate-200 hover:bg-white transition">Creer un quiz</a>
                                @else
                                    <span class="text-xs text-slate-500">Quiz dispo apres creation d'un chapitre</span>
                                @endif
                            </div>
                        </div>
                        @if($lessons->isNotEmpty())
                            <div class="rounded-2xl border border-slate-200 bg-white/70 p-4">
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Etape 4</div>
                                <div class="font-semibold text-slate-900">Quiz & ressources</div>
                                <div class="text-slate-600">Choisir un chapitre.</div>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($lessons as $lesson)
                                        <a href="{{ route('quiz.manage', ['moduleId' => $module->id]) }}" class="px-3 py-2 rounded-full text-xs font-semibold border border-slate-200 hover:bg-white transition">Quiz</a>
                                        <a href="{{ route('resources.manage', ['lessonId' => $lesson->id]) }}" class="px-3 py-2 rounded-full text-xs font-semibold border border-slate-200 hover:bg-white transition">Ressources</a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-white/70 p-4 text-slate-500">
                                Creez un chapitre pour activer l'etape 4.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>