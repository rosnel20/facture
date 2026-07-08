{{-- Conteneur global de toasts : succès + erreurs, empilés proprement --}}
<div class="fixed top-5 right-5 z-[100] w-[calc(100%-2.5rem)] max-w-xs space-y-2.5" x-data>

    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)"
             x-show="show" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2">
            <div class="relative overflow-hidden bg-white rounded-xl shadow-soft border border-emerald-100 pl-4 pr-9 py-3.5 flex items-start gap-3">
                <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-emerald-600">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                </span>
                <p class="text-sm text-slate-700 leading-snug pt-0.5">{{ session('success') }}</p>
                <button type="button" @click="show = false"
                    class="absolute top-2.5 right-2.5 text-slate-300 hover:text-slate-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                    </svg>
                </button>
                <div class="absolute bottom-0 left-0 h-0.5 bg-emerald-400"
                     x-init="$el.style.transition='width 4s linear'; requestAnimationFrame(() => $el.style.width='0%')"
                     style="width:100%"></div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)"
             x-show="show" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2">
            <div class="relative overflow-hidden bg-white rounded-xl shadow-soft border border-red-100 pl-4 pr-9 py-3.5 flex items-start gap-3">
                <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full bg-red-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-red-500">
                        <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-7-4a1 1 0 10-2 0v4a1 1 0 002 0V6zm-1 8a1.25 1.25 0 100-2.5 1.25 1.25 0 000 2.5z" clip-rule="evenodd" />
                    </svg>
                </span>
                <p class="text-sm text-slate-700 leading-snug pt-0.5">{{ session('error') }}</p>
                <button type="button" @click="show = false"
                    class="absolute top-2.5 right-2.5 text-slate-300 hover:text-slate-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                    </svg>
                </button>
                <div class="absolute bottom-0 left-0 h-0.5 bg-red-400"
                     x-init="$el.style.transition='width 5s linear'; requestAnimationFrame(() => $el.style.width='0%')"
                     style="width:100%"></div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)"
                 x-show="show" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2">
                <div class="relative overflow-hidden bg-white rounded-xl shadow-soft border border-red-100 pl-4 pr-9 py-3.5 flex items-start gap-3">
                    <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full bg-red-50 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-red-500">
                            <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-7-4a1 1 0 10-2 0v4a1 1 0 002 0V6zm-1 8a1.25 1.25 0 100-2.5 1.25 1.25 0 000 2.5z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <p class="text-sm text-slate-700 leading-snug pt-0.5">{{ $error }}</p>
                    <button type="button" @click="show = false"
                        class="absolute top-2.5 right-2.5 text-slate-300 hover:text-slate-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                        </svg>
                    </button>
                    <div class="absolute bottom-0 left-0 h-0.5 bg-red-400"
                         x-init="$el.style.transition='width 5s linear'; requestAnimationFrame(() => $el.style.width='0%')"
                         style="width:100%"></div>
                </div>
            </div>
        @endforeach
    @endif

</div>
