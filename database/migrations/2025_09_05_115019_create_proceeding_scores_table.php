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
        Schema::create('proceeding_scores', function (Blueprint $table) {
            $table->id();
            $table->uuid('proceeding_id');
            $table->enum('scope', ['first','appeal','cassation','taftish1','taftish2','taftish3']);
            // scope — “qaysi bosqichga taalluqli baho” emas, bu satrning mazmun nomi.
            // Asosiy qoida: appeal/cassation bosqichida faqat 1-inst sudyasidan olinadi.
            // Taftishlarda paneldagi rollarga 100%/50% bo‘lib tarqatamiz.
            $table->unsignedTinyInteger('penalty'); // 0..50 (bazaviy ball)
            $table->text('reason')->nullable();
            $table->foreign('proceeding_id')->references('id')->on('proceedings')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proceeding_scores');
    }
};
