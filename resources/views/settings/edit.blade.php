@extends('layouts.app')

@section('title', 'Paramètres')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="mb-8">
        <p class="text-[11px] font-bold text-brand-500 uppercase tracking-widest mb-1">Facturation</p>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Paramètres de l'entreprise</h1>
        <p class="text-slate-400 text-sm mt-1">Ces informations apparaissent sur chaque facture générée.</p>
    </div>

    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data"
          x-data="{ submitting: false }" @submit="submitting = true"
          class="bg-white rounded-2xl shadow-card border border-slate-100 p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                <x-icon name="image" class="w-3.5 h-3.5 text-slate-400" /> Logo
            </label>
            <div class="flex items-center gap-4">
                @if($settings->logo_path)
                    <img src="{{ asset('storage/' . $settings->logo_path) }}" class="h-14 w-14 object-contain rounded-xl border border-slate-100 bg-slate-50 p-1">
                @else
                    <span class="h-14 w-14 rounded-xl bg-brand-50 flex items-center justify-center text-brand-400">
                        <x-icon name="building" class="w-6 h-6" />
                    </span>
                @endif
                <input type="file" name="logo" accept="image/*" class="text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:text-sm file:font-medium hover:file:bg-brand-100">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 flex items-center gap-1.5">
                <x-icon name="building" class="w-3.5 h-3.5 text-slate-400" /> Nom de l'entreprise *
            </label>
            <input type="text" name="company_name" value="{{ old('company_name', $settings->company_name) }}" required
                   class="w-full rounded-xl border-2 border-slate-300 bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition text-sm shadow-sm">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 flex items-center gap-1.5">
                    <x-icon name="phone" class="w-3.5 h-3.5 text-slate-400" /> Téléphone
                </label>
                <input type="text" name="phone" value="{{ old('phone', $settings->phone) }}"
                       class="w-full rounded-xl border-2 border-slate-300 bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition text-sm shadow-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 flex items-center gap-1.5">
                    <x-icon name="chat" class="w-3.5 h-3.5 text-slate-400" /> WhatsApp
                </label>
                <input type="text" name="whatsapp" value="{{ old('whatsapp', $settings->whatsapp) }}"
                       class="w-full rounded-xl border-2 border-slate-300 bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition text-sm shadow-sm">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 flex items-center gap-1.5">
                <x-icon name="location" class="w-3.5 h-3.5 text-slate-400" /> Adresse
            </label>
            <input type="text" name="address" value="{{ old('address', $settings->address) }}"
                   class="w-full rounded-xl border-2 border-slate-300 bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition text-sm shadow-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 flex items-center gap-1.5">
                <x-icon name="mail" class="w-3.5 h-3.5 text-slate-400" /> Email
            </label>
            <input type="email" name="email" value="{{ old('email', $settings->email) }}"
                   class="w-full rounded-xl border-2 border-slate-300 bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition text-sm shadow-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 flex items-center gap-1.5">
                <x-icon name="footer" class="w-3.5 h-3.5 text-slate-400" /> Pied de page des factures
            </label>
            <input type="text" name="footer_text" value="{{ old('footer_text', $settings->footer_text) }}"
                   class="w-full rounded-xl border-2 border-slate-300 bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-100 transition text-sm shadow-sm">
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" :disabled="submitting"
                    class="bg-brand-600 hover:bg-brand-700 disabled:opacity-70 text-white font-semibold px-6 py-3 rounded-xl shadow-soft transition flex items-center gap-2 min-w-[180px] justify-center text-sm">
                <svg x-show="submitting" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span x-show="!submitting" class="flex items-center gap-2"><x-icon name="save" class="w-4 h-4" /> Enregistrer</span>
                <span x-show="submitting" x-cloak>Enregistrement...</span>
            </button>
        </div>
    </form>
</div>
@endsection
