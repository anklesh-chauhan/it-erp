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
        Schema::create('leave_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->foreignId('leave_type_id')->constrained();

            $table->string('entry_type');
            $table->decimal('quantity', 5, 2);

            $table->decimal('balance_before', 5, 2)->nullable();
            $table->decimal('balance_after', 5, 2)->nullable();

            $table->date('effective_date');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('leave_ledger_entries');
    }
};
