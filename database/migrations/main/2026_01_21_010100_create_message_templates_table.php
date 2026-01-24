<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        Schema::connection($this->connection)->create('message_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // booking, payment, reminder, general
            $table->string('type'); // email, sms, in-app
            $table->string('subject')->nullable(); // for email
            $table->text('body');
            $table->json('variables')->nullable(); // available placeholders
            $table->boolean('is_active')->default(true);
            $table->integer('version')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            
            $table->index(['category', 'type']);
        });

        // Insert default templates
        DB::connection('mysql')->table('message_templates')->insert([
            [
                'name' => 'Booking Confirmed',
                'category' => 'booking',
                'type' => 'email',
                'subject' => 'Booking Confirmation - {{facility_name}}',
                'body' => 'Dear {{citizen_name}},<br><br>Your booking has been confirmed!<br><br>Booking ID: {{booking_id}}<br>Facility: {{facility_name}}<br>Date: {{booking_date}}<br>Time: {{booking_time}}<br><br>Thank you for using our facility reservation system.',
                'variables' => json_encode(['citizen_name', 'booking_id', 'facility_name', 'booking_date', 'booking_time']),
                'is_active' => true,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Booking Confirmed',
                'category' => 'booking',
                'type' => 'sms',
                'subject' => null,
                'body' => 'Hi {{citizen_name}}! Your booking at {{facility_name}} on {{booking_date}} is confirmed. Booking ID: {{booking_id}}',
                'variables' => json_encode(['citizen_name', 'booking_id', 'facility_name', 'booking_date']),
                'is_active' => true,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Payment Received',
                'category' => 'payment',
                'type' => 'email',
                'subject' => 'Payment Receipt - {{booking_id}}',
                'body' => 'Dear {{citizen_name}},<br><br>We have received your payment of ₱{{amount}} for booking {{booking_id}}.<br><br>Transaction ID: {{transaction_id}}<br>Payment Date: {{payment_date}}<br><br>Thank you!',
                'variables' => json_encode(['citizen_name', 'booking_id', 'amount', 'transaction_id', 'payment_date']),
                'is_active' => true,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Booking Reminder',
                'category' => 'reminder',
                'type' => 'sms',
                'subject' => null,
                'body' => 'Reminder: Your booking at {{facility_name}} is tomorrow at {{booking_time}}. See you there!',
                'variables' => json_encode(['facility_name', 'booking_time']),
                'is_active' => true,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('message_templates');
    }
};
