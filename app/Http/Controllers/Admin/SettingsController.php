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
     * Display the settings dashboard.
     * Fetches general system settings from the 'settings' table.
     */
    public function index()
    {
        // Using the default database connection (lgu1_auth)
        $settings = DB::table('settings')->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
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
        session(['user_role' => 'admin']);

        return back()->with('success', 'Profile updated (Email is locked).');
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
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'LGU Configuration saved successfully.');
    }
}