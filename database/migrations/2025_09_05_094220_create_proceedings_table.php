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
        Schema::create('proceedings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('judge_id'); // shu ish bo‘yicha BAHOLANAYOTGAN sudya (1-inst sudyasi)
            $table->enum('type', ['first', 'appeal', 'cassation', 'taftish1', 'taftish2', 'taftish3']);
            $table->uuid('parent_id')->nullable();         // zanjir (appeal -> first, t1 -> appeal/cassation)
            $table->uuid('root_first_id')->nullable();     // doimo 1-instansiya proceeding.id (expiry uchun)
            $table->timestamp('issued_at')->nullable();     // aynan shu bosqich qarori sanasi
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('judge_id')->references('id')->on('judges')->cascadeOnDelete();
            $table->foreign('parent_id')->references('id')->on('proceedings')->nullOnDelete();
            $table->foreign('root_first_id')->references('id')->on('proceedings')->nullOnDelete();
            $table->index(['type', 'root_first_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proceedings');
    }
};
