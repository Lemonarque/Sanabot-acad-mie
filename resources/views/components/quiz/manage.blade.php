<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-4xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Formateur</div>
                <h2 class="font-display text-4xl">Quiz</h2>
                <p class="text-slate-600 mt-2">Section : {{ $module->title }}</p>
            </div>
            <a href="{{ route('lessons.manage', ['moduleId' => $module->id]) }}" class="inline-flex items-center gap-2 px-5 py-2 bg-white/80 hover:bg-white border border-slate-200 rounded-full text-sm font-medium text-slate-700 transition shadow-sm">
                ← Retour aux chapitres
            </a>
        </div>

        <div class="glass-card rounded-3xl p-6 mb-8">
            <form wire:submit.prevent="save" class="grid gap-4">
                <div>
                    <label class="block text-sm font-medium">Titre du quiz</label>
                    <input type="text" wire:model.defer="title" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2" required>
                    @error('title') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-slate-900 text-white px-5 py-2 rounded-full font-semibold">{{ $editing ? 'Mettre a jour' : 'Creer' }}</button>
                    <a href="{{ route('lessons.manage', ['moduleId' => $module->id]) }}" class="bg-slate-200 text-slate-700 px-5 py-2 rounded-full font-semibold hover:bg-slate-300 transition">Annuler</a>
                    @if($editing)
                        <button type="button" wire:click="deleteQuiz" class="bg-rose-500 text-white px-5 py-2 rounded-full font-semibold" onclick="return confirm('Supprimer ce quiz ?')">Supprimer</button>
                    @endif
                </div>
            </form>
        </div>

        @if($quiz)
            <div class="glass-card rounded-3xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-display text-2xl">Questions du quiz de section</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('quiz.builder', ['moduleId' => $module->id]) }}" class="px-4 py-2 rounded-full bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">Gérer le quiz</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
