<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            // expires_at already exists, only add new fields
            $table->timestamp('renewed_at')->nullable()->after('expires_at');
            $table->integer('renewal_count')->default(0)->after('renewed_at');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['renewed_at', 'renewal_count']);
        });
    }
};