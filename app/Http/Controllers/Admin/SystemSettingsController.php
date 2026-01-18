<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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
}
