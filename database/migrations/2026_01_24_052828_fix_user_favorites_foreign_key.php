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
        Schema::table('user_favorites', function (Blueprint $table) {
            // Drop the foreign key constraint - facilities are in a different database
            $table->dropForeign(['facility_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_favorites', function (Blueprint $table) {
            // Re-add the foreign key constraint
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
        });
    }
};
