@extends('layouts.app')

@section('title', 'Facture créée')

@push('styles')
<style>
    .success-wrap {
        max-width: 640px;
        margin: 0 auto;
    }

    /* ─── Icône de succès animée ─────────────────────────────────── */
    .success-icon {
        width: 84px; height: 84px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: linear-gradient(135deg,#34d399,#059669);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 10px 30px -8px rgba(5,150,105,.5);
        animation: pop .45s cubic-bezier(.34,1.56,.64,1);
    }
    .success-icon svg { width: 40px; height: 40px; color: #fff; }
    @keyframes pop {
        0%   { transform: scale(0); opacity: 0; }
        60%  { transform: scale(1.08); opacity: 1; }
        100% { transform: scale(1); }
    }

    .success-title { font-size: 24px; font-weight: 800; color: #0f172a; text-align: center; letter-spacing: -.5px; }
    .success-sub   { font-size: 13.5px; color: #94a3b8; text-align: center; margin-top: 6px; }

    /* ─── Carte récap facture ────────────────────────────────────── */
    .recap-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        padding: 24px;
        margin-top: 28px;
        box-shadow: 0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
    }
    .recap-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 16px;
        margin-bottom: 16px;
        border-bottom: 1px dashed #e2e8f0;
    }
    .recap-num {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 800;
        color: #0e66ca;
        background: #e0eefe;
        padding: 5px 12px;
        border-radius: 20px;
    }
    .recap-date { font-size: 11.5px; color: #94a3b8; }
    .recap-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 7px 0;
        font-size: 13.5px;
        color: #64748b;
    }
    .recap-row span:last-child { color: #0f172a; font-weight: 600; text-align: right; }
    .recap-row.grand {
        border-top: 2px solid #f1f5f9;
        margin-top: 6px;
        padding-top: 14px;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
    }
    .recap-row.grand span:last-child { color: #1c84ec; }

    /* ─── Actions rapides ────────────────────────────────────────── */
    .actions-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 22px;
    }
    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 13px 14px;
        border-radius: 14px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: opacity .15s, transform .1s;
    }
    .action-btn:hover { opacity: .92; transform: translateY(-1px); }
    .action-btn svg { width: 17px; height: 17px; flex-shrink: 0; }

    .action-whatsapp {
        grid-column: 1 / -1;
        background: linear-gradient(135deg,#25d366,#128c4a);
        color: #fff;
        box-shadow: 0 6px 18px -4px rgba(18,140,74,.45);
        padding: 15px 14px;
        font-size: 14.5px;
    }
    .action-pdf {
        background: linear-gradient(135deg,#1c84ec,#0e66ca);
        color: #fff;
        box-shadow: 0 4px 14px rgba(28,132,236,.3);
    }
    .action-download {
        background: #f1f5f9;
        color: #334155;
    }

    /* ─── Suite ──────────────────────────────────────────────────── */
    .continue-row {
        display: flex;
        justify-content: center;
        gap: 22px;
        margin-top: 26px;
        flex-wrap: wrap;
    }
    .continue-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        transition: color .15s;
    }
    .continue-link:hover { color: #1c84ec; }
    .continue-link svg { width: 15px; height: 15px; }

    @media (max-width: 420px) {
        .actions-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="success-wrap">

    <div class="success-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
        </svg>
    </div>

    <h1 class="success-title">Facture générée avec succès</h1>
    <p class="success-sub">Le PDF est prêt. Choisissez comment continuer ci-dessous.</p>

    {{-- ── RÉCAPITULATIF ─────────────────────────────────────────── --}}
    <div class="recap-card">
        <div class="recap-head">
            <span class="recap-num">
                <x-icon name="invoice" class="w-3.5 h-3.5" />
                {{ $invoice->invoice_number }}
            </span>
            <span class="recap-date">{{ $invoice->created_at->format('d/m/Y à H:i') }}</span>
        </div>

        <div class="recap-row">
            <span>Client</span>
            <span>{{ $invoice->client_name }}</span>
        </div>
        <div class="recap-row">
            <span>Téléphone</span>
            <span>{{ $invoice->client_phone }}</span>
        </div>
        <div class="recap-row">
            <span>Articles</span>
            <span>{{ $invoice->items->count() }} ligne(s)</span>
        </div>
        @if($invoice->discount > 0)
            <div class="recap-row">
                <span>Remise</span>
                <span>− {{ number_format($invoice->discount, 0, ',', ' ') }} FCFA</span>
            </div>
        @endif
        <div class="recap-row grand">
            <span>Total</span>
            <span>{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</span>
        </div>

        {{-- ── ACTIONS RAPIDES ──────────────────────────────────── --}}
        <div class="actions-grid">
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $invoice->client_phone) }}?text={{ urlencode(
                    "Bonjour {$invoice->client_name}\nMerci pour votre achat chez {$settings->company_name}.\nVeuillez trouver votre facture : " . route('invoices.download', $invoice) . "\nNous restons à votre disposition."
                ) }}"
               target="_blank"
               class="action-btn action-whatsapp">
                <x-icon name="chat" class="w-5 h-5" />
                Envoyer sur WhatsApp
            </a>

            <a href="{{ route('invoices.show', $invoice) }}" target="_blank" class="action-btn action-pdf">
                <x-icon name="eye" />
                Voir le PDF
            </a>

            <a href="{{ route('invoices.download', $invoice) }}" class="action-btn action-download">
                <x-icon name="download" />
                Télécharger
            </a>
        </div>
    </div>

    {{-- ── CONTINUER ─────────────────────────────────────────────── --}}
    <div class="continue-row">
        <a href="{{ route('invoices.create') }}" class="continue-link">
            <x-icon name="plus" />
            Créer une nouvelle facture
        </a>
        <a href="{{ route('invoices.index') }}" class="continue-link">
            <x-icon name="history" />
            Voir l'historique
        </a>
    </div>
</div>
@endsection
