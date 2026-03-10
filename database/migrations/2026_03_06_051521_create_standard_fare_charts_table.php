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
        Schema::create('standard_fare_charts', function (Blueprint $table) {
            $table->id();

             /*
            |--------------------------------------------------------------------------
            | Route Information
            |--------------------------------------------------------------------------
            */
            $table->foreignId('from_city_id')
                ->constrained('cities')
                ->cascadeOnDelete();

            $table->foreignId('to_city_id')
                ->constrained('cities')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Transport Mode
            |--------------------------------------------------------------------------
            */
            $table->foreignId('transport_mode_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Distance / Fare
            |--------------------------------------------------------------------------
            */
            $table->decimal('distance_km', 8, 2)->nullable();

            $table->decimal('fare_amount', 10, 2)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Optional Territory Override
            |--------------------------------------------------------------------------
            */
            $table->foreignId('territory_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_active')->default(true);

            /*
            |--------------------------------------------------------------------------
            | System Fields
            |--------------------------------------------------------------------------
            */
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
        Schema::dropIfExists('standard_fare_charts');
    }
};
