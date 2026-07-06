<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Invoice;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Historique des factures, avec recherche simple.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $invoices = Invoice::query()
            ->when($search, function ($query, $search) {
                $query->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhere('client_phone', 'like', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('invoices.history', compact('invoices', 'search'));
    }

    /**
     * Formulaire de création d'une nouvelle facture.
     */
    public function create()
    {
        return view('invoices.create');
    }

    /**
     * Enregistre la facture, génère le QR code puis le PDF.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $data = $request->validated();

        // Calcul du sous-total à partir des articles
        $subtotal = 0;
        foreach ($data['items'] as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $discount = $data['discount'] ?? 0;
        $total = max($subtotal - $discount, 0);

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateNextNumber(),
            'client_name'    => $data['client_name'],
            'client_phone'   => $data['client_phone'],
            'client_address' => $data['client_address'] ?? null,
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'total'          => $total,
            'observation'    => $data['observation'] ?? null,
        ]);

        foreach ($data['items'] as $item) {
            $invoice->items()->create([
                'designation' => $item['designation'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'line_total'  => $item['quantity'] * $item['unit_price'],
            ]);
        }

        $this->generateQrCode($invoice);
        $this->generatePdf($invoice);

        return redirect()
            ->route('invoices.success', $invoice)
            ->with('success', 'Facture ' . $invoice->invoice_number . ' générée avec succès.');
    }

    /**
     * Génère le QR code SVG pointant vers la page publique de vérification.
     *
     * Compatibilité universelle : on génère le SVG manuellement avec
     * la librairie chillerlan/php-qrcode (API stable) OU en fallback
     * avec endroid/qr-code v4/v5 selon la version installée.
     * Si aucune n'est disponible, on saute silencieusement le QR code.
     */
    protected function generateQrCode(Invoice $invoice): void
    {
        $url  = route('invoices.verify', $invoice->invoice_number);
        $path = 'invoices/qrcodes/' . $invoice->invoice_number . '.svg';
        $svg  = null;

        // ── Tentative 1 : endroid/qr-code v4 (Writer instancié directement) ──
        if ($svg === null && class_exists(\Endroid\QrCode\QrCode::class)) {
            try {
                $qrCode = \Endroid\QrCode\QrCode::create($url)
                    ->setSize(200)
                    ->setMargin(10);

                $writer = new \Endroid\QrCode\Writer\SvgWriter();
                $result = $writer->write($qrCode);
                $svg    = $result->getString();
            } catch (\Throwable) {
                $svg = null;
            }
        }

        // ── Tentative 2 : endroid/qr-code v3 (Builder façade) ──────────────
        if ($svg === null && class_exists(\Endroid\QrCode\Builder\Builder::class)) {
            try {
                $builder = new \Endroid\QrCode\Builder\Builder(
                    writer: new \Endroid\QrCode\Writer\SvgWriter(),
                    data: $url,
                    size: 200,
                    margin: 10,
                );
                $svg = $builder->build()->getString();
            } catch (\Throwable) {
                $svg = null;
            }
        }

        // ── Tentative 3 : chillerlan/php-qrcode ────────────────────────────
        if ($svg === null && class_exists(\chillerlan\QRCode\QRCode::class)) {
            try {
                $options = new \chillerlan\QRCode\QROptions([
                    'outputType' => \chillerlan\QRCode\Output\QROutputInterface::OUTPUT_MARKUP_SVG,
                ]);
                $svg = (new \chillerlan\QRCode\QRCode($options))->render($url);
            } catch (\Throwable) {
                $svg = null;
            }
        }

        // ── Fallback : QR code SVG minimaliste via Google Charts (HTTP) ────
        if ($svg === null) {
            try {
                $encoded = urlencode($url);
                $svg = @file_get_contents(
                    "https://api.qrserver.com/v1/create-qr-code/?size=200x200&format=svg&data={$encoded}",
                    false,
                    stream_context_create(['http' => ['timeout' => 5]])
                ) ?: null;
            } catch (\Throwable) {
                $svg = null;
            }
        }

        if ($svg !== null) {
            Storage::disk('public')->put($path, $svg);
            $invoice->update(['qrcode_path' => $path]);
        }
        // Si aucune méthode ne fonctionne, on continue sans QR code
        // (le PDF sera généré, juste sans QR code)
    }

    /**
     * Génère le PDF de la facture via DomPDF et l'enregistre sur le disque.
     */
    protected function generatePdf(Invoice $invoice): void
    {
        $settings = Setting::current();
        $invoice->load('items');

        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice'  => $invoice,
            'settings' => $settings,
        ])->setPaper('a4', 'portrait');

        $path = 'invoices/pdf/' . $invoice->invoice_number . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        $invoice->update(['pdf_path' => $path]);
    }

    /**
     * Page de confirmation moderne affichée juste après la création
     * d'une facture, avec récapitulatif et actions rapides
     * (voir le PDF, télécharger, envoyer sur WhatsApp).
     */
    public function success(Invoice $invoice)
    {
        $invoice->load('items');
        $settings = Setting::current();

        return view('invoices.success', compact('invoice', 'settings'));
    }

    /**
     * Affiche le PDF dans le navigateur.
     */
    public function show(Invoice $invoice)
    {
        if (! $invoice->pdf_path || ! Storage::disk('public')->exists($invoice->pdf_path)) {
            $this->generatePdf($invoice);
        }

        return response(Storage::disk('public')->get($invoice->pdf_path))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $invoice->invoice_number . '.pdf"');
    }

    /**
     * Télécharge le PDF de la facture.
     */
    public function download(Invoice $invoice)
    {
        if (! $invoice->pdf_path || ! Storage::disk('public')->exists($invoice->pdf_path)) {
            $this->generatePdf($invoice);
        }

        return Storage::disk('public')->download(
            $invoice->pdf_path,
            $invoice->invoice_number . '.pdf'
        );
    }

    /**
     * Supprime une facture (et son PDF / QR code associés).
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->pdf_path) {
            Storage::disk('public')->delete($invoice->pdf_path);
        }
        if ($invoice->qrcode_path) {
            Storage::disk('public')->delete($invoice->qrcode_path);
        }

        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Facture supprimée.');
    }

    /**
     * Page publique de vérification d'une facture (cible du QR code).
     */
    public function verify(string $invoiceNumber)
    {
        $invoice = Invoice::where('invoice_number', $invoiceNumber)
            ->with('items')
            ->firstOrFail();

        $settings = Setting::current();

        return view('invoices.verify', compact('invoice', 'settings'));
    }
}
