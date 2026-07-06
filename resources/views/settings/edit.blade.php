@extends('layouts.app')

@section('title', 'Paramètres')

@push('styles')
<style>
    /* ─── Floating label input (identique aux autres pages) ─────── */
    .field {
        position: relative;
        margin-bottom: 0;
    }
    .field input,
    .field textarea {
        width: 100%;
        padding: 22px 14px 10px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        font-size: 14px;
        line-height: 1.3;
        color: #0f172a;
        outline: none;
        transition: border-color .18s, background .18s, box-shadow .18s;
        appearance: none;
        -webkit-appearance: none;
    }
    .field input:focus,
    .field textarea:focus {
        border-color: #1c84ec;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(28,132,236,.12);
    }
    .field input.has-error,
    .field textarea.has-error {
        border-color: #ef4444;
        background: #fff5f5;
    }
    .field label {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        font-size: 13px;
        color: #94a3b8;
        pointer-events: none;
        transition: all .15s ease;
        font-weight: 500;
    }
    .field input:focus ~ label,
    .field input:not(:placeholder-shown) ~ label,
    .field textarea:focus ~ label,
    .field textarea:not(:placeholder-shown) ~ label {
        top: 9px;
        transform: none;
        font-size: 10px;
        color: #1c84ec;
        font-weight: 600;
        letter-spacing: .4px;
        text-transform: uppercase;
    }
    .field input.has-error:focus ~ label,
    .field input.has-error:not(:placeholder-shown) ~ label {
        color: #ef4444;
    }
    .field .field-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #cbd5e1;
        pointer-events: none;
    }
    .field .field-icon svg { width: 16px; height: 16px; }

    .err { font-size: 11px; color: #ef4444; margin-top: 4px; padding-left: 4px; }

    /* ─── Card sections (identique aux autres pages) ────────────── */
    .section-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        padding: 28px;
        margin-bottom: 18px;
        box-shadow: 0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
    }
    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
    }
    .section-icon {
        width: 40px; height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg,#f0f7ff,#e0eefe);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .section-icon svg { width: 18px; height: 18px; color: #1c84ec; }
    .section-title { font-size: 15px; font-weight: 700; color: #0f172a; }
    .section-sub   { font-size: 12px; color: #94a3b8; margin-top: 1px; }

    /* ─── Bloc logo ───────────────────────────────────────────── */
    .logo-drop {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        background: #f8fafc;
        border: 1.5px dashed #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
    }
    .logo-drop input[type="file"] {
        max-width: 100%;
    }

    @media (max-width: 380px) {
        .section-card { padding: 18px; }
        .page-header-title { font-size: 22px; }
        .btn-submit { width: 100%; min-width: 0; }
    }

    /* ─── Bouton submit (identique aux autres pages) ─────────────── */
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg,#1c84ec,#0e66ca);
        color: #ffffff;
        font-weight: 700;
        font-size: 14px;
        padding: 14px 32px;
        border-radius: 14px;
        border: none;
        cursor: pointer;
        transition: opacity .15s, transform .1s;
        box-shadow: 0 4px 14px rgba(28,132,236,.35);
        min-width: 200px;
        justify-content: center;
    }
    .btn-submit:hover:not(:disabled) { opacity: .92; transform: translateY(-1px); }
    .btn-submit:disabled { opacity: .65; cursor: not-allowed; }

    /* ─── Page header (identique aux autres pages) ──────────────── */
    .page-header-badge {
        font-size: 10px;
        font-weight: 800;
        color: #1c84ec;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 4px;
    }
    .page-header-title {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -.5px;
    }
    .page-header-sub { font-size: 13px; color: #94a3b8; margin-top: 4px; }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto" x-data="{ submitting: false }">

    {{-- En-tête page --}}
    <div class="mb-8">
        <p class="page-header-badge">Facturation</p>
        <h1 class="page-header-title">Paramètres de l'entreprise</h1>
        <p class="page-header-sub">Ces informations apparaissent sur chaque facture générée.</p>
    </div>

    @if($errors->any())
        <div class="section-card" style="border-color:#fecaca; background:#fff5f5; margin-bottom:18px;">
            <div style="display:flex;align-items:center;gap:8px;color:#ef4444;font-weight:700;margin-bottom:8px;">
                <x-icon name="alert" class="w-5 h-5" /> Veuillez corriger les erreurs suivantes
            </div>
            <ul style="margin:0;padding-left:20px;color:#ef4444;font-size:13px;line-height:1.8;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data"
          @submit="submitting = true">
        @csrf
        @method('PUT')

        {{-- ── BLOC LOGO ────────────────────────────────────────────── --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <x-icon name="image" class="w-5 h-5" style="color:#1c84ec" />
                </div>
                <div>
                    <div class="section-title">Logo de l'entreprise</div>
                    <div class="section-sub">Affiché en haut de chaque facture</div>
                </div>
            </div>

            <div class="logo-drop">
                @if($settings->logo_path)
                    <img src="{{ asset('storage/' . $settings->logo_path) }}" class="h-14 w-14 object-contain rounded-xl border border-brand-100 bg-white p-1">
                @else
                    <span class="h-14 w-14 rounded-xl bg-brand-50 flex items-center justify-center text-brand-400 flex-shrink-0">
                        <x-icon name="building" class="w-6 h-6" />
                    </span>
                @endif
                <input type="file" name="logo" accept="image/*"
                       class="text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:text-sm file:font-medium hover:file:bg-brand-100">
            </div>
        </div>

        {{-- ── BLOC COORDONNÉES ─────────────────────────────────────── --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <x-icon name="building" class="w-5 h-5" style="color:#1c84ec" />
                </div>
                <div>
                    <div class="section-title">Coordonnées</div>
                    <div class="section-sub">Informations affichées sur vos factures</div>
                </div>
            </div>

            <div class="grid md:grid-cols-1 gap-5">
                {{-- Nom entreprise --}}
                <div class="field">
                    <input type="text"
                           name="company_name"
                           id="company_name"
                           value="{{ old('company_name', $settings->company_name) }}"
                           placeholder=" "
                           class="{{ $errors->has('company_name') ? 'has-error' : '' }}"
                           required>
                    <label for="company_name">Nom de l'entreprise *</label>
                    <span class="field-icon"><x-icon name="building" /></span>
                    @error('company_name') <div class="err">{{ $message }}</div> @enderror
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    {{-- Téléphone --}}
                    <div class="field">
                        <input type="text"
                               name="phone"
                               id="phone"
                               value="{{ old('phone', $settings->phone) }}"
                               placeholder=" "
                               autocomplete="tel">
                        <label for="phone">Téléphone</label>
                        <span class="field-icon"><x-icon name="phone" /></span>
                    </div>

                    {{-- WhatsApp --}}
                    <div class="field">
                        <input type="text"
                               name="whatsapp"
                               id="whatsapp"
                               value="{{ old('whatsapp', $settings->whatsapp) }}"
                               placeholder=" ">
                        <label for="whatsapp">WhatsApp</label>
                        <span class="field-icon"><x-icon name="chat" /></span>
                    </div>
                </div>

                {{-- Adresse --}}
                <div class="field">
                    <input type="text"
                           name="address"
                           id="address"
                           value="{{ old('address', $settings->address) }}"
                           placeholder=" ">
                    <label for="address">Adresse</label>
                    <span class="field-icon"><x-icon name="location" /></span>
                </div>

                {{-- Email --}}
                <div class="field">
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email', $settings->email) }}"
                           placeholder=" "
                           autocomplete="email">
                    <label for="email">Email</label>
                    <span class="field-icon"><x-icon name="mail" /></span>
                </div>

                {{-- Pied de page --}}
                <div class="field">
                    <input type="text"
                           name="footer_text"
                           id="footer_text"
                           value="{{ old('footer_text', $settings->footer_text) }}"
                           placeholder=" ">
                    <label for="footer_text">Pied de page des factures</label>
                    <span class="field-icon"><x-icon name="footer" /></span>
                </div>
            </div>
        </div>

        {{-- ── BOUTON SUBMIT ────────────────────────────────────────── --}}
        <div style="display:flex; justify-content:flex-end;">
            <button type="submit" class="btn-submit" :disabled="submitting">
                <svg x-show="submitting" class="animate-spin" style="width:18px;height:18px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <template x-if="!submitting">
                    <span style="display:flex;align-items:center;gap:8px;">
                        <x-icon name="save" class="w-4 h-4" /> Enregistrer
                    </span>
                </template>
                <span x-show="submitting" x-cloak>Enregistrement…</span>
            </button>
        </div>
    </form>
</div>
@endsection
