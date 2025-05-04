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
        Schema::create('visit_route_tour_plan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_route_id')->constrained('visit_routes')->onDelete('cascade');
            $table->foreignId('tour_plan_id')->constrained('tour_plans')->onDelete('cascade');
            $table->integer('visit_order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_route_tour_plan');
    }
};
