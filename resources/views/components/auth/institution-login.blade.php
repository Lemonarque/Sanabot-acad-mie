<div class="min-h-screen aurora-bg py-16">
    <div class="max-w-md mx-auto px-6">
        <div class="glass-card rounded-3xl p-8 border border-slate-200">
            <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Connexion</div>
            <h2 class="font-display text-3xl text-slate-900 mt-2">Espace Institution</h2>
            <p class="text-slate-600 mt-2">Connectez-vous pour gérer vos apprenants et vos quotas.</p>

            <form wire:submit.prevent="login" class="space-y-4 mt-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Email institution</label>
                    <input type="email" wire:model.defer="email" class="mt-1 w-full rounded-xl border-slate-200 px-4 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Mot de passe</label>
                    <input type="password" wire:model.defer="password" class="mt-1 w-full rounded-xl border-slate-200 px-4 py-2" required>
                </div>

                @if($error)
                    <div class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">{{ $error }}</div>
                @endif

                <button type="submit" class="w-full px-5 py-2.5 rounded-full text-white font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">
                    Se connecter
                </button>
            </form>

            <div class="mt-4 text-xs text-slate-500">
                Besoin d\'un compte institution ? Inscrivez-vous en tant qu\'institution puis faites votre demande de quota à l\'admin.
            </div>
        </div>
    </div>
</div>
