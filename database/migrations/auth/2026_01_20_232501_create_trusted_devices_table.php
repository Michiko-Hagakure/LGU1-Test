<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'auth_db';

    public function up(): void
    {
        Schema::connection($this->connection)->create('trusted_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('device_fingerprint', 255)->index();
            $table->string('device_name', 100);
            $table->string('ip_address', 45);
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->timestamp('trusted_at');
            $table->timestamp('last_used_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'device_fingerprint']);
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('trusted_devices');
    }
};
