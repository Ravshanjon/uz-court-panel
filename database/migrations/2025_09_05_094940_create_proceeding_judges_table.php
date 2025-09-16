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
        Schema::create('proceeding_judges', function (Blueprint $table) {
            $table->id();
            $table->uuid('proceeding_id');
            $table->uuid('judge_id'); // panel sudyasi
            $table->enum('role', ['speaker','presiding','jury']);
            $table->timestamps();

            $table->unique(['proceeding_id','role']);
            $table->foreign('proceeding_id')->references('id')->on('proceedings')->cascadeOnDelete();
            $table->foreign('judge_id')->references('id')->on('judges')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proceeding_judges');
    }
};
