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
        Schema::create('sales_dcr_visits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sales_dcr_id')->constrained()->cascadeOnDelete();

            $table->morphs('visitable');
            // Customer / Lead / Doctor / Retailer / Distributor

            $table->foreignId('visit_type_id')->nullable();
            $table->foreignId('visit_purpose_id')->nullable();
            $table->foreignId('visit_outcome_id')->nullable();

            $table->time('check_in_at')->nullable();
            $table->time('check_out_at')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->boolean('is_joint_work')->default(false);

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
        Schema::dropIfExists('sales_dcr_visits');
    }
};
