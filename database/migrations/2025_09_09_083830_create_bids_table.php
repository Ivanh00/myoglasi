<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Bidder
            $table->decimal('amount', 10, 2); // Ponuđena cena
            $table->boolean('is_winning')->default(false); // Da li je trenutno pobednička
            $table->boolean('is_auto_bid')->default(false); // Automatska ponuda
            $table->decimal('max_bid', 10, 2)->nullable(); // Maksimalna auto ponuda
            $table->string('ip_address')->nullable(); // IP adresa za bezbednost
            $table->timestamps();
            
            // Index for performance
            $table->index(['auction_id', 'is_winning']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};