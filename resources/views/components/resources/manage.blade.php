<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-5xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Formateur</div>
                <h2 class="font-display text-4xl">Ressources</h2>
                <p class="text-slate-600 mt-2">Chapitre : {{ $lesson->title }}</p>
            </div>
            <a href="{{ route('lessons.manage', ['moduleId' => $lesson->module_id]) }}" class="inline-flex items-center gap-2 px-5 py-2 bg-white/80 hover:bg-white border border-slate-200 rounded-full text-sm font-medium text-slate-700 transition shadow-sm">
                ← Retour au chapitre
            </a>
        </div>

        <div class="glass-card rounded-3xl p-6 mb-8">
            <form wire:submit.prevent="save" class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Titre de la ressource</label>
                    <input type="text" wire:model.defer="title" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2" required>
                    @error('title') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">URL</label>
                    <input type="url" wire:model.defer="url" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2" required>
                    @error('url') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Type</label>
                    <select wire:model.defer="type" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2" required>
                        <option value="">Selectionner</option>
                        <option value="pdf">PDF</option>
                        <option value="video">Video</option>
                        <option value="lien">Lien</option>
                        <option value="autre">Autre</option>
                    </select>
                    @error('type') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="bg-slate-900 text-white px-5 py-2 rounded-full font-semibold">{{ $editingId ? 'Mettre a jour' : 'Ajouter' }}</button>
                    @if($editingId)
                        <button type="button" wire:click="cancelEdit" class="bg-slate-200 text-slate-700 px-5 py-2 rounded-full font-semibold">Annuler</button>
                    @endif
                </div>
            </form>
        </div>

        <div class="glass-card rounded-3xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display text-2xl">Liste des ressources</h3>
                <span class="chip px-3 py-1 rounded-full text-xs">{{ $resources->count() }} ressources</span>
            </div>
            @if($resources->isEmpty())
                <p class="text-slate-600">Aucune ressource pour ce chapitre.</p>
            @else
                <ul class="divide-y divide-slate-200">
                    @foreach($resources as $resource)
                        <li class="py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $resource->title }}</div>
                                <div class="text-sm text-slate-600">{{ $resource->type }} � <a href="{{ $resource->url }}" class="text-slate-900 underline" target="_blank">{{ $resource->url }}</a></div>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $resource->id }})" class="px-4 py-2 rounded-full bg-amber-400 text-white font-semibold hover:bg-amber-500 transition">Editer</button>
                                <button wire:click="delete({{ $resource->id }})" class="px-4 py-2 rounded-full bg-rose-500 text-white font-semibold hover:bg-rose-600 transition" onclick="return confirm('Supprimer cette ressource ?')">Supprimer</button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
