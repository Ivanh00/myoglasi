<!DOCTYPE html>
<html lang="sr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Radovi u toku - {{ config('app.name', 'PazAriO') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- TailwindCSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-sky-50 to-indigo-100 min-h-screen flex flex-col">
    <div class="flex-1 flex items-center justify-center">
        <div class="max-w-lg mx-auto px-6">
            <div class="text-center">
                <!-- Logo -->
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-sky-600 dark:text-sky-400 mb-2">PazAriO</h1>
                    <div class="w-24 h-1 bg-sky-600 mx-auto rounded-full"></div>
                </div>

                <!-- Maintenance Icon -->
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full shadow-lg mb-6">
                        <i class="fas fa-tools text-sky-600 dark:text-sky-400 text-4xl"></i>
                    </div>

                    <h2 class="text-3xl font-bold text-slate-900 mb-4">Radovi u toku</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400 mb-6">
                        Trenutno vršimo tehničke radove na poboljšanju naše platforme.
                    </p>
                </div>

                <!-- Information -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">
                        <i class="fas fa-info-circle text-sky-500 mr-2"></i>
                        Što radimo
                    </h3>
                    <ul class="text-left text-slate-600 dark:text-slate-400 space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Poboljšavamo performanse sajta
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Dodajemo nove funkcionalnosti
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Ažuriramo sigurnosne protokole
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Optimizujemo korisničko iskustvo
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="bg-sky-50 border border-sky-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-sky-900 mb-2">Hitni slučajevi</h4>
                    <p class="text-sky-800 text-sm">
                        Za hitne upite kontaktirajte nas na:
                    </p>
                    <div class="mt-2 space-y-1">
                        <p class="text-sky-800 font-medium">
                            <i class="fas fa-envelope mr-2"></i>
                            {{ \App\Models\Setting::get('support_email', 'support@pazario.rs') }}
                        </p>
                    </div>
                </div>

                <!-- Expected Return -->
                <div class="text-center">
                    <h4 class="text-lg font-semibold text-slate-900 mb-2">
                        Uskoro ćemo ponovo biti dostupni
                    </h4>
                    <p class="text-slate-600 dark:text-slate-400 mb-4">
                        Hvala vam na razumevanju i strpljenju!
                    </p>

                    <!-- Logout for authenticated users -->
                    @auth
                        <div class="mt-6 pt-4 border-t border-slate-200">
                            <p class="text-sm text-slate-500 mb-2">Ulogovani ste kao:
                                <strong>{{ auth()->user()->name }}</strong></p>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Odjavi se
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-4 bg-white border-t">
        <p class="text-slate-500 text-sm">
            &copy; {{ date('Y') }} PazAriO. Sva prava zadržana.
        </p>
    </footer>

    <!-- Auto-refresh script to check if maintenance mode is disabled -->
    <script>
        // Check every 30 seconds if maintenance mode is disabled
        setInterval(function() {
            fetch('/api/maintenance-status')
                .then(response => response.json())
                .then(data => {
                    if (!data.maintenance_mode) {
                        // Maintenance mode is disabled, refresh the current page
                        window.location.reload();
                    }
                })
                .catch(error => {
                    // Silently ignore errors
                    console.log('Checking maintenance status...');
                });
        }, 30000); // Check every 30 seconds
    </script>
</body>

</html>
