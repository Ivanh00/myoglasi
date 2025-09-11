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
        Schema::table('listings', function (Blueprint $table) {
            $table->enum('listing_type', ['listing', 'service', 'giveaway'])->default('listing')->after('status');
            $table->decimal('price', 10, 2)->nullable()->change(); // Make price nullable for giveaways
            $table->index('listing_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropIndex(['listing_type']);
            $table->dropColumn('listing_type');
            $table->decimal('price', 10, 2)->nullable(false)->change(); // Revert price to not nullable
        });
    }
};
