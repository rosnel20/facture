{{-- Contenu de la sidebar, partagé entre version desktop et drawer mobile --}}

<div class="px-6 pt-7 pb-6">
    <div class="flex items-center gap-3">
        <span class="w-11 h-11 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-800 flex items-center justify-center text-white font-bold text-sm shadow-soft">MT</span>
        <div>
            <p class="text-base font-bold text-slate-800 leading-tight tracking-tight">MUNDO TECH</p>
            <p class="text-xs text-slate-400 font-medium">Facturation</p>
        </div>
    </div>
</div>

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

<div class="px-4 pb-4">
    <div class="h-px bg-slate-200/60 mb-3 mx-2"></div>
    <a href="{{ route('settings.edit') }}"
       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all relative
              {{ request()->routeIs('settings.edit') ? 'bg-white text-brand-700 shadow-card' : 'text-slate-500 hover:bg-white/60 hover:text-slate-700' }}">
        <x-icon name="settings" class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('settings.edit') ? 'text-brand-600' : 'text-slate-400 group-hover:text-slate-500' }}" />
        <span>Paramètres</span>
    </a>
</div>

<div class="px-6 pb-7">
    <div class="text-[11px] text-slate-400 font-medium text-center pt-2">
        © {{ date('Y') }} MUNDO TECH
    </div>
</div>
