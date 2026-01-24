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
        Schema::table('facilities', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('address');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->text('full_address')->nullable()->after('longitude');
            $table->string('city')->nullable()->after('full_address');
            $table->integer('view_count')->default(0)->after('city');
            $table->decimal('rating', 3, 2)->nullable()->after('view_count');
            
            $table->index(['latitude', 'longitude']);
            $table->index('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropIndex(['latitude', 'longitude']);
            $table->dropIndex(['city']);
            $table->dropColumn(['latitude', 'longitude', 'full_address', 'city', 'view_count', 'rating']);
        });
    }
};
