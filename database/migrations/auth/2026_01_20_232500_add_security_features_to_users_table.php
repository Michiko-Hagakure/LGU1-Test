<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'auth_db';

    public function up(): void
    {
        Schema::connection($this->connection)->table('users', function (Blueprint $table) {
            // Two-Factor Authentication
            $table->string('two_factor_pin', 255)->nullable()->after('password_hash');
            $table->boolean('two_factor_enabled')->default(false)->after('two_factor_pin');
            
            // Privacy Settings
            $table->enum('profile_visibility', ['public', 'private'])->default('private')->after('two_factor_enabled');
            $table->boolean('show_reviews_publicly')->default(true)->after('profile_visibility');
            $table->boolean('show_booking_count')->default(false)->after('show_reviews_publicly');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_pin',
                'two_factor_enabled',
                'profile_visibility',
                'show_reviews_publicly',
                'show_booking_count'
            ]);
        });
    }
};
