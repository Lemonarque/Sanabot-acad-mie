<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-8">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Formateur</div>
                <h2 class="font-display text-4xl text-slate-900">Certificats du cours</h2>
                <p class="text-slate-600 mt-2">{{ $course->title }}</p>
            </div>
            <a href="{{ route('courses.manage') }}" class="chip px-4 py-2 rounded-full text-sm">Retour aux cours</a>
        </div>

        @if($message)
            <div class="mb-6 p-4 rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-sm">
                ✅ {{ $message }}
            </div>
        @endif

        <div class="glass-card rounded-3xl p-6 mb-6">
            <h3 class="font-display text-2xl text-slate-900 mb-4">Ajouter un certificat</h3>
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_1fr_auto] gap-4 items-end">
                <div>
                    <label class="block text-xs uppercase tracking-[0.2em] text-slate-500">Apprenant</label>
                    <select wire:model="selectedEnrollmentId" class="mt-2 block w-full border-slate-200 rounded-full px-4 py-2.5">
                        <option value="">Sélectionner un apprenant</option>
                        @foreach($enrollments as $enrollment)
                            <option value="{{ $enrollment->id }}">{{ $enrollment->user?->name }} ({{ $enrollment->user?->email }})</option>
                        @endforeach
                    </select>
                    @error('selectedEnrollmentId')
                        <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-[0.2em] text-slate-500">PDF certificat (optionnel)</label>
                    <input type="file" wire:model="certificatePdf" accept="application/pdf" class="mt-2 block w-full border-slate-200 rounded-full px-4 py-2.5 bg-white" />
                    @error('certificatePdf')
                        <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button wire:click="issueCertificate" class="px-5 py-2 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition whitespace-nowrap">
                    Ajouter certificat
                </button>
            </div>
        </div>

        <div class="glass-card rounded-3xl p-6">
            <h3 class="font-display text-2xl text-slate-900 mb-4">Apprenants inscrits</h3>

            @if($enrollments->isEmpty())
                <div class="rounded-2xl bg-white/75 border border-slate-200 p-6 text-slate-600">
                    Aucun apprenant inscrit à ce cours pour le moment.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($enrollments as $enrollment)
                        <div class="rounded-2xl bg-white/75 border border-slate-200 p-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $enrollment->user?->name ?? '—' }}</div>
                                <div class="text-sm text-slate-600">{{ $enrollment->user?->email ?? '—' }}</div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                @if($enrollment->certificate)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">🏅 Certificat ajouté</span>

                                    @if(!empty($enrollment->certificate->pdf_path))
                                        <a href="{{ asset($enrollment->certificate->pdf_path) }}" target="_blank" class="px-4 py-2 rounded-full bg-white border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                                            Ouvrir PDF
                                        </a>
                                    @endif

                                    <button wire:click="revokeCertificate({{ $enrollment->certificate->id }})" onclick="return confirm('Retirer ce certificat ?')" class="px-4 py-2 rounded-full bg-rose-100/80 text-rose-700 text-sm font-semibold hover:bg-rose-200/80 transition">
                                        Retirer
                                    </button>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Aucun certificat</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
