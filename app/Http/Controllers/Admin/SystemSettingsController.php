<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $categories = [
            'booking' => 'Booking Rules',
            'payment' => 'Payment',
            'discount' => 'Discounts',
            'security' => 'Security',
            'notification' => 'Notifications',
            'communication' => 'Email & SMS',
            'system' => 'System',
        ];

        $settingsByCategory = [];
        foreach ($categories as $key => $label) {
            $settingsByCategory[$key] = [
                'label' => $label,
                'settings' => SystemSetting::where('category', $key)->orderBy('key')->get(),
            ];
        }

        return view('admin.settings.index', compact('settingsByCategory'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'announcement_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Validation failed: ' . $validator->errors()->first())
                ->withInput();
        }

        try {
            $updated = 0;
            
            // Handle announcement image removal
            if ($request->has('remove_announcement_image')) {
                $announcementSetting = SystemSetting::where('key', 'system.announcement')->first();
                if ($announcementSetting && $announcementSetting->announcement_image) {
                    // Delete the image file
                    if (file_exists(public_path($announcementSetting->announcement_image))) {
                        unlink(public_path($announcementSetting->announcement_image));
                    }
                    // Clear the image path from database
                    $announcementSetting->update(['announcement_image' => null]);
                }
            }
            // Handle announcement image upload
            elseif ($request->hasFile('announcement_image')) {
                $image = $request->file('announcement_image');
                $imageName = 'announcement_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/announcements'), $imageName);
                $imagePath = 'uploads/announcements/' . $imageName;
                
                // Update or create the announcement image setting
                $announcementSetting = SystemSetting::where('key', 'system.announcement')->first();
                if ($announcementSetting) {
                    // Delete old image if exists
                    if ($announcementSetting->announcement_image && file_exists(public_path($announcementSetting->announcement_image))) {
                        unlink(public_path($announcementSetting->announcement_image));
                    }
                    $announcementSetting->update(['announcement_image' => $imagePath]);
                }
            }
            
            // Log incoming data for debugging
            \Log::info('Settings update request received', [
                'settings_count' => count($request->input('settings', [])),
                'settings_data' => $request->input('settings'),
            ]);
            
            // Get all boolean settings to handle unchecked checkboxes
            $booleanSettings = SystemSetting::where('type', 'boolean')->pluck('key')->toArray();
            
            // First, set all boolean settings to false
            foreach ($booleanSettings as $boolKey) {
                if (!isset($request->input('settings')[$boolKey])) {
                    SystemSetting::where('key', $boolKey)->update(['value' => 'false']);
                }
            }
            
            // Then update all submitted settings
            foreach ($request->input('settings') as $key => $value) {
                $setting = SystemSetting::where('key', $key)->first();
                
                if ($setting) {
                    // Convert value based on type
                    $valueToStore = $value;
                    if ($setting->type === 'boolean') {
                        $valueToStore = $value ? 'true' : 'false';
                    } elseif ($setting->type === 'array' || $setting->type === 'json') {
                        $valueToStore = is_array($value) ? json_encode($value) : $value;
                    }

                    \Log::info("Updating setting: {$key}", [
                        'old_value' => $setting->value,
                        'new_value' => $valueToStore,
                        'type' => $setting->type,
                    ]);

                    $setting->update(['value' => $valueToStore]);
                    $updated++;
                }
            }

            // Clear cache after updates
            SystemSetting::clearCache();

            return redirect()->route('admin.settings.index')
                ->with('success', "Settings updated successfully! ({$updated} settings changed)");

        } catch (\Exception $e) {
            \Log::error('Settings update failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update settings: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateLguSettings(Request $request)
    {
        try {
            $data = $request->except('_token');
            
            foreach ($data as $key => $value) {
                SystemSetting::updateOrCreate(
                    ['key' => 'system.' . $key],
                    [
                        'value' => $value,
                        'type' => 'string',
                        'category' => 'system',
                        'description' => ucwords(str_replace('_', ' ', $key))
                    ]
                );
            }
            
            SystemSetting::clearCache();
            
            return redirect()->route('admin.settings.index')
                ->with('success', 'LGU Configuration saved successfully!');
        } catch (\Exception $e) {
            \Log::error('LGU settings update failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update LGU settings: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function clearCache()
    {
        try {
            SystemSetting::clearCache();
            
            return redirect()->route('admin.settings.index')
                ->with('success', 'Settings cache cleared successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    public function updateCommunicationSettings(Request $request)
    {
        $request->validate([
            'email_smtp_host' => 'nullable|string|max:255',
            'email_smtp_port' => 'nullable|integer',
            'email_smtp_username' => 'nullable|string|max:255',
            'email_smtp_password' => 'nullable|string|max:255',
            'email_smtp_encryption' => 'nullable|in:tls,ssl',
            'email_from_address' => 'nullable|email|max:255',
            'email_from_name' => 'nullable|string|max:255',
            'email_signature' => 'nullable|string',
            'sms_provider' => 'nullable|in:semaphore,twilio,vonage',
            'sms_api_key' => 'nullable|string|max:255',
            'sms_sender_name' => 'nullable|string|max:11',
        ]);

        try {
            $settings = [
                'email_smtp_host' => $request->email_smtp_host,
                'email_smtp_port' => $request->email_smtp_port,
                'email_smtp_username' => $request->email_smtp_username,
                'email_smtp_encryption' => $request->email_smtp_encryption,
                'email_from_address' => $request->email_from_address,
                'email_from_name' => $request->email_from_name,
                'email_signature' => $request->email_signature,
                'email_enabled' => $request->has('email_enabled') ? '1' : '0',
                'sms_provider' => $request->sms_provider,
                'sms_sender_name' => $request->sms_sender_name,
                'sms_enabled' => $request->has('sms_enabled') ? '1' : '0',
            ];

            // Handle encrypted fields
            if ($request->filled('email_smtp_password')) {
                $settings['email_smtp_password'] = $request->email_smtp_password;
            }
            if ($request->filled('sms_api_key')) {
                $settings['sms_api_key'] = $request->sms_api_key;
            }

            foreach ($settings as $key => $value) {
                DB::connection('auth_db')->table('system_settings')
                    ->where('key', $key)
                    ->update(['value' => $value, 'updated_at' => now()]);
            }

            SystemSetting::clearCache();

            return back()->with('success', 'Communication settings updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Communication settings update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            $testEmail = $request->test_email;
            
            Mail::raw('This is a test email from your LGU Facility Reservation System.', function ($message) use ($testEmail) {
                $message->to($testEmail)
                    ->subject('Test Email - LGU System');
            });

            return back()->with('success', 'Test email sent successfully to ' . $testEmail);
        } catch (\Exception $e) {
            \Log::error('Test email failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    public function testSms(Request $request)
    {
        $request->validate([
            'test_phone' => 'required|string|max:15',
        ]);

        try {
            $testPhone = $request->test_phone;
            $provider = DB::connection('auth_db')->table('system_settings')
                ->where('key', 'sms_provider')->value('value');
            $apiKey = DB::connection('auth_db')->table('system_settings')
                ->where('key', 'sms_api_key')->value('value');
            $senderName = DB::connection('auth_db')->table('system_settings')
                ->where('key', 'sms_sender_name')->value('value');

            if (empty($apiKey)) {
                return back()->with('error', 'SMS API key not configured.');
            }

            $message = 'This is a test SMS from your LGU Facility Reservation System.';

            if ($provider === 'semaphore') {
                $this->sendSemaphoreSms($testPhone, $message, $apiKey, $senderName);
            } else {
                return back()->with('error', 'SMS provider not supported yet: ' . $provider);
            }

            return back()->with('success', 'Test SMS sent successfully to ' . $testPhone);
        } catch (\Exception $e) {
            \Log::error('Test SMS failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send test SMS: ' . $e->getMessage());
        }
    }

    private function sendSemaphoreSms($phone, $message, $apiKey, $senderName)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.semaphore.co/api/v4/messages');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'apikey' => $apiKey,
            'number' => $phone,
            'message' => $message,
            'sendername' => $senderName,
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        if (isset($result['error'])) {
            throw new \Exception($result['message'] ?? 'SMS sending failed');
        }
    }
}
