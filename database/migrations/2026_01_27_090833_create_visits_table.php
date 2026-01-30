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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            $table->string('document_number')->unique();

            $table->foreignId('employee_id')->constrained('users');
            $table->foreignId('reporting_manager_id')->nullable()->constrained('users');

            $table->foreignId('territory_id')->constrained();
            $table->foreignId('patch_id')->constrained();

            $table->foreignId('sales_tour_plan_id')->nullable()->constrained('sales_tour_plans');

            $table->foreignId('sales_tour_plan_detail_id')->nullable()->constrained('sales_tour_plan_details');

            $table->date('visit_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->enum('visit_type', ['planned', 'unplanned'])->default('planned');
            $table->enum('visit_status', ['draft', 'started', 'completed', 'cancelled'])->default('draft');

            $table->enum('approval_status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();

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
        Schema::dropIfExists('visits');
    }
};
