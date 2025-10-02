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
        // Fix all users who have business plan but business_plan_total = 0 or NULL
        $businessLimit = \App\Models\Setting::get('business_plan_limit', 10);

        \App\Models\User::where('payment_plan', 'business')
            ->where(function($q) {
                $q->where('business_plan_total', 0)
                  ->orWhereNull('business_plan_total');
            })
            ->update(['business_plan_total' => $businessLimit]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this fix
    }
};
