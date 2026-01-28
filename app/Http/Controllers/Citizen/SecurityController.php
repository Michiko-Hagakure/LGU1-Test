<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SecurityController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        // Get user data
        $user = DB::connection('auth_db')
            ->table('users')
            ->where('id', $userId)
            ->first();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Get trusted devices
        $trustedDevices = DB::connection('auth_db')
            ->table('trusted_devices')
            ->where('user_id', $user->id)
            ->orderBy('last_used_at', 'desc')
            ->get();
        
        // Get active sessions
        $activeSessions = DB::connection('auth_db')
            ->table('user_sessions')
            ->where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->orderBy('last_active_at', 'desc')
            ->get();
        
        // Get login history (last 20)
        $loginHistory = DB::connection('auth_db')
            ->table('login_history')
            ->where('user_id', $user->id)
            ->orderBy('attempted_at', 'desc')
            ->limit(20)
            ->get();
        
        return view('citizen.security.index', compact(
            'user',
            'trustedDevices',
            'activeSessions',
            'loginHistory'
        ));
    }
    
    public function changePassword(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        $user = DB::connection('auth_db')
            ->table('users')
            ->where('id', $userId)
            ->first();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]/',
            'confirm_password' => 'required|same:new_password',
        ]);
        
        // Verify current password
        if (!Hash::check($request->current_password, $user->password_hash)) {
            return back()->with('error', 'Current password is incorrect.');
        }
        
        // Update password
        DB::connection('auth_db')
            ->table('users')
            ->where('id', $user->id)
            ->update(['password_hash' => Hash::make($request->new_password)]);
        
        return back()->with('success', 'Password changed successfully.');
    }
    
    public function enable2FA(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        $user = DB::connection('auth_db')
            ->table('users')
            ->where('id', $userId)
            ->first();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'pin' => 'required|digits:6',
            'confirm_pin' => 'required|same:pin',
        ]);
        
        // Hash and store PIN
        DB::connection('auth_db')
            ->table('users')
            ->where('id', $user->id)
            ->update([
                'two_factor_pin' => Hash::make($request->pin),
                'two_factor_enabled' => true,
            ]);
        
        return back()->with('success', '2FA enabled successfully. Your account is now more secure.');
    }
    
    public function disable2FA(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        $user = DB::connection('auth_db')
            ->table('users')
            ->where('id', $userId)
            ->first();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'password' => 'required',
        ]);
        
        // Verify password
        if (!Hash::check($request->password, $user->password_hash)) {
            return back()->with('error', 'Incorrect password.');
        }
        
        // Disable 2FA
        DB::connection('auth_db')
            ->table('users')
            ->where('id', $user->id)
            ->update([
                'two_factor_pin' => null,
                'two_factor_enabled' => false,
            ]);
        
        // Soft delete all trusted devices
        DB::connection('auth_db')
            ->table('trusted_devices')
            ->where('user_id', $user->id)
            ->update([
                'deleted_at' => now(),
                'deleted_by' => $user->id
            ]);
        
        return back()->with('success', '2FA disabled. All trusted devices have been removed.');
    }
    
    public function removeTrustedDevice($id)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        DB::connection('auth_db')
            ->table('trusted_devices')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->update([
                'deleted_at' => now(),
                'deleted_by' => $userId
            ]);
        
        return back()->with('success', 'Trusted device removed successfully.');
    }
    
    public function removeAllTrustedDevices()
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        DB::connection('auth_db')
            ->table('trusted_devices')
            ->where('user_id', $userId)
            ->update([
                'deleted_at' => now(),
                'deleted_by' => $userId
            ]);
        
        return back()->with('success', 'All trusted devices removed. You will need to verify with 2FA PIN on next login from any device.');
    }
    
    public function revokeSession(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        $sessionId = $request->input('session_id');
        
        // Get the session
        $session = DB::connection('auth_db')
            ->table('user_sessions')
            ->where('session_id', $sessionId)
            ->where('user_id', $userId)
            ->first();
        
        if (!$session) {
            return back()->with('error', 'Session not found.');
        }
        
        // Soft delete session from database
        DB::connection('auth_db')
            ->table('user_sessions')
            ->where('session_id', $sessionId)
            ->update([
                'deleted_at' => now(),
                'deleted_by' => $userId
            ]);
        
        return back()->with('success', 'Session revoked successfully.');
    }
    
    public function revokeAllOtherSessions()
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        $currentSessionId = session()->getId();
        
        // Soft delete all sessions except current
        DB::connection('auth_db')
            ->table('user_sessions')
            ->where('user_id', $userId)
            ->where('session_id', '!=', $currentSessionId)
            ->update([
                'deleted_at' => now(),
                'deleted_by' => $userId
            ]);
        
        return back()->with('success', 'All other sessions have been logged out.');
    }
    
    public function updatePrivacySettings(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'profile_visibility' => 'required|in:public,private',
        ]);
        
        DB::connection('auth_db')
            ->table('users')
            ->where('id', $userId)
            ->update([
                'profile_visibility' => $request->profile_visibility,
                'show_reviews_publicly' => $request->has('show_reviews_publicly'),
                'show_booking_count' => $request->has('show_booking_count'),
            ]);
        
        return back()->with('success', 'Privacy settings updated successfully.');
    }
    
    public function requestDataDownload()
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        // Gather user data from auth database
        $userData = DB::connection('auth_db')
            ->table('users')
            ->where('id', $userId)
            ->first();
        
        // Gather booking data from facilities database
        $bookings = DB::connection('facilities_db')
            ->table('bookings')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->where('bookings.user_id', $userId)
            ->select(
                'bookings.id',
                'bookings.status',
                'bookings.start_time',
                'bookings.end_time',
                'bookings.purpose',
                'bookings.created_at',
                'facilities.name as facility_name'
            )
            ->orderBy('bookings.created_at', 'desc')
            ->get();
        
        // Gather reviews
        $reviews = DB::connection('facilities_db')
            ->table('facility_reviews')
            ->join('facilities', 'facility_reviews.facility_id', '=', 'facilities.facility_id')
            ->where('facility_reviews.user_id', $userId)
            ->select(
                'facility_reviews.rating',
                'facility_reviews.review',
                'facility_reviews.created_at',
                'facilities.name as facility_name'
            )
            ->get();
        
        // Build CSV content
        $csvContent = "";
        
        // Section 1: User Information
        $csvContent .= "=== MY PERSONAL DATA EXPORT ===\r\n";
        $csvContent .= "Export Date," . now()->format('F d, Y h:i A') . "\r\n\r\n";
        
        $csvContent .= "=== USER INFORMATION ===\r\n";
        $csvContent .= "Username," . ($userData->username ?? 'N/A') . "\r\n";
        $csvContent .= "Email," . ($userData->email ?? 'N/A') . "\r\n";
        $csvContent .= "Full Name," . ($userData->full_name ?? 'N/A') . "\r\n";
        $csvContent .= "Account Created," . ($userData->created_at ?? 'N/A') . "\r\n\r\n";
        
        // Section 2: Bookings
        $csvContent .= "=== BOOKING HISTORY (" . count($bookings) . " records) ===\r\n";
        $csvContent .= "ID,Facility,Purpose,Status,Date,Time,Created At\r\n";
        
        foreach ($bookings as $booking) {
            $date = date('M d, Y', strtotime($booking->start_time));
            $startTime = date('h:i A', strtotime($booking->start_time));
            $endTime = date('h:i A', strtotime($booking->end_time));
            $csvContent .= sprintf(
                "%d,\"%s\",\"%s\",%s,%s,%s - %s,%s\r\n",
                $booking->id,
                str_replace('"', '""', $booking->facility_name),
                str_replace('"', '""', $booking->purpose),
                ucfirst($booking->status),
                $date,
                $startTime,
                $endTime,
                $booking->created_at
            );
        }
        
        $csvContent .= "\r\n";
        
        // Section 3: Reviews
        $csvContent .= "=== MY REVIEWS (" . count($reviews) . " records) ===\r\n";
        $csvContent .= "Facility,Rating,Review,Date\r\n";
        
        foreach ($reviews as $review) {
            $csvContent .= sprintf(
                "\"%s\",%d stars,\"%s\",%s\r\n",
                str_replace('"', '""', $review->facility_name),
                $review->rating,
                str_replace('"', '""', $review->review ?? ''),
                date('M d, Y', strtotime($review->created_at))
            );
        }
        
        $filename = 'my_data_export_' . date('Y-m-d') . '.csv';
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
    private function getLocationFromIP($ip)
    {
        try {
            $response = Http::get("http://ip-api.com/json/{$ip}");
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'country' => $data['country'] ?? 'Unknown',
                    'city' => $data['city'] ?? 'Unknown',
                ];
            }
        } catch (\Exception $e) {
            \Log::error('IP Geolocation failed: ' . $e->getMessage());
        }
        
        return [
            'country' => 'Unknown',
            'city' => 'Unknown',
        ];
    }
}
