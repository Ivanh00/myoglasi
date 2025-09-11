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
        // First update the enum to include both old and new values
        \DB::statement("ALTER TABLE listing_promotions MODIFY COLUMN type ENUM('featured_category', 'featured_homepage', 'highlighted', 'auto_refresh', 'large_image', 'double_images', 'extended_duration')");
        
        // Then update existing large_image promotions to double_images
        \DB::table('listing_promotions')
            ->where('type', 'large_image')
            ->update(['type' => 'double_images']);
            
        // Finally remove the old large_image value from enum
        \DB::statement("ALTER TABLE listing_promotions MODIFY COLUMN type ENUM('featured_category', 'featured_homepage', 'highlighted', 'auto_refresh', 'double_images', 'extended_duration')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert double_images promotions to large_image
        \DB::table('listing_promotions')
            ->where('type', 'double_images')
            ->update(['type' => 'large_image']);
            
        // Revert the enum values
        \DB::statement("ALTER TABLE listing_promotions MODIFY COLUMN type ENUM('featured_category', 'featured_homepage', 'highlighted', 'auto_refresh', 'large_image', 'extended_duration')");
    }
};
