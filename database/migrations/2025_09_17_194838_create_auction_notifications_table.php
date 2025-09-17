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
        Schema::create('auction_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('start'); // 'start', 'end', 'outbid'
            $table->boolean('notified')->default(false);
            $table->timestamps();

            $table->unique(['auction_id', 'user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_notifications');
    }
};
