<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\AccountMasterCreditType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account_master_credit_details', function (Blueprint $table) {
            $table->id();
            $table->enum('credit_type', AccountMasterCreditType::values())->nullable();
            $table->integer('credit_days')->nullable(); // Number of credit days
            $table->decimal('credit_limit', 15, 2)->nullable(); // Maximum allowed credit limit
            $table->string('credit_status')->default('active'); // e.g., active, hold, suspended
            $table->date('credit_review_date')->nullable(); // Last review/update
            $table->text('credit_terms')->nullable(); // Notes or terms for credit
            $table->text('remark')->nullable(); // Internal notes
            $table->foreignId('account_master_id')->constrained()->cascadeOnDelete(); // Foreign key to account master

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_master_credit_details');
    }
};
