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
        Schema::create('patches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->foreignId('territory_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_pin_code_id')->nullable()->constrained('city_pin_codes')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('color')->nullable(); // For UI representation, e.g., hex color code
            $table->string('created_by')->nullable(); // User ID or name of the creator
            $table->string('updated_by')->nullable(); // User ID or name of the updater
            $table->string('deleted_by')->nullable(); // User ID or name of the
            $table->softDeletes(); // For soft delete functionality
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patches');
    }
};
