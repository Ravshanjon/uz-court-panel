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
        Schema::create('judges_registries', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->foreignId('region_id')->nullable()->constrained('regions');
            $table->string('full_name')->nullable();
            $table->date('brith_day')->nullable();
            $table->date('judges_anouncment')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judges_registries');
    }
};
