<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        Schema::connection($this->connection)->create('notification_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // email, sms, in-app
            $table->json('recipients'); // user IDs or email/phone numbers
            $table->string('subject')->nullable();
            $table->text('message');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('status')->default('pending'); // pending, sending, sent, failed
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('sent_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->json('delivery_details')->nullable();
            $table->text('error_message')->nullable();
            $table->unsignedBigInteger('sent_by');
            $table->timestamps();
            
            $table->index(['status', 'scheduled_at']);
            $table->index('sent_by');
        });

        Schema::connection($this->connection)->create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->string('recipient'); // email or phone
            $table->string('type'); // email, sms
            $table->string('status'); // sent, failed, bounced
            $table->text('response')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->foreign('campaign_id')->references('id')->on('notification_campaigns')->onDelete('cascade');
            $table->index(['campaign_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('notification_logs');
        Schema::connection($this->connection)->dropIfExists('notification_campaigns');
    }
};
