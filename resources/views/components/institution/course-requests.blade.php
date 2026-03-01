<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="mb-4">
            <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Institution</div>
            <h2 class="font-display text-3xl text-slate-900">Demandes cours · {{ $institution->name }}</h2>
            <p class="text-slate-600 mt-1">Gérez les demandes d'accès payant et d'ajustement tarifaire.</p>
        </div>

        @include('components.institution._nav', ['active' => 'course-requests'])

        @if($message)
            <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 text-sm">{{ $message }}</div>
        @endif
        @if($error)
            <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700 text-sm">{{ $error }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
            <div class="glass-card rounded-2xl p-3">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Cours payants autorisés</div>
                <div class="text-2xl font-bold text-slate-900 mt-1">{{ $this->authorizedPaidCoursesCount }}</div>
            </div>
            <div class="glass-card rounded-2xl p-3">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Demandes d'accès en attente</div>
                <div class="text-2xl font-bold text-slate-900 mt-1">{{ $this->pendingAccessRequestsCount }}</div>
            </div>
            <div class="glass-card rounded-2xl p-3">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Demandes prix en attente</div>
                <div class="text-2xl font-bold text-slate-900 mt-1">{{ $this->pendingPriceRequestsCount }}</div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-2 mb-6">
            <div class="glass-card rounded-3xl p-4">
                <h3 class="font-display text-xl text-slate-900 mb-3">Demande d'accès aux cours payants</h3>
                <form wire:submit.prevent="submitCourseAccessRequest" class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-700">Cours payant</label>
                        <select wire:model.defer="selectedCourseForAccessRequest" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2 text-sm" required>
                            <option value="">Sélectionner un cours</option>
                            @foreach($courses as $course)
                                @if($course->is_paid && $course->price > 0)
                                    <option value="{{ $course->id }}">{{ $course->title }} ({{ number_format((float) $course->price, 0, ',', ' ') }} XOF)</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700">Justification (optionnel)</label>
                        <textarea wire:model.defer="accessRequestNote" rows="2" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2 text-sm" placeholder="Contexte, besoin métier, volume attendu..."></textarea>
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center min-h-11 px-5 py-2 rounded-full text-white text-sm font-semibold transition hover:opacity-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-slate-300" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Envoyer la demande</button>
                </form>

                <div class="mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-slate-700">Statut des cours</span>
                        <span class="text-xs text-slate-500">{{ $paginatedCourses->total() }} cours</span>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white/70 p-2 max-h-44 overflow-y-auto pr-1">
                        <div class="space-y-2">
                        @forelse($paginatedCourses as $course)
                            @php
                                $courseAccess = $courseAccesses->get($course->id);
                                $isPending = in_array($course->id, $pendingAccessCourseIds, true);
                            @endphp
                            <div class="rounded-xl border border-slate-200 bg-white/70 p-2.5 flex items-center justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="font-semibold text-sm text-slate-900 truncate">{{ $course->title }}</div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        @if($course->is_paid && $course->price)
                                            Prix public: {{ number_format((float) $course->price, 0, ',', ' ') }} XOF
                                            @if($courseAccess && $courseAccess->adjusted_price !== null)
                                                · Prix ajusté: {{ number_format((float) $courseAccess->adjusted_price, 0, ',', ' ') }} XOF
                                            @endif
                                        @else
                                            Gratuit · accès libre
                                        @endif
                                    </div>
                                </div>
                                @if(! $course->is_paid || (float) $course->price <= 0)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700">Libre</span>
                                @elseif($courseAccess && $courseAccess->is_enabled)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700">Autorisé</span>
                                @elseif($isPending)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-700">En attente</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-700">Non autorisé</span>
                                @endif
                            </div>
                        @empty
                            <div class="text-sm text-slate-500">Aucun cours actif disponible.</div>
                        @endforelse
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <button
                            type="button"
                            wire:click="previousPage('statusPage')"
                            class="inline-flex items-center justify-center min-h-11 px-4 py-2 rounded-full text-sm font-semibold border border-slate-200 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-slate-300 {{ $paginatedCourses->onFirstPage() ? 'bg-slate-100 text-slate-400 cursor-not-allowed opacity-60' : 'bg-white text-slate-700 hover:bg-slate-50' }}"
                            {{ $paginatedCourses->onFirstPage() ? 'disabled' : '' }}
                        >
                            Précédent
                        </button>

                        <div class="text-sm text-slate-600 font-semibold">
                            Page {{ $paginatedCourses->currentPage() }} / {{ $paginatedCourses->lastPage() }}
                        </div>

                        <button
                            type="button"
                            wire:click="nextPage('statusPage')"
                            class="inline-flex items-center justify-center min-h-11 px-4 py-2 rounded-full text-sm font-semibold text-white transition hover:opacity-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-slate-300 {{ $paginatedCourses->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}"
                            style="background: linear-gradient(135deg, var(--teal), var(--mint));"
                            {{ $paginatedCourses->hasMorePages() ? '' : 'disabled' }}
                        >
                            Suivant
                        </button>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-3xl p-4">
                <h3 class="font-display text-xl text-slate-900 mb-3">Demande tarifaire ajustable</h3>
                <form wire:submit.prevent="submitCoursePriceRequest" class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-700">Cours</label>
                        <select wire:model.defer="selectedCourseForPriceRequest" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2 text-sm" required>
                            <option value="">Sélectionner un cours</option>
                            @foreach($courses as $course)
                                @if($course->is_paid && $course->price > 0)
                                    <option value="{{ $course->id }}">{{ $course->title }} ({{ number_format((float) $course->price, 0, ',', ' ') }} XOF)</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700">Prix demandé (XOF)</label>
                        <input type="number" min="0" wire:model.defer="requestedPrice" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700">Justification (optionnel)</label>
                        <textarea wire:model.defer="priceRequestNote" rows="2" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2 text-sm" placeholder="Contexte, volume, durée, budget..."></textarea>
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center min-h-11 px-5 py-2 rounded-full text-white text-sm font-semibold transition hover:opacity-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-slate-300" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Envoyer la demande</button>
                </form>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <div class="glass-card rounded-3xl p-4">
                <h3 class="font-display text-xl text-slate-900 mb-3">Demandes d'accès récentes</h3>
                <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                    @forelse($accessRequests as $accessRequest)
                        <div class="rounded-xl border border-slate-200 bg-white/70 p-2.5 text-sm">
                            <div class="font-semibold text-slate-900 text-sm">{{ $accessRequest->course?->title ?? 'Cours supprimé' }}</div>
                            <div class="text-slate-500 text-xs">{{ $accessRequest->created_at?->format('d/m/Y H:i') }}</div>
                            @if($accessRequest->note)
                                <div class="text-slate-600 mt-1">{{ $accessRequest->note }}</div>
                            @endif
                            <div class="mt-2">
                                <span class="px-2 py-1 rounded-full text-[11px] font-semibold {{ $accessRequest->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($accessRequest->status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ ucfirst($accessRequest->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">Aucune demande d'accès pour le moment.</div>
                    @endforelse
                </div>
            </div>

            <div class="glass-card rounded-3xl p-4">
                <h3 class="font-display text-xl text-slate-900 mb-3">Demandes tarifaires récentes</h3>
                <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                    @forelse($priceRequests as $priceRequest)
                        <div class="rounded-xl border border-slate-200 bg-white/70 p-2.5 text-sm">
                            <div class="font-semibold text-slate-900 text-sm">{{ $priceRequest->course?->title ?? 'Cours supprimé' }}</div>
                            <div class="text-slate-600">Demandé: {{ number_format((float) $priceRequest->requested_price, 0, ',', ' ') }} XOF</div>
                            @if($priceRequest->approved_price !== null)
                                <div class="text-slate-600">Approuvé: {{ number_format((float) $priceRequest->approved_price, 0, ',', ' ') }} XOF</div>
                            @endif
                            <div class="mt-1">
                                <span class="px-2 py-1 rounded-full text-[11px] font-semibold {{ $priceRequest->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($priceRequest->status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ ucfirst($priceRequest->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">Aucune demande tarifaire pour le moment.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
