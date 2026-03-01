<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-4xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Formateur</div>
                <h2 class="font-display text-4xl">Quiz</h2>
                <p class="text-slate-600 mt-2">Chapitre : {{ $lesson->title }}</p>
            </div>
            <a href="{{ url()->previous() }}" class="chip px-4 py-2 rounded-full text-sm">Retour au chapitre</a>
        </div>

        <div class="glass-card rounded-3xl p-6 mb-8">
            <form wire:submit.prevent="save" class="grid gap-4">
                <div>
                    <label class="block text-sm font-medium">Titre du quiz</label>
                    <input type="text" wire:model.defer="title" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2" required>
                    @error('title') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-slate-900 text-white px-5 py-2 rounded-full font-semibold">{{ $editing ? 'Mettre à jour' : 'Créer' }}</button>
                    @if($editing)
                        <button type="button" wire:click="deleteQuiz" class="bg-rose-500 text-white px-5 py-2 rounded-full font-semibold" onclick="return confirm('Supprimer ce quiz ?')">Supprimer</button>
                    @endif
                </div>
            </form>
        </div>

        @if($quiz)
            <div class="glass-card rounded-3xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-display text-2xl">Questions du quiz</h3>
                    <a href="{{ route('quiz.questions.manage', ['quizId' => $quiz->id]) }}" class="px-4 py-2 rounded-full bg-slate-900 text-white font-semibold">Gérer les questions</a>
                </div>
            </div>
        @endif
    </div>
</div>
