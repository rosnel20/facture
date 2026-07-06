@extends('layouts.app')

@section('title', 'Nouvelle facture')


@push('styles')
<style>
    /* ─── Floating label input ─────────────────────────────────── */
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
    /* Quand l'input est focus ou a une valeur → label monte */
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
    /* Icône dans le champ */
    .field .field-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #cbd5e1;
        pointer-events: none;
    }
    .field .field-icon svg { width: 16px; height: 16px; }

    /* Input article (pas de floating label, plus compact) */
    .item-input {
        width: 100%;
        padding: 9px 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
        font-size: 13px;
        color: #0f172a;
        outline: none;
        transition: border-color .15s, box-shadow .15s, background .15s;
    }
    .item-input:focus {
        border-color: #1c84ec;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(28,132,236,.10);
    }
    .item-input::placeholder { color: #cbd5e1; }

    /* Card sections */
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

    /* Grille articles header */
    .items-head {
        display: grid;
        grid-template-columns: 1fr 90px 130px 100px 36px;
        gap: 8px;
        padding: 0 4px 8px 4px;
        font-size: 10px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .7px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 10px;
    }
    .items-head .th-right { text-align: right; }

    /* Ligne article */
    .item-row {
        display: grid;
        grid-template-columns: 1fr 90px 130px 100px 36px;
        gap: 8px;
        align-items: center;
        background: #fafafa;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 10px 10px;
        margin-bottom: 8px;
        transition: border-color .15s;
    }
    .item-row:hover { border-color: #e0eefe; }

    .item-total {
        text-align: right;
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        padding-right: 4px;
    }
    .item-remove {
        width: 32px; height: 32px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: #fca5a5;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background .15s, color .15s;
    }
    .item-remove:hover { background: #fef2f2; color: #ef4444; }

    /* ─── Responsive : articles empilés sur mobile ──────────────── */
    @media (max-width: 767px) {
        .item-row {
            grid-template-columns: 1fr 1fr;
            grid-template-areas:
                "desig  desig"
                "qty    price"
                "total  remove";
            row-gap: 8px;
        }
        .item-row .item-designation { grid-area: desig; }
        .item-row .item-qty         { grid-area: qty; }
        .item-row .item-price       { grid-area: price; }
        .item-row .item-total       { grid-area: total; text-align: left; padding-right: 0; }
        .item-row .item-remove      { grid-area: remove; justify-self: end; }
    }

    @media (max-width: 380px) {
        .section-card { padding: 18px; }
        .page-header-title { font-size: 22px; }
        .btn-submit { width: 100%; min-width: 0; }
    }

    /* Résumé totaux */
    .totals-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        font-size: 13px;
        color: #64748b;
    }
    .totals-row.grand {
        border-top: 2px solid #f1f5f9;
        margin-top: 8px;
        padding-top: 12px;
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
    }
    .totals-row.grand .grand-amount { color: #1c84ec; }

    /* Btn submit */
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
        min-width: 230px;
        justify-content: center;
    }
    .btn-submit:hover:not(:disabled) { opacity: .92; transform: translateY(-1px); }
    .btn-submit:disabled { opacity: .65; cursor: not-allowed; }

    /* Btn ajouter article */
    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #1c84ec;
        background: #f0f7ff;
        border: none;
        border-radius: 10px;
        padding: 7px 14px;
        cursor: pointer;
        transition: background .15s;
    }
    .btn-add:hover { background: #e0eefe; }

    /* Error text */
    .err { font-size: 11px; color: #ef4444; margin-top: 4px; padding-left: 4px; }

    /* Page header */
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
<div class="max-w-4xl mx-auto" x-data="invoiceForm()" x-init="init()">

    {{-- En-tête page --}}
    <div class="mb-8">
        <p class="page-header-badge">Facturation</p>
        <h1 class="page-header-title">Nouvelle facture</h1>
        <p class="page-header-sub">Renseignez les informations ci-dessous pour générer une facture professionnelle.</p>
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

    <form method="POST" action="{{ route('invoices.store') }}" @submit="submitting = true">
        @csrf

        {{-- ── BLOC CLIENT ──────────────────────────────────────────── --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <x-icon name="user" class="w-5 h-5" style="color:#1c84ec" />
                </div>
                <div>
                    <div class="section-title">Informations client</div>
                    <div class="section-sub">Coordonnées du destinataire de la facture</div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                {{-- Nom --}}
                <div class="field">
                    <input type="text"
                           name="client_name"
                           id="client_name"
                           value="{{ old('client_name') }}"
                           placeholder=" "
                           class="{{ $errors->has('client_name') ? 'has-error' : '' }}"
                           autocomplete="name">
                    <label for="client_name">Nom complet du client *</label>
                    <span class="field-icon"><x-icon name="user" /></span>
                    @error('client_name') <div class="err">{{ $message }}</div> @enderror
                </div>

                {{-- Téléphone --}}
                <div class="field">
                    <input type="text"
                           name="client_phone"
                           id="client_phone"
                           value="{{ old('client_phone') }}"
                           placeholder=" "
                           class="{{ $errors->has('client_phone') ? 'has-error' : '' }}"
                           autocomplete="tel">
                    <label for="client_phone">Numéro WhatsApp *</label>
                    <span class="field-icon"><x-icon name="phone" /></span>
                    @error('client_phone') <div class="err">{{ $message }}</div> @enderror
                </div>

                {{-- Adresse --}}
                <div class="field md:col-span-2">
                    <input type="text"
                           name="client_address"
                           id="client_address"
                           value="{{ old('client_address') }}"
                           placeholder=" ">
                    <label for="client_address">Adresse (optionnelle)</label>
                    <span class="field-icon"><x-icon name="location" /></span>
                </div>
            </div>
        </div>

        {{-- ── BLOC ARTICLES ────────────────────────────────────────── --}}
        <div class="section-card">
            <div class="section-header flex-wrap">
                <div class="section-icon">
                    <x-icon name="cart" class="w-5 h-5" style="color:#1c84ec" />
                </div>
                <div style="flex:1; min-width:150px;">
                    <div class="section-title">Articles</div>
                    <div class="section-sub">Ajoutez les produits ou services facturés</div>
                </div>
                <button type="button" class="btn-add" @click="addItem()">
                    <x-icon name="plus" class="w-4 h-4" /> Ajouter un article
                </button>
            </div>

            {{-- En-têtes colonnes (desktop uniquement) --}}
            <div class="items-head hidden md:grid">
                <div>Désignation</div>
                <div>Quantité</div>
                <div>Prix unitaire</div>
                <div class="th-right">Sous-total</div>
                <div></div>
            </div>

            {{-- Lignes articles --}}
            <div>
                <template x-for="(item, index) in items" :key="index">
                    <div class="item-row">
                        <input type="text"
                               :name="`items[${index}][designation]`"
                               x-model="item.designation"
                               placeholder="Désignation du produit / service"
                               class="item-input item-designation"
                               required>

                        <input type="number"
                               :name="`items[${index}][quantity]`"
                               x-model.number="item.quantity"
                               min="1"
                               placeholder="Qté"
                               class="item-input item-qty"
                               @input="calculateTotal()"
                               required>

                        <input type="number"
                               step="0.01"
                               :name="`items[${index}][unit_price]`"
                               x-model.number="item.unit_price"
                               min="0"
                               placeholder="Prix (FCFA)"
                               class="item-input item-price"
                               @input="calculateTotal()"
                               required>

                        <div class="item-total"
                             x-text="formatMoney(item.quantity * item.unit_price)"></div>

                        <button type="button" class="item-remove" @click="removeItem(index)"
                                title="Supprimer">
                            <x-icon name="close" class="w-4 h-4" />
                        </button>
                    </div>
                </template>
            </div>
        </div>

        {{-- ── REMISE + OBSERVATION + TOTAUX ───────────────────────── --}}
        <div class="section-card">
            <div class="grid md:grid-cols-2 gap-5 mb-6">
                {{-- Remise --}}
                <div class="field">
                    <input type="number"
                           step="0.01"
                           name="discount"
                           id="discount"
                           x-model.number="discount"
                           @input="calculateTotal()"
                           min="0"
                           value="{{ old('discount', 0) }}"
                           placeholder=" ">
                    <label for="discount">Remise (FCFA)</label>
                    <span class="field-icon"><x-icon name="discount" /></span>
                </div>

                {{-- Observation --}}
                <div class="field">
                    <input type="text"
                           name="observation"
                           id="observation"
                           value="{{ old('observation') }}"
                           placeholder=" ">
                    <label for="observation">Observation / Note</label>
                    <span class="field-icon"><x-icon name="note" /></span>
                </div>
            </div>

            {{-- Résumé totaux --}}
            <div style="border-top:1px solid #f1f5f9; padding-top:16px;">
                <div style="max-width:320px; margin-left:auto;">
                    <div class="totals-row">
                        <span>Sous-total</span>
                        <span x-text="formatMoney(subtotal)"></span>
                    </div>
                    <div class="totals-row" style="color:#ef4444;">
                        <span>Remise</span>
                        <span x-text="'− ' + formatMoney(discount)"></span>
                    </div>
                    <div class="totals-row grand">
                        <span>Total à payer</span>
                        <span class="grand-amount" x-text="formatMoney(total)"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── BOUTON SUBMIT ────────────────────────────────────────── --}}
        <div style="display:flex; justify-content:flex-end; margin-top:4px;">
            <button type="submit" class="btn-submit" :disabled="submitting">
                <svg x-show="submitting" class="animate-spin" style="width:18px;height:18px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <template x-if="!submitting">
                    <span style="display:flex;align-items:center;gap:8px;">
                        <x-icon name="invoice" class="w-5 h-5" />
                        Générer la facture
                    </span>
                </template>
                <span x-show="submitting" x-cloak>Génération en cours…</span>
            </button>
        </div>

    </form>
</div>

<script>
function invoiceForm() {
    return {
        items: [{ designation: '', quantity: 1, unit_price: 0 }],
        discount: {{ old('discount', 0) }},
        subtotal: 0,
        total: 0,
        submitting: false,
        init() { this.calculateTotal(); },
        addItem() {
            this.items.push({ designation: '', quantity: 1, unit_price: 0 });
        },
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
                this.calculateTotal();
            }
        },
        calculateTotal() {
            this.subtotal = this.items.reduce((s, i) => s + (i.quantity * i.unit_price || 0), 0);
            this.total = Math.max(this.subtotal - (this.discount || 0), 0);
        },
        formatMoney(v) {
            return (v || 0).toLocaleString('fr-FR', { minimumFractionDigits: 0 }) + ' FCFA';
        }
    }
}
</script>
@endsection
