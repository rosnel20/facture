<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification facture {{ $invoice->invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 max-w-md w-full p-6 text-center">
        <div class="text-4xl mb-2">✅</div>
        <h1 class="text-xl font-bold text-[#0b1f3a] mb-1">Facture authentique</h1>
        <p class="text-gray-500 text-sm mb-6">Cette facture a bien été émise par {{ $settings->company_name }}.</p>

        <div class="bg-gray-50 rounded-lg p-4 text-left text-sm space-y-2">
            <div class="flex justify-between"><span class="text-gray-500">Numéro</span><span class="font-medium">{{ $invoice->invoice_number }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Client</span><span class="font-medium">{{ $invoice->client_name }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Date</span><span class="font-medium">{{ $invoice->created_at->format('d/m/Y') }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Montant total</span><span class="font-medium">{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</span></div>
        </div>

        <p class="text-xs text-gray-400 mt-6">{{ $settings->company_name }} — Vérification automatique</p>
    </div>
</body>
</html>
