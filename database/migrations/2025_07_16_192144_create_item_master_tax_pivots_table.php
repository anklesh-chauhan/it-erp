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
        Schema::create('item_master_tax_pivots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_master_id')->constrained()->onDelete('cascade');
            $table->foreignId('tax_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_master_tax_pivots');
    }
};
