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
        Schema::connection('auth_db')->create('backup_downloads', function (Blueprint $table) {
            $table->id();
            $table->string('backup_file');
            $table->string('otp_hash');
            $table->unsignedBigInteger('requested_by');
            $table->timestamp('otp_expires_at');
            $table->boolean('downloaded')->default(false);
            $table->timestamp('downloaded_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->index('backup_file');
            $table->index('otp_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('auth_db')->dropIfExists('backup_downloads');
    }
};
