<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div style="display:flex;gap:1.5rem;align-items:flex-start;">
            @include('components.admin.sidebar')

            <div class="flex-1">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
                    <div>
                        <div class="text-xs uppercase tracking-[0.25em] text-slate-500">Administration</div>
                        <h2 class="font-display text-4xl text-slate-900">Gestion des institutions</h2>
                        <p class="text-slate-600 mt-2">Validez les demandes de quotas et suivez les capacités accordées.</p>
                    </div>
                </div>

                <div class="rounded-3xl p-6 border border-blue-200 mb-6" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.06), rgba(96, 165, 250, 0.04));">
                    <h3 class="font-display text-2xl text-slate-900 mb-4">Demandes institutions</h3>
                    <div class="space-y-3">
                        @forelse($requests as $request)
                            <div class="rounded-2xl bg-white/80 border border-slate-200 p-4">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                    <div>
                                        <div class="font-semibold text-slate-900">{{ $request->institution->name }}</div>
                                        <div class="text-sm text-slate-600">Demande : {{ $request->requested_seats }} place(s)</div>
                                        <div class="text-xs text-slate-500">Statut : {{ ucfirst($request->status) }}</div>
                                    </div>

                                    @if($request->status === 'pending')
                                        <div class="flex flex-wrap items-center gap-2">
                                            <input type="number" min="1" wire:model.defer="approvalSeats.{{ $request->id }}" placeholder="Places à accorder" class="w-40 rounded-xl border-slate-200 px-3 py-2 text-sm" />
                                            <button wire:click="approveRequest({{ $request->id }})" class="px-4 py-2 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Approuver</button>
                                            <button wire:click="rejectRequest({{ $request->id }})" class="px-4 py-2 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, #f08ea3, #f4c55e);">Refuser</button>
                                        </div>
                                    @else
                                        <div class="text-sm text-slate-600">Accordé : {{ $request->approved_seats ?? 0 }} place(s)</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-slate-600">Aucune demande institution en attente.</div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl p-6 border border-blue-200 mb-6" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.06), rgba(96, 165, 250, 0.04));">
                    <h3 class="font-display text-2xl text-slate-900 mb-4">Demandes d'ajustement tarifaire</h3>
                    <div class="space-y-3">
                        @forelse($priceRequests as $priceRequest)
                            <div class="rounded-2xl bg-white/80 border border-slate-200 p-4">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                    <div>
                                        <div class="font-semibold text-slate-900">{{ $priceRequest->institution->name }} · {{ $priceRequest->course?->title ?? 'Cours supprimé' }}</div>
                                        <div class="text-sm text-slate-600">Demandé : {{ number_format((float) $priceRequest->requested_price, 0, ',', ' ') }} XOF</div>
                                        <div class="text-xs text-slate-500">Statut : {{ ucfirst($priceRequest->status) }}</div>
                                    </div>

                                    @if($priceRequest->status === 'pending')
                                        <div class="flex flex-wrap items-center gap-2">
                                            <input type="number" min="0" wire:model.defer="approvalPrices.{{ $priceRequest->id }}" placeholder="Prix approuvé" class="w-40 rounded-xl border-slate-200 px-3 py-2 text-sm" />
                                            <button wire:click="approvePriceRequest({{ $priceRequest->id }})" class="px-4 py-2 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Approuver</button>
                                            <button wire:click="rejectPriceRequest({{ $priceRequest->id }})" class="px-4 py-2 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, #f08ea3, #f4c55e);">Refuser</button>
                                        </div>
                                    @else
                                        <div class="text-sm text-slate-600">Approuvé : {{ $priceRequest->approved_price !== null ? number_format((float) $priceRequest->approved_price, 0, ',', ' ') . ' XOF' : '—' }}</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-slate-600">Aucune demande tarifaire institution en attente.</div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl p-6 border border-blue-200 mb-6" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.06), rgba(96, 165, 250, 0.04));">
                    <h3 class="font-display text-2xl text-slate-900 mb-4">Demandes d'accès aux cours payants</h3>
                    <div class="space-y-3">
                        @forelse($accessRequests as $accessRequest)
                            <div class="rounded-2xl bg-white/80 border border-slate-200 p-4">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                    <div>
                                        <div class="font-semibold text-slate-900">{{ $accessRequest->institution->name }} · {{ $accessRequest->course?->title ?? 'Cours supprimé' }}</div>
                                        <div class="text-sm text-slate-600">
                                            @if($accessRequest->course?->is_paid && $accessRequest->course?->price)
                                                Prix public : {{ number_format((float) $accessRequest->course->price, 0, ',', ' ') }} XOF
                                            @else
                                                Cours gratuit (normalement libre)
                                            @endif
                                        </div>
                                        @if($accessRequest->note)
                                            <div class="text-xs text-slate-500 mt-1">Note : {{ $accessRequest->note }}</div>
                                        @endif
                                        <div class="text-xs text-slate-500">Statut : {{ ucfirst($accessRequest->status) }}</div>
                                    </div>

                                    @if($accessRequest->status === 'pending')
                                        <div class="flex flex-wrap items-center gap-2">
                                            <button wire:click="approveAccessRequest({{ $accessRequest->id }})" class="px-4 py-2 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Approuver</button>
                                            <button wire:click="rejectAccessRequest({{ $accessRequest->id }})" class="px-4 py-2 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, #f08ea3, #f4c55e);">Refuser</button>
                                        </div>
                                    @else
                                        <div class="text-sm text-slate-600">Traité le {{ $accessRequest->reviewed_at?->format('d/m/Y H:i') ?? '—' }}</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-slate-600">Aucune demande d'accès en attente.</div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl p-6 border border-blue-200" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.06), rgba(96, 165, 250, 0.04));">
                    <h3 class="font-display text-2xl text-slate-900 mb-4">Institutions enregistrées</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-xs uppercase tracking-[0.2em] text-slate-400 border-b border-slate-200">
                                    <th class="py-3">Institution</th>
                                    <th class="py-3">Compte propriétaire</th>
                                    <th class="py-3">Quota accordé</th>
                                    <th class="py-3">Créée le</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse($institutions as $institution)
                                    <tr>
                                        <td class="py-4 font-semibold text-slate-900">{{ $institution->name }}</td>
                                        <td class="py-4 text-slate-600">{{ $institution->owner?->email }}</td>
                                        <td class="py-4"><span class="chip px-3 py-1 rounded-full text-xs">{{ $institution->approved_learner_quota }}</span></td>
                                        <td class="py-4 text-slate-500">{{ $institution->created_at?->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="py-4 text-slate-600">Aucune institution pour le moment.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                                                                                                                                                                                               