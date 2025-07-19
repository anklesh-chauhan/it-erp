<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws \Exception
     */
    public function up(): void
    {
        try {
            Schema::create('account_settings', function (Blueprint $table) {
                $table->id();
                $table->string('category', 50)->index()->comment('Setting category (e.g., discount, tax, quotation, sales_order, invoice)');
                $table->string('name', 100)->index()->comment('Human-readable name for the setting');
                $table->string('key', 100)->comment('Unique key for programmatic access');
                $table->unique(['category', 'key'])->comment('Ensure key is unique within a category');
                $table->json('value')->nullable()->comment('JSON value for flexible data storage');
                $table->enum('type', ['string', 'integer', 'float', 'boolean', 'json', 'enum', 'array'])
                    ->default('string')
                    ->comment('Data type of the setting value');
                $table->text('description')->nullable()->comment('Detailed description of the setting');
                $table->boolean('is_active')->default(true)->index()->comment('Active status of the setting');
                $table->string('group', 50)->nullable()->index()->comment('Setting group for organization');
                $table->unsignedSmallInteger('priority')->default(0)->comment('Priority order for processing');
                $table->json('metadata')->nullable()->comment('Additional metadata for the setting, e.g., enum options');
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
                $table->softDeletes();
                $table->comment('Stores account configuration settings for discounts, taxes, quotations, sales orders, invoices, etc.');
            });

            DB::statement('ALTER TABLE account_settings COMMENT = "Stores account configuration settings with versioning and audit trails for discounts, taxes, quotations, sales orders, and invoices"');
        } catch (\Exception $e) {
            Log::error('Migration failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws \Exception
     */
    public function down(): void
    {
        try {
            Schema::table('account_settings', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
            });

            Schema::dropIfExists('account_settings');
        } catch (\Exception $e) {
            Log::error('Migration rollback failed: ' . $e->getMessage());
            throw $e;
        }
    }
};