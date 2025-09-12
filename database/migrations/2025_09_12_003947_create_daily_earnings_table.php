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
        Schema::create('daily_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('type', ['games', 'daily_contest_winner', 'game_leaderboard_click_game', 'game_leaderboard_memory_game', 'game_leaderboard_number_game', 'game_leaderboard_puzzle_game', 'referral', 'bonus']);
            $table->integer('amount');
            $table->string('description');
            $table->text('details')->nullable(); // JSON data for game details
            $table->timestamps();

            $table->unique(['user_id', 'date', 'type']); // One earning per type per day per user
            $table->index(['date', 'type']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_earnings');
    }
};
