<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="glass-card rounded-3xl p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="lg:flex-1 lg:pr-4">
                    <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Espace apprenant</div>
                    <h2 class="font-display text-4xl text-slate-900">Catalogue des cours</h2>
                    <p class="text-slate-600 mt-2 max-w-2xl">Parcourez les cours valides, explorez les sections et commencez un parcours adapte a vos objectifs.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="chip px-4 py-2 rounded-full text-xs">{{ $courses->count() }} cours</span>
                    <a href="{{ route('apprenant.dashboard') }}" class="chip px-4 py-2 rounded-full text-sm">Retour dashboard</a>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-[1fr_280px] gap-4">
                <div>
                    <label class="block text-xs uppercase tracking-[0.2em] text-slate-500">Recherche</label>
                    <div class="mt-2 flex items-center gap-2">
                        <input type="text" wire:model.defer="search" wire:keydown.enter="applyFilters" placeholder="Rechercher un cours, une section ou une catégorie..." class="block w-full border-slate-200 rounded-full px-4 py-2.5" />
                        <button type="button" wire:click="applyFilters" class="px-4 py-2 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition whitespace-nowrap">
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

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($courses as $course)
                @php
                    $chapters = $course->modules->sum(fn($m) => $m->lessons->count());
                @endphp
                <div class="glass-card rounded-3xl p-4 flex flex-col gap-3">
                    @if($course->presentation_image_url)
                        <div class="w-full rounded-2xl border border-slate-200 overflow-hidden" style="height: 11rem;">
                            <img
                                src="{{ $course->presentation_image_url }}"
                                alt="Image de présentation du cours {{ $course->title }}"
                                class="w-full h-full object-contain bg-slate-100"
                                loading="lazy"
                            >
                        </div>
                    @else
                        <div class="w-full rounded-2xl bg-gradient-to-br from-emerald-100 via-sky-50 to-amber-100 border border-slate-200 flex items-center justify-center text-slate-600 text-sm" style="height: 11rem;">
                            Apercu du cours
                        </div>
                    @endif

                    <div>
                        <div class="flex items-center justify-between gap-2.5">
                            <span class="text-[10px] uppercase tracking-[0.18em] text-slate-500">Cours</span>
                            <span class="chip px-2 py-0.5 rounded-full text-[10px]">Valide</span>
                        </div>
                        <h3 class="font-display text-base mt-1.5 text-slate-900 line-clamp-2 leading-snug">{{ $course->title }}</h3>
                        <p class="text-xs text-slate-600 mt-1 line-clamp-2">{{ $course->description }}</p>
                    </div>

                    <div class="flex flex-wrap gap-1 text-[11px]">
                        <span class="inline-flex items-center rounded-full bg-white/75 border border-slate-200 px-2 py-0.5 text-slate-600">
                            <span class="font-semibold text-slate-900 mr-1">{{ $course->modules->count() }}</span> Sections
                        </span>
                        <span class="inline-flex items-center rounded-full bg-white/75 border border-slate-200 px-2 py-0.5 text-slate-600">
                            <span class="font-semibold text-slate-900 mr-1">{{ $chapters }}</span> Chapitres
                        </span>
                        <span class="inline-flex items-center rounded-full bg-white/75 border border-slate-200 px-2 py-0.5 text-slate-600">
                            <span class="font-semibold text-slate-900 mr-1">{{ $course->enrollments_count }}</span> Inscrits
                        </span>
                    </div>

                    <div class="flex items-end justify-between gap-2.5 pt-2 border-t border-slate-100">
                        <div class="text-[11px] text-slate-500 min-w-0">
                            <span class="block mb-0.5 truncate">Formateur : <span class="font-semibold text-slate-700">{{ $course->creator->name ?? 'N/A' }}</span></span>
                            @if($course->is_paid && $course->price > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold" style="background: linear-gradient(135deg, rgba(53, 167, 178, 0.15), rgba(122, 230, 210, 0.15)); color: var(--teal);">
                                    💰 {{ number_format($course->price, 0, ',', ' ') }} XOF
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 rounded-full text-[11px] font-semibold text-emerald-700">
                                    🎁 Gratuit
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('apprenant.courses.show', $course->id) }}" class="shrink-0 px-4 py-2 rounded-full text-sm text-white font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Voir le cours</a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-slate-600">Aucun cours trouve.</div>
            @endforelse
        </div>
    </div>
</div>
