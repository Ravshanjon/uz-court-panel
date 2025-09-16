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
        Schema::create('establishments', function (Blueprint $table) {
            $table->id();
            $table->integer('number_state')->nullable();
            $table->foreignId('court_specialty_id')->nullable()->constrained('court_specialities')->cascadeOnDelete();
            $table->foreignId('court_type_id')->nullable()->constrained('court_types')->cascadeOnDelete('cascade');
            $table->foreignId('region_id')->nullable()->constrained('regions')->cascadeOnDelete();
            $table->foreignId('provinces_district_id')->nullable()->constrained('provinces_districts')->cascadeOnDelete('cascade');
            $table->foreignId('district_type_id')->nullable()->constrained('district_types')->cascadeOnDelete('cascade');
            $table->foreignId('court_name_id')->nullable()->constrained('court_names')->cascadeOnDelete('cascade');
            $table->foreignId('position_id')->nullable()->constrained('positions')->cascadeOnDelete('cascade');
            $table->foreignId('position_category_id')->nullable()->constrained('position_categories')->cascadeOnDelete('cascade');
            $table->foreignId('document_type_id')->nullable()->constrained('document_types')->cascadeOnDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishments');
    }
};
