<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds notification preferences for favorite facilities.
     */
    public function up(): void
    {
        Schema::table('user_favorites', function (Blueprint $table) {
            $table->boolean('notify_updates')->default(true)->after('facility_id');
            $table->boolean('notify_availability')->default(true)->after('notify_updates');
            $table->boolean('notify_price_changes')->default(false)->after('notify_availability');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_favorites', function (Blueprint $table) {
            $table->dropColumn(['notify_updates', 'notify_availability', 'notify_price_changes']);
        });
    }
};
