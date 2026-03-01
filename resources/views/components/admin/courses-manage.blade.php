<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div style="display:flex;gap:1.5rem;align-items:flex-start;">
            @include('components.admin.sidebar')

            <div>
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
                    <div>
                        <div class="text-xs uppercase tracking-[0.25em] text-slate-500">Administration</div>
                        <h2 class="font-display text-4xl text-slate-900">Gestion des cours</h2>
                        <p class="text-slate-600 mt-2">Validez, modifiez et structurez l'ensemble du catalogue.</p>
                    </div>
                </div>

                <div class="rounded-3xl p-6 mb-6 border border-purple-200" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.06), rgba(192, 132, 250, 0.04));">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-3">
                        <input type="text" wire:model.defer="search" placeholder="Rechercher un cours..." class="w-full lg:flex-1 border-slate-200 rounded-full px-5 py-2 focus:ring-2 focus:ring-emerald-200" />
                        <select wire:model.defer="status" class="rounded-full px-4 py-2 border-slate-200">
                            <option value="all">Tous les statuts</option>
                            <option value="pending">En attente</option>
                            <option value="approved">Valide</option>
                            <option value="rejected">Rejete</option>
                        </select>
                        <button wire:click="searchCourses" class="px-5 py-2 rounded-full text-white font-semibold transition" style="background: linear-gradient(135deg, var(--teal), var(--sky));">Filtrer</button>
                        <div class="text-sm text-slate-500">{{ $courses->count() }} cours</div>
                    </div>
                </div>

                <div class="rounded-3xl p-6 border border-purple-200" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.06), rgba(192, 132, 250, 0.04));">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-display text-2xl text-slate-900">Catalogue global</h3>
                        <span class="text-xs uppercase tracking-[0.2em] text-slate-400">Contenus</span>
                    </div>
                    @if($courses->isEmpty())
                        <p class="text-slate-600">Aucun cours pour le moment.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-xs uppercase tracking-[0.2em] text-slate-400 border-b border-slate-200">
                                        <th class="py-3">Cours</th>
                                        <th class="py-3">Categorie</th>
                                        <th class="py-3">Formateur</th>
                                        <th class="py-3">Sections</th>
                                        <th class="py-3">Inscrits</th>
                                        <th class="py-3">Prix</th>
                                        <th class="py-3">Carousel</th>
                                        <th class="py-3">Statut</th>
                                        <th class="py-3">Actif</th>
                                        <th class="py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach($courses as $course)
                                        <tr>
                                            <td class="py-4">
                                                <div class="font-semibold text-slate-900">{{ $course->title }}</div>
                                                <div class="text-sm text-slate-600">{{ $course->description }}</div>
                                            </td>
                                            <td class="py-4 text-slate-600">{{ $course->category?->name ?? '—' }}</td>
                                            <td class="py-4 text-slate-600">{{ $course->creator?->name ?? '—' }}</td>
                                            <td class="py-4 text-slate-600">{{ $course->modules_count }}</td>
                                            <td class="py-4 text-slate-600">{{ $course->enrollments_count }}</td>
                                            <td class="py-4">
                                                @if($course->is_paid)
                                                    <span class="chip px-3 py-1 rounded-full text-xs font-semibold" style="background: rgba(53, 167, 178, 0.12); color: var(--teal);">
                                                        💰 {{ number_format($course->price, 0, ',', ' ') }} XOF
                                                    </span>
                                                @else
                                                    <span class="chip px-3 py-1 rounded-full text-xs font-semibold" style="background: rgba(123, 191, 100, 0.15); color: var(--mint);">
                                                        ✓ Gratuit
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4">
                                                @if($course->show_on_home_carousel)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background: rgba(53, 167, 178, 0.12); color: var(--teal);">
                                                        Oui @if($course->home_carousel_order) (#{{ $course->home_carousel_order }}) @endif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background: rgba(148, 163, 184, 0.16); color: #64748b;">
                                                        Non
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background: rgba(53, 167, 178, 0.12); color: var(--teal);">
                                                    {{ $course->status }}
                                                </span>
                                            </td>
                                            <td class="py-4">
                                                @if($course->is_active)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background: rgba(123, 191, 100, 0.15); color: var(--mint);">Actif</span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background: rgba(240, 194, 100, 0.18); color: var(--amber);">Inactif</span>
                                                @endif
                                            </td>
                                            <td class="py-4">
                                                <div class="flex flex-wrap justify-end gap-2">
                                                    <button wire:click="setStatus({{ $course->id }}, 'approved')" class="px-3 py-2 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Valider</button>
                                                    <button wire:click="setStatus({{ $course->id }}, 'rejected')" class="px-3 py-2 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, #f08ea3, #f4c55e);">Rejeter</button>
                                                    <button wire:click="toggleActive({{ $course->id }})" class="px-3 py-2 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--sky));">
                                                        {{ $course->is_active ? 'Desactiver' : 'Activer' }}
                                                    </button>
                                                    <button wire:click="toggleHomeCarousel({{ $course->id }})" class="px-3 py-2 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, var(--sky), #6366f1);">
                                                        {{ $course->show_on_home_carousel ? 'Retirer carousel' : 'Ajouter carousel' }}
                                                    </button>
                                                    <button wire:click="edit({{ $course->id }})" class="px-3 py-2 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--sky));">Editer</button>
                                                    <button wire:click="delete({{ $course->id }})" class="px-3 py-2 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, #f08ea3, #f4c55e);" onclick="return confirm('Supprimer ce cours ?')">Supprimer</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                @if($editingId)
                    <div class="fixed inset-0 bg-slate-900/40 flex items-center justify-center z-50">
                        <div class="rounded-3xl p-8 w-full max-w-3xl max-h-[85vh] overflow-y-auto border border-purple-300" style="background: linear-gradient(135deg, rgba(248, 250, 252, 0.95), rgba(241, 245, 249, 0.9)), linear-gradient(135deg, rgba(168, 85, 247, 0.08), rgba(192, 132, 250, 0.05));">
                            <h4 class="font-display text-2xl mb-4">Modifier le cours</h4>
                            <form wire:submit.prevent="update" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium">Categorie</label>
                                    <select wire:model.defer="category_id" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2">
                                        <option value="">Selectionner</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Titre</label>
                                    <input type="text" wire:model.defer="title" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Description courte</label>
                                    <input type="text" wire:model.defer="short_description" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Description</label>
                                    <textarea wire:model.defer="description" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2" rows="3" required></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Description detaillee</label>
                                    <textarea wire:model.defer="detailed_description" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2" rows="3"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Objectifs</label>
                                    <textarea wire:model.defer="objectives" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2" rows="2"></textarea>
                                </div>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium">Public cible</label>
                                        <input type="text" wire:model.defer="target_audience" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Niveau</label>
                                        <input type="text" wire:model.defer="level" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Langue</label>
                                        <input type="text" wire:model.defer="language" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Duree totale (min)</label>
                                        <input type="number" min="0" wire:model.defer="total_duration_minutes" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2">
                                    </div>
                                </div>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium">Certification active</label>
                                        <select wire:model.defer="certification_enabled" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2">
                                            <option value="1">Oui</option>
                                            <option value="0">Non</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Moyenne minimale</label>
                                        <input type="number" step="0.01" wire:model.defer="min_average" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Evaluation finale</label>
                                        <select wire:model.defer="final_evaluation_mode" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2">
                                            <option value="optional">Optionnelle</option>
                                            <option value="required">Obligatoire</option>
                                            <option value="none">Aucune</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Validation manuelle</label>
                                        <select wire:model.defer="manual_validation" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2">
                                            <option value="0">Non</option>
                                            <option value="1">Oui</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Mode de paiement</label>
                                        <select wire:model.defer="payment_mode" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2">
                                            <option value="module">Par section</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Pricing Section -->
                                <div class="border-t border-slate-200 pt-4 mt-4">
                                    <label class="flex items-center gap-3 cursor-pointer mb-4">
                                        <input 
                                            type="checkbox" 
                                            wire:model.defer="isPaid"
                                            class="rounded border-slate-200"
                                        >
                                        <span class="font-semibold text-slate-900">Ce cours est payant</span>
                                    </label>

                                    @if($isPaid)
                                        <div>
                                            <label class="block text-sm font-medium">Prix (XOF)</label>
                                            <input 
                                                type="number" 
                                                wire:model.defer="coursePrice"
                                                min="100"
                                                step="100"
                                                placeholder="Entrez le prix"
                                                class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                            >
                                            <p class="text-xs text-slate-500 mt-1">Prix minimum: 100 XOF</p>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Statut</label>
                                    <select wire:model.defer="courseStatus" class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2">
                                        <option value="pending">En attente</option>
                                        <option value="approved">Valide</option>
                                        <option value="rejected">Rejete</option>
                                    </select>
                                </div>

                                <div class="border-t border-slate-200 pt-4 mt-4">
                                    <label class="flex items-center gap-3 cursor-pointer mb-3">
                                        <input
                                            type="checkbox"
                                            wire:model.defer="showOnHomeCarousel"
                                            class="rounded border-slate-200"
                                        >
                                        <span class="font-semibold text-slate-900">Afficher dans le carousel de la page présentation</span>
                                    </label>

                                    @if($showOnHomeCarousel)
                                        <div>
                                            <label class="block text-sm font-medium">Ordre d'affichage (optionnel)</label>
                                            <input
                                                type="number"
                                                min="1"
                                                max="99"
                                                wire:model.defer="homeCarouselOrder"
                                                class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                            >
                                            <p class="text-xs text-slate-500 mt-1">Plus le chiffre est petit, plus le cours apparaît tôt.</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex gap-2 mt-4">
                                    <button type="submit" class="text-white px-4 py-2 rounded-full font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Enregistrer</button>
                                    <button type="button" wire:click="cancelEdit" class="bg-slate-200 text-slate-700 px-4 py-2 rounded-full font-semibold">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
