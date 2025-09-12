<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum to include leaderboard types
        \DB::statement("ALTER TABLE daily_earnings MODIFY COLUMN type ENUM('games', 'daily_contest_winner', 'game_leaderboard_click_game', 'game_leaderboard_memory_game', 'game_leaderboard_number_game', 'game_leaderboard_puzzle_game', 'referral', 'bonus')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum
        \DB::statement("ALTER TABLE daily_earnings MODIFY COLUMN type ENUM('games', 'daily_contest_winner', 'referral', 'bonus')");
    }
};
