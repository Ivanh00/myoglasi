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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('city')->nullable();
            $table->timestamp('login_at')->nullable();
            $table->timestamp('last_activity');
            $table->integer('request_count')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for performance
            $table->index(['ip_address']);
            $table->index(['user_id', 'is_active']);
            $table->index(['last_activity']);
            $table->index(['session_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
