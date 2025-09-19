<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    <!-- Current Status -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-sky-50 p-6 rounded-lg border border-sky-200">
            <div class="flex items-center">
                <div class="p-2 bg-sky-100 rounded-lg">
                    <i class="fas fa-wallet text-sky-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-sky-600">Trenutni balans</p>
                    <p class="text-xl font-semibold text-sky-900">{{ number_format(auth()->user()->balance, 0, ',', '.') }} RSD</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 p-6 rounded-lg border border-green-200">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-trophy text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-green-600">Danas zaradio</p>
                    <p class="text-xl font-semibold text-green-900">{{ number_format($this->todaysEarnings, 0, ',', '.') }} RSD</p>
                </div>
            </div>
        </div>

        <div class="bg-amber-50 p-6 rounded-lg border border-amber-200">
            <div class="flex items-center">
                <div class="p-2 bg-amber-100 rounded-lg">
                    <i class="fas fa-gamepad text-amber-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-amber-600">Preostalo danas</p>
                    <p class="text-xl font-semibold text-amber-900">{{ number_format($this->remainingCredits, 0, ',', '.') }} RSD</p>
                </div>
            </div>
        </div>
    </div>

    @if($this->remainingCredits > 0)
        <!-- Game Selection -->
        @if(!$gameActive && !$gameCompleted)
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6">Izaberite igru</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Click Game -->
                    <div class="border-2 border-slate-200 rounded-lg p-6 text-center hover:border-sky-500 hover:bg-sky-50 transition-all cursor-pointer"
                         wire:click="startGame('click_game')">
                        <div class="w-16 h-16 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-mouse-pointer text-sky-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Igra klikanja</h3>
                        <p class="text-sm text-slate-600 mb-3">Klikni što više puta za 30 sekundi</p>
                        <span class="bg-sky-100 text-sky-800 text-xs px-2 py-1 rounded-full">Do 10 RSD</span>
                    </div>

                    <!-- Memory Game -->
                    <div class="border-2 border-slate-200 rounded-lg p-6 text-center hover:border-green-500 hover:bg-green-50 transition-all cursor-pointer"
                         wire:click="startGame('memory_game')">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-brain text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Igra memorije</h3>
                        <p class="text-sm text-slate-600 mb-3">Zapamti parove boja</p>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Do 15 RSD</span>
                    </div>

                    <!-- Number Game -->
                    <div class="border-2 border-slate-200 rounded-lg p-6 text-center hover:border-purple-500 hover:bg-purple-50 transition-all cursor-pointer"
                         wire:click="startGame('number_game')">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calculator text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Igra brojeva</h3>
                        <p class="text-sm text-slate-600 mb-3">Pogodi broj matematički</p>
                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">Do 20 RSD</span>
                    </div>

                </div>
            </div>
        @endif

        <!-- Active Game Area -->
        @if($gameActive && $selectedGame === 'click_game')
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-4">Igra klikanja</h2>
                    
                    <div class="mb-6">
                        <div class="text-3xl font-bold text-sky-600 mb-2">{{ $clickCount }}</div>
                        <p class="text-slate-600">klikova</p>
                    </div>

                    <div class="mb-6">
                        <div class="w-32 h-32 bg-sky-500 hover:bg-sky-600 rounded-full flex items-center justify-center mx-auto cursor-pointer transform hover:scale-105 transition-all shadow-lg"
                             wire:click="clickGame">
                            <i class="fas fa-mouse-pointer text-white text-3xl"></i>
                        </div>
                        <p class="text-slate-600 mt-4">Klikni na dugme što više puta!</p>
                    </div>

                    <div class="mb-6">
                        <div class="text-lg font-semibold text-slate-900 dark:text-slate-100" x-data="{ timeLeft: 30 }" 
                             x-init="
                                let interval = setInterval(() => {
                                    timeLeft--;
                                    if (timeLeft <= 0) {
                                        clearInterval(interval);
                                        $wire.completeGame();
                                    }
                                }, 1000);
                             ">
                            Vreme: <span x-text="timeLeft"></span>s
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2 mt-2">
                            <div class="bg-sky-600 h-2 rounded-full transition-all duration-1000" 
                                 style="width: 100%" 
                                 x-bind:style="'width: ' + (timeLeft / 30 * 100) + '%'"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Memory Game -->
        @if($gameActive && $selectedGame === 'memory_game')
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-4">Igra memorije</h2>
                    
                    <div class="mb-6">
                        <div class="text-lg font-bold text-green-600 mb-2">Potezi: {{ $memoryMoves }}</div>
                    </div>

                    <div class="max-w-sm mx-auto mb-6">
                        <div class="grid grid-cols-4 gap-2">
                            @for($i = 0; $i < 12; $i++)
                                @php $color = $memoryCards[$i] ?? 'gray'; @endphp
                                <div wire:click="flipMemoryCard({{ $i }})"
                                     class="w-14 h-14 rounded-lg cursor-pointer transition-all transform hover:scale-105 flex items-center justify-center {{ $memoryBlocked ? 'pointer-events-none' : '' }}
                                     @if(in_array($i, $memoryFlipped) || in_array($i, $memoryMatched))
                                         @if($color === 'red') bg-red-500
                                         @elseif($color === 'blue') bg-sky-500
                                         @elseif($color === 'green') bg-green-500
                                         @elseif($color === 'yellow') bg-amber-500
                                         @elseif($color === 'purple') bg-purple-500
                                         @elseif($color === 'orange') bg-orange-500
                                         @else bg-slate-500
                                         @endif
                                     @else
                                         bg-slate-300 hover:bg-slate-400
                                     @endif">
                                    @if(in_array($i, $memoryFlipped) || in_array($i, $memoryMatched))
                                        <span class="text-white font-bold text-sm">{{ strtoupper(substr($color, 0, 1)) }}</span>
                                    @else
                                        <i class="fas fa-question text-slate-600"></i>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="text-sm text-slate-600">Kliknite na kartice da ih okrenete i pronađite parove!</div>
                </div>
            </div>
        @endif

        <!-- Number Game -->
        @if($gameActive && $selectedGame === 'number_game')
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-4">Igra brojeva</h2>
                    
                    <div class="mb-6">
                        <div class="text-lg text-slate-600 mb-2">Ciljni broj:</div>
                        <div class="text-4xl font-bold text-purple-600 mb-4">{{ $numberTarget }}</div>
                        <div class="text-lg text-slate-600 mb-2">Trenutni broj:</div>
                        <div class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $numberCurrent }}</div>
                        <div class="text-sm text-slate-500 mt-2">Potezi: {{ $numberMoves }}</div>
                    </div>

                    <div class="grid grid-cols-4 gap-3 max-w-md mx-auto">
                        <button wire:click="numberGameAction('add', 1)" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600">+1</button>
                        <button wire:click="numberGameAction('add', 5)" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600">+5</button>
                        <button wire:click="numberGameAction('add', 10)" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600">+10</button>
                        <button wire:click="numberGameAction('multiply', 2)" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600">×2</button>
                        
                        <button wire:click="numberGameAction('subtract', 1)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">-1</button>
                        <button wire:click="numberGameAction('subtract', 5)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">-5</button>
                        <button wire:click="numberGameAction('subtract', 10)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">-10</button>
                        <button wire:click="numberGameAction('divide', 2)" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">÷2</button>
                    </div>
                    
                    <div class="mt-4 text-sm text-slate-600">Koristite dugmad da dostignete ciljni broj!</div>
                </div>
            </div>
        @endif

        <!-- Game Completed -->
        @if($gameCompleted)
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trophy text-green-600 text-3xl"></i>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-4">Igra završena!</h2>
                    
                    <div class="mb-6">
                        <div class="text-3xl font-bold text-sky-600 mb-2">{{ $gameScore }}</div>
                        <p class="text-slate-600">{{ $selectedGame === 'click_game' ? 'klikova' : 'poena' }}</p>
                    </div>

                    <button wire:click="resetGame" 
                        class="px-6 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                        <i class="fas fa-redo mr-2"></i>
                        Igraj ponovo
                    </button>
                </div>
            </div>
        @endif
    @else
        <!-- Daily limit reached -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8 text-center">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-clock text-slate-400 text-3xl"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">Dnevni limit dostignut</h2>
            <p class="text-slate-600 mb-4">Već ste zaradili maksimalno kredita danas ({{ $this->maxDaily }} RSD).</p>
            <p class="text-sm text-slate-500">Vratite se sutra za nove igre!</p>
        </div>
    @endif

    <!-- Today's Leaderboard -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">
                <i class="fas fa-trophy text-amber-500 mr-2"></i>
                Danas najbolji igrači
            </h2>
            <div class="text-sm text-slate-600">
                Bonus: {{ number_format(\App\Models\Setting::get('game_leaderboard_bonus', 50), 0, ',', '.') }} RSD za #1
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($todaysLeaderboard as $gameType => $players)
                @if(in_array($gameType, ['click_game', 'memory_game', 'number_game']))
                    <div class="border border-slate-200 rounded-lg p-4">
                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-4 flex items-center">
                            @if($gameType === 'click_game')
                                <i class="fas fa-mouse-pointer text-sky-600 mr-2"></i>
                                Igra klikanja
                            @elseif($gameType === 'memory_game')
                                <i class="fas fa-brain text-green-600 mr-2"></i>
                                Igra memorije
                            @else
                                <i class="fas fa-calculator text-purple-600 mr-2"></i>
                                Igra brojeva
                            @endif
                        </h3>
                    
                    @if($players->count() > 0)
                        <div class="space-y-2">
                            @foreach($players->take(5) as $index => $player)
                                <div class="flex items-center justify-between p-2 rounded {{ $index === 0 ? 'bg-amber-50 border border-amber-200' : 'bg-slate-50' }}">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center mr-3 {{ $index === 0 ? 'bg-amber-500 text-white' : 'bg-slate-400 text-white' }} text-xs font-bold">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-slate-900 dark:text-slate-100 {{ auth()->id() === $player->user_id ? 'text-sky-600' : '' }}">
                                                {{ $player->user->name }}
                                                @if(auth()->id() === $player->user_id)
                                                    <span class="text-xs text-sky-600">(Vi)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="font-bold {{ $index === 0 ? 'text-amber-600' : 'text-slate-600' }}">
                                        {{ number_format($player->score) }}
                                        @if($index === 0)
                                            <i class="fas fa-crown ml-1"></i>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-slate-500">
                            <i class="fas fa-users text-2xl mb-2"></i>
                            <p>Nema igrača danas</p>
                        </div>
                    @endif
                </div>
                @endif
            @endforeach
        </div>
        
        @if(\App\Models\Setting::get('game_leaderboard_enabled', true))
            <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-amber-600 mr-2"></i>
                    <span class="text-sm text-amber-800">
                        <strong>Dnevni bonus:</strong> Najbolji igrač po igri svaki dan dobija {{ number_format(\App\Models\Setting::get('game_leaderboard_bonus', 50), 0, ',', '.') }} RSD bonus!
                    </span>
                </div>
            </div>
        @endif
    </div>

    <!-- Recent Earnings -->
    @if($recentEarnings->count() > 0)
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6">Vaše zarade (7 dana)</h2>
            
            <div class="space-y-3">
                @foreach($recentEarnings as $earning)
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg mr-3">
                                @if(str_contains($earning->type, 'leaderboard'))
                                    <i class="fas fa-trophy text-amber-600"></i>
                                @else
                                    <i class="fas fa-gamepad text-green-600"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 dark:text-slate-100">{{ $earning->description }}</p>
                                <p class="text-sm text-slate-600">{{ $earning->date->format('d.m.Y') }}</p>
                            </div>
                        </div>
                        <div class="{{ str_contains($earning->type, 'leaderboard') ? 'text-amber-600' : 'text-green-600' }} font-bold">
                            +{{ number_format($earning->amount, 0, ',', '.') }} RSD
                            @if(str_contains($earning->type, 'leaderboard'))
                                <i class="fas fa-crown ml-1"></i>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Success/Error Messages -->
    @if(session()->has('success'))
        <div class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-3"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if(session()->has('info'))
        <div class="fixed top-4 right-4 z-50 bg-sky-500 text-white px-6 py-4 rounded-lg shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-3"></i>
                {{ session('info') }}
            </div>
        </div>
    @endif
</div>

<script>
    // Auto-hide flash messages
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('.fixed.top-4.right-4');
            flashMessages.forEach(msg => {
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 3000);
    });


    // Memory game card flip delay
    document.addEventListener('livewire:init', () => {
        Livewire.on('clearMemoryCards', () => {
            setTimeout(() => {
                @this.call('clearMemoryFlipped');
            }, 1000);
        });
    });
</script>