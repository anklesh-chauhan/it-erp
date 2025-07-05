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
        Schema::create('visit_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name or identifier for the route (e.g., "East Ahmedabad Route", "Route A")
            $table->foreignId('user_id')->constrained('users'); // Assigned to a specific user (salesperson/field staff)
            $table->date('route_date'); // Date for which the route is planned
            $table->foreignId('lead_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('contact_detail_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('address_id')->nullable()->constrained()->onDelete('set null');
            $table->string('description')->nullable(); // Optional description of the route
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_routes');
    }
};
