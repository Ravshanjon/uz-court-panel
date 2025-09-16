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
                $table->foreignId('parents_id')->nullable()->constrained('parents')->cascadeOnDelete();
                $table->string('name')->nullable();
                $table->date('birth_date')->nullable();
                $table->string('birth_place')->nullable();
                $table->string('working_place')->nullable();
                $table->string('live_place')->nullable();

                $table->boolean('is_deceased')->nullable()->default(false);
                $table->text('death_note')->nullable();

                $table->boolean('marriage_annulled')->nullable()->default(false);
                $table->text('annulment_note')->nullable();

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
