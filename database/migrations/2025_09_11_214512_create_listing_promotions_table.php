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
        Schema::create('listing_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->onDelete('cascade');
            $table->enum('type', [
                'featured_category',     // Top kategorije
                'featured_homepage',     // Top glavne strane  
                'highlighted',          // Istaknut oglas
                'auto_refresh',         // Automatsko osvežavanje
                'large_image',          // Dupla veličina slike
                'extended_duration'     // Produžena trajnost
            ]);
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->integer('price_paid'); // Koliko je korisnik platio za ovu promociju
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['listing_id', 'type']);
            $table->index(['type', 'is_active', 'expires_at']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listing_promotions');
    }
};
