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
        Schema::create('judge_activities', function (Blueprint $table) {
            $table->id();
            $table->integer('criminal_first_instance_avg')->default(0);
            $table->integer('criminal_appeal_avg')->default(0);
            $table->integer('criminal_cassation_avg')->default(0);
            $table->integer('admin_violation_first_instance_avg')->default(0);
            $table->integer('admin_violation_appeal_avg')->default(0);
            $table->integer('admin_violation_cassation_avg')->default(0);
            $table->integer('materials_first_instance_avg')->default(0);
            $table->integer('materials_appeal_avg')->default(0);
            $table->integer('materials_cassation_avg')->default(0);
            $table->integer('civil_appeal_avg')->default(0);
            $table->integer('civil_cassation_avg')->default(0);
            $table->integer('economic_first_instance_avg')->default(0);
            $table->integer('economic_appeal_avg')->default(0);
            $table->integer('economic_cassation_avg')->default(0);
            $table->integer('administrative_case_first_instance_avg')->default(0);
            $table->integer('administrative_case_appeal_avg')->default(0);
            $table->integer('administrative_case_cassation_avg')->default(0);
            $table->integer('forum_topics_count')->default(0);
            $table->integer('forum_comments_count')->default(0);
            $table->integer('min_workload_first_instance')->default(0);
            $table->integer('min_workload_appeal')->default(0);
            $table->integer('min_workload_cassation')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judge_activities');
    }
};
