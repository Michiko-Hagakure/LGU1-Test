<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'auth_db';

    public function up(): void
    {
        Schema::connection($this->connection)->create('login_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('device_name', 100);
            $table->string('ip_address', 45);
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->string('failure_reason', 255)->nullable();
            $table->boolean('required_2fa')->default(false);
            $table->timestamp('attempted_at');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('status');
            $table->index('attempted_at');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('login_history');
    }
};
