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
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('isp')->nullable();
            $table->boolean('is_bot')->default(false);
            $table->boolean('is_suspicious')->default(false);
            $table->integer('request_count')->default(1);
            $table->timestamp('first_visit');
            $table->timestamp('last_activity');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamps();

            // Indexes for performance
            $table->index(['ip_address']);
            $table->index(['last_activity']);
            $table->index(['is_suspicious']);
            $table->index(['country_code']);
            $table->unique(['ip_address']); // One record per IP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
