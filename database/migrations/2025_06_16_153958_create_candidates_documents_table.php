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
        Schema::create('candidates_documents', function (Blueprint $table) {
            $table->id();
            $table->year('year')->nullable();
            $table->foreignId('type_id')->nullable()->constrained('types')->cascadeOnDelete();// Масала тоифаси;
            $table->foreignId('region_id')->nullable()->constrained('regions')->cascadeOnDelete('cascade');
            $table->uuid('judge_id')->nullable();
            $table->foreign('judge_id')->references('id')->on('judges')->onDelete('cascade');
            $table->string('position')->nullable();
            $table->integer('code')->nullable();
            $table->integer('number')->nullable(); // 3
            $table->string('full_name')->nullable(); // Бегимов Руфат Мусурмонович
            $table->text('appointment_info');
            $table->date('start_date')->nullable(); // 12.07.2018
            $table->date('end_date')->nullable(); // 12.07.2023
            $table->foreignId('court_specialty_id')->nullable()->constrained('court_specialities')->cascadeOnDelete();
            $table->foreignId('superme_judges_id')->nullable()->constrained('superme_judges')->cascadeOnDelete();
            $table->foreignId('status_candidates_id')->nullable()->constrained('status_candidates')->cascadeOnDelete();
            $table->date('renewed_date')->nullable();
            $table->string('term_type')->nullable(); // Янги муддатга
            $table->string('court_type')->nullable(); // Иқтисодий
            $table->string('judge_level')->nullable(); // Туман судьяси
            $table->string('suitability')->nullable(); // Муносиб
            $table->date('decision_date')->nullable(); // 07.01.2023
            $table->string('transferred_to')->nullable(); // Инспекцияга ўтказилган
            $table->string('inspector_name')->nullable(); // И.Хакимов
            $table->string('discussion_status')->nullable();
            $table->date('final_date')->nullable(); // 18.07.2023
            $table->string('final_result')->nullable(); // Лойиқ
            $table->string('final_region')->nullable(); // Самарқанд вилояти
            $table->text('final_position')->nullable(); // Самарқанд шаҳар иқтисодий судининг судьяси
            $table->string('term_length')->nullable(); // 10 йил
            $table->string('final_court_type')->nullable(); // Иқтисодий
            $table->date('final_approval_date')->nullable(); // 18.07.2023
            $table->string('document_number')->nullable(); // 1807
            $table->boolean('is_sent')->default(false);
            $table->unsignedBigInteger('sent_by')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates_documents');
    }
};
