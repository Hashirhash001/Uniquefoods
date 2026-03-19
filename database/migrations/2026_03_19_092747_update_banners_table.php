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
        Schema::table('banners', function (Blueprint $table) {
            $table->string('title_color', 20)->nullable()->after('text_color');
            $table->string('subtitle_color', 20)->nullable()->after('title_color');
            $table->string('description_color', 20)->nullable()->after('subtitle_color');
            $table->string('subtitle_bg_color', 20)->nullable()->after('description_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('title_color');
            $table->dropColumn('subtitle_color');
            $table->dropColumn('description_color');
            $table->dropColumn('subtitle_bg_color');
        });
    }
};
