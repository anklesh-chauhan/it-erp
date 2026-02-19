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
        Schema::create('sales_dcrs', function (Blueprint $table) {
            $table->id();
            $table->date('dcr_date');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sales_tour_plan_id')->nullable()->nullOnDelete();

            $table->enum('status', [
                'draft',
                'submitted',
                'approved',
                'rejected',
            ])->default('draft');

            $table->decimal('total_expense', 10, 2)->default(0);

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->blameable();
            $table->blameableSoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_dcrs');
    }
};
