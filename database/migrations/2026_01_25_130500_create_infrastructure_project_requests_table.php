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
        Schema::connection('facilities_db')->create('infrastructure_project_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_project_id')->nullable()->comment('Project ID from Infrastructure PM system');
            $table->string('requesting_office');
            $table->string('contact_person');
            $table->string('position')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('project_title');
            $table->string('project_category');
            $table->string('project_location')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('problem_identified');
            $table->string('scope_item1')->nullable();
            $table->string('scope_item2')->nullable();
            $table->string('scope_item3')->nullable();
            $table->decimal('estimated_budget', 15, 2)->nullable();
            $table->enum('priority_level', ['low', 'medium', 'high'])->default('medium');
            $table->date('requested_start_date')->nullable();
            $table->string('prepared_by')->nullable();
            $table->string('prepared_position')->nullable();
            $table->enum('status', [
                'draft',
                'submitted',
                'received',
                'under_review',
                'approved',
                'rejected',
                'in_progress',
                'completed'
            ])->default('submitted');
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('submitted_by_user_id')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('external_project_id');
            $table->index('status');
            $table->index('submitted_by_user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('facilities_db')->dropIfExists('infrastructure_project_requests');
    }
};
