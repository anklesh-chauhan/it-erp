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
        Schema::create('sales_daily_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique(); // Sr. No.
            $table->date('expense_date'); // Expense Date
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User
            $table->date('transaction_date'); // Transaction Date
            $table->nullableMorphs('category'); // Expense Category (Polymorphic)
            $table->foreignId('expense_type_id')->nullable()->constrained('expense_types')->onDelete('set null');
            $table->foreignId('tour_plan_id')->nullable()->constrained('tour_plans')->onDelete('set null');
            $table->decimal('rate_amount', 10, 2)->nullable(); // Rate Amount
            $table->decimal('claim_amount', 10, 2)->nullable(); // Claim Amount
            $table->decimal('approved_amount', 10, 2)->nullable(); // Approved Amount
            $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('set null'); // Approver Name (user_id)
            $table->text('remarks')->nullable(); // Remarks
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_daily_expenses');
    }
};
