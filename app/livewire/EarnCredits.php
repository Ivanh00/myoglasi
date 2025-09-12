<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DailyEarning;
use App\Models\GameSession;
use App\Models\Transaction;
use App\Models\Setting;

class EarnCredits extends Component
{
    public $selectedGame = null;
    public $gameActive = false;
    public $gameScore = 0;
    public $clickCount = 0;
    public $timeLeft = 30;
    public $gameCompleted = false;
    
    // Memory game state
    public $memoryCards = [];
    public $memoryFlipped = [];
    public $memoryMatched = [];
    public $memoryMoves = 0;
    
    // Snake game state
    public $snakePosition = [];
    public $snakeDirection = 'right';
    public $snakeFood = [];
    public $snakeScore = 0;
    public $snakeGameOver = false;
    
    // Number game state
    public $numberTarget = 0;
    public $numberCurrent = 0;
    public $numberMoves = 0;
    public $numberCompleted = false;

    public function startGame($gameType)
    {
        if (!Setting::get('game_credit_enabled', true)) {
            session()->flash('error', 'Zaradjivanje kroz igrice je trenutno onemogućeno.');
            return;
        }

        $todaysEarnings = DailyEarning::getTodaysEarnings(auth()->id());
        $maxDaily = Setting::get('game_credit_amount', 100);
        
        if ($todaysEarnings >= $maxDaily) {
            session()->flash('error', 'Već ste zaradili maksimalno kredita danas (' . $maxDaily . ' RSD).');
            return;
        }

        $this->selectedGame = $gameType;
        $this->gameActive = true;
        $this->gameScore = 0;
        $this->clickCount = 0;
        $this->timeLeft = 30;
        $this->gameCompleted = false;
        
        // Initialize game-specific state
        switch($gameType) {
            case 'memory_game':
                $this->initializeMemoryGame();
                break;
            case 'snake_game':
                $this->initializeSnakeGame();
                $this->dispatch('startSnakeGame');
                break;
            case 'number_game':
                $this->initializeNumberGame();
                break;
        }

        // Create game session
        GameSession::create([
            'user_id' => auth()->id(),
            'game_type' => $gameType,
            'started_at' => now(),
            'game_data' => json_encode(['initial_state' => true])
        ]);
    }

    public function clickGame()
    {
        if (!$this->gameActive || $this->selectedGame !== 'click_game') {
            return;
        }

        $this->clickCount++;
        $this->gameScore = $this->clickCount;
    }

    public function completeGame()
    {
        if (!$this->gameActive) {
            return;
        }

        $this->gameActive = false;
        $this->gameCompleted = true;
        
        // Stop snake game if active
        if ($this->selectedGame === 'snake_game') {
            $this->dispatch('stopSnakeGame');
        }

        // Calculate credits based on game type and score
        $creditsEarned = $this->calculateCreditsForGame($this->selectedGame, $this->gameScore);
        
        $todaysEarnings = DailyEarning::getTodaysEarnings(auth()->id());
        $maxDaily = Setting::get('game_credit_amount', 100);
        $remaining = $maxDaily - $todaysEarnings;
        
        $creditsEarned = min($creditsEarned, $remaining);

        if ($creditsEarned > 0) {
            // Add credits to user balance
            auth()->user()->increment('balance', $creditsEarned);

            // Update or create daily earning record
            $dailyEarning = DailyEarning::where('user_id', auth()->id())
                ->where('date', today())
                ->where('type', 'games')
                ->first();

            if ($dailyEarning) {
                // Update existing record
                $dailyEarning->increment('amount', $creditsEarned);
                $dailyEarning->update([
                    'description' => 'Zaradio kroz igrice (ukupno: ' . ($dailyEarning->amount) . ' RSD)',
                    'details' => json_encode([
                        'total_games' => ($dailyEarning->details['total_games'] ?? 0) + 1,
                        'latest_game' => [
                            'game_type' => $this->selectedGame,
                            'score' => $this->gameScore,
                            'clicks' => $this->clickCount,
                            'credits' => $creditsEarned
                        ]
                    ])
                ]);
            } else {
                // Create new record
                DailyEarning::create([
                    'user_id' => auth()->id(),
                    'date' => today(),
                    'type' => 'games',
                    'amount' => $creditsEarned,
                    'description' => 'Zaradio kroz ' . $this->getGameName($this->selectedGame),
                    'details' => json_encode([
                        'total_games' => 1,
                        'latest_game' => [
                            'game_type' => $this->selectedGame,
                            'score' => $this->gameScore,
                            'clicks' => $this->clickCount,
                            'credits' => $creditsEarned
                        ]
                    ])
                ]);
            }

            // Create transaction record
            Transaction::create([
                'user_id' => auth()->id(),
                'type' => 'game_earnings',
                'amount' => $creditsEarned,
                'status' => 'completed',
                'description' => 'Zaradio kredit kroz igru: ' . $this->getGameName($this->selectedGame),
                'reference_number' => 'GAME-' . now()->timestamp,
            ]);

            session()->flash('success', 'Čestitamo! Zaradili ste ' . $creditsEarned . ' RSD!');
        } else {
            session()->flash('info', 'Pokušajte ponovo sutra - dostigli ste dnevni limit.');
        }

        // Update game session
        $session = GameSession::where('user_id', auth()->id())
            ->where('game_type', $this->selectedGame)
            ->whereNull('completed_at')
            ->latest()
            ->first();
            
        if ($session) {
            $session->update([
                'score' => $this->gameScore,
                'credits_earned' => $creditsEarned,
                'completed_at' => now()
            ]);
        }
    }

    public function resetGame()
    {
        $this->selectedGame = null;
        $this->gameActive = false;
        $this->gameScore = 0;
        $this->clickCount = 0;
        $this->timeLeft = 30;
        $this->gameCompleted = false;
    }

    private function getGameName($gameType)
    {
        return match($gameType) {
            'click_game' => 'Igru klikanja',
            'memory_game' => 'Igru memorije',
            'number_game' => 'Igru brojeva',
            'puzzle_game' => 'Slagalicu',
            'snake_game' => 'Zmiju',
            default => 'Nepoznatu igru'
        };
    }

    private function calculateCreditsForGame($gameType, $score)
    {
        return match($gameType) {
            'click_game' => min(10, floor($score / 10)), // 1 credit per 10 clicks, max 10
            'memory_game' => min(15, floor($score / 2)), // More credits for memory, max 15
            'number_game' => min(20, $score), // 1 credit per correct answer, max 20
            'puzzle_game' => min(12, floor((100 - $score) / 5)), // Fewer moves = more credits, max 12
            'snake_game' => min(25, floor($score / 3)), // 1 credit per 3 food items, max 25
            default => 0
        };
    }

    // Memory Game Methods
    public function initializeMemoryGame()
    {
        $colors = ['red', 'blue', 'green', 'yellow', 'purple', 'orange'];
        $cards = array_merge($colors, $colors); // Duplicate each color
        shuffle($cards);
        
        $this->memoryCards = $cards;
        $this->memoryFlipped = [];
        $this->memoryMatched = [];
        $this->memoryMoves = 0;
        $this->timeLeft = 60; // 60 seconds for memory game
    }

    public function flipMemoryCard($index)
    {
        if (!$this->gameActive || $this->selectedGame !== 'memory_game') return;
        if (in_array($index, $this->memoryFlipped) || in_array($index, $this->memoryMatched)) return;
        if (count($this->memoryFlipped) >= 2) return;

        $this->memoryFlipped[] = $index;

        if (count($this->memoryFlipped) === 2) {
            $this->memoryMoves++;
            
            // Check for match
            if ($this->memoryCards[$this->memoryFlipped[0]] === $this->memoryCards[$this->memoryFlipped[1]]) {
                $this->memoryMatched = array_merge($this->memoryMatched, $this->memoryFlipped);
                $this->memoryFlipped = [];
                
                // Check if game completed
                if (count($this->memoryMatched) === count($this->memoryCards)) {
                    $this->gameScore = max(0, 50 - $this->memoryMoves); // Better score for fewer moves
                    $this->completeGame();
                }
            } else {
                // Clear flipped cards after delay (handled by JavaScript)
                $this->dispatch('clearMemoryCards');
            }
        }
    }

    public function clearMemoryFlipped()
    {
        $this->memoryFlipped = [];
    }

    // Snake Game Methods
    public function initializeSnakeGame()
    {
        $this->snakePosition = [[10, 10], [10, 9], [10, 8]]; // Start with 3 segments
        $this->snakeDirection = 'right';
        $this->snakeFood = $this->generateSnakeFood();
        $this->snakeScore = 0;
        $this->snakeGameOver = false;
        $this->timeLeft = 120; // 2 minutes for snake game
    }

    public function moveSnake($direction)
    {
        if (!$this->gameActive || $this->selectedGame !== 'snake_game' || $this->snakeGameOver) return;
        
        $this->snakeDirection = $direction;
    }

    public function updateSnakePosition()
    {
        if (!$this->gameActive || $this->snakeGameOver) return;

        $head = $this->snakePosition[0];
        $newHead = $head;

        // Move head based on direction
        switch($this->snakeDirection) {
            case 'up': $newHead[1]--; break;
            case 'down': $newHead[1]++; break;
            case 'left': $newHead[0]--; break;
            case 'right': $newHead[0]++; break;
        }

        // Check boundaries (20x20 grid)
        if ($newHead[0] < 0 || $newHead[0] >= 20 || $newHead[1] < 0 || $newHead[1] >= 20) {
            $this->snakeGameOver = true;
            $this->gameScore = $this->snakeScore;
            $this->completeGame();
            return;
        }

        // Check self collision
        if (in_array($newHead, $this->snakePosition)) {
            $this->snakeGameOver = true;
            $this->gameScore = $this->snakeScore;
            $this->completeGame();
            return;
        }

        // Add new head
        array_unshift($this->snakePosition, $newHead);

        // Check food collision
        if ($newHead === $this->snakeFood) {
            $this->snakeScore++;
            $this->snakeFood = $this->generateSnakeFood();
        } else {
            // Remove tail if no food eaten
            array_pop($this->snakePosition);
        }
    }

    private function generateSnakeFood()
    {
        do {
            $food = [rand(0, 19), rand(0, 19)];
        } while (in_array($food, $this->snakePosition));
        
        return $food;
    }

    // Number Game Methods
    public function initializeNumberGame()
    {
        $this->numberTarget = rand(50, 200);
        $this->numberCurrent = 0;
        $this->numberMoves = 0;
        $this->numberCompleted = false;
        $this->timeLeft = 60; // 60 seconds for number game
    }

    public function numberGameAction($action, $value)
    {
        if (!$this->gameActive || $this->selectedGame !== 'number_game') return;

        $this->numberMoves++;
        
        switch($action) {
            case 'add': $this->numberCurrent += $value; break;
            case 'subtract': $this->numberCurrent -= $value; break;
            case 'multiply': $this->numberCurrent *= $value; break;
            case 'divide': $this->numberCurrent = floor($this->numberCurrent / $value); break;
        }

        // Check if target reached
        if ($this->numberCurrent === $this->numberTarget) {
            $this->numberCompleted = true;
            $this->gameScore = max(0, 20 - $this->numberMoves); // Better score for fewer moves
            $this->completeGame();
        }
    }

    public function getTodaysEarningsProperty()
    {
        return DailyEarning::getTodaysEarnings(auth()->id());
    }

    public function getMaxDailyProperty()
    {
        return Setting::get('game_credit_amount', 100);
    }

    public function getRemainingCreditsProperty()
    {
        return max(0, $this->maxDaily - $this->todaysEarnings);
    }

    public function render()
    {
        $recentEarnings = DailyEarning::where('user_id', auth()->id())
            ->whereIn('type', ['games', 'game_leaderboard_click_game', 'game_leaderboard_memory_game', 'game_leaderboard_number_game', 'game_leaderboard_puzzle_game', 'game_leaderboard_snake_game'])
            ->whereDate('date', '>=', now()->subDays(7))
            ->orderBy('date', 'desc')
            ->get();

        // Get today's leaderboard for each game
        $todaysLeaderboard = [];
        $gameTypes = ['click_game', 'memory_game', 'number_game', 'puzzle_game', 'snake_game'];
        
        foreach ($gameTypes as $gameType) {
            $topPlayers = GameSession::where('game_type', $gameType)
                ->whereDate('created_at', today())
                ->whereNotNull('completed_at')
                ->with('user')
                ->orderBy('score', 'desc')
                ->limit(10)
                ->get();
                
            $todaysLeaderboard[$gameType] = $topPlayers;
        }

        return view('livewire.earn-credits', [
            'recentEarnings' => $recentEarnings,
            'todaysLeaderboard' => $todaysLeaderboard
        ])->layout('layouts.app');
    }
}
