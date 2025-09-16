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
        Schema::create('private_awards', function (Blueprint $table) {
            $table->id();
            $table->uuid('judges_id');
            $table->foreign('judges_id')->references('id')->on('judges')->cascadeOnDelete();
            $table->date('date');
            $table->string('file');
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_awards');
    }
};
