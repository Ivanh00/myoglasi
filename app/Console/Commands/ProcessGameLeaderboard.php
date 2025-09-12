<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GameSession;
use App\Models\DailyEarning;
use App\Models\Transaction;
use App\Models\Setting;

class ProcessGameLeaderboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:leaderboard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process game leaderboard and award daily winners';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Setting::get('game_leaderboard_enabled', true)) {
            $this->info('Game leaderboard is disabled.');
            return;
        }

        $yesterday = now()->subDay();
        $bonusAmount = Setting::get('game_leaderboard_bonus', 50);
        $gameTypes = ['click_game', 'memory_game', 'number_game'];

        foreach ($gameTypes as $gameType) {
            // Find yesterday's best player for this game type
            $winner = GameSession::where('game_type', $gameType)
                ->whereDate('created_at', $yesterday->toDateString())
                ->whereNotNull('completed_at')
                ->with('user')
                ->orderBy('score', 'desc')
                ->first();

            if (!$winner) {
                $this->info("No players for {$gameType} yesterday.");
                continue;
            }

            // Check if winner already got leaderboard bonus for this date and game
            $alreadyRewarded = DailyEarning::where('user_id', $winner->user_id)
                ->where('date', $yesterday->toDateString())
                ->where('type', 'game_leaderboard_' . $gameType)
                ->exists();

            if ($alreadyRewarded) {
                $this->info("Leaderboard winner already rewarded for {$gameType} on " . $yesterday->toDateString());
                continue;
            }

            // Award the winner
            $winner->user->increment('balance', $bonusAmount);

            // Create daily earning record
            DailyEarning::create([
                'user_id' => $winner->user_id,
                'date' => $yesterday->toDateString(),
                'type' => 'game_leaderboard_' . $gameType,
                'amount' => $bonusAmount,
                'description' => 'Dnevni pobednik - ' . $this->getGameName($gameType) . ' (skor: ' . $winner->score . ')',
                'details' => json_encode([
                    'game_type' => $gameType,
                    'winning_score' => $winner->score,
                    'contest_date' => $yesterday->toDateString()
                ])
            ]);

            // Create transaction record
            Transaction::create([
                'user_id' => $winner->user_id,
                'type' => 'game_leaderboard_bonus',
                'amount' => $bonusAmount,
                'status' => 'completed',
                'description' => 'Dnevni leaderboard bonus - ' . $this->getGameName($gameType),
                'reference_number' => 'LEAD-' . $yesterday->format('Ymd') . '-' . strtoupper(substr($gameType, 0, 4)),
            ]);

            // Send notification to winner
            \App\Models\Message::create([
                'sender_id' => 1, // System
                'receiver_id' => $winner->user_id,
                'listing_id' => null,
                'message' => "Čestitamo! Najbolji ste u " . $this->getGameName($gameType) . "!\n\nPostigli ste najbolji rezultat ({$winner->score} poena) dana " . $yesterday->format('d.m.Y') . " i zaradili ste bonus od {$bonusAmount} RSD!\n\nNastavi da igraš i osvajaj nove bonuse!",
                'subject' => 'Dnevni leaderboard bonus!',
                'is_system_message' => true,
                'is_read' => false
            ]);

            $this->info("Leaderboard winner for {$gameType}: {$winner->user->name} (score: {$winner->score}) - awarded {$bonusAmount} RSD");
        }
    }

    private function getGameName($gameType)
    {
        return match($gameType) {
            'click_game' => 'igri klikanja',
            'memory_game' => 'igri memorije',
            'number_game' => 'igri brojeva',
            'puzzle_game' => 'slagalici',
            'snake_game' => 'zmiji',
            'reaction_game' => 'igri reakcije',
            default => 'nepoznatoj igri'
        };
    }
}
