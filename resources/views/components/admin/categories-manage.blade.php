<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div style="display:flex;gap:1.5rem;align-items:flex-start;">
            @include('components.admin.sidebar')

            <div>
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
                    <div>
                        <div class="text-xs uppercase tracking-[0.25em] text-slate-500">Administration</div>
                        <h2 class="font-display text-4xl text-slate-900">Gestion des categories</h2>
                        <p class="text-slate-600 mt-2">Structurez les formations par domaines.</p>
                    </div>
                </div>

                <div class="rounded-3xl p-6 mb-6 border border-green-200" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.06), rgba(74, 222, 128, 0.04));">
                    <div class="flex flex-col md:flex-row md:items-center gap-3">
                        <input type="text" wire:model.defer="name" placeholder="Nom de la categorie" class="w-full md:flex-1 border-slate-200 rounded-full px-5 py-2" />
                        <input type="text" wire:model.defer="description" placeholder="Description (optionnel)" class="w-full md:flex-1 border-slate-200 rounded-full px-5 py-2" />
                        <button wire:click="{{ $editingId ? 'update' : 'create' }}" class="px-5 py-2 rounded-full text-white font-semibold transition" style="background: linear-gradient(135deg, var(--teal), var(--mint));">
                            {{ $editingId ? 'Mettre a jour' : 'Ajouter' }}
                        </button>
                        @if($editingId)
                            <button wire:click="cancelEdit" class="px-5 py-2 rounded-full bg-slate-200 text-slate-700 font-semibold">Annuler</button>
                        @endif
                    </div>
                </div>

                <div class="rounded-3xl p-6 border border-green-200" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.06), rgba(74, 222, 128, 0.04));">
                    <h3 class="font-display text-2xl text-slate-900 mb-4">Categories existantes</h3>
                    @if($categories->isEmpty())
                        <p class="text-slate-600">Aucune categorie pour le moment.</p>
                    @else
                        <ul class="divide-y divide-slate-200">
                            @foreach($categories as $category)
                                <li class="py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <div class="font-semibold text-slate-900">{{ $category->name }}</div>
                                        <div class="text-sm text-slate-600">{{ $category->description }}</div>
                                        <div class="text-xs text-slate-400">Slug : {{ $category->slug }}</div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button wire:click="edit({{ $category->id }})" class="px-4 py-2 rounded-full bg-amber-400 text-white font-semibold hover:bg-amber-500 transition">Editer</button>
                                        <button wire:click="delete({{ $category->id }})" class="px-4 py-2 rounded-full bg-rose-500 text-white font-semibold hover:bg-rose-600 transition" onclick="return confirm('Supprimer cette categorie ?')">Supprimer</button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
