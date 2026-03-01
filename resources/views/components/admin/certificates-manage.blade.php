<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div style="display:flex;gap:1.5rem;align-items:flex-start;">
            @include('components.admin.sidebar')

            <div>
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
                    <div>
                        <div class="text-xs uppercase tracking-[0.25em] text-slate-500">Administration</div>
                        <h2 class="font-display text-4xl text-slate-900">Certificats</h2>
                        <p class="text-slate-600 mt-2">Controlez les attestations delivrees.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('admin.dashboard') }}" class="px-5 py-2 rounded-full chip font-semibold hover:bg-white transition">Retour dashboard</a>
                        <a href="{{ route('admin.courses.manage') }}" class="px-5 py-2 rounded-full text-white font-semibold transition" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Voir les cours</a>
                    </div>
                </div>

                <div class="rounded-3xl p-6 mb-6 border border-yellow-200" style="background: linear-gradient(135deg, rgba(202, 138, 4, 0.06), rgba(234, 179, 8, 0.04));">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-3">
                        <input type="text" wire:model.defer="search" placeholder="Rechercher un apprenant ou un cours..." class="w-full lg:flex-1 border-slate-200 rounded-full px-5 py-2 focus:ring-2 focus:ring-emerald-200" />
                        <button wire:click="searchCertificates" class="px-5 py-2 rounded-full text-white font-semibold transition" style="background: linear-gradient(135deg, var(--teal), var(--sky));">Rechercher</button>
                        <div class="text-sm text-slate-500">{{ $certificates->count() }} certificat(s)</div>
                    </div>
                </div>

                <div class="rounded-3xl p-6 border border-yellow-200" style="background: linear-gradient(135deg, rgba(202, 138, 4, 0.06), rgba(234, 179, 8, 0.04));">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-display text-2xl text-slate-900">Attestations emises</h3>
                        <span class="text-xs uppercase tracking-[0.2em] text-slate-400">Documents</span>
                    </div>
                    @if($certificates->isEmpty())
                        <p class="text-slate-600">Aucun certificat pour le moment.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-xs uppercase tracking-[0.2em] text-slate-400 border-b border-slate-200">
                                        <th class="py-3">Apprenant</th>
                                        <th class="py-3">Cours</th>
                                        <th class="py-3">Date</th>
                                        <th class="py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach($certificates as $certificate)
                                        <tr>
                                            <td class="py-4">
                                                <div class="font-semibold text-slate-900">{{ $certificate->enrollment?->user?->name ?? '—' }}</div>
                                                <div class="text-sm text-slate-600">{{ $certificate->enrollment?->user?->email ?? '' }}</div>
                                            </td>
                                            <td class="py-4 text-slate-600">{{ $certificate->enrollment?->course?->title ?? '—' }}</td>
                                            <td class="py-4 text-slate-600">{{ $certificate->created_at?->format('d/m/Y') }}</td>
                                            <td class="py-4">
                                                <div class="flex justify-end gap-2">
                                                    <button wire:click="revoke({{ $certificate->id }})" class="px-3 py-2 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, #f08ea3, #f4c55e);" onclick="return confirm('Revoquer ce certificat ?')">Revoquer</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
