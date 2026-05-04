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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_online')->default(false)->after('availability');
            $table->string('cover_position', 20)->default('center')->after('is_online');
            $table->string('theme_color', 20)->nullable()->after('cover_position');
            $table->string('theme_bg', 20)->nullable()->after('theme_color');
            $table->string('theme_text', 20)->nullable()->after('theme_bg');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_online', 'cover_position', 'theme_color', 'theme_bg', 'theme_text']);
        });
    }
};
