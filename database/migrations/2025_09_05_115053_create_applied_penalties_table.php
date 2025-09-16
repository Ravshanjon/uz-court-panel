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
        Schema::create('applied_penalties', function (Blueprint $table) {
            $table->id();
            $table->uuid('proceeding_id');
            $table->uuid('root_first_id')->nullable(); // ← MUHIM: nullable
            $table->uuid('judge_id');
            $table->enum('role_context', ['target','speaker','presiding','jury']);
            $table->unsignedTinyInteger('amount');
            $table->timestamp('effective_from')->nullable();
            $table->timestamp('effective_until')->nullable();
            $table->boolean('voided')->default(false);
            $table->timestamps();

            $table->index(['judge_id','effective_until','voided']);

            // FKlar: parent jadvallar allaqachon yaratilgan bo’lsin!
            $table->foreign('proceeding_id')->references('id')->on('proceedings')->cascadeOnDelete();
            $table->foreign('root_first_id')->references('id')->on('proceedings')->nullOnDelete(); // ← endi ishlaydi
            $table->foreign('judge_id')->references('id')->on('judges')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applied_penalties');
    }
};
