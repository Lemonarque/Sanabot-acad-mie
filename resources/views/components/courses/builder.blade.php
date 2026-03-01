<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Formateur</div>
                <h2 class="font-display text-4xl">Gestionnaire de Cours</h2>
                <p class="text-slate-600 mt-2">{{ $course ? 'Cours : ' . $course->title : 'Création d\'un nouveau cours' }}</p>
            </div>
            <a href="{{ route('courses.manage') }}" class="inline-flex items-center gap-2 px-5 py-2 bg-white/80 hover:bg-white border border-slate-200 rounded-full text-sm font-medium text-slate-700 transition shadow-sm">
                ← Retour à la liste
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
                <!-- Course Settings -->
                <div id="course-params" class="glass-card rounded-3xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-display text-2xl">Etape 1 · Parametres du cours</h3>
                        <span class="chip px-3 py-1 rounded-full text-xs">Configuration</span>
                    </div>

                    @if ($editing)
                        <form wire:submit.prevent="saveCourse" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium">Titre du cours</label>
                                <input 
                                    type="text" 
                                    wire:model="courseTitle"
                                    placeholder="Titre du cours"
                                    class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                >
                                @error('courseTitle') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Description</label>
                                <textarea 
                                    wire:model="courseDescription"
                                    placeholder="Description détaillée du cours"
                                    rows="2"
                                    class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                ></textarea>
                                @error('courseDescription') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Objectifs (optionnel)</label>
                                <textarea 
                                    wire:model="courseObjectives"
                                    placeholder="Objectifs pédagogiques"
                                    rows="2"
                                    class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Image de présentation (optionnel)</label>
                                <input
                                    type="file"
                                    wire:model="courseImageFile"
                                    accept="image/*,.jpg,.jpeg,.png,.webp,.gif,.bmp,.svg,.avif,.heic,.heif"
                                    class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                >
                                <p class="text-xs text-slate-500 mt-1">Formats: JPG, PNG, WEBP, GIF, BMP, SVG, AVIF, HEIC/HEIF · Max 5 MB</p>
                                @error('courseImageFile') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                <div wire:loading wire:target="courseImageFile" class="text-xs text-slate-500 mt-1">Téléchargement de l'image...</div>

                                @if($course && $course->presentation_image_url)
                                    <p class="text-xs text-slate-500 mt-2">Image actuelle enregistrée :</p>
                                    <div class="mt-2 rounded-2xl overflow-hidden border border-slate-200">
                                        <img src="{{ $course->presentation_image_url }}" alt="Image actuelle du cours" class="w-full h-44 object-cover">
                                    </div>
                                @endif
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium">Catégorie</label>
                                    <select 
                                        wire:model="courseCategory"
                                        class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                    >
                                        <option value="">Sélectionner</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('courseCategory') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Niveau</label>
                                    <select 
                                        wire:model="courseLevel"
                                        class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                    >
                                        <option value="beginner">Débutant</option>
                                        <option value="intermediate">Intermédiaire</option>
                                        <option value="advanced">Avancé</option>
                                    </select>
                                    @error('courseLevel') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium">Langue</label>
                                    <input 
                                        type="text" 
                                        wire:model="language"
                                        placeholder="fr, en, es..."
                                        class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                    >
                                    @error('language') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Durée totale (min)</label>
                                    <input 
                                        type="number" 
                                        wire:model="totalDurationMinutes"
                                        min="0"
                                        class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                    >
                                    @error('totalDurationMinutes') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Public cible (optionnel)</label>
                                <input 
                                    type="text" 
                                    wire:model="targetAudience"
                                    placeholder="Ex: Développeurs débutants"
                                    class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                >
                            </div>

                            <!-- Pricing Section -->
                            <div class="mt-6 pt-4 border-t border-slate-200">
                                <label class="flex items-center gap-3 cursor-pointer mb-4">
                                    <input 
                                        type="checkbox" 
                                        wire:model="isPaid"
                                        class="rounded border-slate-200"
                                    >
                                    <span class="font-semibold text-slate-900">Ce cours est payant</span>
                                </label>

                                @if($isPaid)
                                    <div>
                                        <label class="block text-sm font-medium">Prix (XOF)</label>
                                        <input 
                                            type="number" 
                                            wire:model="coursePrice"
                                            min="100"
                                            step="100"
                                            placeholder="Entrez le prix"
                                            class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                        >
                                        <p class="text-xs text-slate-500 mt-1">Prix minimum: 100 XOF</p>
                                        @error('coursePrice') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                <button 
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="saveCourse,courseImageFile"
                                    class="bg-slate-900 text-white px-5 py-2 rounded-full font-semibold"
                                >
                                    Valider
                                </button>
                                <button 
                                    type="button"
                                    wire:click="cancelEditCourse"
                                    class="bg-slate-200 text-slate-700 px-5 py-2 rounded-full font-semibold"
                                >
                                    Annuler
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="space-y-4">
                            <div>
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Titre</div>
                                <div class="font-semibold text-slate-900">{{ $courseTitle ?: '(non défini)' }}</div>
                            </div>
                            <div>
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Description</div>
                                <div class="text-slate-600 text-sm">{{ $courseDescription ?: '(non défini)' }}</div>
                            </div>
                            <div>
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Image de présentation</div>
                                @if($course && $course->presentation_image_url)
                                    <div class="mt-2 rounded-2xl overflow-hidden border border-slate-200">
                                        <img src="{{ $course->presentation_image_url }}" alt="Image du cours" class="w-full h-40 object-cover">
                                    </div>
                                @else
                                    <div class="text-slate-600 text-sm">(aucune image)</div>
                                @endif
                            </div>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Niveau</div>
                                    <div class="font-semibold text-slate-900 capitalize">
                                        @switch($courseLevel)
                                            @case('beginner')
                                                Débutant
                                                @break
                                            @case('intermediate')
                                                Intermédiaire
                                                @break
                                            @case('advanced')
                                                Avancé
                                                @break
                                            @default
                                                {{ $courseLevel }}
                                        @endswitch
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Langue</div>
                                    <div class="font-semibold text-slate-900 uppercase">{{ $language }}</div>
                                </div>
                            </div>
                            
                            <!-- Pricing Display -->
                            <div class="mt-4 pt-4 border-t border-slate-200">
                                @if($isPaid)
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-semibold text-slate-600">💰 Cours payant:</span>
                                        <span class="chip px-3 py-1 rounded-full text-sm font-bold" style="background: linear-gradient(135deg, var(--teal), var(--mint)); color: white;">
                                            {{ number_format($coursePrice, 0, ',', ' ') }} XOF
                                        </span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-semibold text-slate-600">✓ Cours gratuit</span>
                                        <span class="chip px-3 py-1 rounded-full text-xs bg-emerald-100/80 text-emerald-700">Accès libre</span>
                                    </div>
                                @endif
                            </div>

                            <button 
                                type="button"
                                wire:click="startEditCourse"
                                class="bg-amber-400 text-white px-5 py-2 rounded-full font-semibold hover:bg-amber-500 transition"
                            >
                                Editer
                            </button>
                        </div>
                    @endif
                </div>

                @if ($course)
                    <!-- Add Module -->
                    <div id="add-module" class="glass-card rounded-3xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-display text-2xl">Etape 2 · Creer une section</h3>
                            <span class="chip px-3 py-1 rounded-full text-xs">Structure du cours</span>
                        </div>

                        <form wire:submit.prevent="addModule" class="grid gap-4">
                            <div>
                                <label class="block text-sm font-medium">Titre de la section</label>
                                <input 
                                    type="text"
                                    wire:model="newModuleTitle"
                                    placeholder="Titre de la section"
                                    class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                >
                                @error('newModuleTitle') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Description</label>
                                <textarea 
                                    wire:model="newModuleDescription"
                                    placeholder="Description de la section"
                                    rows="2"
                                    class="mt-1 block w-full border-slate-200 rounded-xl px-4 py-2"
                                ></textarea>
                                @error('newModuleDescription') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <button 
                                type="submit"
                                class="w-full bg-slate-900 text-white px-5 py-2 rounded-full font-semibold"
                            >
                                + Ajouter section
                            </button>
                        </form>
                    </div>

                    <!-- Modules List -->
                    <div id="modules-list" class="glass-card rounded-3xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-display text-2xl">Etape 3 · Liste des sections</h3>
                            <span class="chip px-3 py-1 rounded-full text-xs">{{ count($modules) }}</span>
                        </div>

                        @if (count($modules) > 0)
                            <ul class="divide-y divide-slate-200">
                                @foreach ($modules as $index => $module)
                                    <li class="py-4">
                                        @if ($editingModuleId === $module['id'])
                                            <!-- Edit Mode -->
                                            <form wire:submit.prevent="updateModule" class="space-y-3">
                                                <input 
                                                    type="text"
                                                    wire:model="editModuleTitle"
                                                    class="w-full border-slate-200 rounded-xl px-4 py-2"
                                                >
                                                <textarea 
                                                    wire:model="editModuleDescription"
                                                    rows="2"
                                                    class="w-full border-slate-200 rounded-xl px-4 py-2"
                                                ></textarea>
                                                <div class="flex gap-2">
                                                    <button type="submit" class="bg-emerald-500 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-emerald-600 transition">
                                                        Enregistrer
                                                    </button>
                                                    <button 
                                                        type="button"
                                                        wire:click="$set('editingModuleId', null)"
                                                        class="bg-slate-400 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-slate-500 transition"
                                                    >
                                                        Annuler
                                                    </button>
                                                </div>
                                            </form>
                                        @else
                                            <!-- View Mode -->
                                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-3">
                                                <div>
                                                    <div class="font-semibold text-slate-900">{{ $module['title'] }}</div>
                                                    <div class="text-sm text-slate-600">{{ $module['description'] }}</div>
                                                    <span class="chip px-3 py-1 rounded-full text-xs mt-2 inline-block">{{ $module['lessonsCount'] }} chapitre(s)</span>
                                                </div>
                                            </div>

                                            <div class="flex flex-wrap gap-2">
                                                <a 
                                                    href="{{ route('lessons.manage', ['moduleId' => $module['id']]) }}"
                                                    class="px-4 py-2 rounded-full bg-sky-500 text-white font-semibold hover:bg-sky-600 transition"
                                                >
                                                    Gerer chapitres
                                                </a>
                                                <button 
                                                    type="button"
                                                    wire:click="editModule({{ $module['id'] }})"
                                                    class="px-4 py-2 rounded-full bg-amber-400 text-white font-semibold hover:bg-amber-500 transition"
                                                >
                                                    Editer
                                                </button>
                                                @if ($index > 0)
                                                    <button 
                                                        type="button"
                                                        wire:click="moveModule({{ $module['id'] }}, 'up')"
                                                        class="px-3 py-2 rounded-full bg-slate-400 text-white text-sm hover:bg-slate-500 transition"
                                                    >
                                                        ↑
                                                    </button>
                                                @endif
                                                @if ($index < count($modules) - 1)
                                                    <button 
                                                        type="button"
                                                        wire:click="moveModule({{ $module['id'] }}, 'down')"
                                                        class="px-3 py-2 rounded-full bg-slate-400 text-white text-sm hover:bg-slate-500 transition"
                                                    >
                                                        ↓
                                                    </button>
                                                @endif
                                                <button 
                                                    type="button"
                                                    wire:click="deleteModule({{ $module['id'] }})"
                                                    onclick="return confirm('Supprimer cette section ?')"
                                                    class="px-4 py-2 rounded-full bg-rose-500 text-white font-semibold hover:bg-rose-600 transition ml-auto"
                                                >
                                                    Supprimer
                                                </button>
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-slate-600">Aucune section. Commencez par en ajouter une.</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar: Roadmap & Stats -->
            <div class="space-y-6">
                <!-- Stats -->
                <div class="grid gap-3">
                    <div class="glass-card rounded-3xl p-4">
                        <div class="text-3xl font-bold text-sky-600">{{ $stats['modules'] }}</div>
                        <div class="text-sm text-slate-600">Sections</div>
                    </div>
                    <div class="glass-card rounded-3xl p-4">
                        <div class="text-3xl font-bold text-emerald-600">{{ $stats['lessons'] }}</div>
                        <div class="text-sm text-slate-600">Chapitres</div>
                    </div>
                    <div class="glass-card rounded-3xl p-4">
                        <div class="text-3xl font-bold text-indigo-600">{{ $stats['quizzes'] }}</div>
                        <div class="text-sm text-slate-600">Quiz</div>
                    </div>
                </div>

                <!-- Roadmap -->
                <div class="glass-card rounded-3xl p-6">
                    <h3 class="font-display text-xl mb-3">Roadmap</h3>
                    <div class="space-y-3 text-sm">
                        <a href="#course-params" class="rounded-2xl border border-slate-200 bg-white/70 p-4 hover:bg-white transition block">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Etape 1</div>
                            <div class="font-semibold text-slate-900">Configuration</div>
                            <div class="text-slate-600">Titre, description, niveau.</div>
                        </a>
                        @if ($course)
                            <a href="#add-module" class="rounded-2xl border border-slate-200 bg-white/70 p-4 hover:bg-white transition block">
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Etape 2</div>
                                <div class="font-semibold text-slate-900">Sections</div>
                                <div class="text-slate-600">Ajouter et ordonner.</div>
                            </a>
                            <div class="rounded-2xl border border-slate-200 bg-white/70 p-4">
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Etape 3</div>
                                <div class="font-semibold text-slate-900">Chapitres</div>
                                <div class="text-slate-600">{{ $stats['lessons'] }} chapitre(s)</div>
                            </div>
                            <a href="{{ route('quiz.course.builder', ['courseId' => $course->id]) }}" class="rounded-2xl border border-slate-200 bg-white/70 p-4 hover:bg-white transition block">
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Etape 4</div>
                                <div class="font-semibold text-slate-900">Évaluation finale</div>
                                <div class="text-slate-600">Créer le quiz final du cours (indépendant des sections).</div>
                            </a>
                        @else
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-white/70 p-4 text-slate-500">
                                Créez le cours pour activer les étapes 2-4.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
