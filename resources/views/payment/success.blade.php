<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement réussi - Sanabot Académie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-50 via-white to-teal-50/30">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-2xl w-full">
            <!-- Animation de succès -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-teal-500 to-mint-500 mb-6 animate-bounce">
                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="font-display text-4xl mb-3">Paiement réussi !</h1>
                <p class="text-xl text-slate-600">Votre inscription a été confirmée avec succès</p>
            </div>

            <!-- Détails du paiement -->
            @if(isset($payment))
            <div class="glass-card rounded-3xl p-8 mb-6">
                <div class="mb-6 pb-6 border-b border-slate-200">
                    <h2 class="font-semibold text-lg mb-4">Détails de votre inscription</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Cours:</span>
                            <span class="font-semibold">{{ $payment->course->title }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Montant payé:</span>
                            <span class="font-semibold" style="color: var(--teal);">{{ number_format($payment->amount, 0, ',', ' ') }} XOF</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Référence:</span>
                            <span class="font-mono text-sm">{{ $payment->transaction_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Date:</span>
                            <span>{{ $payment->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-4 bg-teal-50 rounded-xl">
                        <svg class="w-6 h-6 text-teal-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-slate-700">
                            <p class="font-semibold mb-1">Prochaines étapes</p>
                            <ul class="space-y-1 list-disc list-inside">
                                <li>Vous avez maintenant accès à tout le contenu du cours</li>
                                <li>Un email de confirmation vous a été envoyé</li>
                                <li>Commencez votre formation dès maintenant !</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ isset($payment) ? route('courses.show', $payment->course_id) : route('dashboard') }}" 
                   class="px-8 py-4 rounded-2xl text-white font-bold text-center hover:shadow-lg transition-all"
                   style="background: linear-gradient(135deg, var(--teal), var(--mint));">
                    Commencer le cours
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="px-8 py-4 rounded-2xl bg-slate-100 text-slate-700 font-semibold text-center hover:bg-slate-200 transition-all">
                    Retour au tableau de bord
                </a>
            </div>
        </div>
    </div>
</body>
</html>
