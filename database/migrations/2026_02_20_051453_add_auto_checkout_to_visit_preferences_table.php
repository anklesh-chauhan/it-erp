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
        Schema::table('visit_preferences', function (Blueprint $table) {
            $table->boolean('enable_auto_checkout')->default(false)->after('allow_manual_time_edit');
            $table->time('auto_checkout_time')->nullable()->after('enable_auto_checkout');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visit_preferences', function (Blueprint $table) {
            $table->dropColumn(['enable_auto_checkout', 'auto_checkout_time']);
        });
    }
};
