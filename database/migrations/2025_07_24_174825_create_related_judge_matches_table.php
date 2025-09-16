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
        Schema::create('related_judge_matches', function (Blueprint $table) {
            $table->id();
            $table->uuid('judge_a_id');
            $table->uuid('judge_b_id');
            $table->string('relation_type');
            $table->string('relative_name')->nullable();
            $table->unsignedTinyInteger('match_percent')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('related_judge_matches');
    }
};
