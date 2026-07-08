<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — MUNDO TECH</title>
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
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-brand-50 via-white to-brand-100 flex items-center justify-center p-4">

    @include('partials.toasts')

    <div class="w-full max-w-sm">

        <div class="flex flex-col items-center mb-8">
            @php($__loginSettings = \App\Models\Setting::current())
            @if($__loginSettings->logo_path)
                <img src="{{ asset('storage/' . $__loginSettings->logo_path) }}"
                     alt="{{ $__loginSettings->company_name }}"
                     class="w-16 h-16 rounded-2xl object-contain bg-white shadow-soft border border-brand-100 p-2 mb-3">
            @else
                <span class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-800 flex items-center justify-center text-white font-bold text-lg shadow-soft mb-3">MT</span>
            @endif
            <h1 class="text-xl font-bold text-slate-800">{{ $__loginSettings->company_name }}</h1>
            <p class="text-sm text-slate-400">Connecte-toi pour accéder à ton espace</p>
        </div>

        <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-6 sm:p-8">

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4"
                  x-data="{ loading: false }" @submit="loading = true">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Adresse e-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition"
                        placeholder="toi@mundotech.com">
                </div>

                <div x-data="{ show: false }">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mot de passe</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" required
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 pr-14 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition"
                            placeholder="••••••••">
                        <button type="button" @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-brand-600 text-xs font-semibold">
                            <span x-text="show ? 'Cacher' : 'Voir'"></span>
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-500">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-400">
                    Se souvenir de moi
                </label>

                <button type="submit" :disabled="loading"
                    class="w-full bg-gradient-to-r from-brand-500 to-brand-700 hover:from-brand-600 hover:to-brand-800 disabled:opacity-70 disabled:cursor-not-allowed text-white font-semibold text-sm py-3 rounded-xl shadow-soft transition-all flex items-center justify-center gap-2">
                    <svg x-show="loading" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         class="w-4 h-4 animate-spin">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
                        <path fill="currentColor" class="opacity-75" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="loading ? 'Connexion...' : 'Se connecter'"></span>
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">© {{ date('Y') }} {{ $__loginSettings->company_name }}</p>
    </div>

</body>
</html>
