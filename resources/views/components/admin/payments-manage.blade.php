<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div style="display:flex;gap:1.5rem;align-items:flex-start;">
            @include('components.admin.sidebar')

            <div>
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
                    <div>
                        <div class="text-xs uppercase tracking-[0.25em]" style="color: var(--teal);">Administration</div>
                        <h2 class="font-display text-4xl text-slate-900">Paiements</h2>
                        <p class="text-slate-600 mt-2">Suivi des transactions et accès sections.</p>
                    </div>
                </div>

                <div class="rounded-3xl p-6 mb-6 border border-cyan-200" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.06), rgba(34, 211, 238, 0.04));">
                    <div class="flex flex-col md:flex-row md:items-center gap-3">
                        <input type="text" wire:model.defer="search" placeholder="Rechercher un apprenant ou une formation..." class="w-full md:flex-1 border-slate-200 rounded-full px-5 py-2" />
                        <button wire:click="searchPayments" class="px-5 py-2 rounded-full text-white font-semibold transition" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Rechercher</button>
                        <div class="text-sm text-slate-500">{{ $payments->count() }} paiement(s)</div>
                    </div>
                </div>

                <div class="rounded-3xl p-6 border border-cyan-200" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.06), rgba(34, 211, 238, 0.04));">
                    <h3 class="font-display text-2xl text-slate-900 mb-4">Historique</h3>
                    @if($payments->isEmpty())
                        <p class="text-slate-600">Aucun paiement pour le moment.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-xs uppercase tracking-[0.2em] text-slate-400 border-b border-slate-200">
                                        <th class="py-3">Apprenant</th>
                                        <th class="py-3">Formation</th>
                                        <th class="py-3">Montant</th>
                                        <th class="py-3">Méthode</th>
                                        <th class="py-3">Statut</th>
                                        <th class="py-3">Transaction</th>
                                        <th class="py-3">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach($payments as $payment)
                                        @php
                                            $courseName = $payment->course?->title ?? $payment->module?->course?->title ?? '—';
                                            $statusBadge = match($payment->status) {
                                                'completed' => ['bg' => 'rgba(16, 185, 129, 0.15)', 'color' => '#10b981', 'label' => '✓ Payé', 'icon' => '✓'],
                                                'pending' => ['bg' => 'rgba(251, 191, 36, 0.15)', 'color' => '#f59e0b', 'label' => '⏳ En attente', 'icon' => '⏳'],
                                                'cancelled' => ['bg' => 'rgba(156, 163, 175, 0.15)', 'color' => '#6b7280', 'label' => '✕ Annulé', 'icon' => '✕'],
                                                'failed' => ['bg' => 'rgba(239, 68, 68, 0.15)', 'color' => '#ef4444', 'label' => '✕ Échoué', 'icon' => '✕'],
                                                default => ['bg' => 'rgba(156, 163, 175, 0.15)', 'color' => '#6b7280', 'label' => $payment->status, 'icon' => '•'],
                                            };
                                            $methodIcon = match($payment->payment_method) {
                                                'mobile_money' => '📱',
                                                'card' => '💳',
                                                default => '💰',
                                            };
                                        @endphp
                                        <tr>
                                            <td class="py-4">
                                                <div class="font-semibold text-slate-900">{{ $payment->user?->name ?? '—' }}</div>
                                                <div class="text-xs text-slate-500">{{ $payment->user?->email ?? '' }}</div>
                                            </td>
                                            <td class="py-4 text-slate-700 font-medium">{{ $courseName }}</td>
                                            <td class="py-4 font-semibold" style="color: var(--teal);">{{ number_format($payment->amount, 0, ',', ' ') }} XOF</td>
                                            <td class="py-4">
                                                @if($payment->payment_method)
                                                    <span class="inline-flex items-center gap-1 text-sm">
                                                        <span>{{ $methodIcon }}</span>
                                                        <span class="text-slate-600">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                                    </span>
                                                @else
                                                    <span class="text-slate-400">—</span>
                                                @endif
                                            </td>
                                            <td class="py-4">
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold" style="background: {{ $statusBadge['bg'] }}; color: {{ $statusBadge['color'] }};">
                                                    <span>{{ $statusBadge['icon'] }}</span>
                                                    <span>{{ $statusBadge['label'] }}</span>
                                                </span>
                                            </td>
                                            <td class="py-4">
                                                @if($payment->transaction_id)
                                                    <span class="font-mono text-xs text-slate-600" title="{{ $payment->transaction_id }}">{{ substr($payment->transaction_id, 0, 12) }}...</span>
                                                @else
                                                    <span class="text-slate-400">—</span>
                                                @endif
                                            </td>
                                            <td class="py-4 text-slate-600 text-sm">{{ $payment->paid_at?->format('d/m/Y H:i') ?? $payment->created_at?->format('d/m/Y H:i') }}</td>
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
