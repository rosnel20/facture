{{-- Ce partial est rendu deux fois :
     1) à l'intérieur de #invoices-results lors du chargement normal de la page
     2) tel quel (page complète) lors des requêtes AJAX de la recherche live —
        le JS va simplement piocher le contenu de #invoices-results dans la réponse. --}}

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
<div class="mt-6" id="invoices-pagination">
    {{ $invoices->links() }}
</div>
