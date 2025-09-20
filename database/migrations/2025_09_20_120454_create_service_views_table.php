<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id')->nullable();
            $table->timestamps();

            // Unique constraint to track unique views
            $table->unique(['service_id', 'user_id']);
            $table->index(['service_id', 'ip_address', 'session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_views');
    }
};