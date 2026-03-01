<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-4xl mx-auto px-6">
        <div class="flex items-end justify-between mb-8">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Apprenant</div>
                <h2 class="font-display text-4xl">Mes certificats</h2>
                <p class="text-slate-600 mt-2">Téléchargez vos attestations lorsque vos sections sont validées.</p>
            </div>
            <a href="{{ route('apprenant.dashboard') }}" class="chip px-4 py-2 rounded-full text-sm">Retour dashboard</a>
        </div>

        <div class="glass-card rounded-3xl p-6">
            <div class="space-y-6">
                @forelse($certificates as $certificate)
                    <div class="rounded-2xl bg-white/70 border border-slate-200 p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <div class="font-semibold text-slate-900">{{ $certificate->enrollment->course->title }}</div>
                            <div class="text-sm text-slate-600">Certificat généré le {{ $certificate->created_at->format('d/m/Y') }}</div>
                        </div>
                        <div>
                            @if($certificate->pdf_path)
                                <a href="{{ asset($certificate->pdf_path) }}" target="_blank" class="px-4 py-2 rounded-full bg-slate-900 text-white font-semibold">Télécharger le PDF</a>
                            @else
                                <span class="text-sm text-slate-500">PDF indisponible</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-slate-600">Aucune attestation ou certificat disponible.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>