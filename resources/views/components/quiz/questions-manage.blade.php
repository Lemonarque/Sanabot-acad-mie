<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-4xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Formateur</div>
                <h2 class="font-display text-4xl">Questions</h2>
                <p class="text-slate-600 mt-2">Quiz : {{ $quiz->title }}</p>
            </div>
            <a href="{{ route('quiz.manage', ['moduleId' => $quiz->module_id]) }}" class="inline-flex items-center gap-2 px-5 py-2 bg-white/80 hover:bg-white border border-slate-200 rounded-full text-sm font-medium text-slate-700 transition shadow-sm">
                ← Retour au quiz
            </a>
        </div>

        <div class="glass-card rounded-3xl p-6 mb-8">
            <form wire:submit.prevent="save" class="grid gap-4">
                <div>
                    <label class="block text-sm font-medium">Intitule de la question</label>
                    <input type="text" wire:model.defer="content" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2" required>
                    @error('content') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-slate-900 text-white px-5 py-2 rounded-full font-semibold">{{ $editingId ? 'Mettre a jour' : 'Ajouter' }}</button>
                    <button type="button" wire:click="cancelEdit" class="bg-slate-200 text-slate-700 px-5 py-2 rounded-full font-semibold hover:bg-slate-300 transition">Annuler</button>
                </div>
            </form>
        </div>

        <div class="glass-card rounded-3xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display text-2xl">Liste des questions</h3>
                <span class="chip px-3 py-1 rounded-full text-xs">{{ $questions->count() }} questions</span>
            </div>
            @if($questions->isEmpty())
                <p class="text-slate-600">Aucune question pour ce quiz.</p>
            @else
                <ul class="divide-y divide-slate-200">
                    @foreach($questions as $question)
                        <li class="py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $question->content }}</div>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $question->id }})" class="px-4 py-2 rounded-full bg-amber-400 text-white font-semibold hover:bg-amber-500 transition">Editer</button>
                                <button wire:click="delete({{ $question->id }})" class="px-4 py-2 rounded-full bg-rose-500 text-white font-semibold hover:bg-rose-600 transition" onclick="return confirm('Supprimer cette question ?')">Supprimer</button>
                                <a href="{{ route('quiz.answers.manage', ['questionId' => $question->id]) }}" class="px-4 py-2 rounded-full bg-sky-500 text-white font-semibold hover:bg-sky-600 transition">Reponses</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
