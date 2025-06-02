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
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->uuid('judge_id');
            $table->foreign('judge_id')->references('id')->on('judges')->cascadeOnDelete();
            $table->string('father_name');
            $table->date('father_brith_date');
            $table->string('father_lives_place');
            $table->string('mother_name');
            $table->date('mother_brith_date');
            $table->string('father_live_place');
            $table->string('wife_name');
            $table->date('wife_brith_date');
            $table->string('wife_live_place');
            $table->string('kids_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
