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
        Schema::create('sales_dcr_visit_feedback', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sales_dcr_visit_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('visit_feedback_question_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('answer')->nullable(); // 1–5, yes/no, etc.
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('sales_dcr_visit_feedback');
    }
};
