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
        Schema::create('territory_organizational_unit_pivot', function (Blueprint $table) {
            $table->foreignId('territory_id')->constrained('territories', 'id', 'terr_org_unit_terr_id_fk')->onDelete('cascade');
            $table->foreignId('organizational_unit_id')->constrained('organizational_units', 'id', 'terr_org_unit_id_fk')->onDelete('cascade');
            $table->primary(['territory_id', 'organizational_unit_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('territory_organizational_unit_pivot');
    }
};
