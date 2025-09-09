<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Seller
            $table->decimal('starting_price', 10, 2); // Početna cena
            $table->decimal('buy_now_price', 10, 2)->nullable(); // Kupi odmah cena
            $table->decimal('current_price', 10, 2); // Trenutna najviša ponuda
            $table->integer('total_bids')->default(0); // Ukupan broj ponuda
            $table->timestamp('starts_at'); // Kad počinje aukcija
            $table->timestamp('ends_at'); // Kad završava aukcija
            $table->timestamp('extended_at')->nullable(); // Kad je produžena
            $table->integer('extension_count')->default(0); // Broj produžavanja
            $table->enum('status', ['active', 'ended', 'cancelled'])->default('active');
            $table->foreignId('winner_id')->nullable()->constrained('users')->onDelete('set null'); // Pobednik
            $table->timestamps();
            
            // Ensure one auction per listing
            $table->unique('listing_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};