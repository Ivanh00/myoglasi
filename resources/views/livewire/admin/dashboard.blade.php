<div class="space-y-6">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-slate-900">Admin Dashboard</h2>
                <button wire:click="refreshData" class="bg-sky-600 text-white px-4 py-2 rounded-lg hover:bg-sky-700">
                    Osveži podatke
                </button>
            </div>

            <!-- Main Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <div class="bg-gradient-to-r from-sky-500 to-sky-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sky-100 text-sm">Ukupno korisnika</p>
                            <p class="text-3xl font-bold">{{ number_format($stats['total_users']) }}</p>
                            <p class="text-sky-100 text-xs mt-1">+{{ $stats['recent_users'] }} u 7 dana</p>
                        </div>
                        <svg class="w-8 h-8 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-green-100 text-sm">Aktivni oglasi</p>
                            <p class="text-3xl font-bold">{{ number_format($stats['active_listings']) }}</p>
                            <p class="text-green-100 text-xs mt-1">{{ $stats['pending_listings'] }} na čekanju</p>
                        </div>
                        <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-purple-100 text-sm">Ukupno oglasa</p>
                            <p class="text-3xl font-bold">{{ number_format($stats['total_listings']) }}</p>
                            <p class="text-purple-100 text-xs mt-1">+{{ $stats['recent_listings'] }} u 7 dana</p>
                        </div>
                        <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-orange-100 text-sm">Ukupni prihodi</p>
                            <p class="text-3xl font-bold">{{ number_format($stats['total_revenue'], 0) }}</p>
                            <p class="text-orange-100 text-xs mt-1">RSD</p>
                        </div>
                        <svg class="w-8 h-8 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-red-100 text-sm">Blokirani oglasi</p>
                            <p class="text-3xl font-bold">{{ number_format($stats['blocked_listings']) }}</p>
                            <p class="text-red-100 text-xs mt-1">{{ $stats['unread_messages'] }} nepročitane poruke</p>
                        </div>
                        <svg class="w-8 h-8 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white border border-slate-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-slate-500">Danas registracija</h3>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['today_registrations'] }}</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-slate-500">Danas oglasa</h3>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['today_listings'] }}</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-slate-500">Prosečno stanje</h3>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['avg_user_balance'], 0) }} RSD</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-slate-500">Korisnici sa kreditima</h3>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['users_with_balance'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Registrations Chart -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Registracije korisnika (30 dana)</h3>
            <canvas id="usersChart" width="400" height="200"></canvas>
        </div>

        <!-- Listings Chart -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Novi oglasi (30 dana)</h3>
            <canvas id="listingsChart" width="400" height="200"></canvas>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Prihodi (30 dana)</h3>
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>

        <!-- Category Distribution -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Distribucija po kategorijama</h3>
            <canvas id="categoriesChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Users -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Poslednji korisnici</h3>
            <div class="space-y-3">
                @foreach ($recentUsers as $user)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div class="flex items-center">
                            @if($user->avatar)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="ml-3">
                                <p class="text-sm font-medium text-slate-900">{{ $user->name }}</p>
                                <p class="text-sm text-slate-500">{{ $user->email }}</p>
                                <p class="text-xs text-slate-400">Balans: {{ number_format($user->balance ?? 0, 0) }} RSD</p>
                            </div>
                        </div>
                        <span class="text-xs text-slate-400">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Listings -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Poslednji oglasi</h3>
            <div class="space-y-3">
                @foreach ($recentListings as $listing)
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-slate-900">{{ Str::limit($listing->title, 20) }}</p>
                                <p class="text-sm text-slate-500">{{ $listing->category->name ?? 'Bez kategorije' }}</p>
                                <p class="text-xs text-slate-400">{{ $listing->user->name }}</p>
                            </div>
                            <span class="text-xs text-slate-400">{{ $listing->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="mt-2 flex justify-between items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($listing->status === 'active') bg-green-100 text-green-800
                                @elseif($listing->status === 'pending') bg-amber-100 text-amber-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($listing->status) }}
                            </span>
                            <span class="text-sm font-medium text-green-600">{{ number_format($listing->price, 0) }} RSD</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Users by Listings -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Najaktivniji korisnici</h3>
            <div class="space-y-3">
                @foreach ($topUsers as $user)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div class="flex items-center">
                            @if($user->avatar)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-slate-500 flex items-center justify-center text-white font-medium text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="ml-3">
                                <p class="text-sm font-medium text-slate-900">{{ $user->name }}</p>
                                <p class="text-xs text-slate-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-purple-600">{{ $user->listings_count }}</p>
                            <p class="text-xs text-slate-400">oglasa</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User registrations chart
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    new Chart(usersCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($chartData['users'])->pluck('date')) !!},
            datasets: [{
                label: 'Novi korisnici',
                data: {!! json_encode(collect($chartData['users'])->pluck('count')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Listings chart
    const listingsCtx = document.getElementById('listingsChart').getContext('2d');
    new Chart(listingsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($chartData['listings'])->pluck('date')) !!},
            datasets: [{
                label: 'Novi oglasi',
                data: {!! json_encode(collect($chartData['listings'])->pluck('count')) !!},
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Revenue chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($chartData['revenue'])->pluck('date')) !!},
            datasets: [{
                label: 'Prihod (RSD)',
                data: {!! json_encode(collect($chartData['revenue'])->pluck('revenue')) !!},
                backgroundColor: 'rgba(245, 158, 11, 0.8)',
                borderColor: 'rgb(245, 158, 11)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Categories pie chart
    const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
    new Chart(categoriesCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(collect($chartData['categories'])->pluck('name')) !!},
            datasets: [{
                label: 'Oglasi po kategorijama',
                data: {!! json_encode(collect($chartData['categories'])->pluck('count')) !!},
                backgroundColor: [
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(236, 72, 153, 0.8)',
                    'rgba(6, 182, 212, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(251, 146, 60, 0.8)',
                    'rgba(168, 85, 247, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>