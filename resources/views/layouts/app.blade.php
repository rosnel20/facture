<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MUNDO TECH') — Facturation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] },
                    colors: {
                        brand: {
                            50: '#f0f7ff', 100: '#e0eefe', 200: '#bcdcfd', 300: '#84c2fc',
                            400: '#45a1f8', 500: '#1c84ec', 600: '#0e66ca', 700: '#0d52a3',
                            800: '#104686', 900: '#123c6f',
                        },
                    },
                    boxShadow: {
                        soft: '0 4px 20px -4px rgba(14, 102, 202, 0.18)',
                        card: '0 1px 2px 0 rgba(15, 23, 42, 0.04), 0 1px 6px -1px rgba(15, 23, 42, 0.06)',
                    },
                },
            },
        };
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', ui-sans-serif, system-ui; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-thumb { background: #bcdcfd; border-radius: 999px; }
        ::-webkit-scrollbar-track { background: transparent; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 min-h-screen text-slate-700 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">

        {{-- ============ SIDEBAR DESKTOP ============ --}}
        <aside class="hidden lg:flex lg:flex-col w-72 flex-shrink-0 sticky top-0 h-screen overflow-y-auto bg-gradient-to-b from-brand-200 via-brand-50 to-white border-r border-slate-200/70">
            @include('partials.sidebar-content')
        </aside>

        {{-- ============ SIDEBAR MOBILE (drawer) ============ --}}
        <div x-show="sidebarOpen" x-cloak
             class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"></div>

        <aside x-show="sidebarOpen" x-cloak
               class="fixed inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-brand-200 via-brand-50 to-white shadow-2xl lg:hidden flex flex-col"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-150"
               x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
            <div class="flex justify-end p-3">
                <button @click="sidebarOpen = false" class="w-9 h-9 rounded-full hover:bg-white/70 flex items-center justify-center text-slate-400">
                    <x-icon name="close" class="w-5 h-5" />
                </button>
            </div>
            @include('partials.sidebar-content')
        </aside>

        {{-- ============ CONTENU PRINCIPAL ============ --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Barre supérieure mobile --}}
            <header class="lg:hidden sticky top-0 z-30 bg-white/90 backdrop-blur border-b border-slate-100 px-4 py-3 flex items-center justify-between">
                <button @click="sidebarOpen = true" class="w-10 h-10 rounded-xl hover:bg-brand-50 flex items-center justify-center text-slate-600">
                    <x-icon name="menu" class="w-5 h-5" />
                </button>
                <div class="flex items-center gap-2">
                    @php($__topbarSettings = \App\Models\Setting::current())
                    @if($__topbarSettings->logo_path)
                        <img src="{{ asset('storage/' . $__topbarSettings->logo_path) }}"
                             alt="{{ $__topbarSettings->company_name }}"
                             class="w-8 h-8 rounded-lg object-contain bg-white border border-brand-100 p-1">
                    @else
                        <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white text-xs font-bold">MT</span>
                    @endif
                    <span class="font-bold text-slate-800 text-sm">{{ $__topbarSettings->company_name }}</span>
                </div>
                <div class="w-10"></div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-10">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- ============ TOASTS ============ --}}
    <div x-data="toastManager()" x-init="init()"
         class="fixed top-4 right-4 z-[100] w-[calc(100%-2rem)] max-w-sm space-y-3 pointer-events-none"
         @toast.window="push($event.detail.type, $event.detail.message)">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-6"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-6"
                 class="pointer-events-auto rounded-xl shadow-xl border px-4 py-3.5 flex items-start gap-3 bg-white"
                 :class="{
                    'border-emerald-200': toast.type === 'success',
                    'border-red-200': toast.type === 'error',
                    'border-brand-200': toast.type === 'info'
                 }">
                <span class="mt-0.5"
                      :class="{
                        'text-emerald-500': toast.type === 'success',
                        'text-red-500': toast.type === 'error',
                        'text-brand-500': toast.type === 'info'
                      }">
                    <template x-if="toast.type === 'success'"><x-icon name="check-circle" class="w-5 h-5" /></template>
                    <template x-if="toast.type === 'error'"><x-icon name="alert" class="w-5 h-5" /></template>
                    <template x-if="toast.type === 'info'"><x-icon name="info" class="w-5 h-5" /></template>
                </span>
                <p class="text-sm text-slate-700 flex-1 pt-0.5" x-text="toast.message"></p>
                <button @click="dismiss(toast.id)" class="text-slate-300 hover:text-slate-500 mt-0.5">
                    <x-icon name="close" class="w-4 h-4" />
                </button>
            </div>
        </template>
    </div>

    <script>
        function toastManager() {
            return {
                toasts: [],
                init() {
                    @if (session('success'))
                        this.push('success', @json(session('success')));
                    @endif
                    @if ($errors->any())
                        this.push('error', 'Veuillez corriger les erreurs signalées dans le formulaire.');
                    @endif
                },
                push(type, message) {
                    const id = Date.now() + Math.random();
                    this.toasts.push({ id, type, message, show: true });
                    setTimeout(() => this.dismiss(id), 5000);
                },
                dismiss(id) {
                    const t = this.toasts.find(t => t.id === id);
                    if (t) t.show = false;
                    setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 250);
                }
            }
        }
    </script>

    {{-- Envoi WhatsApp avec pièce jointe réelle quand c'est possible.
         1) Sur mobile (Android/iOS récents) : l'API de partage native du
            navigateur permet d'attacher vraiment le PDF au partage — un
            seul geste, on choisit WhatsApp dans le menu qui s'ouvre.
         2) Sur desktop ou navigateur non compatible : on retombe sur la
            méthode précédente (téléchargement + ouverture de WhatsApp),
            il suffit alors de joindre le fichier manuellement.
         Important : on copie le message dans le presse-papier et on
         affiche le toast AVANT d'ouvrir le partage natif, car une fois
         la fenêtre de partage ouverte, la page perd le focus et le
         presse-papier devient inaccessible (silencieusement refusé). --}}
    <script>
        async function sendInvoiceOnWhatsapp(downloadUrl, phone, message, fileName) {
            try {
                const response = await fetch(downloadUrl);
                if (!response.ok) throw new Error('download failed');
                const blob = await response.blob();
                const file = new File([blob], fileName, { type: 'application/pdf' });

                if (navigator.canShare && navigator.canShare({ files: [file] })) {
                    // On copie et on prévient AVANT d'ouvrir le partage,
                    // pendant que la page a encore le focus.
                    try {
                        await navigator.clipboard.writeText(message);
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'info',
                                message: "Message copié dans le presse-papier — colle-le comme légende dans WhatsApp après avoir choisi le contact."
                            }
                        }));
                    } catch (clipErr) {
                        // Presse-papier indisponible : pas grave, le fichier sera quand même partagé.
                    }

                    await navigator.share({ files: [file], text: message });

                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'info',
                            message: "N'oublie pas de coller le message copié comme légende avant d'envoyer sur WhatsApp."
                        }
                    }));
                    return;
                }
            } catch (err) {
                if (err && err.name === 'AbortError') {
                    // L'utilisateur a annulé le partage : on ne fait rien de plus.
                    return;
                }
                // Sinon on continue vers la méthode de secours ci-dessous.
            }

            window.open('https://wa.me/' + phone + '?text=' + encodeURIComponent(message), '_blank', 'noopener');

            const link = document.createElement('a');
            link.href = downloadUrl;
            link.rel = 'noopener';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            if (!navigator.canShare) {
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        type: 'info',
                        message: "Le PDF a été téléchargé — il ne reste qu'à le joindre dans la conversation WhatsApp."
                    }
                }));
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
