<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="nav-shell backdrop-blur fixed top-0 left-0 right-0 z-[200]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center gap-4 py-3">
            <div class="flex-1 min-w-[220px]">
                <a href="{{ route(auth()->check() ? 'dashboard' : 'home') }}" wire:navigate>
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60 flex items-center justify-center">
                            <x-application-logo class="block h-6 w-6 fill-current" style="color: var(--teal);" />
                        </div>
                        <div class="leading-tight">
                            <div class="text-sm uppercase tracking-[0.3em] text-slate-500">Loucks Health</div>
                            <div class="text-base font-semibold text-slate-900">Academie</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="hidden lg:flex items-center gap-6">
                @if(auth()->check() && auth()->user()->hasRole('admin'))
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" wire:navigate>
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('admin.categories.manage')" :active="request()->routeIs('admin.categories.manage')" wire:navigate>
                        Categories
                    </x-nav-link>
                    <x-nav-link :href="route('admin.users.manage')" :active="request()->routeIs('admin.users.manage')" wire:navigate>
                        Utilisateurs
                    </x-nav-link>
                    <x-nav-link :href="route('admin.courses.manage')" :active="request()->routeIs('admin.courses.manage')" wire:navigate>
                        Cours
                    </x-nav-link>
                @elseif(auth()->check() && auth()->user()->hasRole('apprenant'))
                    <x-nav-link :href="route('apprenant.dashboard')" :active="request()->routeIs('apprenant.dashboard')" wire:navigate>
                        Tableau de bord
                    </x-nav-link>
                    <x-nav-link :href="route('apprenant.courses.catalogue')" :active="request()->routeIs('apprenant.courses.catalogue')" wire:navigate>
                        Les cours
                    </x-nav-link>
                    <x-nav-link :href="route('apprenant.progress')" :active="request()->routeIs('apprenant.progress')" wire:navigate>
                        Progression
                    </x-nav-link>
                @elseif(auth()->check() && auth()->user()->hasRole('institution'))
                    <x-nav-link :href="route('institution.dashboard')" :active="request()->routeIs('institution.dashboard')" wire:navigate>
                        Institution
                    </x-nav-link>
                @endif
            </div>

            <div class="hidden lg:flex items-center gap-3 ml-auto">
                @if(auth()->check() && auth()->user()->hasRole('formateur'))
                    <a href="{{ route('courses.manage') }}" class="nav-pill px-4 py-2 rounded-full text-sm font-semibold text-slate-900 hover:shadow-md transition">
                        Creer un cours
                    </a>
                @elseif(auth()->check() && auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.courses.manage') }}" wire:navigate class="nav-pill px-4 py-2 rounded-full text-sm font-semibold text-slate-900 hover:shadow-md transition">
                        Gerer les cours
                    </a>
                @elseif(auth()->check() && auth()->user()->hasRole('institution'))
                    <a href="{{ route('institution.dashboard') }}" wire:navigate class="nav-pill px-4 py-2 rounded-full text-sm font-semibold text-slate-900 hover:shadow-md transition">
                        Espace institution
                    </a>
                @endif

                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-slate-600 bg-white hover:text-slate-900 focus:outline-none transition">
                            @if(auth()->check())
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                            @else
                                <div class="text-slate-500">Invite</div>
                            @endif
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-xs uppercase tracking-[0.2em] text-slate-400">Raccourcis</div>
                        @if(auth()->check() && auth()->user()->hasRole('admin'))
                            <x-dropdown-link :href="route('admin.dashboard')" wire:navigate>Dashboard admin</x-dropdown-link>
                            <x-dropdown-link :href="route('admin.categories.manage')" wire:navigate>Categories</x-dropdown-link>
                            <x-dropdown-link :href="route('admin.courses.manage')" wire:navigate>Gestion des cours</x-dropdown-link>
                        @elseif(auth()->check() && auth()->user()->hasRole('apprenant'))
                            <x-dropdown-link :href="route('apprenant.courses.catalogue')" wire:navigate>Catalogue</x-dropdown-link>
                        @elseif(auth()->check() && auth()->user()->hasRole('institution'))
                            <x-dropdown-link :href="route('institution.dashboard')" wire:navigate>Dashboard institution</x-dropdown-link>
                        @endif
                        @if(auth()->check())
                            <x-dropdown-link :href="route('profile')" wire:navigate>Parametres du profil</x-dropdown-link>
                        @endif
                        <div class="my-2 border-t border-slate-200"></div>
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>Deconnexion</x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="ml-auto lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->check() && auth()->user()->hasRole('admin'))
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" wire:navigate>
                    Dashboard
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.categories.manage')" :active="request()->routeIs('admin.categories.manage')" wire:navigate>
                    Categories
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.manage')" :active="request()->routeIs('admin.users.manage')" wire:navigate>
                    Utilisateurs
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.courses.manage')" :active="request()->routeIs('admin.courses.manage')" wire:navigate>
                    Cours
                </x-responsive-nav-link>
            @elseif(auth()->check() && auth()->user()->hasRole('formateur'))
                <x-responsive-nav-link :href="route('courses.catalogue')" :active="request()->routeIs('courses.catalogue')" wire:navigate>
                    Mes cours
                </x-responsive-nav-link>
            @elseif(auth()->check() && auth()->user()->hasRole('apprenant'))
                <x-responsive-nav-link :href="route('apprenant.dashboard')" :active="request()->routeIs('apprenant.dashboard')" wire:navigate>
                    Tableau de bord
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('apprenant.courses.catalogue')" :active="request()->routeIs('apprenant.courses.catalogue')" wire:navigate>
                    Les cours
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('apprenant.progress')" :active="request()->routeIs('apprenant.progress')" wire:navigate>
                    Progression
                </x-responsive-nav-link>
            @elseif(auth()->check() && auth()->user()->hasRole('institution'))
                <x-responsive-nav-link :href="route('institution.dashboard')" :active="request()->routeIs('institution.dashboard')" wire:navigate>
                    Institution
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-slate-200">
            <div class="px-4">
                @if(auth()->check())
                    <div class="font-medium text-base text-slate-900" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="font-medium text-sm text-slate-500">{{ auth()->user()->email }}</div>
                @else
                    <div class="font-medium text-base text-slate-900">Invite</div>
                @endif
            </div>
            <div class="mt-3 space-y-1">
                @if(auth()->check())
                    <x-responsive-nav-link :href="route('profile')" wire:navigate>
                        Parametres du profil
                    </x-responsive-nav-link>
                @endif
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>Deconnexion</x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
