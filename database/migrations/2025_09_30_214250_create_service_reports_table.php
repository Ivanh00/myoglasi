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
        Schema::create('service_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Ko je prijavio
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // Koja usluga
            $table->string('reason'); // Razlog prijave
            $table->text('details'); // Detaljno objašnjenje
            $table->enum('status', ['pending', 'reviewed', 'resolved'])->default('pending'); // Status prijave
            $table->text('admin_notes')->nullable(); // Napomene administratora
            $table->timestamps();

            // Prevent duplicate reports from same user for same service
            $table->unique(['user_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_reports');
    }
};
