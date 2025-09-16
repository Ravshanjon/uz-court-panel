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
        Schema::create('appeals', function (Blueprint $table) {
            $table->id();
            $table->uuid('judge_id'); // main sudya (Judges.id)
            $table->date('appeal_date')->nullable();
            $table->string('judge_full_name')->nullable();
            $table->foreignId('court_name_id')->nullable()->constrained('court_names')->cascadeOnDelete();
            $table->foreignId('type_of_decision_id')->nullable()->constrained('type_of_decisions')->cascadeOnDelete();
            $table->foreignId('job_category_id')->nullable()->constrained('job_categories')->cascadeOnDelete();
            $table->string('case_type')->nullable();
            $table->text('sides')->nullable();
            $table->text('content')->nullable();
            $table->string('file')->nullable();
            $table->decimal('score', 10, 2)->default(0); // jamlangan ta’sir (ixtiyoriy)
            $table->timestamps();
            $table->index('judge_id');
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
