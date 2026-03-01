<div class="rounded-3xl p-5 h-fit sticky top-24 max-h-[calc(100vh-6rem)] overflow-y-auto border border-teal-200" style="background: linear-gradient(135deg, rgba(248, 250, 252, 0.9), rgba(241, 245, 249, 0.7)), linear-gradient(135deg, rgba(53, 167, 178, 0.06), rgba(123, 191, 100, 0.04));" style="width:260px;flex:0 0 260px;">
    <div class="mb-6">
        <div class="text-xs uppercase tracking-[0.25em]" style="color: var(--teal);">Administration</div>
        <div class="font-display text-2xl text-slate-900">Panneau Admin</div>
    </div>
    
    <div class="space-y-6 text-sm">
        <!-- Dashboard & Stats -->
        <div>
            <div class="text-xs uppercase tracking-[0.2em] text-slate-400 mb-2 px-3">Vue d'ensemble</div>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-teal-50 to-emerald-50 text-slate-900 font-semibold border border-emerald-200' : 'text-slate-600 hover:bg-white' }}">
                <span class="text-lg">📊</span>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- Contenu -->
        <div>
            <div class="text-xs uppercase tracking-[0.2em] text-slate-400 mb-2 px-3">Contenu</div>
            <div class="space-y-1">
                <a href="{{ route('admin.courses.manage') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('admin.courses.manage') ? 'bg-gradient-to-r from-teal-50 to-emerald-50 text-slate-900 font-semibold border border-emerald-200' : 'text-slate-600 hover:bg-white' }}">
                    <span class="text-lg">🎓</span>
                    <span>Formations</span>
                </a>
                <a href="{{ route('admin.categories.manage') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('admin.categories.manage') ? 'bg-gradient-to-r from-teal-50 to-emerald-50 text-slate-900 font-semibold border border-emerald-200' : 'text-slate-600 hover:bg-white' }}">
                    <span class="text-lg">📂</span>
                    <span>Catégories</span>
                </a>
            </div>
        </div>

        <!-- Utilisateurs -->
        <div>
            <div class="text-xs uppercase tracking-[0.2em] text-slate-400 mb-2 px-3">Utilisateurs</div>
            <div class="space-y-1">
                <a href="{{ route('admin.users.manage') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('admin.users.manage') ? 'bg-gradient-to-r from-teal-50 to-emerald-50 text-slate-900 font-semibold border border-emerald-200' : 'text-slate-600 hover:bg-white' }}">
                    <span class="text-lg">👥</span>
                    <span>Gestion utilisateurs</span>
                </a>
                <a href="{{ route('admin.enrollments.manage') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('admin.enrollments.manage') ? 'bg-gradient-to-r from-teal-50 to-emerald-50 text-slate-900 font-semibold border border-emerald-200' : 'text-slate-600 hover:bg-white' }}">
                    <span class="text-lg">✍️</span>
                    <span>Inscriptions</span>
                </a>
                <a href="{{ route('admin.institutions.manage') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('admin.institutions.manage') ? 'bg-gradient-to-r from-teal-50 to-emerald-50 text-slate-900 font-semibold border border-emerald-200' : 'text-slate-600 hover:bg-white' }}">
                    <span class="text-lg">🏢</span>
                    <span>Institutions</span>
                </a>
            </div>
        </div>

        <!-- Finance -->
        <div>
            <div class="text-xs uppercase tracking-[0.2em] text-slate-400 mb-2 px-3">Finance</div>
            <div class="space-y-1">
                <a href="{{ route('admin.payments.manage') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('admin.payments.manage') ? 'bg-gradient-to-r from-teal-50 to-emerald-50 text-slate-900 font-semibold border border-emerald-200' : 'text-slate-600 hover:bg-white' }}">
                    <span class="text-lg">💳</span>
                    <span>Paiements</span>
                </a>
                <a href="{{ route('admin.certificates.manage') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('admin.certificates.manage') ? 'bg-gradient-to-r from-teal-50 to-emerald-50 text-slate-900 font-semibold border border-emerald-200' : 'text-slate-600 hover:bg-white' }}">
                    <span class="text-lg">🏆</span>
                    <span>Certificats</span>
                </a>
            </div>
        </div>

        <!-- Retour -->
        <div class="pt-4 border-t border-slate-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-600 hover:bg-white">
                    <span class="text-lg">←</span>
                    <span>Quitter admin</span>
                </button>
            </form>
        </div>
    </div>
</div>
