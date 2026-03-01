<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div style="display:flex;gap:1.5rem;align-items:flex-start;">
            @include('components.admin.sidebar')

            <div class="flex-1">
                <!-- Header avec actions rapides -->
                <div class="rounded-3xl p-6 mb-8 border border-teal-200" style="background: linear-gradient(135deg, rgba(53, 167, 178, 0.08), rgba(123, 191, 100, 0.08));">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div>
                            <div class="text-xs uppercase tracking-[0.25em]" style="color: var(--teal);">Administration Globale</div>
                            <h2 class="font-display text-5xl text-slate-900">Tableau de bord</h2>
                            <p class="text-slate-600 mt-2">Pilotage complet de la plateforme d'apprentissage</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('admin.categories.manage') }}" class="px-5 py-2 rounded-full chip font-semibold hover:bg-white transition text-sm">➕ Catégorie</a>
                            <a href="{{ route('admin.users.manage') }}" class="px-5 py-2 rounded-full text-white font-semibold shadow-sm transition text-sm" style="background: linear-gradient(135deg, var(--teal), var(--mint));">➕ Utilisateur</a>
                        </div>
                    </div>
                </div>

                <!-- KPI Cards -->
                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4 mb-8">
                    <div class="rounded-3xl p-6 hover:shadow-lg transition border border-blue-200" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.08), rgba(96, 165, 250, 0.05));">
                        <div class="flex items-start justify-between mb-3">
                            <div class="text-4xl">👥</div>
                            <div class="chip px-2 py-1 rounded-full text-xs bg-blue-200/60 text-blue-900">Utilisateurs</div>
                        </div>
                        <div class="text-4xl font-display text-slate-900 mb-2">{{ $usersCount }}</div>
                        <div class="text-sm text-slate-500">
                            <span class="font-semibold text-emerald-600">{{ $formateursCount }}</span> formateurs · 
                            <span class="font-semibold text-sky-600">{{ $apprenantsCount }}</span> apprenants
                        </div>
                    </div>

                    <div class="rounded-3xl p-6 hover:shadow-lg transition border border-purple-200" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.08), rgba(192, 132, 250, 0.05));">
                        <div class="flex items-start justify-between mb-3">
                            <div class="text-4xl">🎓</div>
                            <div class="chip px-2 py-1 rounded-full text-xs bg-purple-200/60 text-purple-900">Contenus</div>
                        </div>
                        <div class="text-4xl font-display text-slate-900 mb-2">{{ $coursesCount }}</div>
                        <div class="text-sm text-slate-500">
                            <span class="font-semibold" style="color: var(--teal);">{{ $paidCoursesCount }}</span> payants · 
                            <span class="font-semibold text-emerald-600">{{ $freeCoursesCount }}</span> gratuits
                        </div>
                    </div>

                    <div class="rounded-3xl p-6 hover:shadow-lg transition border border-teal-300" style="background: linear-gradient(135deg, rgba(53, 167, 178, 0.12), rgba(123, 191, 100, 0.1));">
                        <div class="flex items-start justify-between mb-3">
                            <div class="text-4xl">💰</div>
                            <div class="chip px-2 py-1 rounded-full text-xs" style="background: rgba(53, 167, 178, 0.25); color: var(--teal); font-weight: 600;">Revenus</div>
                        </div>
                        <div class="text-3xl font-display mb-2" style="color: var(--teal);">{{ number_format($revenueTotal, 0, ',', ' ') }}</div>
                        <div class="text-sm text-slate-500">
                            Ce mois: <span class="font-semibold" style="color: var(--teal);">{{ number_format($totalRevenueMonth, 0, ',', ' ') }}</span> XOF
                        </div>
                    </div>

                    <div class="rounded-3xl p-6 hover:shadow-lg transition border border-amber-200" style="background: linear-gradient(135deg, rgba(217, 119, 6, 0.08), rgba(251, 146, 60, 0.05));">
                        <div class="flex items-start justify-between mb-3">
                            <div class="text-4xl">📚</div>
                            <div class="chip px-2 py-1 rounded-full text-xs bg-amber-200/60 text-amber-900">Structure</div>
                        </div>
                        <div class="text-4xl font-display text-slate-900 mb-2">{{ $modulesCount }}</div>
                        <div class="text-sm text-slate-500">
                            Sections · <span class="font-semibold text-slate-700">{{ $lessonsCount }}</span> chapitres
                        </div>
                    </div>
                </div>

                <!-- Stats supplémentaires -->
                <div class="grid gap-5 md:grid-cols-3 mb-8">
                    <div class="rounded-2xl p-5 border border-emerald-200" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(52, 211, 153, 0.05));">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-2xl">✍️</span>
                            <div class="text-xs uppercase tracking-[0.2em]" style="color: var(--teal);">Inscriptions</div>
                        </div>
                        <div class="text-3xl font-display text-slate-900">{{ $enrollmentsCount }}</div>
                    </div>
                    <div class="rounded-2xl p-5 border border-cyan-200" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.08), rgba(34, 211, 238, 0.05));">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-2xl">💳</span>
                            <div class="text-xs uppercase tracking-[0.2em]" style="color: var(--teal);">Transactions</div>
                        </div>
                        <div class="text-3xl font-display text-slate-900">{{ $paymentsCount }}</div>
                        <a href="{{ route('admin.payments.manage') }}" class="text-xs font-semibold mt-1 inline-block" style="color: var(--teal);">Voir détails →</a>
                    </div>
                    <div class="rounded-2xl p-5 border border-yellow-200" style="background: linear-gradient(135deg, rgba(202, 138, 4, 0.08), rgba(234, 179, 8, 0.05));">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-2xl">🏆</span>
                            <div class="text-xs uppercase tracking-[0.2em]" style="color: var(--teal);">Certificats</div>
                        </div>
                        <div class="text-3xl font-display text-slate-900">{{ $certificatesCount }}</div>
                        <div class="text-sm text-slate-500 mt-1">{{ $issuedCertificatesCount }} délivrés</div>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr] mb-10">
                    <div class="rounded-3xl p-6 border border-slate-200" style="background: linear-gradient(135deg, rgba(248, 250, 252, 0.8), rgba(241, 245, 249, 0.6)), linear-gradient(135deg, rgba(53, 167, 178, 0.04), rgba(123, 191, 100, 0.04));">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-display text-2xl text-slate-900">Cours en attente</h3>
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-400">Validation</span>
                        </div>
                        @if($pendingCourses->isEmpty())
                            <p class="text-slate-600">Aucun cours en attente.</p>
                        @else
                            <ul class="divide-y divide-slate-200">
                                @foreach($pendingCourses as $course)
                                    <li class="py-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $course->title }}</div>
                                            <div class="text-sm text-slate-600">{{ $course->description }}</div>
                                            <div class="text-xs uppercase tracking-[0.2em] mt-2 text-slate-400">{{ $course->status }}</div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button wire:click="validateCourse({{ $course->id }})" class="px-4 py-2 rounded-full text-white font-semibold transition" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Valider</button>
                                            <button wire:click="rejectCourse({{ $course->id }})" class="px-4 py-2 rounded-full text-white font-semibold transition" style="background: linear-gradient(135deg, #f08ea3, #f4c55e);">Rejeter</button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="rounded-3xl p-6 border border-slate-200" style="background: linear-gradient(135deg, rgba(248, 250, 252, 0.8), rgba(241, 245, 249, 0.6)), linear-gradient(135deg, rgba(53, 167, 178, 0.04), rgba(123, 191, 100, 0.04));">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-display text-2xl text-slate-900">Certificats</h3>
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-400">Controle</span>
                        </div>
                        <div class="grid gap-4">
                            <div class="rounded-2xl bg-white/70 border border-slate-200 p-4">
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Total</div>
                                <div class="text-2xl font-display text-slate-900 mt-1">{{ $certificatesCount }}</div>
                            </div>
                            <div class="rounded-2xl bg-white/70 border border-slate-200 p-4">
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Delivres</div>
                                <div class="text-2xl font-display text-slate-900 mt-1">{{ $issuedCertificatesCount }}</div>
                            </div>
                            <div class="rounded-2xl bg-white/70 border border-slate-200 p-4">
                                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Inscriptions</div>
                                <div class="text-2xl font-display text-slate-900 mt-1">{{ $enrollmentsCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-3">
                    <div class="glass-card rounded-3xl p-6">
                        <h3 class="font-display text-xl text-slate-900 mb-4">Derniers utilisateurs</h3>
                        <ul class="divide-y divide-slate-200">
                            @foreach($recentUsers as $user)
                                <li class="py-3">
                                    <div class="font-semibold text-slate-900">{{ $user->name }}</div>
                                    <div class="text-sm text-slate-500">{{ $user->email }}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="rounded-3xl p-6 border border-cyan-200" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.06), rgba(34, 211, 238, 0.04));">
                        <h3 class="font-display text-xl text-slate-900 mb-4">Derniers paiements</h3>
                        @if($recentPayments->isEmpty())
                            <p class="text-slate-600">Aucun paiement pour le moment.</p>
                        @else
                            <ul class="divide-y divide-slate-200">
                                @foreach($recentPayments as $payment)
                                    @php
                                        $courseName = $payment->course?->title ?? $payment->module?->course?->title ?? '—';
                                        $statusIcon = match($payment->status) {
                                            'completed' => '✓',
                                            'pending' => '⏳',
                                            'cancelled' => '✕',
                                            'failed' => '✕',
                                            default => '•',
                                        };
                                        $statusColor = match($payment->status) {
                                            'completed' => 'text-emerald-600',
                                            'pending' => 'text-amber-600',
                                            'cancelled', 'failed' => 'text-slate-400',
                                            default => 'text-slate-600',
                                        };
                                    @endphp
                                    <li class="py-3">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="font-semibold text-slate-900">{{ $payment->user?->name ?? '—' }}</div>
                                                <div class="text-sm text-slate-500">{{ $courseName }}</div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-semibold" style="color: var(--teal);">{{ number_format($payment->amount, 0, ',', ' ') }} XOF</div>
                                                <div class="text-xs {{ $statusColor }} flex items-center gap-1 justify-end mt-1">
                                                    <span>{{ $statusIcon }}</span>
                                                    <span>{{ ucfirst($payment->status) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('admin.payments.manage') }}" class="block mt-4 text-center text-sm font-semibold" style="color: var(--teal);">
                                Voir tous les paiements →
                            </a>
                        @endif
                    </div>

                    <div class="rounded-3xl p-6 border border-purple-200" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.06), rgba(192, 132, 250, 0.04));">
                        <h3 class="font-display text-xl text-slate-900 mb-4">Top formations</h3>
                        <ul class="divide-y divide-slate-200">
                            @foreach($topCourses as $course)
                                <li class="py-3">
                                    <div class="font-semibold text-slate-900">{{ $course->title }}</div>
                                    <div class="text-sm text-slate-500">{{ $course->enrollments_count }} inscrits</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2 mt-6">
                    <div class="rounded-3xl p-6 border border-cyan-200" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.06), rgba(34, 211, 238, 0.04));">
                        <h3 class="font-display text-xl text-slate-900 mb-4">Derniers cours</h3>
                        <ul class="divide-y divide-slate-200">
                            @foreach($recentCourses as $course)
                                <li class="py-3">
                                    <div class="font-semibold text-slate-900">{{ $course->title }}</div>
                                    <div class="text-sm text-slate-500">{{ $course->creator?->name ?? '—' }}</div>
                                    @if($course->is_paid)
                                        <div class="text-xs text-teal-600 font-semibold mt-1">💰 {{ number_format($course->price, 0, ',', ' ') }} XOF</div>
                                    @else
                                        <div class="text-xs text-emerald-600 font-semibold mt-1">✓ Gratuit</div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="glass-card rounded-3xl p-6">
                        <h3 class="font-display text-xl text-slate-900 mb-4">💰 Revenus par cours</h3>
                        @if($courseRevenue->isEmpty())
                            <p class="text-slate-600">Aucune transaction pour le moment.</p>
                        @else
                            <ul class="divide-y divide-slate-200">
                                @foreach($courseRevenue as $payment)
                                    <li class="py-3">
                                        <div class="font-semibold text-slate-900">{{ $payment->module?->course?->title ?? '—' }}</div>
                                        <div class="flex items-center justify-between mt-1">
                                            <div class="text-sm text-slate-500">{{ $payment->user?->name ?? '—' }}</div>
                                            <div class="text-sm font-semibold" style="color: var(--teal);">{{ number_format($payment->amount, 0, ',', ' ') }} XOF</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
