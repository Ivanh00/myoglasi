<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Ko je prijavio
            $table->foreignId('listing_id')->constrained()->onDelete('cascade'); // Koji oglas
            $table->string('reason'); // Razlog prijave
            $table->text('details'); // Detaljno objašnjenje
            $table->enum('status', ['pending', 'reviewed', 'resolved'])->default('pending'); // Status prijave
            $table->text('admin_notes')->nullable(); // Napomene administratora
            $table->timestamps();
            
            // Prevent duplicate reports from same user for same listing
            $table->unique(['user_id', 'listing_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_reports');
    }
};