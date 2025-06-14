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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'judge_id')) {
                $table->uuid('judge_id')->nullable()->after('id');
                $table->foreign('judge_id')->references('id')->on('judges')->nullOnDelete();
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['judge_id']);
            $table->dropColumn('judge_id');
        });
    }
};
