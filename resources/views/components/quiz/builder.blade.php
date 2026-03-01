<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Formateur</div>
                <h2 class="font-display text-4xl">{{ $isCourseEvaluation ? 'Évaluation finale' : 'Gestion du quiz' }}</h2>
                <p class="text-slate-600 mt-2">
                    {{ $isCourseEvaluation ? 'Cours : ' . $course->title : 'Section : ' . $module->title }}
                </p>
            </div>
            <a href="{{ $isCourseEvaluation ? route('courses.builder.edit', ['courseId' => $course->id]) : route('lessons.manage', ['moduleId' => $module->id]) }}" class="inline-flex items-center gap-2 px-5 py-2 bg-white/80 hover:bg-white border border-slate-200 rounded-full text-sm font-medium text-slate-700 transition shadow-sm">
                {{ $isCourseEvaluation ? '← Retour au cours' : '← Retour aux chapitres' }}
            </a>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-100/80 border border-emerald-300 rounded-2xl text-emerald-700 text-sm">
                ✓ {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 bg-rose-100/80 border border-rose-300 rounded-2xl text-rose-700 text-sm">
                ✕ {{ session('error') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <!-- Main Content -->
            <div class="space-y-6">
                <!-- Quiz Setup -->
                <div id="quiz-params" class="glass-card rounded-3xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-display text-2xl">Etape 1 · Configuration du quiz</h3>
                        <span class="chip px-3 py-1 rounded-full text-xs">Paramètres</span>
                    </div>

                    <form wire:submit.prevent="saveQuiz" class="grid gap-4">
                        <div>
                            <label class="block text-sm font-medium">Titre du quiz</label>
                            <input 
                                type="text" 
                                wire:model="quizTitle"
                                placeholder="Ex: Quiz Routing Laravel"
                                class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                            >
                            @error('quizTitle') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Score minimum (%)</label>
                                <input 
                                    type="number" 
                                    wire:model="minScore"
                                    min="0"
                                    max="100"
                                    class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                >
                                @error('minScore') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Essais maximum</label>
                                <input 
                                    type="number" 
                                    wire:model="maxAttempts"
                                    min="1"
                                    class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                >
                                @error('maxAttempts') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button 
                                type="submit"
                                class="bg-slate-900 text-white px-5 py-2 rounded-full font-semibold"
                            >
                                Valider
                            </button>
                            <a
                                href="{{ $isCourseEvaluation ? route('courses.builder.edit', ['courseId' => $course->id]) : route('quiz.manage', ['moduleId' => $module->id]) }}"
                                class="bg-slate-200 text-slate-700 px-5 py-2 rounded-full font-semibold hover:bg-slate-300 transition"
                            >
                                Annuler
                            </a>
                            @if ($quiz)
                                <button 
                                    type="button"
                                    wire:click="deleteQuiz"
                                    onclick="return confirm('Supprimer ce quiz et toutes ses questions ?')"
                                    class="bg-rose-500 text-white px-5 py-2 rounded-full font-semibold hover:bg-rose-600 transition"
                                >
                                    Supprimer
                                </button>
                            @endif
                        </div>
                    </form>
                </div>

                @if ($quiz)
                    <!-- Add Question -->
                    <div id="add-question" class="glass-card rounded-3xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-display text-2xl">Etape 2 · Ajouter une question</h3>
                            <span class="chip px-3 py-1 rounded-full text-xs">Questions</span>
                        </div>
                        
                        <form wire:submit.prevent="addQuestion" class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium">Texte de la question</label>
                                <textarea 
                                    wire:model="newQuestion"
                                    placeholder="Entrez votre question..."
                                    rows="2"
                                    class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                ></textarea>
                                @error('newQuestion') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button 
                                    type="submit"
                                    class="bg-slate-900 text-white px-5 py-2 rounded-full font-semibold"
                                >
                                    + Ajouter question
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('newQuestion', '')"
                                    class="bg-slate-200 text-slate-700 px-5 py-2 rounded-full font-semibold hover:bg-slate-300 transition"
                                >
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Questions List -->
                    <div id="questions-list" class="glass-card rounded-3xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-display text-2xl">Etape 3 · Reponses</h3>
                            <span class="chip px-3 py-1 rounded-full text-xs">{{ count($questions) }}</span>
                        </div>

                        @if (count($questions) === 0)
                            <p class="text-slate-600">Aucune question. Commencez par en ajouter une.</p>
                        @else
                            <ul class="divide-y divide-slate-200">
                                @foreach ($questions as $questionIndex => $question)
                                    <li class="py-4">
                                        <!-- Question Header -->
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                @if ($editingQuestionId === $questionIndex)
                                                    <textarea 
                                                        wire:model="questions.{{ $questionIndex }}.content"
                                                        rows="2"
                                                        class="w-full border-slate-200 rounded-xl px-4 py-2"
                                                    ></textarea>
                                                @else
                                                    <div class="font-semibold text-slate-900">{{ $question['content'] }}</div>
                                                @endif
                                            </div>
                                            <div class="flex gap-2 ml-3">
                                                @if ($editingQuestionId === $questionIndex)
                                                    <button 
                                                        type="button"
                                                        wire:click="updateQuestion({{ $questionIndex }})"
                                                        class="px-3 py-1 bg-emerald-500 text-white rounded-full text-sm hover:bg-emerald-600 transition"
                                                    >
                                                        Valider
                                                    </button>
                                                    <button 
                                                        type="button"
                                                        wire:click="clearEditingQuestion"
                                                        class="px-3 py-1 bg-slate-400 text-white rounded-full text-sm hover:bg-slate-500 transition"
                                                    >
                                                        Annuler
                                                    </button>
                                                @else
                                                    <button 
                                                        type="button"
                                                        wire:click="setEditingQuestion({{ $questionIndex }})"
                                                        class="px-3 py-1 bg-amber-400 text-white rounded-full text-sm hover:bg-amber-500 transition"
                                                    >
                                                        Editer
                                                    </button>
                                                    <button 
                                                        type="button"
                                                        wire:click="deleteQuestion({{ $questionIndex }})"
                                                        onclick="return confirm('Supprimer cette question ?')"
                                                        class="px-3 py-1 bg-rose-500 text-white rounded-full text-sm hover:bg-rose-600 transition"
                                                    >
                                                        Supprimer
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Answers -->
                                        <div class="ml-4">
                                            <div class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Reponses ({{ count($question['answers']) }})</div>
                                            @forelse ($question['answers'] as $answerIndex => $answer)
                                                <div class="flex items-center gap-2 p-2 mb-2 rounded-lg bg-white/50 hover:bg-white/70 transition">
                                                    <button 
                                                        type="button"
                                                        wire:click="toggleCorrectAnswer({{ $questionIndex }}, {{ $answerIndex }})"
                                                        class="flex-shrink-0 w-5 h-5 rounded border-2 transition {{ $answer['is_correct'] ? 'bg-emerald-500 border-emerald-600' : 'bg-white border-slate-300' }}"
                                                        title="{{ $answer['is_correct'] ? 'Bonne réponse' : 'Mauvaise réponse' }}"
                                                    >
                                                        @if ($answer['is_correct'])
                                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                        @endif
                                                    </button>
                                                    <input 
                                                        type="text"
                                                        wire:model="questions.{{ $questionIndex }}.answers.{{ $answerIndex }}.content"
                                                        wire:change="updateAnswer({{ $questionIndex }}, {{ $answerIndex }})"
                                                        class="flex-1 px-2 py-1 border-0 bg-transparent text-slate-900 focus:outline-none text-sm"
                                                    >
                                                    <button 
                                                        type="button"
                                                        wire:click="deleteAnswer({{ $questionIndex }}, {{ $answerIndex }})"
                                                        onclick="return confirm('Supprimer cette réponse ?')"
                                                        class="text-rose-500 hover:text-rose-700 transition"
                                                    >
                                                        🗑
                                                    </button>
                                                </div>
                                            @empty
                                                <p class="text-slate-500 text-xs italic">Aucune reponse</p>
                                            @endforelse

                                            @if ($editingQuestionId === $questionIndex)
                                                <div class="mt-3 p-3 bg-white/50 rounded-lg border border-slate-200">
                                                    <div class="flex gap-2 mb-2">
                                                        <input 
                                                            type="text"
                                                            wire:model="newAnswerText"
                                                            placeholder="Nouvelle réponse..."
                                                            class="flex-1 px-3 py-2 border-slate-200 rounded-lg text-sm"
                                                        >
                                                        <label class="flex items-center gap-2 px-3 py-2 border-slate-200 rounded-lg text-sm cursor-pointer">
                                                            <input 
                                                                type="checkbox"
                                                                wire:model="newAnswerCorrect"
                                                            >
                                                            <span>Correcte</span>
                                                        </label>
                                                    </div>
                                                    <div class="flex flex-wrap gap-2">
                                                        <button 
                                                            type="button"
                                                            wire:click="addAnswer({{ $questionIndex }})"
                                                            class="px-3 py-2 bg-slate-900 text-white rounded-lg text-sm font-semibold"
                                                        >
                                                            + Ajouter reponse
                                                        </button>
                                                        <button 
                                                            type="button"
                                                            wire:click="clearEditingQuestion"
                                                            class="px-3 py-2 bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold hover:bg-slate-300 transition"
                                                        >
                                                            Fermer
                                                        </button>
                                                    </div>
                                                    @error('newAnswerText') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar: Roadmap -->
            <div class="space-y-6">
                <div class="glass-card rounded-3xl p-6">
                    <h3 class="font-display text-xl mb-3">Roadmap</h3>
                    <div class="space-y-3 text-sm">
                        <a href="#quiz-params" class="rounded-2xl border border-slate-200 bg-white/70 p-4 hover:bg-white transition block">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Etape 1</div>
                            <div class="font-semibold text-slate-900">Configuration</div>
                            <div class="text-slate-600">Titre, seuil, essais.</div>
                        </a>
                        @if ($quiz)
                            <a href="#add-question" class="rounded-2xl border border-slate-200 bg-white/70 p-4 hover:bg-white transition block">
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Etape 2</div>
                                <div class="font-semibold text-slate-900">Questions</div>
                                <div class="text-slate-600">Ajouter et organiser.</div>
                            </a>
                            <div class="rounded-2xl border border-slate-200 bg-white/70 p-4">
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Etape 3</div>
                                <div class="font-semibold text-slate-900">Reponses</div>
                                <div class="text-slate-600">{{ count($questions) }} question(s)</div>
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-white/70 p-4 text-slate-500">
                                Créez {{ $isCourseEvaluation ? 'l’évaluation finale' : 'le quiz' }} pour activer l'étape 2.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
