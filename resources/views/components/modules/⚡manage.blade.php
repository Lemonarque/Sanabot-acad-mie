<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Formateur</div>
                <h2 class="font-display text-4xl">Sections</h2>
                <p class="text-slate-600 mt-2">Cours : {{ $course->title }}</p>
            </div>
            <a href="{{ url()->previous() }}" class="chip px-4 py-2 rounded-full text-sm">Retour aux cours</a>
        </div>

        <div class="glass-card rounded-3xl p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display text-2xl">Etape 2 · Creer une section</h3>
                <span class="chip px-3 py-1 rounded-full text-xs">Structure du cours</span>
            </div>
            <form wire:submit.prevent="{{ $editingId ? 'update' : 'create' }}" class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Titre de la section</label>
                    <input type="text" wire:model.defer="title" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2" required>
                    @error('title') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Description</label>
                    <textarea wire:model.defer="description" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2" required></textarea>
                    @error('description') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="bg-slate-900 text-white px-5 py-2 rounded-full font-semibold">{{ $editingId ? 'Mettre a jour' : 'Ajouter' }}</button>
                    @if($editingId)
                        <button type="button" wire:click="$reset('title', 'description', 'editingId')" class="bg-slate-200 text-slate-700 px-5 py-2 rounded-full font-semibold">Annuler</button>
                    @endif
                </div>
            </form>
        </div>

        <div class="glass-card rounded-3xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display text-2xl">Liste des sections</h3>
                <span class="chip px-3 py-1 rounded-full text-xs">{{ $modules->count() }} sections</span>
            </div>
            @if($modules->isEmpty())
                <p class="text-slate-600">Aucune section pour ce cours.</p>
            @else
                <ul class="divide-y divide-slate-200">
                    @foreach($modules as $module)
                        <li class="py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $module->title }}</div>
                                <div class="text-sm text-slate-600">{{ $module->description }}</div>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $module->id }})" class="px-4 py-2 rounded-full bg-amber-400 text-white font-semibold hover:bg-amber-500 transition">Editer</button>
                                <button wire:click="delete({{ $module->id }})" class="px-4 py-2 rounded-full bg-rose-500 text-white font-semibold hover:bg-rose-600 transition" onclick="return confirm('Supprimer cette section ?')">Supprimer</button>
                                <a href="{{ route('lessons.manage', ['moduleId' => $module->id]) }}" class="px-4 py-2 rounded-full bg-sky-500 text-white font-semibold hover:bg-sky-600 transition">Gerer chapitres</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
