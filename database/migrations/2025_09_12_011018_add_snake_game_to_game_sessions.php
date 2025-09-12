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
        // Update enum to include snake_game
        \DB::statement("ALTER TABLE game_sessions MODIFY COLUMN game_type ENUM('click_game', 'memory_game', 'number_game', 'puzzle_game', 'snake_game')");
        
        // Update daily_earnings enum to include snake leaderboard
        \DB::statement("ALTER TABLE daily_earnings MODIFY COLUMN type ENUM('games', 'daily_contest_winner', 'game_leaderboard_click_game', 'game_leaderboard_memory_game', 'game_leaderboard_number_game', 'game_leaderboard_puzzle_game', 'game_leaderboard_snake_game', 'referral', 'bonus')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enums
        \DB::statement("ALTER TABLE game_sessions MODIFY COLUMN game_type ENUM('click_game', 'memory_game', 'number_game', 'puzzle_game')");
        \DB::statement("ALTER TABLE daily_earnings MODIFY COLUMN type ENUM('games', 'daily_contest_winner', 'game_leaderboard_click_game', 'game_leaderboard_memory_game', 'game_leaderboard_number_game', 'game_leaderboard_puzzle_game', 'referral', 'bonus')");
    }
};
