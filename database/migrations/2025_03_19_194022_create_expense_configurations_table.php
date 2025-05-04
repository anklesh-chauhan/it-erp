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
        Schema::create('expense_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('expense_type_id')->constrained('expense_types')->onDelete('cascade');
            $table->foreignId('transport_mode_id')->nullable()->constrained('transport_modes')->onDelete('set null');
            $table->decimal('rate_per_km', 8, 2)->nullable(); // For travel-based expenses
            $table->decimal('fixed_expense', 10, 2)->nullable(); // For fixed expenses like meals, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_configurations');
    }
};
