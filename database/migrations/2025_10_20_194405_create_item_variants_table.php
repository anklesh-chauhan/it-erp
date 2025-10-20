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
        Schema::create('item_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_master_id')->constrained()->cascadeOnDelete();
            $table->string('variant_name');
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->decimal('selling_price', 15, 2)->nullable();
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->decimal('discount', 5, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->foreignId('unit_of_measurement_id')->nullable()->constrained();
            $table->foreignId('packaging_type_id')->nullable()->constrained();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('item_masters', function (Blueprint $table) {
            $table->boolean('has_variants')->default(false)->after('item_name');
            $table->string('sku')->unique()->nullable()->after('item_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_variants');
        Schema::table('item_masters', function (Blueprint $table) {
            $table->dropColumn('has_variants');
            $table->dropColumn('sku');
        });
    }
};
