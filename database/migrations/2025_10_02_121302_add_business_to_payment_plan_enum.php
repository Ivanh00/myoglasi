<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN payment_plan ENUM('per_listing', 'monthly', 'yearly', 'business', 'free') DEFAULT 'free'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN payment_plan ENUM('per_listing', 'monthly', 'yearly', 'free') DEFAULT 'free'");
    }
};
