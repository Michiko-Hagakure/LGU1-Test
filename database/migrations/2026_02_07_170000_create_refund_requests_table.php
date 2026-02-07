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
        Schema::connection('facilities_db')->create('refund_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('booking_reference', 20);
            $table->string('applicant_name');
            $table->string('applicant_email')->nullable();
            $table->string('applicant_phone')->nullable();
            $table->string('facility_name')->nullable();

            // Refund amounts
            $table->decimal('original_amount', 10, 2);
            $table->decimal('refund_percentage', 5, 2)->default(100.00);
            $table->decimal('refund_amount', 10, 2);

            // Reason for refund
            $table->enum('refund_type', ['admin_rejected', 'citizen_cancelled'])->default('admin_rejected');
            $table->text('reason')->nullable();

            // Citizen's chosen refund method (filled by citizen)
            $table->enum('refund_method', ['cash', 'gcash', 'maya', 'bank_transfer'])->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();

            // Status tracking
            $table->enum('status', ['pending_method', 'pending_processing', 'processing', 'completed', 'failed'])->default('pending_method');

            // Treasurer processing
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->string('or_number')->nullable();
            $table->text('treasurer_notes')->nullable();

            // Admin who initiated
            $table->unsignedBigInteger('initiated_by')->nullable();

            $table->timestamps();

            $table->index('booking_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('facilities_db')->dropIfExists('refund_requests');
    }
};
