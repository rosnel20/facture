{{-- Actions communes à la vue tableau (desktop) et cartes (mobile) --}}
<div class="flex flex-wrap justify-end md:justify-end gap-1.5" x-data="{ deleting: false }">

    <a href="{{ route('invoices.show', $invoice) }}" target="_blank" title="Voir le PDF"
       class="inline-flex items-center gap-1.5 text-xs font-medium text-brand-600 bg-brand-50 hover:bg-brand-100 px-2.5 py-1.5 rounded-lg transition">
        <x-icon name="eye" class="w-4 h-4" /> <span class="hidden sm:inline">Voir</span>
    </a>

    <a href="{{ route('invoices.download', $invoice) }}" title="Télécharger"
       class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 px-2.5 py-1.5 rounded-lg transition">
        <x-icon name="download" class="w-4 h-4" /> <span class="hidden sm:inline">Télécharger</span>
    </a>

    <button type="button"
       onclick="sendInvoiceOnWhatsapp(@js(route('invoices.download', $invoice)), @js(preg_replace('/\D/', '', $invoice->client_phone)), @js("Bonjour {$invoice->client_name}\nMerci pour votre achat chez MUNDO TECH.\nVeuillez trouver ci-joint votre facture.\nNous restons à votre disposition."), @js($invoice->invoice_number . '.pdf'))"
       title="Partager la facture sur WhatsApp"
       class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1.5 rounded-lg transition">
        <x-icon name="chat" class="w-4 h-4" /> <span class="hidden sm:inline">WhatsApp</span>
    </button>

    <form method="POST" action="{{ route('invoices.destroy', $invoice) }}"
          @submit="deleting = true"
          onsubmit="return confirm('Supprimer définitivement la facture {{ $invoice->invoice_number }} ?');">
        @csrf
        @method('DELETE')
        <button type="submit" :disabled="deleting" title="Supprimer"
                class="inline-flex items-center gap-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 disabled:opacity-60 px-2.5 py-1.5 rounded-lg transition">
            <template x-if="!deleting"><x-icon name="trash" class="w-4 h-4" /></template>
            <span x-show="!deleting" class="hidden sm:inline">Supprimer</span>
            <span x-show="deleting" x-cloak>Suppression...</span>
        </button>
    </form>
</div>
