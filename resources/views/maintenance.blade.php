<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Radovi u toku - {{ config('app.name', 'MyOglasi') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- TailwindCSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="max-w-lg mx-auto px-6">
        <div class="text-center">
            <!-- Logo -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-blue-600 mb-2">MyOglasi</h1>
                <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full"></div>
            </div>
            
            <!-- Maintenance Icon -->
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full shadow-lg mb-6">
                    <i class="fas fa-tools text-blue-600 text-4xl"></i>
                </div>
                
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Radovi u toku</h2>
                <p class="text-lg text-gray-600 mb-6">
                    Trenutno vršimo tehničke radove na poboljšanju naše platforme.
                </p>
            </div>
            
            <!-- Information -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Što radimo
                </h3>
                <ul class="text-left text-gray-600 space-y-2">
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
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-blue-900 mb-2">Hitni slučajevi</h4>
                <p class="text-blue-800 text-sm">
                    Za hitne upite kontaktirajte nas na:
                </p>
                <div class="mt-2 space-y-1">
                    <p class="text-blue-800 font-medium">
                        <i class="fas fa-envelope mr-2"></i>
                        {{ \App\Models\Setting::get('support_email', 'support@myoglasi.rs') }}
                    </p>
                </div>
            </div>
            
            <!-- Expected Return -->
            <div class="text-center">
                <h4 class="text-lg font-semibold text-gray-900 mb-2">
                    Uskoro ćemo ponovo biti dostupni
                </h4>
                <p class="text-gray-600 mb-4">
                    Hvala vam na razumevanju i strpljenju!
                </p>
                
                <!-- Logout for authenticated users -->
                @auth
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-2">Ulogovani ste kao: <strong>{{ auth()->user()->name }}</strong></p>
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
    
    <!-- Footer -->
    <div class="fixed bottom-0 left-0 right-0 text-center py-4 bg-white border-t">
        <p class="text-gray-500 text-sm">
            &copy; {{ date('Y') }} MyOglasi. Sva prava zadržana.
        </p>
    </div>
</body>
</html>