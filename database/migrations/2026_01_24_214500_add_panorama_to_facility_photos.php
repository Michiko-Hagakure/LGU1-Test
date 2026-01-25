<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds panorama support to facility photos for 360° virtual tours.
     */
    public function up(): void
    {
        Schema::connection('facilities_db')->table('facility_photos', function (Blueprint $table) {
            $table->boolean('is_panorama')->default(false)->after('is_primary');
            $table->string('panorama_type')->nullable()->after('is_panorama'); // equirectangular, cubemap
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('facilities_db')->table('facility_photos', function (Blueprint $table) {
            $table->dropColumn(['is_panorama', 'panorama_type']);
        });
    }
};
