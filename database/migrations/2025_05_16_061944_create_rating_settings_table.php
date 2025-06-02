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
        Schema::create('rating_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('quality_score')->default(50);
            $table->integer('etiquette_score')->default(15);
            $table->integer('ethics_score')->default(30);
            $table->integer('foreign_language_bonus')->nullable()->default(0);
            $table->integer('adding_rating')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_settings');
    }
};
