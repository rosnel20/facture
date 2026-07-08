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

    /* ─── Recherche live ─────────────────────────────────────── */
    .search-field .spinner {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 15px; height: 15px;
        border: 2px solid #e2e8f0;
        border-top-color: #1c84ec;
        border-radius: 50%;
        animation: search-spin .6s linear infinite;
    }
    @keyframes search-spin { to { transform: translateY(-50%) rotate(360deg); } }
    #invoices-results { transition: opacity .12s ease; }
    #invoices-results.is-loading { opacity: .5; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto"
     x-data="invoiceLiveSearch(@js($search))"
     x-init="init()">

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-5 mb-7">
        <div>
            <p class="page-header-badge">Facturation</p>
            <h1 class="page-header-title">Historique</h1>
            <p class="page-header-sub" id="invoices-count">
                <strong style="color:#0f172a;">{{ $invoices->total() }}</strong> facture(s) enregistrée(s)
            </p>
        </div>

        {{-- Recherche --}}
        <form method="GET" action="{{ route('invoices.index') }}" class="search-wrapper" @submit.prevent="fetchResults()">
            <div class="search-field">
                <x-icon name="search" x-show="!loading"></x-icon>
                <span class="spinner" x-show="loading" x-cloak></span>
                <input type="text"
                       name="search"
                       x-model="search"
                       @input="onInput()"
                       placeholder="Client, numéro, téléphone…"
                       autocomplete="off">
            </div>
            <button type="submit" class="btn-search">
                Rechercher
            </button>
            <a href="{{ route('invoices.index') }}"
               x-show="search"
               x-cloak
               @click.prevent="search = ''; fetchResults()"
               style="font-size:12px;color:#94a3b8;white-space:nowrap;text-decoration:none;padding:4px;cursor:pointer;">
               Effacer
            </a>
        </form>
    </div>

    {{-- ══ Résultats (remplacés dynamiquement à chaque recherche) ══ --}}
    <div id="invoices-results" :class="{ 'is-loading': loading }" @click="onResultsClick($event)">
        @include('invoices.partials.results-list', ['invoices' => $invoices])
    </div>

</div>
@endsection

@push('scripts')
<script>
    function invoiceLiveSearch(initialSearch) {
        return {
            search: initialSearch || '',
            loading: false,
            timer: null,
            baseUrl: @js(route('invoices.index')),

            init() {
                // Gère le bouton "précédent/suivant" du navigateur après une recherche.
                window.addEventListener('popstate', () => {
                    const params = new URLSearchParams(window.location.search);
                    this.search = params.get('search') || '';
                    this.fetchResults(false);
                });
            },

            onInput() {
                clearTimeout(this.timer);
                this.timer = setTimeout(() => this.fetchResults(), 400);
            },

            // Intercepte uniquement les clics sur les liens de pagination pour rester
            // en AJAX, sans toucher aux actions des lignes (modifier / supprimer / whatsapp…).
            onResultsClick(event) {
                const link = event.target.closest('#invoices-pagination a');
                if (!link) return;
                event.preventDefault();
                this.fetchResults(true, link.href);
            },

            fetchResults(pushState = true, explicitUrl = null) {
                let url = explicitUrl;
                if (!url) {
                    const u = new URL(this.baseUrl, window.location.origin);
                    if (this.search) u.searchParams.set('search', this.search);
                    url = u.toString();
                }

                this.loading = true;

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.text())
                    .then(html => {
                        const doc = new DOMParser().parseFromString(html, 'text/html');

                        const newResults = doc.getElementById('invoices-results');
                        if (newResults) {
                            document.getElementById('invoices-results').innerHTML = newResults.innerHTML;
                        }

                        const newCount = doc.getElementById('invoices-count');
                        if (newCount) {
                            document.getElementById('invoices-count').innerHTML = newCount.innerHTML;
                        }

                        if (pushState) {
                            window.history.pushState({}, '', url);
                        }
                    })
                    .catch(() => {
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: { type: 'error', message: "Impossible de charger les résultats, réessaie." }
                        }));
                    })
                    .finally(() => { this.loading = false; });
            }
        }
    }
</script>
@endpush
