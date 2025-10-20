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
        Schema::create('customer_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('account_masters')->cascadeOnDelete();
            $table->foreignId('item_master_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('item_variant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->decimal('price', 15, 2)->nullable(); // customer-specific price
            $table->decimal('discount', 5, 2)->default(0); // in percentage
            $table->timestamps();

            $table->unique(['customer_id', 'item_master_id', 'item_variant_id'], 'customer_price_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_prices');
    }
};
