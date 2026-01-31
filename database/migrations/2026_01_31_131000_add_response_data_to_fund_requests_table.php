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
        Schema::connection('auth_db')->table('fund_requests', function (Blueprint $table) {
            $table->json('response_data')->nullable()->after('feedback');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('auth_db')->table('fund_requests', function (Blueprint $table) {
            $table->dropColumn('response_data');
        });
    }
};
