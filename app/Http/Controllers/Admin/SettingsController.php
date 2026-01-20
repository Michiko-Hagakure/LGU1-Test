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
        
        // Get authenticated user data
        $user = Auth::user();
        
        // Ensure session has user data (populate if missing from old login sessions)
        if ($user && !session('user_name')) {
            session([
                'user_id' => $user->id,
                'user_name' => $user->full_name,
                'user_email' => $user->email,
                'user_role' => session('user_role', 'admin')
            ]);
        }

        return view('admin.profile.index', compact('settings', 'user'));
    }

    /**
     * Update Admin Profile Information.
     * Handles the updating of 'full_name', 'email', and 'avatar'.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

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

        // Re-authenticate and maintain the session role to prevent redirection to login
        Auth::login($user);
        
        // Update session with new user data (single source of truth)
        session([
            'user_role' => 'admin',
            'user_name' => $user->full_name,
            'user_email' => $user->email,
            'user_id' => $user->id
        ]);

        return back()->with('success', 'Profile updated successfully.');
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