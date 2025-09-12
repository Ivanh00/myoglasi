<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">
            <i class="fas fa-coins text-yellow-500 mr-3"></i>
            Zaradi kredit
        </h1>
        <p class="text-gray-600">Igraj igrice i zarađuj kredit za postavljanje oglasa!</p>
    </div>

    <!-- Current Status -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-wallet text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-blue-600">Trenutni balans</p>
                    <p class="text-xl font-semibold text-blue-900">{{ number_format(auth()->user()->balance, 0, ',', '.') }} RSD</p>
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

        <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-gamepad text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-yellow-600">Preostalo danas</p>
                    <p class="text-xl font-semibold text-yellow-900">{{ number_format($this->remainingCredits, 0, ',', '.') }} RSD</p>
                </div>
            </div>
        </div>
    </div>

    @if($this->remainingCredits > 0)
        <!-- Game Selection -->
        @if(!$gameActive && !$gameCompleted)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Izaberite igru</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                    <!-- Click Game -->
                    <div class="border-2 border-gray-200 rounded-lg p-6 text-center hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer"
                         wire:click="startGame('click_game')">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-mouse-pointer text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Igra klikanja</h3>
                        <p class="text-sm text-gray-600 mb-3">Klikni što više puta za 30 sekundi</p>
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Do 10 RSD</span>
                    </div>

                    <!-- Memory Game -->
                    <div class="border-2 border-gray-200 rounded-lg p-6 text-center hover:border-green-500 hover:bg-green-50 transition-all cursor-pointer"
                         wire:click="startGame('memory_game')">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-brain text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Igra memorije</h3>
                        <p class="text-sm text-gray-600 mb-3">Zapamti parove boja</p>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Do 15 RSD</span>
                    </div>

                    <!-- Number Game -->
                    <div class="border-2 border-gray-200 rounded-lg p-6 text-center hover:border-purple-500 hover:bg-purple-50 transition-all cursor-pointer"
                         wire:click="startGame('number_game')">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calculator text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Igra brojeva</h3>
                        <p class="text-sm text-gray-600 mb-3">Pogodi broj matematički</p>
                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">Do 20 RSD</span>
                    </div>

                    <!-- Snake Game -->
                    <div class="border-2 border-gray-200 rounded-lg p-6 text-center hover:border-red-500 hover:bg-red-50 transition-all cursor-pointer"
                         wire:click="startGame('snake_game')">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-worm text-red-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Zmija</h3>
                        <p class="text-sm text-gray-600 mb-3">Skupljaj hranu i rasti</p>
                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">Do 25 RSD</span>
                    </div>

                    <!-- Puzzle Game -->
                    <div class="border-2 border-gray-200 rounded-lg p-6 text-center hover:border-orange-500 hover:bg-orange-50 transition-all cursor-pointer"
                         wire:click="startGame('puzzle_game')">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-puzzle-piece text-orange-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Slagalica</h3>
                        <p class="text-sm text-gray-600 mb-3">Složi sliku u što manje poteza</p>
                        <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">Do 15 RSD</span>
                    </div>

                    <!-- Reaction Game -->
                    <div class="border-2 border-gray-200 rounded-lg p-6 text-center hover:border-pink-500 hover:bg-pink-50 transition-all cursor-pointer"
                         wire:click="startGame('reaction_game')">
                        <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-stopwatch text-pink-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Reakcija</h3>
                        <p class="text-sm text-gray-600 mb-3">Klikni čim se pojavi zeleno</p>
                        <span class="bg-pink-100 text-pink-800 text-xs px-2 py-1 rounded-full">Do 18 RSD</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Active Game Area -->
        @if($gameActive && $selectedGame === 'click_game')
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Igra klikanja</h2>
                    
                    <div class="mb-6">
                        <div class="text-3xl font-bold text-blue-600 mb-2">{{ $clickCount }}</div>
                        <p class="text-gray-600">klikova</p>
                    </div>

                    <div class="mb-6">
                        <div class="w-32 h-32 bg-blue-500 hover:bg-blue-600 rounded-full flex items-center justify-center mx-auto cursor-pointer transform hover:scale-105 transition-all shadow-lg"
                             wire:click="clickGame">
                            <i class="fas fa-mouse-pointer text-white text-3xl"></i>
                        </div>
                        <p class="text-gray-600 mt-4">Klikni na dugme što više puta!</p>
                    </div>

                    <div class="mb-6">
                        <div class="text-lg font-semibold text-gray-900" x-data="{ timeLeft: 30 }" 
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
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-1000" 
                                 style="width: 100%" 
                                 x-bind:style="'width: ' + (timeLeft / 30 * 100) + '%'"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Memory Game -->
        @if($gameActive && $selectedGame === 'memory_game')
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Igra memorije</h2>
                    
                    <div class="mb-6">
                        <div class="text-lg font-bold text-green-600 mb-2">Potezi: {{ $memoryMoves }}</div>
                    </div>

                    <div class="grid grid-cols-4 gap-2 max-w-md mx-auto mb-6">
                        @foreach($memoryCards as $index => $color)
                            <div wire:click="flipMemoryCard({{ $index }})"
                                 class="w-16 h-16 rounded-lg cursor-pointer transition-all transform hover:scale-105 flex items-center justify-center
                                 @if(in_array($index, $memoryFlipped) || in_array($index, $memoryMatched))
                                     bg-{{ $color }}-500
                                 @else
                                     bg-gray-300
                                 @endif">
                                @if(in_array($index, $memoryFlipped) || in_array($index, $memoryMatched))
                                    <span class="text-white font-bold">{{ strtoupper(substr($color, 0, 1)) }}</span>
                                @else
                                    <i class="fas fa-question text-gray-600"></i>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="text-sm text-gray-600">Kliknite na kartice da ih okrenete i pronađite parove!</div>
                </div>
            </div>
        @endif

        <!-- Number Game -->
        @if($gameActive && $selectedGame === 'number_game')
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Igra brojeva</h2>
                    
                    <div class="mb-6">
                        <div class="text-lg text-gray-600 mb-2">Ciljni broj:</div>
                        <div class="text-4xl font-bold text-purple-600 mb-4">{{ $numberTarget }}</div>
                        <div class="text-lg text-gray-600 mb-2">Trenutni broj:</div>
                        <div class="text-3xl font-bold text-gray-900">{{ $numberCurrent }}</div>
                        <div class="text-sm text-gray-500 mt-2">Potezi: {{ $numberMoves }}</div>
                    </div>

                    <div class="grid grid-cols-4 gap-3 max-w-md mx-auto">
                        <button wire:click="numberGameAction('add', 1)" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">+1</button>
                        <button wire:click="numberGameAction('add', 5)" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">+5</button>
                        <button wire:click="numberGameAction('add', 10)" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">+10</button>
                        <button wire:click="numberGameAction('multiply', 2)" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600">×2</button>
                        
                        <button wire:click="numberGameAction('subtract', 1)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">-1</button>
                        <button wire:click="numberGameAction('subtract', 5)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">-5</button>
                        <button wire:click="numberGameAction('subtract', 10)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">-10</button>
                        <button wire:click="numberGameAction('divide', 2)" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">÷2</button>
                    </div>
                    
                    <div class="mt-4 text-sm text-gray-600">Koristite dugmad da dostignete ciljni broj!</div>
                </div>
            </div>
        @endif

        <!-- Snake Game -->
        @if($gameActive && $selectedGame === 'snake_game')
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Zmija</h2>
                    
                    <div class="mb-6">
                        <div class="text-3xl font-bold text-red-600 mb-2">{{ $snakeScore }}</div>
                        <p class="text-gray-600">pojedio</p>
                    </div>

                    <div class="max-w-md mx-auto">
                        <!-- Snake Grid -->
                        <canvas id="snakeCanvas" width="400" height="400" 
                                class="border-2 border-gray-400 rounded-lg bg-gray-100"></canvas>

                        <!-- Snake Controls -->
                        <div class="mt-4">
                            <div class="flex justify-center">
                                <button onclick="changeSnakeDirection('up')" class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                            </div>
                            <div class="flex justify-center gap-2 mt-2">
                                <button onclick="changeSnakeDirection('left')" class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                                <button onclick="changeSnakeDirection('down')" class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                                <button onclick="changeSnakeDirection('right')" class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-sm text-gray-600">Koristite dugmad ili strelice na tastaturi za upravljanje!</div>
                </div>
            </div>
        @endif

        <!-- Puzzle Game -->
        @if($gameActive && $selectedGame === 'puzzle_game')
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Slagalica</h2>
                    
                    <div class="mb-6">
                        <div class="text-lg font-bold text-orange-600 mb-2">Potezi: {{ $puzzleMoves }}</div>
                    </div>

                    <div class="grid grid-cols-3 gap-1 max-w-xs mx-auto mb-6">
                        @foreach($puzzleTiles as $index => $tile)
                            <div wire:click="movePuzzleTile({{ $index }})"
                                 class="w-16 h-16 border-2 border-gray-300 rounded-lg cursor-pointer transition-all transform hover:scale-105 flex items-center justify-center
                                 @if($tile === 0)
                                     bg-gray-100
                                 @else
                                     bg-orange-100 hover:bg-orange-200
                                 @endif">
                                @if($tile !== 0)
                                    <span class="text-xl font-bold text-orange-600">{{ $tile }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="text-sm text-gray-600">Kliknite na pločice da ih pomerite ka praznom mestu!</div>
                    <div class="text-xs text-gray-500 mt-2">Cilj: Poređajte brojeve od 1 do 8</div>
                </div>
            </div>
        @endif

        <!-- Reaction Game -->
        @if($gameActive && $selectedGame === 'reaction_game')
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Igra reakcije</h2>
                    
                    <div class="mb-6">
                        <div class="text-lg font-bold text-pink-600 mb-2">Runda: {{ $reactionRound + 1 }}/5</div>
                        @if(count($reactionTimes) > 0)
                            <div class="text-sm text-gray-600">Prosečno vreme: {{ number_format(array_sum($reactionTimes) / count($reactionTimes), 0) }}ms</div>
                        @endif
                    </div>

                    <div class="max-w-sm mx-auto mb-6">
                        @if($reactionWaiting)
                            <div class="w-32 h-32 bg-red-500 rounded-full flex items-center justify-center mx-auto">
                                <span class="text-white font-bold text-lg">ČEKAJ...</span>
                            </div>
                            <p class="text-gray-600 mt-4">Čekajte da se dugme ozeleni!</p>
                        @elseif($reactionActive)
                            <div wire:click="reactionClick" 
                                 class="w-32 h-32 bg-green-500 rounded-full flex items-center justify-center mx-auto cursor-pointer transform hover:scale-105 transition-all shadow-lg">
                                <span class="text-white font-bold text-lg">KLIK!</span>
                            </div>
                            <p class="text-gray-600 mt-4">KLIKNITE ODMAH!</p>
                        @else
                            <button wire:click="startReactionRound" 
                                    class="w-32 h-32 bg-gray-400 rounded-full flex items-center justify-center mx-auto">
                                <span class="text-white font-bold text-lg">START</span>
                            </button>
                            <p class="text-gray-600 mt-4">Kliknite za početak runde</p>
                        @endif
                    </div>
                    
                    <div class="text-sm text-gray-600">Kliknite na zeleno dugme što brže možete!</div>
                </div>
            </div>
        @endif

        <!-- Game Completed -->
        @if($gameCompleted)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trophy text-green-600 text-3xl"></i>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Igra završena!</h2>
                    
                    <div class="mb-6">
                        <div class="text-3xl font-bold text-blue-600 mb-2">{{ $gameScore }}</div>
                        <p class="text-gray-600">{{ $selectedGame === 'click_game' ? 'klikova' : 'poena' }}</p>
                    </div>

                    <button wire:click="resetGame" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-redo mr-2"></i>
                        Igraj ponovo
                    </button>
                </div>
            </div>
        @endif
    @else
        <!-- Daily limit reached -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-clock text-gray-400 text-3xl"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Dnevni limit dostignut</h2>
            <p class="text-gray-600 mb-4">Već ste zaradili maksimalno kredita danas ({{ $this->maxDaily }} RSD).</p>
            <p class="text-sm text-gray-500">Vratite se sutra za nove igre!</p>
        </div>
    @endif

    <!-- Today's Leaderboard -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">
                <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                Danas najbolji igrači
            </h2>
            <div class="text-sm text-gray-600">
                Bonus: {{ number_format(\App\Models\Setting::get('game_leaderboard_bonus', 50), 0, ',', '.') }} RSD za #1
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($todaysLeaderboard as $gameType => $players)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        @if($gameType === 'click_game')
                            <i class="fas fa-mouse-pointer text-blue-600 mr-2"></i>
                            Igra klikanja
                        @elseif($gameType === 'memory_game')
                            <i class="fas fa-brain text-green-600 mr-2"></i>
                            Igra memorije
                        @elseif($gameType === 'number_game')
                            <i class="fas fa-calculator text-purple-600 mr-2"></i>
                            Igra brojeva
                        @elseif($gameType === 'snake_game')
                            <i class="fas fa-worm text-red-600 mr-2"></i>
                            Zmija
                        @elseif($gameType === 'puzzle_game')
                            <i class="fas fa-puzzle-piece text-orange-600 mr-2"></i>
                            Slagalica
                        @else
                            <i class="fas fa-stopwatch text-pink-600 mr-2"></i>
                            Reakcija
                        @endif
                    </h3>
                    
                    @if($players->count() > 0)
                        <div class="space-y-2">
                            @foreach($players->take(5) as $index => $player)
                                <div class="flex items-center justify-between p-2 rounded {{ $index === 0 ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50' }}">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center mr-3 {{ $index === 0 ? 'bg-yellow-500 text-white' : 'bg-gray-400 text-white' }} text-xs font-bold">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 {{ auth()->id() === $player->user_id ? 'text-blue-600' : '' }}">
                                                {{ $player->user->name }}
                                                @if(auth()->id() === $player->user_id)
                                                    <span class="text-xs text-blue-600">(Vi)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="font-bold {{ $index === 0 ? 'text-yellow-600' : 'text-gray-600' }}">
                                        {{ number_format($player->score) }}
                                        @if($index === 0)
                                            <i class="fas fa-crown ml-1"></i>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-users text-2xl mb-2"></i>
                            <p>Nema igrača danas</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        @if(\App\Models\Setting::get('game_leaderboard_enabled', true))
            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                    <span class="text-sm text-yellow-800">
                        <strong>Dnevni bonus:</strong> Najbolji igrač po igri svaki dan dobija {{ number_format(\App\Models\Setting::get('game_leaderboard_bonus', 50), 0, ',', '.') }} RSD bonus!
                    </span>
                </div>
            </div>
        @endif
    </div>

    <!-- Recent Earnings -->
    @if($recentEarnings->count() > 0)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Vaše zarade (7 dana)</h2>
            
            <div class="space-y-3">
                @foreach($recentEarnings as $earning)
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg mr-3">
                                @if(str_contains($earning->type, 'leaderboard'))
                                    <i class="fas fa-trophy text-yellow-600"></i>
                                @else
                                    <i class="fas fa-gamepad text-green-600"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $earning->description }}</p>
                                <p class="text-sm text-gray-600">{{ $earning->date->format('d.m.Y') }}</p>
                            </div>
                        </div>
                        <div class="{{ str_contains($earning->type, 'leaderboard') ? 'text-yellow-600' : 'text-green-600' }} font-bold">
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
        <div class="fixed top-4 right-4 z-50 bg-blue-500 text-white px-6 py-4 rounded-lg shadow-lg">
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

    // Snake Game JavaScript Implementation
    let snake = {
        position: [[10, 10], [10, 9], [10, 8]],
        direction: 'right',
        food: [15, 15],
        score: 0,
        gameOver: false,
        canvas: null,
        ctx: null,
        gridSize: 20,
        tileCount: 20,
        interval: null
    };

    function initSnakeGame() {
        console.log('Initializing snake game...');
        snake.canvas = document.getElementById('snakeCanvas');
        console.log('Canvas found:', snake.canvas);
        
        if (!snake.canvas) {
            console.error('Snake canvas not found!');
            return;
        }
        
        snake.ctx = snake.canvas.getContext('2d');
        console.log('Canvas context:', snake.ctx);
        
        // Test canvas with simple rect
        snake.ctx.fillStyle = 'red';
        snake.ctx.fillRect(10, 10, 50, 50);
        console.log('Test rect drawn');
        
        snake.position = [[10, 10], [10, 9], [10, 8]];
        snake.direction = 'right';
        snake.food = [Math.floor(Math.random() * 20), Math.floor(Math.random() * 20)];
        snake.score = 0;
        snake.gameOver = false;
        
        console.log('Snake initialized, starting game loop...');
        
        // Start game loop
        snake.interval = setInterval(updateSnake, 300); // Slower for testing
        drawSnake();
    }

    function updateSnake() {
        if (snake.gameOver) return;

        // Move head
        let head = [...snake.position[0]];
        switch(snake.direction) {
            case 'up': head[1]--; break;
            case 'down': head[1]++; break;
            case 'left': head[0]--; break;
            case 'right': head[0]++; break;
        }

        // Check boundaries
        if (head[0] < 0 || head[0] >= 20 || head[1] < 0 || head[1] >= 20) {
            endSnakeGame();
            return;
        }

        // Check self collision
        if (snake.position.some(segment => segment[0] === head[0] && segment[1] === head[1])) {
            endSnakeGame();
            return;
        }

        snake.position.unshift(head);

        // Check food collision
        if (head[0] === snake.food[0] && head[1] === snake.food[1]) {
            snake.score++;
            @this.set('snakeScore', snake.score);
            generateFood();
        } else {
            snake.position.pop();
        }

        drawSnake();
    }

    function generateFood() {
        do {
            snake.food = [Math.floor(Math.random() * 20), Math.floor(Math.random() * 20)];
        } while (snake.position.some(segment => segment[0] === snake.food[0] && segment[1] === snake.food[1]));
    }

    function drawSnake() {
        if (!snake.ctx) {
            console.error('No canvas context!');
            return;
        }

        // Clear canvas
        snake.ctx.fillStyle = '#f3f4f6';
        snake.ctx.fillRect(0, 0, 400, 400);

        // Draw grid lines for debugging
        snake.ctx.strokeStyle = '#e5e7eb';
        snake.ctx.lineWidth = 0.5;
        for (let i = 0; i <= 20; i++) {
            snake.ctx.beginPath();
            snake.ctx.moveTo(i * 20, 0);
            snake.ctx.lineTo(i * 20, 400);
            snake.ctx.stroke();
            
            snake.ctx.beginPath();
            snake.ctx.moveTo(0, i * 20);
            snake.ctx.lineTo(400, i * 20);
            snake.ctx.stroke();
        }

        // Draw snake
        snake.position.forEach((segment, index) => {
            snake.ctx.fillStyle = index === 0 ? '#dc2626' : '#ef4444'; // Head darker
            snake.ctx.fillRect(segment[0] * 20 + 1, segment[1] * 20 + 1, 18, 18);
        });

        // Draw food
        snake.ctx.fillStyle = '#fbbf24';
        snake.ctx.fillRect(snake.food[0] * 20 + 1, snake.food[1] * 20 + 1, 18, 18);
    }

    function changeSnakeDirection(newDirection) {
        // Prevent reverse direction
        const opposites = { up: 'down', down: 'up', left: 'right', right: 'left' };
        if (snake.direction !== opposites[newDirection]) {
            snake.direction = newDirection;
        }
    }

    function endSnakeGame() {
        snake.gameOver = true;
        clearInterval(snake.interval);
        @this.set('gameScore', snake.score);
        @this.call('completeGame');
    }

    // Snake game keyboard controls
    document.addEventListener('keydown', function(e) {
        if (@json($gameActive) && @json($selectedGame) === 'snake_game') {
            switch(e.key) {
                case 'ArrowUp':
                    e.preventDefault();
                    changeSnakeDirection('up');
                    break;
                case 'ArrowDown':
                    e.preventDefault();
                    changeSnakeDirection('down');
                    break;
                case 'ArrowLeft':
                    e.preventDefault();
                    changeSnakeDirection('left');
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    changeSnakeDirection('right');
                    break;
            }
        }
    });

    // Initialize snake when game starts
    Livewire.on('startSnakeGame', () => {
        console.log('startSnakeGame event received');
        setTimeout(() => {
            console.log('Attempting to initialize snake...');
            initSnakeGame();
        }, 500); // Longer delay to ensure canvas is rendered
    });

    Livewire.on('stopSnakeGame', () => {
        if (snake.interval) {
            clearInterval(snake.interval);
        }
    });

    // Memory game card flip delay
    Livewire.on('clearMemoryCards', (data) => {
        setTimeout(() => {
            @this.call('clearMemoryFlipped');
        }, data.delay || 1000);
    });
</script>