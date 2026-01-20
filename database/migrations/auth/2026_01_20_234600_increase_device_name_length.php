<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'auth_db';

    public function up(): void
    {
        Schema::connection($this->connection)->table('login_history', function (Blueprint $table) {
            $table->string('device_name', 500)->change();
        });
        
        Schema::connection($this->connection)->table('user_sessions', function (Blueprint $table) {
            $table->string('device_name', 500)->change();
        });
        
        Schema::connection($this->connection)->table('trusted_devices', function (Blueprint $table) {
            $table->string('device_name', 500)->change();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('login_history', function (Blueprint $table) {
            $table->string('device_name', 100)->change();
        });
        
        Schema::connection($this->connection)->table('user_sessions', function (Blueprint $table) {
            $table->string('device_name', 100)->change();
        });
        
        Schema::connection($this->connection)->table('trusted_devices', function (Blueprint $table) {
            $table->string('device_name', 100)->change();
        });
    }
};
