<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'auth_db';

    public function up(): void
    {
        // Insert email and SMS settings into existing system_settings table
        $settings = [
            // Email settings
            ['category' => 'communication', 'key' => 'email_smtp_host', 'value' => '', 'type' => 'string', 'description' => 'SMTP server host', 'group' => 'email', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'communication', 'key' => 'email_smtp_port', 'value' => '587', 'type' => 'string', 'description' => 'SMTP server port', 'group' => 'email', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'communication', 'key' => 'email_smtp_username', 'value' => '', 'type' => 'string', 'description' => 'SMTP username', 'group' => 'email', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'communication', 'key' => 'email_smtp_password', 'value' => '', 'type' => 'encrypted', 'description' => 'SMTP password (encrypted)', 'group' => 'email', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'communication', 'key' => 'email_smtp_encryption', 'value' => 'tls', 'type' => 'string', 'description' => 'SMTP encryption (tls/ssl)', 'group' => 'email', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'communication', 'key' => 'email_from_address', 'value' => 'noreply@lgu.gov.ph', 'type' => 'string', 'description' => 'Default from email address', 'group' => 'email', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'communication', 'key' => 'email_from_name', 'value' => 'LGU Facility Reservation System', 'type' => 'string', 'description' => 'Default from name', 'group' => 'email', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'communication', 'key' => 'email_signature', 'value' => '<p>Best regards,<br>LGU Facility Reservation Team</p>', 'type' => 'html', 'description' => 'Email signature HTML', 'group' => 'email', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            
            // SMS settings
            ['category' => 'communication', 'key' => 'sms_provider', 'value' => 'semaphore', 'type' => 'string', 'description' => 'SMS gateway provider (semaphore/twilio/vonage)', 'group' => 'sms', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'communication', 'key' => 'sms_api_key', 'value' => '', 'type' => 'encrypted', 'description' => 'SMS API key (encrypted)', 'group' => 'sms', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'communication', 'key' => 'sms_sender_name', 'value' => 'LGU', 'type' => 'string', 'description' => 'SMS sender name', 'group' => 'sms', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'communication', 'key' => 'sms_enabled', 'value' => '0', 'type' => 'boolean', 'description' => 'Enable/disable SMS notifications', 'group' => 'sms', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'communication', 'key' => 'email_enabled', 'value' => '1', 'type' => 'boolean', 'description' => 'Enable/disable email notifications', 'group' => 'email', 'is_public' => false, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($settings as $setting) {
            DB::connection($this->connection)->table('system_settings')->insertOrIgnore($setting);
        }
    }

    public function down(): void
    {
        DB::connection($this->connection)->table('system_settings')
            ->where('category', 'communication')
            ->delete();
    }
};
