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
        Schema::create('ip_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->nullable(); // For single IP
            $table->string('ip_range_start')->nullable(); // For IP ranges
            $table->string('ip_range_end')->nullable(); // For IP ranges
            $table->enum('type', ['single', 'range', 'whitelist'])->default('single');
            $table->enum('action', ['block', 'allow'])->default('block');
            $table->text('reason');
            $table->timestamp('expires_at')->nullable(); // NULL = permanent
            $table->foreignId('created_by')->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_generated')->default(false); // System generated vs manual
            $table->timestamps();

            // Indexes for performance
            $table->index(['ip_address', 'is_active']);
            $table->index(['expires_at', 'is_active']);
            $table->index(['type', 'action', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_blocks');
    }
};
