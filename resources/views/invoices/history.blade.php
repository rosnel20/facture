@extends('layouts.app')

@section('title', 'Historique des factures')


@push('styles')
<style>
    .page-header-badge  { font-size:10px; font-weight:800; color:#1c84ec; text-transform:uppercase; letter-spacing:2px; margin-bottom:4px; }
    .page-header-title  { font-size:26px; font-weight:800; color:#0f172a; letter-spacing:-.5px; }
    .page-header-sub    { font-size:13px; color:#94a3b8; margin-top:4px; }

    /* ─── Barre de recherche ──────────────────────────────────── */
    .search-wrapper {
        display: flex;
        gap: 10px;
        align-items: center;
        width: 100%;
        flex-wrap: wrap;
    }
    .search-field {
        position: relative;
        flex: 1 1 200px;
    }
    .search-field svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px; height: 16px;
        color: #94a3b8;
        pointer-events: none;
    }
    .search-field input {
        width: 100%;
        padding: 11px 14px 11px 40px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        font-size: 13px;
        color: #0f172a;
        outline: none;
        transition: border-color .15s, background .15s, box-shadow .15s;
        min-width: 0;
    }
    .search-field input:focus {
        border-color: #1c84ec;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(28,132,236,.12);
    }
    .search-field input::placeholder { color: #cbd5e1; }

    .btn-search {
        padding: 11px 20px;
        background: linear-gradient(135deg,#1c84ec,#0e66ca);
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: opacity .15s, transform .1s;
        box-shadow: 0 2px 8px rgba(28,132,236,.3);
        white-space: nowrap;
    }
    .btn-search:hover { opacity: .9; transform: translateY(-1px); }

    /* ─── Table desktop ──────────────────────────────────────── */
    .data-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(15,23,42,.06);
    }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: #f8fafc; border-bottom: 2px solid #f1f5f9; }
    .data-table th {
        padding: 12px 18px;
        text-align: left;
        font-size: 10px;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .8px;
        white-space: nowrap;
    }
    .data-table th.th-right { text-align: right; }
    .data-table tbody tr {
        border-bottom: 1px solid #f8fafc;
        transition: background .12s;
    }
    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table tbody tr:hover { background: #fafbff; }
    .data-table td {
        padding: 13px 18px;
        font-size: 13px;
        color: #334155;
        vertical-align: middle;
    }
    .data-table td.td-right { text-align: right; }

    /* Badges */
    .num-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 700;
        color: #0e66ca;
        background: #e0eefe;
        padding: 3px 10px;
        border-radius: 20px;
    }
    .amount-cell {
        font-weight: 700;
        color: #0f172a;
        font-size: 13px;
    }
    .date-chip {
        font-size: 11px;
        color: #94a3b8;
        background: #f8fafc;
        border-radius: 6px;
        padding: 2px 8px;
        display: inline-block;
    }

    /* ─── Cartes mobile ──────────────────────────────────────── */
    .mobile-card {
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 18px;
        padding: 16px;
        box-shadow: 0 1px 3px rgba(15,23,42,.05);
    }
    .mobile-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    /* État vide */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #94a3b8;
    }
    .empty-state svg { width: 48px; height: 48px; color: #e2e8f0; margin: 0 auto 12px; display:block; }
    .empty-state p { font-size: 14px; margin: 0; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-5 mb-7">
        <div>
            <p class="page-header-badge">Facturation</p>
            <h1 class="page-header-title">Historique</h1>
            <p class="page-header-sub">
                <strong style="color:#0f172a;">{{ $invoices->total() }}</strong> facture(s) enregistrée(s)
            </p>
        </div>

        {{-- Recherche --}}
        <form method="GET" action="{{ route('invoices.index') }}" class="search-wrapper">
            <div class="search-field">
                <x-icon name="search" />
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Client, numéro, téléphone…"
                       autocomplete="off">
            </div>
            <button type="submit" class="btn-search">
                Rechercher
            </button>
            @if($search)
                <a href="{{ route('invoices.index') }}"
                   style="font-size:12px;color:#94a3b8;white-space:nowrap;text-decoration:none;padding:4px;">
                   Effacer
                </a>
            @endif
        </form>
    </div>

    {{-- ══ VUE TABLEAU (desktop) ══════════════════════════════════════ --}}
    <div class="data-card hidden md:block">
        <table class="data-table">
            <thead>
                <tr>
                    <th>N° Facture</th>
                    <th>Client</th>
                    <th>Téléphone</th>
                    <th>Date</th>
                    <th class="th-right">Montant</th>
                    <th class="th-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invoices as $invoice)
                    <tr>
                        <td>
                            <span class="num-badge">
                                <x-icon name="invoice" class="w-3.5 h-3.5" />
                                {{ $invoice->invoice_number }}
                            </span>
                        </td>
                        <td style="font-weight:600; color:#0f172a;">{{ $invoice->client_name }}</td>
                        <td style="color:#64748b;">{{ $invoice->client_phone }}</td>
                        <td>
                            <span class="date-chip">{{ $invoice->created_at->format('d/m/Y') }}</span>
                        </td>
                        <td class="td-right">
                            <span class="amount-cell">{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</span>
                        </td>
                        <td class="td-right">
                            @include('invoices.partials.row-actions', ['invoice' => $invoice])
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <x-icon name="empty" />
                                <p>Aucune facture trouvée.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ══ VUE CARTES (mobile) ════════════════════════════════════════ --}}
    <div class="md:hidden space-y-3">
        @forelse ($invoices as $invoice)
            <div class="mobile-card">
                <div class="mobile-card-header">
                    <div>
                        <span class="num-badge" style="margin-bottom:6px;display:inline-flex;">
                            {{ $invoice->invoice_number }}
                        </span>
                        <div style="font-weight:700; color:#0f172a; font-size:14px; margin-top:4px;">
                            {{ $invoice->client_name }}
                        </div>
                    </div>
                    <div style="font-weight:800; color:#0f172a; font-size:14px;">
                        {{ number_format($invoice->total, 0, ',', ' ') }} FCFA
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:11px; color:#94a3b8; margin-bottom:12px;">
                    <span style="display:flex;align-items:center;gap:4px;">
                        <x-icon name="phone" class="w-3.5 h-3.5" /> {{ $invoice->client_phone }}
                    </span>
                    <span class="date-chip">{{ $invoice->created_at->format('d/m/Y') }}</span>
                </div>
                @include('invoices.partials.row-actions', ['invoice' => $invoice])
            </div>
        @empty
            <div class="mobile-card">
                <div class="empty-state">
                    <x-icon name="empty" />
                    <p>Aucune facture pour le moment.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $invoices->links() }}
    </div>

</div>
@endsection
