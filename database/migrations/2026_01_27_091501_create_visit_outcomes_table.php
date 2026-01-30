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
        Schema::create('visit_outcomes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->string('outcome_type');
            $table->text('notes')->nullable();
            $table->blameable();
            $table->blameableSoftDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_outcomes');
    }
};
