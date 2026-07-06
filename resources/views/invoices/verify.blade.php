<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification facture {{ $invoice->invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f7ff', 100: '#e0eefe', 200: '#bcdcfd', 300: '#84c2fc',
                            400: '#45a1f8', 500: '#1c84ec', 600: '#0e66ca', 700: '#0d52a3',
                            800: '#104686', 900: '#123c6f',
                        },
                    },
                    boxShadow: {
                        soft: '0 4px 20px -4px rgba(14, 102, 202, 0.18)',
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-gradient-to-b from-brand-50 to-white min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-soft border border-brand-100 max-w-md w-full p-6 text-center">
        <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-brand-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-7 h-7 text-brand-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-xl font-bold text-brand-900 mb-1">Facture authentique</h1>
        <p class="text-slate-400 text-sm mb-6">Cette facture a bien été émise par {{ $settings->company_name }}.</p>

        <div class="bg-brand-50 rounded-xl p-4 text-left text-sm space-y-2">
            <div class="flex justify-between"><span class="text-slate-400">Numéro</span><span class="font-medium text-brand-900">{{ $invoice->invoice_number }}</span></div>
            <div class="flex justify-between"><span class="text-slate-400">Client</span><span class="font-medium text-brand-900">{{ $invoice->client_name }}</span></div>
            <div class="flex justify-between"><span class="text-slate-400">Date</span><span class="font-medium text-brand-900">{{ $invoice->created_at->format('d/m/Y') }}</span></div>
            <div class="flex justify-between"><span class="text-slate-400">Montant total</span><span class="font-medium text-brand-900">{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</span></div>
        </div>

        <p class="text-xs text-slate-400 mt-6">{{ $settings->company_name }} — Vérification automatique</p>
    </div>
</body>
</html>
