{{-- Contenu de la sidebar, partagé entre version desktop et drawer mobile --}}

@php($__sidebarSettings = \App\Models\Setting::current())

<div class="px-6 pt-7 pb-6">
    <div class="flex items-center gap-3">
        @if($__sidebarSettings->logo_path)
            <img src="{{ asset('storage/' . $__sidebarSettings->logo_path) }}"
                 alt="{{ $__sidebarSettings->company_name }}"
                 class="w-11 h-11 rounded-2xl object-contain bg-white shadow-soft border border-brand-100 p-1.5">
        @else
            <span class="w-11 h-11 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-800 flex items-center justify-center text-white font-bold text-sm shadow-soft">MT</span>
        @endif
        <div>
            <p class="text-base font-bold text-slate-800 leading-tight tracking-tight">{{ $__sidebarSettings->company_name }}</p>
            <p class="text-xs text-slate-400 font-medium">Facturation</p>
        </div>
    </div>
</div>

{{-- Carte du compte connecté --}}
@auth
<div class="px-6 pb-5">
    <div class="flex items-center gap-3 bg-white/80 rounded-xl px-3 py-2.5 shadow-card border border-brand-100/60">
        <span class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-400 to-brand-700 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </span>
        <div class="min-w-0">
            <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p>
            <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
        </div>
    </div>
</div>
@endauth

<div class="px-6"><div class="h-px bg-slate-200/60"></div></div>

<nav class="flex-1 px-4 py-6 space-y-1">
    <p class="px-3 text-[10.5px] font-bold text-slate-400 uppercase tracking-widest mb-3">Navigation</p>

    <a href="{{ route('invoices.create') }}"
       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all relative
              {{ request()->routeIs('invoices.create') || request()->routeIs('invoices.store') ? 'bg-white text-brand-700 shadow-card' : 'text-slate-500 hover:bg-white/60 hover:text-slate-700' }}">
        <x-icon name="invoice" class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('invoices.create') || request()->routeIs('invoices.store') ? 'text-brand-600' : 'text-slate-400 group-hover:text-slate-500' }}" />
        <span>Nouvelle facture</span>
    </a>

    <a href="{{ route('invoices.index') }}"
       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all relative
              {{ request()->routeIs('invoices.index') ? 'bg-white text-brand-700 shadow-card' : 'text-slate-500 hover:bg-white/60 hover:text-slate-700' }}">
        <x-icon name="history" class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('invoices.index') ? 'text-brand-600' : 'text-slate-400 group-hover:text-slate-500' }}" />
        <span>Historique des factures</span>
    </a>
</nav>

<div class="px-4 pb-2">
    <div class="h-px bg-slate-200/60 mb-3 mx-2"></div>
    <a href="{{ route('settings.edit') }}"
       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all relative
              {{ request()->routeIs('settings.edit') ? 'bg-white text-brand-700 shadow-card' : 'text-slate-500 hover:bg-white/60 hover:text-slate-700' }}">
        <x-icon name="settings" class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('settings.edit') ? 'text-brand-600' : 'text-slate-400 group-hover:text-slate-500' }}" />
        <span>Paramètres</span>
    </a>
</div>

{{-- Bouton de déconnexion + modal de confirmation --}}
@auth
<div class="px-4 pb-3" x-data="{ confirmLogout: false }">

    <button @click="confirmLogout = true" type="button"
       class="w-full group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-500 hover:bg-red-50 hover:text-red-600 transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
             class="w-[18px] h-[18px] flex-shrink-0 text-slate-400 group-hover:text-red-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
        </svg>
        <span>Déconnexion</span>
    </button>

    {{-- Fond flouté + carte de confirmation --}}
    <div x-show="confirmLogout" x-cloak
         class="fixed inset-0 z-[90] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click.self="confirmLogout = false"
         @keydown.escape.window="confirmLogout = false">

        <div x-show="confirmLogout"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">

            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-red-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
            </div>

            <h3 class="text-base font-bold text-slate-800 mb-1.5">Se déconnecter ?</h3>
            <p class="text-sm text-slate-500 mb-6">
                Tu devras te reconnecter pour accéder à ton espace de facturation, {{ auth()->user()->name }}.
            </p>

            <div class="flex gap-3">
                <button type="button" @click="confirmLogout = false"
                    class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <form method="POST" action="{{ route('logout') }}" class="flex-1"
                      x-data="{ loading: false }" @submit="loading = true">
                    @csrf
                    <button type="submit" :disabled="loading"
                        class="w-full px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-red-500 hover:bg-red-600 disabled:opacity-70 disabled:cursor-not-allowed shadow-soft transition-colors flex items-center justify-center gap-2">
                        <svg x-show="loading" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                             class="w-4 h-4 animate-spin">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
                            <path fill="currentColor" class="opacity-75" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span x-text="loading ? 'Déconnexion...' : 'Déconnexion'"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endauth

<div class="px-6 pb-7">
    <div class="text-[11px] text-slate-400 font-medium text-center pt-2">
        © {{ date('Y') }} {{ $__sidebarSettings->company_name }}
    </div>
</div>
