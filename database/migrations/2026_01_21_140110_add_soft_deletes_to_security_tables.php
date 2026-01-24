<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'auth_db';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add soft deletes to trusted_devices
        Schema::connection($this->connection)->table('trusted_devices', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // Add soft deletes to user_sessions
        Schema::connection($this->connection)->table('user_sessions', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->table('trusted_devices', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('deleted_by');
        });

        Schema::connection($this->connection)->table('user_sessions', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('deleted_by');
        });
    }
};
