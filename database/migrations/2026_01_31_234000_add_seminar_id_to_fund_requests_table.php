<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add seminar_id to link fund requests back to Energy Efficiency seminars
     */
    public function up(): void
    {
        Schema::connection('auth_db')->table('fund_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('seminar_id')->nullable()->after('seminar_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('auth_db')->table('fund_requests', function (Blueprint $table) {
            $table->dropColumn('seminar_id');
        });
    }
};
