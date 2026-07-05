<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        /* ═══════════════════════════════════════════════
           BASE
        ═══════════════════════════════════════════════ */
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        /* ═══════════════════════════════════════════════
           FILIGRANE "PAYÉ"
           DomPDF : position:fixed s'applique à toutes les pages
        ═══════════════════════════════════════════════ */
        .watermark {
            position: fixed;
            top: 38%;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 110px;
            font-weight: bold;
            color: rgba(16, 185, 129, 0.10);   /* vert très transparent */
            letter-spacing: 12px;
            transform: rotate(-35deg);
            z-index: 0;
            pointer-events: none;
        }

        /* ═══════════════════════════════════════════════
           BANDE D'ACCENT HAUT
        ═══════════════════════════════════════════════ */
        .top-bar {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            height: 8px;
            width: 100%;
            margin-bottom: 0;
        }

        /* ═══════════════════════════════════════════════
           WRAPPER PRINCIPAL
        ═══════════════════════════════════════════════ */
        .page {
            padding: 28px 36px 28px 36px;
            position: relative;
            z-index: 1;
        }

        /* ═══════════════════════════════════════════════
           EN-TÊTE
        ═══════════════════════════════════════════════ */
        .header-table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: top; padding: 0; }

        .logo-cell { width: 55%; }
        .logo-cell img { height: 52px; margin-bottom: 6px; display: block; }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: -0.3px;
        }
        .company-tagline {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 1px;
            margin-bottom: 6px;
        }
        .company-meta {
            font-size: 9.5px;
            color: #475569;
            line-height: 1.7;
        }
        .company-meta span {
            color: #94a3b8;
            margin-right: 3px;
        }

        /* Badge FACTURE */
        .invoice-badge-cell { width: 45%; text-align: right; }
        .invoice-badge {
            display: inline-block;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            color: #ffffff;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 3px;
            padding: 7px 20px 6px 20px;
            border-radius: 6px;
            margin-bottom: 8px;
        }
        .invoice-meta {
            font-size: 10px;
            color: #64748b;
            line-height: 1.8;
            text-align: right;
        }
        .invoice-meta strong { color: #0f172a; }

        /* Séparateur élégant */
        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 18px 0 16px 0;
        }
        .divider-accent {
            border: none;
            border-top: 3px solid #0f172a;
            width: 48px;
            margin: 0 0 16px 0;
        }

        /* ═══════════════════════════════════════════════
           BLOC CLIENT + RÉCAPITULATIF
        ═══════════════════════════════════════════════ */
        .info-row { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-row td { vertical-align: top; }

        .client-block {
            width: 58%;
            padding-right: 20px;
        }
        .block-label {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            color: #94a3b8;
            margin-bottom: 8px;
        }
        .client-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #0f172a;
            border-radius: 0 6px 6px 0;
            padding: 10px 14px;
        }
        .client-name {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .client-detail {
            font-size: 9.5px;
            color: #64748b;
            line-height: 1.7;
        }

        .summary-block { width: 42%; }
        .summary-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 9.5px;
            color: #64748b;
            padding: 2px 0;
        }

        /* ═══════════════════════════════════════════════
           TABLEAU ARTICLES
        ═══════════════════════════════════════════════ */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        table.items thead tr {
            background: #0f172a;
        }
        table.items th {
            color: #ffffff;
            padding: 9px 12px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        table.items th.text-right { text-align: right; }

        table.items tbody tr { border-bottom: 1px solid #f1f5f9; }
        table.items tbody tr:nth-child(even) td { background: #f8fafc; }
        table.items td {
            padding: 8px 12px;
            font-size: 10.5px;
            color: #334155;
        }
        table.items td.text-right { text-align: right; }
        table.items td.designation { color: #0f172a; font-weight: 500; }
        table.items td.amount { font-weight: 600; color: #0f172a; }

        /* ═══════════════════════════════════════════════
           TOTAUX
        ═══════════════════════════════════════════════ */
        .totals-wrapper { width: 100%; margin-top: 6px; margin-bottom: 20px; }
        .totals-table {
            width: 280px;
            float: right;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 5px 12px;
            font-size: 10.5px;
        }
        .totals-table tr.subtotal td { color: #64748b; }
        .totals-table tr.discount td { color: #64748b; }
        .totals-table tr.total-row {
            background: #0f172a;
            border-radius: 6px;
        }
        .totals-table tr.total-row td {
            color: #ffffff;
            font-weight: bold;
            font-size: 13px;
            padding: 8px 12px;
        }
        .totals-table tr.total-row td:first-child {
            border-radius: 6px 0 0 6px;
        }
        .totals-table tr.total-row td:last-child {
            border-radius: 0 6px 6px 0;
            text-align: right;
        }
        .totals-table td.label-col { color: #334155; }
        .totals-table td.value-col { text-align: right; }
        .totals-sep {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 2px 0;
        }

        /* ═══════════════════════════════════════════════
           OBSERVATION
        ═══════════════════════════════════════════════ */
        .observation-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-left: 3px solid #f59e0b;
            border-radius: 0 6px 6px 0;
            padding: 8px 12px;
            font-size: 10px;
            color: #78350f;
            margin-bottom: 20px;
        }
        .observation-box strong { color: #92400e; }

        /* ═══════════════════════════════════════════════
           BAS DE PAGE : SIGNATURE + QR + FOOTER
        ═══════════════════════════════════════════════ */
        .bottom-row {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .bottom-row td { vertical-align: bottom; padding: 0; }

        .signature-block { width: 40%; }
        .signature-label {
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 36px;
        }
        .signature-line {
            border-top: 1px solid #cbd5e1;
            width: 140px;
            margin-top: 4px;
        }
        .signature-sublabel {
            font-size: 8px;
            color: #cbd5e1;
            margin-top: 3px;
        }

        .thanks-block { width: 30%; text-align: center; }
        .thanks-text {
            font-size: 10.5px;
            color: #0f172a;
            font-weight: bold;
            font-style: italic;
        }
        .thanks-sub {
            font-size: 8.5px;
            color: #94a3b8;
            margin-top: 3px;
        }

        .qr-block { width: 30%; text-align: right; }
        .qr-block img { height: 80px; border: 1px solid #e2e8f0; border-radius: 4px; padding: 3px; }
        .qr-label {
            font-size: 7.5px;
            color: #94a3b8;
            margin-top: 3px;
        }

        /* ═══════════════════════════════════════════════
           FOOTER FINAL
        ═══════════════════════════════════════════════ */
        .page-footer {
            margin-top: 16px;
            padding-top: 10px;
            border-top: 1px solid #f1f5f9;
            font-size: 8px;
            color: #94a3b8;
            text-align: center;
        }

        /* Clearfix */
        .cf::after { content: ''; display: table; clear: both; }
    </style>
</head>
<body>

    {{-- ── FILIGRANE PAYÉ ──────────────────────────────────────────── --}}
    <div class="watermark">PAYÉ</div>

    {{-- ── BANDE ACCENT ──────────────────────────────────────────────── --}}
    <div class="top-bar"></div>

    <div class="page">

        {{-- ── EN-TÊTE ──────────────────────────────────────────────── --}}
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if($settings->logo_path)
                        <img src="{{ storage_path('app/public/' . $settings->logo_path) }}" alt="Logo">
                    @endif
                    <div class="company-name">{{ $settings->company_name }}</div>
                    <div class="company-tagline">Solutions &amp; Services</div>
                    <div class="company-meta">
                        @if($settings->phone)
                            <span>📞</span> {{ $settings->phone }}<br>
                        @endif
                        @if($settings->whatsapp)
                            <span>💬</span> {{ $settings->whatsapp }}<br>
                        @endif
                        @if($settings->address)
                            <span>📍</span> {{ $settings->address }}<br>
                        @endif
                        @if($settings->email)
                            <span>✉</span> {{ $settings->email }}
                        @endif
                    </div>
                </td>
                <td class="invoice-badge-cell">
                    <div class="invoice-badge">FACTURE</div>
                    <div class="invoice-meta">
                        <strong>N° {{ $invoice->invoice_number }}</strong><br>
                        Date d'émission : {{ $invoice->created_at->format('d/m/Y') }}<br>
                        Heure : {{ $invoice->created_at->format('H:i') }}
                    </div>
                </td>
            </tr>
        </table>

        <hr class="divider">

        {{-- ── CLIENT + RÉCAPITULATIF ──────────────────────────────── --}}
        <table class="info-row">
            <tr>
                <td class="client-block">
                    <div class="block-label">Facturé à</div>
                    <div class="client-card">
                        <div class="client-name">{{ $invoice->client_name }}</div>
                        <div class="client-detail">
                            📞 {{ $invoice->client_phone }}<br>
                            @if($invoice->client_address)
                                📍 {{ $invoice->client_address }}
                            @endif
                        </div>
                    </div>
                </td>
                <td class="summary-block">
                    <div class="block-label">Récapitulatif</div>
                    <div class="summary-card">
                        <div class="summary-row">
                            <span>N° Facture</span>
                            <strong>{{ $invoice->invoice_number }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Date</span>
                            <strong>{{ $invoice->created_at->format('d/m/Y') }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Articles</span>
                            <strong>{{ $invoice->items->count() }}</strong>
                        </div>
                        <div class="summary-row" style="color:#0f172a; font-weight:bold; border-top:1px solid #e2e8f0; margin-top:4px; padding-top:4px;">
                            <span>Montant dû</span>
                            <span>{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- ── TABLEAU DES ARTICLES ─────────────────────────────────── --}}
        <table class="items">
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:47%;">Désignation</th>
                    <th style="width:13%;" class="text-right">Qté</th>
                    <th style="width:17%;" class="text-right">Prix unitaire</th>
                    <th style="width:18%;" class="text-right">Sous-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $i => $item)
                    <tr>
                        <td style="color:#94a3b8; font-size:9px;">{{ $i + 1 }}</td>
                        <td class="designation">{{ $item->designation }}</td>
                        <td class="text-right">{{ number_format($item->quantity, 0, ',', ' ') }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                        <td class="text-right amount">{{ number_format($item->line_total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ── TOTAUX ───────────────────────────────────────────────── --}}
        <div class="totals-wrapper cf">
            <table class="totals-table">
                <tr class="subtotal">
                    <td class="label-col">Sous-total</td>
                    <td class="value-col">{{ number_format($invoice->subtotal, 0, ',', ' ') }} FCFA</td>
                </tr>
                @if($invoice->discount > 0)
                <tr class="discount">
                    <td class="label-col" style="color:#ef4444;">Remise</td>
                    <td class="value-col" style="color:#ef4444;">− {{ number_format($invoice->discount, 0, ',', ' ') }} FCFA</td>
                </tr>
                @endif
                <tr><td colspan="2"><hr class="totals-sep"></td></tr>
                <tr class="total-row">
                    <td>TOTAL</td>
                    <td>{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</td>
                </tr>
            </table>
        </div>

        {{-- ── OBSERVATION ──────────────────────────────────────────── --}}
        @if($invoice->observation)
            <div class="observation-box">
                <strong>Note :</strong> {{ $invoice->observation }}
            </div>
        @endif

        {{-- ── SIGNATURE + MERCI + QR CODE ─────────────────────────── --}}
        <table class="bottom-row">
            <tr>
                <td class="signature-block">
                    <div class="signature-label">Signature &amp; Cachet</div>
                    <div class="signature-line"></div>
                    <div class="signature-sublabel">Autorisé par</div>
                </td>
                <td class="thanks-block">
                    <div class="thanks-text">
                        {{ $settings->footer_text ?? 'Merci pour votre confiance !' }}
                    </div>
                    <div class="thanks-sub">{{ $settings->company_name }}</div>
                </td>
                <td class="qr-block">
                    @if($invoice->qrcode_path && file_exists(storage_path('app/public/' . $invoice->qrcode_path)))
                        <img src="{{ storage_path('app/public/' . $invoice->qrcode_path) }}" alt="QR Code">
                        <div class="qr-label">Scannez pour vérifier l'authenticité</div>
                    @endif
                </td>
            </tr>
        </table>

        {{-- ── FOOTER ───────────────────────────────────────────────── --}}
        <div class="page-footer">
            {{ $settings->company_name }} &nbsp;·&nbsp;
            Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }} &nbsp;·&nbsp;
            {{ $invoice->invoice_number }}
        </div>

    </div>{{-- /page --}}

</body>
</html>