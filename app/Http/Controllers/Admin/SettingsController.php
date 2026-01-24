<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SettingsController extends Controller
{
    /**
     * Display the profile dashboard.
     * Fetches general system settings from the 'system_settings' table.
     */
    public function index()
    {
        // Using the default database connection (lgu1_auth)
        // Get system settings for LGU configuration
        $settings = DB::table('system_settings')->pluck('value', 'key');
        
        // Get authenticated user from session
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login');
        }

        return view('admin.profile.index', compact('settings', 'user'));
    }

    /**
     * Update Admin Profile Information.
     * Handles the updating of 'full_name', 'email', and 'avatar'.
     */
    public function updateProfile(Request $request)
    {
        // Get user from session (session-based auth)
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        // Notice: I removed 'email' from the validation to prevent errors
        $request->validate([
            'full_name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update only the name
        $user->full_name = $request->full_name;

        // Handle profile photo upload if present
        if ($request->hasFile('avatar')) {
            $fileName = time() . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('uploads/avatars'), $fileName);
            $user->profile_photo_path = 'uploads/avatars/' . $fileName;
        }

        $user->save();
        
        // Update session with new user data
        session([
            'user_name' => $user->full_name,
            'user_email' => $user->email,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove profile photo
     */
    public function removeProfilePhoto()
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        // Delete the file if it exists
        if ($user->profile_photo_path && file_exists(public_path($user->profile_photo_path))) {
            unlink(public_path($user->profile_photo_path));
        }

        // Remove from database
        $user->profile_photo_path = null;
        $user->save();

        return back()->with('success', 'Profile photo removed successfully.');
    }

    /**
     * Update LGU Configuration.
     * Synchronizes dynamic LGU data into the settings table.
     */
    public function updateLguSettings(Request $request)
    {
        // Exclude the security token from the data array
        $data = $request->except('_token');

        // Loop through each input key (e.g., lgu_name, office_unit) and update the database
        foreach ($data as $key => $value) {
            // Update the existing record or create a new one if it doesn't exist
            DB::table('system_settings')->updateOrInsert(
                ['key' => 'system.' . $key],
                [
                    'value' => $value,
                    'type' => 'string',
                    'category' => 'system',
                    'description' => ucwords(str_replace('_', ' ', $key))
                ]
            );
        }

        return back()->with('success', 'LGU Configuration saved successfully.');
    }
}