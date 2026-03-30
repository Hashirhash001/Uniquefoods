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
        Schema::create('shipping_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();   // e.g. 'mode', 'free_threshold', 'base_rate', etc.
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, decimal, integer
            $table->string('label');           // human-readable label for admin UI
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_settings');
    }
};
