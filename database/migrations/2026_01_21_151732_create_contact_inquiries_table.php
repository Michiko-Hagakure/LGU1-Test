<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->enum('category', ['general', 'booking_issue', 'payment_issue', 'technical_issue', 'complaint', 'suggestion', 'other']);
            $table->string('subject');
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->enum('status', ['new', 'open', 'pending', 'resolved', 'closed'])->default('new');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->text('staff_notes')->nullable();
            $table->text('resolution')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            
            // Audit fields
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable();
            
            $table->index(['status', 'priority']);
            $table->index('assigned_to');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('contact_inquiries');
    }
};
