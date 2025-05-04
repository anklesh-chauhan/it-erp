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
        Schema::create('sales_dcrs', function (Blueprint $table) {
            $table->id();$table->date('date');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->json('jointwork_user_ids')->nullable(); // For multiple joint work users
            $table->foreignId('visit_type_id')->nullable()->constrained('visit_types')->onDelete('set null')->nullable();
            $table->foreignId('tour_plan_id')->nullable()->constrained('tour_plans')->onDelete('set null')->nullable();
            $table->json('visit_route_ids')->nullable(); // For multiple visit routes
            $table->nullableMorphs('category'); // Polymorphic relation for flexible category assignment
            $table->decimal('expense_total', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_dcrs');
    }
};
