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
        Schema::create('tour_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Salesperson/Field Staff
            $table->date('plan_date');
            $table->string('location'); // Specific location in Ahmedabad or surrounding areas
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('visit_purpose_id')->nullable()->constrained()->onDelete('set null');
            $table->string('target_customer')->nullable(); // Optional: Target customer name
            $table->text('notes')->nullable();
            $table->string('mode_of_transport')->nullable(); // Two-wheeler, Car, Public transport
            $table->decimal('distance_travelled', 8, 2)->nullable(); // Distance travelled in kilometers
            $table->decimal('travel_expenses', 8, 2)->nullable(); // Travel expenses (fuel, tolls, etc.)
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_plans');
    }
};
