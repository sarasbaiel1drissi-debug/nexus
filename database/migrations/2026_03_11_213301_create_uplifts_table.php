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
        Schema::create('uplifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uplifter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('uplifted_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['uplifter_id', 'uplifted_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uplifts');
    }
};
