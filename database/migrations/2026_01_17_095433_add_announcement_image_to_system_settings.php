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
        Schema::connection('auth_db')->table('system_settings', function (Blueprint $table) {
            $table->string('announcement_image')->nullable()->after('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('auth_db')->table('system_settings', function (Blueprint $table) {
            $table->dropColumn('announcement_image');
        });
    }
};
