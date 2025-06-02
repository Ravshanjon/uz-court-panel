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
        Schema::create('appeals', function (Blueprint $table) {
            $table->id();
            $table->uuid('judge_id');
            $table->foreign('judge_id')->references('id')->on('judges')->cascadeOnDelete();
            $table->foreignId('judges_stages_id')->nullable()->constrained('judges_stages')->cascadeOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('regions')->cascadeOnDelete('cascade');
            $table->foreignId('court_name_id')->nullable()->constrained('court_names')->cascadeOnDelete();
            $table->foreignId('court_specialty_id')->nullable()->constrained('court_specialities')->cascadeOnDelete();
            $table->foreignId('court_type_id')->nullable()->constrained('court_types')->cascadeOnDelete();
            $table->string('case_type')->nullable();
            $table->foreignId('job_category_id')->nullable()->constrained()->cascadeOnDelete('cascade');
            $table->foreignId('type_of_decision_id')->nullable()->constrained('type_of_decisions')->cascadeOnDelete();
            $table->foreignId('instances_id')->nullable()->constrained('instances')->cascadeOnDelete();
            $table->foreignId('reasons_id')->nullable()->constraine('reasons')->cascadeOnDelete();
            $table->integer('score')->nullable();
            $table->text('sides')->nullable(); // Ишдаги тарафлар
            $table->text('content')->nullable(); // Иш мазмуни
            $table->string('file')->nullable(); // Файл
            $table->date('appeal_date')->nullable();
            $table->text('appeal_reason')->nullable();
            $table->string('first_instance_decision')->nullable();
            $table->text('cassation')->nullable();
            $table->text('repeat_cassation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appeals');
    }
};
