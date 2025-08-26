<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Admin Dashboard</h2>
            <button wire:click="refreshData" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Osveži podatke
            </button>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach ([['title' => 'Ukupno korisnika', 'value' => $stats['total_users'], 'icon' => 'users', 'color' => 'blue', 'trend' => '+' . $stats['recent_users'] . ' u 7 dana'], ['title' => 'Ukupno oglasa', 'value' => $stats['total_listings'], 'icon' => 'listings', 'color' => 'green', 'trend' => '+' . $stats['recent_listings'] . ' u 7 dana'], ['title' => 'Aktivni oglasi', 'value' => $stats['active_listings'], 'icon' => 'check', 'color' => 'purple', 'trend' => $stats['pending_listings'] . ' na čekanju'], ['title' => 'Prihodi', 'value' => number_format($stats['revenue'], 2) . ' RSD', 'icon' => 'currency', 'color' => 'orange', 'trend' => 'Ukupno zarađeno']] as $stat)
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="p-3 bg-{{ $stat['color'] }}-100 rounded-lg">
                            <svg class="w-6 h-6 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <!-- Ikonice -->
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">{{ $stat['title'] }}</h3>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stat['value'] }}</p>
                            <p class="text-sm text-green-600">{{ $stat['trend'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Live Data Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Users -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Poslednji korisnici</h3>
                <div class="space-y-3">
                    @foreach ($recentUsers as $user)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-sm font-medium">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Listings -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Poslednji oglasi</h3>
                <div class="space-y-3">
                    @foreach ($recentListings as $listing)
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $listing->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $listing->category->name }}</p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $listing->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="mt-2 flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ $listing->user->name }}</span>
                                <span
                                    class="text-sm font-medium text-green-600">{{ number_format($listing->price, 2) }}
                                    RSD</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
