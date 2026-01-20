<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'auth_db';

    public function up(): void
    {
        Schema::connection($this->connection)->create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('session_id', 255)->unique()->index();
            $table->string('device_name', 100);
            $table->string('ip_address', 45);
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->timestamp('logged_in_at');
            $table->timestamp('last_active_at');
            $table->timestamp('expires_at');
            $table->boolean('is_current')->default(false);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('user_sessions');
    }
};
