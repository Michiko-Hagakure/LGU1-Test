<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\FacilityDb;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingManagementController extends Controller
{
    /**
     * Display all bookings with advanced filters
     */
    public function index(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // Build query
        $query = Booking::with(['facility.lguCity', 'user']);

        // Filter by status
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Filter by facility
        if ($request->filled('facility_id') && $request->input('facility_id') !== 'all') {
            $query->where('facility_id', $request->input('facility_id'));
        }

        // Filter by date range (event date)
        if ($request->filled('date_from')) {
            $query->whereDate('start_time', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('start_time', '<=', $request->input('date_to'));
        }

        // Search by booking ID or applicant name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('applicant_name', 'LIKE', "%{$search}%");
            });
        }

        // Order by most recent first
        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);

        // Fetch user names from auth_db for each booking
        foreach ($bookings as $booking) {
            if ($booking->user_id) {
                $user = \DB::connection('auth_db')->table('users')
                    ->where('id', $booking->user_id)
                    ->first();
                
                if ($user) {
                    $booking->user_name = $user->full_name;
                } else {
                    $booking->user_name = $booking->user_name ?? $booking->applicant_name;
                }
            } else {
                // For API bookings (no user_id), preserve existing user_name or fall back to applicant_name
                $booking->user_name = $booking->user_name ?? $booking->applicant_name;
            }
        }

        // Get facilities for filter
        $facilities = FacilityDb::select('facility_id', 'name')
            ->where('is_available', true)
            ->orderBy('name')
            ->get();

        return view('admin.bookings.index', compact('bookings', 'facilities'));
    }

    /**
     * Return bookings as JSON for AJAX polling
     */
    public function getBookingsJson(Request $request)
    {
        $query = Booking::with(['facility.lguCity', 'user']);

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('facility_id') && $request->input('facility_id') !== 'all') {
            $query->where('facility_id', $request->input('facility_id'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('start_time', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('start_time', '<=', $request->input('date_to'));
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('applicant_name', 'LIKE', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->limit(50)->get();

        foreach ($bookings as $booking) {
            if ($booking->user_id) {
                $user = \DB::connection('auth_db')->table('users')->where('id', $booking->user_id)->first();
                $booking->user_name = $user ? $user->full_name : ($booking->user_name ?? $booking->applicant_name);
            } else {
                // For API bookings, preserve existing user_name or fall back to applicant_name
                $booking->user_name = $booking->user_name ?? $booking->applicant_name;
            }
            $booking->booking_reference = 'BK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT);
            $booking->facility_name = $booking->facility->name ?? 'N/A';
            $booking->start_formatted = Carbon::parse($booking->start_time)->format('M d, Y');
            $booking->time_range = Carbon::parse($booking->start_time)->format('h:iA') . '-' . Carbon::parse($booking->end_time)->format('h:iA');
        }

        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'staff_verified' => Booking::where('status', 'staff_verified')->count(),
        ];

        return response()->json(['data' => $bookings, 'stats' => $stats]);
    }

    // For Data
    public function show($id)
    {
        $booking = Booking::with(['facility', 'user'])->find($id);
        if (!$booking) {
            abort(404, 'Booking record not found.');
        }
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Display booking review page for admin
     * Similar to staff review but with payment verification and final confirmation
     */
    public function review($bookingId)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // Get booking with all relationships
        $booking = Booking::with([
            'facility.lguCity',
            'user',
            'equipmentItems'
        ])->findOrFail($bookingId);

        // Get user details from auth_db
        $userFromDb = \DB::connection('auth_db')->table('users')
            ->where('id', $booking->user_id)
            ->first();

        // Get barangay and city names
        $barangay = null;
        $city = null;
        if ($userFromDb) {
            if ($userFromDb->barangay_id) {
                $barangay = \DB::connection('auth_db')->table('barangays')->where('id', $userFromDb->barangay_id)->value('name');
            }
            if ($userFromDb->city_id) {
                $city = \DB::connection('auth_db')->table('cities')->where('id', $userFromDb->city_id)->value('name');
            }
        }

        // Build full address (handle null $userFromDb for API bookings)
        $fullAddress = '';
        if ($userFromDb) {
            $fullAddress = collect([
                $userFromDb->current_address ?? $userFromDb->address ?? null,
                $barangay,
                $city
            ])->filter()->implode(', ');
        }

        // Create standardized user object with fallbacks for API bookings (PF folder, Housing & Resettlement, etc.)
        $user = (object) [
            'name' => ($userFromDb ? ($userFromDb->full_name ?? $userFromDb->name ?? null) : null) ?? $booking->applicant_name ?? $booking->user_name ?? 'N/A',
            'email' => ($userFromDb ? ($userFromDb->email ?? null) : null) ?? $booking->applicant_email ?? 'N/A',
            'phone' => ($userFromDb ? ($userFromDb->mobile_number ?? $userFromDb->phone ?? null) : null) ?? $booking->applicant_phone ?? 'N/A',
            'address' => $fullAddress ?: ($booking->applicant_address ?? 'N/A')
        ];

        // Get uploaded documents
        $documents = [
            'id_front' => $booking->valid_id_front_path,
            'id_back' => $booking->valid_id_back_path,
            'selfie_with_id' => $booking->valid_id_selfie_path,
        ];

        // Get equipment with pricing
        $equipment = $booking->equipmentItems;

        // Check for schedule conflicts
        $conflictData = $booking->checkScheduleConflicts();
        $conflicts = $conflictData['conflicts'] ?? collect();
        $hasConflict = $conflictData['hasConflict'] ?? false;

        // Calculate payment deadline (48 hours from staff verification)
        $paymentDeadline = null;
        $hoursRemaining = null;
        if ($booking->status === 'staff_verified' && $booking->staff_verified_at) {
            $verifiedAt = Carbon::parse($booking->staff_verified_at);
            $paymentDeadline = $verifiedAt->copy()->addHours(48);
            $hoursRemaining = max(0, Carbon::now()->diffInHours($paymentDeadline, false));
        }

        return view('admin.bookings.review', compact(
            'booking',
            'equipment',
            'documents',
            'user',
            'conflicts',
            'paymentDeadline',
            'hoursRemaining'
        ));
    }

    /**
     * Final confirmation of booking
     * Changes status from paid to confirmed
     */
    public function finalConfirm(Request $request, $bookingId)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $booking = Booking::findOrFail($bookingId);

        // Verify booking is in correct status
        if ($booking->status !== 'paid') {
            return back()->with('error', 'Booking must be paid before final confirmation.');
        }

        // Update booking status to confirmed (final state)
        $booking->status = 'confirmed';
        $booking->admin_approved_at = Carbon::now();
        $booking->admin_approved_by = $userId;
        
        // Add admin notes if provided
        if ($request->filled('admin_notes')) {
            $existingNotes = $booking->admin_approval_notes ?? '';
            $booking->admin_approval_notes = $existingNotes . "\n[" . Carbon::now()->format('Y-m-d H:i') . "] " . $request->input('admin_notes');
        }

        $booking->save();

        // Send confirmation notification to citizen
        try {
            $user = User::find($booking->user_id);
            $bookingWithFacility = DB::connection('facilities_db')
                ->table('bookings')
                ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
                ->where('bookings.id', $bookingId)
                ->selectRaw('bookings.*, facilities.name as facility_name, CONCAT("BK", LPAD(bookings.id, 6, "0")) as booking_reference')
                ->first();
            
            if ($user && $bookingWithFacility) {
                $user->notify(new \App\Notifications\BookingConfirmed($bookingWithFacility));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send booking confirmation notification: ' . $e->getMessage());
        }
        
        return redirect()
            ->route('admin.bookings.review', $bookingId)
            ->with('success', 'Booking confirmed! Citizen will be notified.');
    }
}
