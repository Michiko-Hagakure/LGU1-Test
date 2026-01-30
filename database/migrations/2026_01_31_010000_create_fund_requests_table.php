<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table for Energy Efficiency and Conservation Management fund requests
     */
    public function up(): void
    {
        Schema::connection('facilities_db')->create('fund_requests', function (Blueprint $table) {
            $table->id();
            $table->string('requester_name');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->text('purpose');
            $table->text('logistics')->nullable();
            $table->string('status')->default('pending'); // pending, Approved, Rejected
            $table->text('feedback')->nullable();
            $table->string('seminar_info')->nullable();
            $table->string('seminar_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('facilities_db')->dropIfExists('fund_requests');
    }
};
