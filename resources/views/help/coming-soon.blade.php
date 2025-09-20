<x-app-layout>
    <div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-8 text-center">
                <div class="w-20 h-20 bg-sky-100 dark:bg-sky-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-construction text-sky-600 dark:text-sky-400 text-3xl"></i>
                </div>

                <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">{{ $title }}</h1>
                <p class="text-slate-600 dark:text-slate-400 mb-6">Ova stranica je u pripremi...</p>

                <div class="bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-800 rounded-lg p-4 mb-6">
                    <p class="text-sky-800 dark:text-sky-300 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        Radimo na detaljnim vodičima i objašnjenjima za sve funkcionalnosti PazAriO platforme.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Nazad na početnu
                    </a>

                    <a href="{{ route('contact') }}"
                        class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <i class="fas fa-envelope mr-2"></i>
                        Kontaktiraj admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
