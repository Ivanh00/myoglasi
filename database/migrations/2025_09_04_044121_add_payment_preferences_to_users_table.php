<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('payment_plan', ['per_listing', 'monthly', 'yearly', 'free'])->default('per_listing');
            $table->boolean('payment_enabled')->default(true);
            $table->timestamp('plan_expires_at')->nullable();
            $table->integer('free_listings_used')->default(0);
            $table->timestamp('free_listings_reset_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'payment_plan', 
                'payment_enabled', 
                'plan_expires_at', 
                'free_listings_used', 
                'free_listings_reset_at'
            ]);
        });
    }
};