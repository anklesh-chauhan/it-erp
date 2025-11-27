<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('approval_settings', function (Blueprint $table) {
            $table->id();
            $table->json('enabled_modules')->nullable();
            $table->timestamps();
        });

        DB::table('approval_settings')->insert([
                'enabled_modules' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
                ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_settings');
    }
};
