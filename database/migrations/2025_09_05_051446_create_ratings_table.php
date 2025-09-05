<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rater_id')->constrained('users')->onDelete('cascade'); // Ko ocenjuje
            $table->foreignId('rated_user_id')->constrained('users')->onDelete('cascade'); // Ko je ocenjen
            $table->foreignId('listing_id')->constrained()->onDelete('cascade'); // Vezano za koji oglas
            $table->enum('rating', ['positive', 'neutral', 'negative']); // Tip ocene
            $table->text('comment')->nullable(); // Komentar
            $table->timestamps();
            
            // Prevent duplicate ratings - one rating per user per listing
            $table->unique(['rater_id', 'rated_user_id', 'listing_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};