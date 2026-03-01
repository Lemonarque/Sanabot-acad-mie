<div class="min-h-screen aurora-bg flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-5xl grid lg:grid-cols-[1.1fr_0.9fr] gap-8">
        <div class="glass-card rounded-3xl p-8 lg:p-10">
            <div class="text-xs uppercase tracking-[0.3em] text-slate-500">Louck's Health</div>
            <h1 class="font-display text-4xl text-slate-900 mt-3">Connexion</h1>
            <p class="text-slate-600 mt-2">Accedez a vos formations certifiantes et continuez votre progression.</p>

            @if ($error)
                <div class="mt-5 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 px-4 py-3 text-sm">{{ $error }}</div>
            @endif

            <form wire:submit.prevent="login" class="mt-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" wire:model.defer="email" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" required autofocus />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Mot de passe</label>
                    <input type="password" wire:model.defer="password" class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-200" required />
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-emerald-700 hover:text-emerald-800">Mot de passe oublie ?</a>
                    @endif
                    <a href="{{ route('register') }}" class="text-slate-500 hover:text-slate-700">Creer un compte</a>
                </div>
                <button type="submit" class="w-full py-3 rounded-2xl text-white font-semibold shadow-sm transition" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Se connecter</button>
            </form>
        </div>

        <div class="glass-card rounded-3xl p-8 lg:p-10 flex flex-col justify-between" style="background: linear-gradient(135deg, rgba(53, 167, 178, 0.16), rgba(126, 217, 180, 0.2));">
            <div>
                <img
                    src="https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1200&q=80"
                    alt="Apprentissage en ligne"
                    class="w-full h-44 object-cover rounded-2xl border border-white/40 mb-5"
                    loading="lazy"
                >
                <div class="text-xs uppercase tracking-[0.3em] text-emerald-700">SANABOT-ACADEMY</div>
                <h2 class="font-display text-3xl text-slate-900 mt-3">Plateforme certifiante</h2>
                <p class="text-slate-600 mt-3">Formations structurees, progression suivie, certifications verifiables. Tout ce qu'il faut pour des parcours credibles.</p>
            </div>
            <div class="mt-10 space-y-3 text-sm text-slate-700">
                <div class="flex items-center gap-3">
                    <span class="h-2.5 w-2.5 rounded-full" style="background: var(--teal);"></span>
                    <span>Sections payantes et gratuites par parcours</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="h-2.5 w-2.5 rounded-full" style="background: var(--mint);"></span>
                    <span>Suivi de progression et attestations PDF</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="h-2.5 w-2.5 rounded-full" style="background: var(--sky);"></span>
                    <span>Back-office Louck's Health</span>
                </div>
            </div>
        </div>
    </div>
</div>
