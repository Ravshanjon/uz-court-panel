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
        Schema::create('judges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('region_id')->nullable()->constrained('regions')->cascadeOnDelete('cascade');
            $table->string('image')->nullable();
            $table->date('vacancy_start')->nullable();
            $table->string('vacancy_status')->nullable();
            $table->date('eligibility_submission_date')->nullable();
            $table->integer('documents_submitted')->default(0);
            $table->integer('codes');
            $table->foreignId('establishment_id')->nullable()->constrained('establishments')->cascadeOnDelete('cascade');
            $table->foreignId('bonuses_id')->nullable()->constrained('bonuses')->cascadeOnDelete('cascade');
            $table->float('quality_score')->default(0);
            $table->float('etiquette_score')->default(0);
            $table->float('ethics_score')->default(0);
            $table->float('foreign_language_bonus')->default(0);
            $table->float('adding_rating')->default(0);
            $table->float('rating')->default(0);
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('pinfl')->unique();
            $table->string('passport_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('address')->nullable();
            $table->string('qrcode')->nullable();
            $table->boolean('gender')->default(true);
            $table->foreignId('nationality_id')->constrained('nationalities')->cascadeOnDelete('cascade');
            $table->date('appointment_date')->nullable();
            $table->date('document_date')->nullable();
            $table->string('document_number')->nullable();
            $table->foreignId('duration_id')->nullable()->constrained('durations')->cascadeOnDelete('cascade');
            $table->integer('age')->nullable(); // Ensure it's stored as an integer
            $table->date('previous_appointment')->nullable();
            $table->integer('previous_duration')->nullable();
            $table->string('legal_experience')->nullable();
            $table->string('judicial_experience')->nullable();
            $table->date('age_extension_date')->nullable();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete('cascade');
            $table->date('graduation_year')->nullable();
            $table->longText('special_education')->nullable();
            $table->boolean('leadership_experience')->nullable()->default(0);
            $table->boolean('leadership_reserve')->default(0);
            $table->boolean('is_featured')->nullable()->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judges');
    }
};
