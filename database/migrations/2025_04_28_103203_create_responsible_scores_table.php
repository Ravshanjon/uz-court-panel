<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('responsible_scores', function (Blueprint $table) {
            $table->id();
            $table->uuid('judge_id');
            $table->foreign('judge_id')->references('id')->on('judges')->cascadeOnDelete();
            $table->string('judges_box');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsible_scores');
    }
};
