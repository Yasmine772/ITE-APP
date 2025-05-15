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
        Schema::create('specialization_year', function (Blueprint $table) {
            $table->id();
               $table->foreignId('year_id')->constrained()->cascadeOnDelete();
               $table->foreignId('specialization_id')->constrained()->cascadeOnDelete();
               $table->unique(['specialization_id', 'year_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialization_year');
    }
};
