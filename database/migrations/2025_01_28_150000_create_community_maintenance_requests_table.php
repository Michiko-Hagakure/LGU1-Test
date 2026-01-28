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
        Schema::connection('facilities_db')->create('community_maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_report_id')->nullable();
            $table->unsignedBigInteger('facility_id');
            $table->string('facility_name');
            $table->string('resident_name');
            $table->string('contact_info');
            $table->string('subject', 500);
            $table->text('description');
            $table->string('unit_number')->nullable();
            $table->enum('report_type', ['maintenance', 'complaint', 'suggestion', 'emergency'])->default('maintenance');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['submitted', 'reviewed', 'in_progress', 'resolved', 'closed'])->default('submitted');
            $table->unsignedBigInteger('submitted_by_user_id')->nullable();
            $table->timestamps();

            $table->index('external_report_id');
            $table->index('facility_id');
            $table->index('resident_name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('facilities_db')->dropIfExists('community_maintenance_requests');
    }
};
