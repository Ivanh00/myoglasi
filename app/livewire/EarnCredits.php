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

        // Calculate credits based on score
        $creditsEarned = min(10, floor($this->gameScore / 5)); // 2 credits per 10 clicks, max 10
        
        $todaysEarnings = DailyEarning::getTodaysEarnings(auth()->id());
        $maxDaily = Setting::get('game_credit_amount', 100);
        $remaining = $maxDaily - $todaysEarnings;
        
        $creditsEarned = min($creditsEarned, $remaining);

        if ($creditsEarned > 0) {
            // Add credits to user balance
            auth()->user()->increment('balance', $creditsEarned);

            // Record daily earning
            DailyEarning::create([
                'user_id' => auth()->id(),
                'date' => today(),
                'type' => 'games',
                'amount' => $creditsEarned,
                'description' => 'Zaradio kroz ' . $this->getGameName($this->selectedGame),
                'details' => json_encode([
                    'game_type' => $this->selectedGame,
                    'score' => $this->gameScore,
                    'clicks' => $this->clickCount
                ])
            ]);

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
            default => 'Nepoznatu igru'
        };
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
            ->where('type', 'games')
            ->whereDate('date', '>=', now()->subDays(7))
            ->orderBy('date', 'desc')
            ->get();

        return view('livewire.earn-credits', [
            'recentEarnings' => $recentEarnings
        ])->layout('layouts.app');
    }
}
