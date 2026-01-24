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
        Schema::create('user_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('facilities')->onDelete('cascade');
            $table->timestamp('favorited_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['user_id', 'facility_id']);
            $table->index('user_id');
            $table->index('facility_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_favorites');
    }
};
