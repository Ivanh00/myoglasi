<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listing_promotions', function (Blueprint $table) {
            // Make listing_id nullable since we might have service_id instead
            $table->unsignedBigInteger('listing_id')->nullable()->change();

            // Add service_id column
            $table->foreignId('service_id')->nullable()->after('listing_id')->constrained()->onDelete('cascade');

            // Add index for service_id
            $table->index(['service_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('listing_promotions', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropIndex(['service_id', 'is_active']);
            $table->dropColumn('service_id');

            // Make listing_id required again
            $table->unsignedBigInteger('listing_id')->nullable(false)->change();
        });
    }
};