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
        Schema::create('service_inspections', function (Blueprint $table) {
            $table->id();
            $table->uuid('judge_id');
            $table->foreign('judge_id')->references('id')->on('judges')->cascadeOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('regions')->cascadeOnDelete();
            $table->foreignId('mistake_id')->nullable()->constrained('mistakes')->cascadeOnDelete('cascade');
            $table->foreignId('prision_type_id')->nullable()->constrained('prision_type')->cascadeOnDelete();
            $table->string('file');
            $table->foreignId('inspection_conducted_id')->constrained('inspection_conducted')->cascadeOnDelete();
            $table->foreignId('inspection_adults_id')->constrained('inspection_adults')->cascadeOnDelete();
            $table->foreignId('inspection_offices_id')->constrained('inspection_offices')->cascadeOnDelete();
            $table->foreignId('inspection_cases_id')->constrained('inspection_cases')->cascadeOnDelete();
            $table->foreignId('inspection_regulations_id')->constrained('inspection_regulations')->cascadeOnDelete();
            $table->date('inspection_qualification_dates');
            $table->date('date_referred');
            $table->string('report_qualification_judgement');
            $table->date('date_case');
            $table->string('terminated')->nullable();
            $table->string('appealed')->nullable();
            $table->string('overturned')->nullable();
            $table->string('changed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_inspections');
    }
};
