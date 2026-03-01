<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-teal-50/30 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Retour -->
        <a href="{{ route('courses.show', $course->id) }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-teal-600 mb-8 group">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Retour au cours</span>
        </a>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Résumé de la commande -->
            <div class="md:col-span-2">
                <div class="glass-card rounded-3xl p-8">
                    <h1 class="font-display text-3xl mb-2">Finaliser l'inscription</h1>
                    <p class="text-slate-600 mb-8">Choisissez votre méthode de paiement pour accéder au cours</p>

                    @if($errorMessage)
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $errorMessage }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Méthodes de paiement -->
                    <div class="space-y-4 mb-8">
                        <h3 class="font-semibold text-lg mb-4">Méthode de paiement</h3>

                        <!-- Mobile Money -->
                        <label class="block cursor-pointer">
                            <input type="radio" wire:model="paymentMethod" value="mobile_money" class="peer sr-only">
                            <div class="p-6 border-2 rounded-2xl peer-checked:border-teal-500 peer-checked:bg-teal-50/50 hover:border-teal-300 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-mint-500 flex items-center justify-center text-white text-2xl">
                                            📱
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900">Mobile Money</div>
                                            <div class="text-sm text-slate-600">Orange Money, MTN, Moov, Wave</div>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 peer-checked:border-teal-500 peer-checked:bg-teal-500 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="currentColor" viewBox="0 0 12 12">
                                            <path d="M10 3L4.5 8.5 2 6"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Carte Bancaire -->
                        <label class="block cursor-pointer">
                            <input type="radio" wire:model="paymentMethod" value="card" class="peer sr-only">
                            <div class="p-6 border-2 rounded-2xl peer-checked:border-teal-500 peer-checked:bg-teal-50/50 hover:border-teal-300 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white text-2xl">
                                            💳
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900">Carte Bancaire</div>
                                            <div class="text-sm text-slate-600">Visa, Mastercard</div>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 peer-checked:border-teal-500 peer-checked:bg-teal-500"></div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Sécurité -->
                    <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl text-sm text-slate-600">
                        <svg class="w-5 h-5 text-teal-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Paiement 100% sécurisé via <strong>Fedapay</strong>. Vos informations bancaires ne sont jamais stockées sur notre plateforme.</span>
                    </div>

                    <!-- Bouton de paiement -->
                    <button 
                        wire:click="initiatePayment" 
                        wire:loading.attr="disabled"
                        class="w-full mt-8 py-4 rounded-2xl text-white font-bold text-lg flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-lg transition-all"
                        style="background: linear-gradient(135deg, var(--teal), var(--mint));">
                        <span wire:loading.remove>Procéder au paiement</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Redirection en cours...
                        </span>
                    </button>
                </div>
            </div>

            <!-- Récapitulatif -->
            <div class="md:col-span-1">
                <div class="glass-card rounded-3xl p-6 sticky top-8">
                    <h3 class="font-semibold text-lg mb-4">Récapitulatif</h3>

                    <!-- Cours -->
                    <div class="mb-6">
                        @if($course->thumbnail)
                            <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-32 object-cover rounded-xl mb-3">
                        @else
                            <div class="w-full h-32 bg-gradient-to-br from-teal-400 to-mint-400 rounded-xl mb-3 flex items-center justify-center text-white text-4xl">
                                📚
                            </div>
                        @endif
                        <h4 class="font-semibold text-slate-900 mb-1">{{ $course->title }}</h4>
                        <p class="text-sm text-slate-600">Par {{ $course->creator?->name ?? 'Formateur' }}</p>
                    </div>

                    <!-- Prix -->
                    <div class="border-t border-slate-200 pt-4 space-y-3">
                        <div class="flex justify-between text-slate-600">
                            <span>Prix du cours</span>
                            <span class="font-semibold">{{ number_format($effectivePrice, 0, ',', ' ') }} XOF</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-slate-200">
                            <span class="font-bold text-slate-900">Total</span>
                            <span class="font-bold text-2xl" style="color: var(--teal);">{{ number_format($effectivePrice, 0, ',', ' ') }} XOF</span>
                        </div>
                    </div>

                    <!-- Avantages -->
                    <div class="mt-6 pt-6 border-t border-slate-200 space-y-3 text-sm text-slate-600">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-teal-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Accès illimité au cours</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-teal-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Certificat de fin de formation</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-teal-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Suivi de progression</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-teal-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Accès aux ressources</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
