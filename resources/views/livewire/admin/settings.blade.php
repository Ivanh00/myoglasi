<div class="space-y-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Admin Podešavanja</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Site Settings -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Osnovna podešavanja sajta</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Naziv sajta</label>
                            <input type="text" value="MyOglasi" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Admin email</label>
                            <input type="email" value="admin@myoglasi.com" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Taxa za objavljivanje oglasa (RSD)</label>
                            <input type="number" value="100" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="require_approval" checked class="rounded">
                            <label for="require_approval" class="ml-2 text-sm text-gray-700">Oglasi zahtevaju odobrenje</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="allow_registration" checked class="rounded">
                            <label for="allow_registration" class="ml-2 text-sm text-gray-700">Dozvoli registraciju novih korisnika</label>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Email podešavanja</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SMTP Host</label>
                            <input type="text" placeholder="smtp.gmail.com" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SMTP Port</label>
                            <input type="number" value="587" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SMTP Username</label>
                            <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SMTP Password</label>
                            <input type="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- System Info -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informacije o sistemu</h3>
                    
                    <div class="bg-gray-50 p-4 rounded-lg space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Laravel verzija:</span>
                            <span class="font-medium">{{ app()->version() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">PHP verzija:</span>
                            <span class="font-medium">{{ PHP_VERSION }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Livewire verzija:</span>
                            <span class="font-medium">3.x</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Poslednje ažuriranje:</span>
                            <span class="font-medium">{{ now()->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Brza statistika</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ \App\Models\User::count() }}</div>
                            <div class="text-sm text-blue-700">Ukupno korisnika</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-green-600">{{ \App\Models\Listing::count() }}</div>
                            <div class="text-sm text-green-700">Ukupno oglasa</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ \App\Models\Category::count() }}</div>
                            <div class="text-sm text-purple-700">Kategorije</div>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ \App\Models\Transaction::count() }}</div>
                            <div class="text-sm text-orange-700">Transakcije</div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Sistem akcije</h3>
                    
                    <div class="space-y-3">
                        <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Očisti cache
                        </button>
                        <button class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Optimizuj konfiguraciju
                        </button>
                        <button class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                            Generiši sitemap
                        </button>
                        <button class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                            Backup baze podataka
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end space-x-3">
            <button class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Resetuj
            </button>
            <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Sačuvaj podešavanja
            </button>
        </div>
    </div>
</div>
