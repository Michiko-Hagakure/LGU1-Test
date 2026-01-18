<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Booking Settings
            [
                'category' => 'booking',
                'key' => 'booking.max_advance_days',
                'value' => '90',
                'type' => 'integer',
                'description' => 'Maximum days in advance a citizen can book a facility',
                'group' => 'Booking Rules',
                'is_public' => true,
            ],
            [
                'category' => 'booking',
                'key' => 'booking.min_advance_hours',
                'value' => '24',
                'type' => 'integer',
                'description' => 'Minimum hours in advance required for booking',
                'group' => 'Booking Rules',
                'is_public' => true,
            ],
            [
                'category' => 'booking',
                'key' => 'booking.cancellation_deadline_hours',
                'value' => '48',
                'type' => 'integer',
                'description' => 'Hours before event that cancellation is allowed',
                'group' => 'Booking Rules',
                'is_public' => true,
            ],
            
            // Payment Settings
            [
                'category' => 'payment',
                'key' => 'payment.deadline_hours',
                'value' => '48',
                'type' => 'integer',
                'description' => 'Hours allowed for payment after booking approval',
                'group' => 'Payment',
                'is_public' => false,
            ],
            
            // Discount Settings
            [
                'category' => 'discount',
                'key' => 'discount.resident_percentage',
                'value' => '30',
                'type' => 'float',
                'description' => 'Discount percentage for city residents',
                'group' => 'Discounts',
                'is_public' => true,
            ],
            [
                'category' => 'discount',
                'key' => 'discount.senior_percentage',
                'value' => '20',
                'type' => 'float',
                'description' => 'Discount percentage for senior citizens',
                'group' => 'Discounts',
                'is_public' => true,
            ],
            [
                'category' => 'discount',
                'key' => 'discount.pwd_percentage',
                'value' => '20',
                'type' => 'float',
                'description' => 'Discount percentage for persons with disabilities',
                'group' => 'Discounts',
                'is_public' => true,
            ],
            [
                'category' => 'discount',
                'key' => 'discount.student_percentage',
                'value' => '20',
                'type' => 'float',
                'description' => 'Discount percentage for students',
                'group' => 'Discounts',
                'is_public' => true,
            ],
            
            // Security Settings
            [
                'category' => 'security',
                'key' => 'security.session_timeout_minutes',
                'value' => '120',
                'type' => 'integer',
                'description' => 'Minutes of inactivity before automatic logout',
                'group' => 'Security',
                'is_public' => false,
            ],
            [
                'category' => 'security',
                'key' => 'security.otp_expiration_minutes',
                'value' => '5',
                'type' => 'integer',
                'description' => 'Minutes before OTP code expires',
                'group' => 'Security',
                'is_public' => false,
            ],
            [
                'category' => 'security',
                'key' => 'security.max_login_attempts',
                'value' => '5',
                'type' => 'integer',
                'description' => 'Maximum failed login attempts before account lockout',
                'group' => 'Security',
                'is_public' => false,
            ],
            
            // Notification Settings
            [
                'category' => 'notification',
                'key' => 'notification.email_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable email notifications',
                'group' => 'Notifications',
                'is_public' => false,
            ],
            [
                'category' => 'notification',
                'key' => 'notification.sms_enabled',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Enable SMS notifications',
                'group' => 'Notifications',
                'is_public' => false,
            ],
            
            // System Settings
            [
                'category' => 'system',
                'key' => 'system.maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Enable maintenance mode (blocks citizen access)',
                'group' => 'System',
                'is_public' => true,
            ],
            [
                'category' => 'system',
                'key' => 'system.maintenance_message',
                'value' => 'System is currently under maintenance. Please check back later.',
                'type' => 'string',
                'description' => 'Message shown during maintenance mode',
                'group' => 'System',
                'is_public' => true,
            ],
            [
                'category' => 'system',
                'key' => 'system.announcement',
                'value' => '',
                'type' => 'string',
                'description' => 'System-wide announcement banner text',
                'group' => 'System',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('System settings seeded successfully.');
    }
}
