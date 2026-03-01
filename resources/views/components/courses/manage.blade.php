<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
            <div class="md:flex-1 md:pr-4">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Formateur</div>
                <h2 class="font-display text-4xl">Mes Formations</h2>
                <p class="text-slate-600 mt-2">Gérez et structurez vos parcours de formation</p>
            </div>
            <a href="{{ route('courses.builder') }}" class="px-6 py-3 bg-slate-900 text-white rounded-full font-semibold hover:bg-slate-800 transition inline-flex items-center gap-2 self-start md:self-auto">
                ➕ Créer un cours
            </a>
        </div>

        <!-- Success Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-100/80 border border-emerald-300 rounded-2xl text-emerald-700 text-sm">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="glass-card rounded-3xl p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-[1fr_280px] gap-4">
                <div>
                    <label class="block text-xs uppercase tracking-[0.2em] text-slate-500">Recherche</label>
                    <div class="mt-2 flex flex-col sm:flex-row gap-2">
                        <input
                            type="text"
                            wire:model.debounce.400ms="search"
                            wire:keydown.enter="searchCourses"
                            placeholder="Rechercher un cours ou une catégorie..."
                            class="block w-full border-slate-200 rounded-full px-4 py-2.5"
                        >
                        <button
                            type="button"
                            wire:click="searchCourses"
                            class="px-5 py-2.5 rounded-full text-white font-semibold transition bg-slate-900 hover:bg-slate-800"
                        >
                            Rechercher
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-[0.2em] text-slate-500">Catégorie</label>
                    <select wire:model.live="categoryId" class="mt-2 block w-full border-slate-200 rounded-full px-4 py-2.5">
                        <option value="">Toutes les catégories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Courses Grid -->
        @if ($courses->isEmpty())
            <div class="glass-card rounded-3xl p-12 text-center">
                <div class="text-6xl mb-4">📚</div>
                <h3 class="text-2xl font-semibold text-slate-900 mb-2">Aucun cours créé</h3>
                <p class="text-slate-600 mb-6">Commencez par créer votre première formation</p>
                <a href="{{ route('courses.builder') }}" class="inline-block px-6 py-3 bg-slate-900 text-white rounded-full font-semibold hover:bg-slate-800 transition">
                    ➕ Créer un cours
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach ($courses as $course)
                    <div class="glass-card rounded-3xl p-4 hover:shadow-lg transition flex flex-col gap-2.5">
                        <div class="relative rounded-2xl h-36 border border-slate-200 overflow-hidden">
                            @if($course->presentation_image_url)
                                <img
                                    src="{{ $course->presentation_image_url }}"
                                    alt="Image de présentation du cours {{ $course->title }}"
                                    class="absolute inset-0 w-full h-full object-contain bg-slate-100"
                                    loading="lazy"
                                >
                            @else
                                <div class="absolute inset-0 bg-gradient-to-br from-emerald-100 via-sky-50 to-amber-100 flex items-center justify-center text-slate-600 text-xs">
                                    Image de présentation
                                </div>
                            @endif

                            <div class="absolute top-2 right-2 flex items-center gap-1.5">
                                @if ($course->status === 'approved')
                                    <span class="chip px-1.5 py-0.5 rounded-full text-[10px] bg-emerald-100/95 text-emerald-700">Validé</span>
                                @elseif ($course->status === 'rejected')
                                    <span class="chip px-1.5 py-0.5 rounded-full text-[10px] bg-rose-100/95 text-rose-700">Rejeté</span>
                                @else
                                    <span class="chip px-1.5 py-0.5 rounded-full text-[10px] bg-amber-100/95 text-amber-700">En attente</span>
                                @endif
                                @if ($course->is_active)
                                    <span class="chip px-1.5 py-0.5 rounded-full text-[10px] bg-sky-100/95 text-sky-700">Actif</span>
                                @else
                                    <span class="chip px-1.5 py-0.5 rounded-full text-[10px] bg-slate-200/95 text-slate-700">Inactif</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-base font-semibold text-slate-900 line-clamp-1">{{ $course->title }}</h3>
                            <p class="text-sm text-slate-600 line-clamp-2 mt-1">{{ $course->description ?: 'Aucune description' }}</p>
                            <div class="text-xs text-slate-500 mt-1">{{ $course->category?->name ?? 'Sans catégorie' }}</div>
                        </div>

                        <div class="flex items-center gap-4 text-xs">
                            <div class="flex items-baseline gap-1.5">
                                <div class="text-base font-bold text-sky-600 leading-none">{{ $course->modules->count() }}</div>
                                <div class="text-slate-600">Sections</div>
                            </div>
                            <div class="flex items-baseline gap-1.5">
                                <div class="text-base font-bold text-indigo-600 leading-none">{{ $course->enrollments->count() }}</div>
                                <div class="text-slate-600">Apprenants</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <a 
                                href="{{ route('courses.builder.edit', ['courseId' => $course->id]) }}"
                                class="w-full px-2.5 py-2 bg-amber-400 text-white rounded-full text-xs font-semibold text-center hover:bg-amber-500 transition whitespace-nowrap"
                            >
                                Gérer
                            </a>
                            <a
                                href="{{ route('quiz.course.builder', ['courseId' => $course->id]) }}"
                                class="w-full px-2.5 py-2 bg-slate-900 text-white rounded-full text-xs font-semibold text-center hover:bg-slate-800 transition whitespace-nowrap"
                            >
                                Éval finale
                            </a>
                            <a
                                href="{{ route('courses.certificates.manage', ['courseId' => $course->id]) }}"
                                class="w-full px-2.5 py-2 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold text-center hover:bg-emerald-200 transition whitespace-nowrap"
                            >
                                Certificats
                            </a>
                            <button 
                                wire:click="delete({{ $course->id }})"
                                onclick="return confirm('Supprimer ce cours ?')"
                                class="w-full px-2.5 py-2 bg-rose-100/80 text-rose-700 rounded-full text-xs font-semibold text-center hover:bg-rose-200/80 transition whitespace-nowrap"
                            >
                                Supprimer
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
