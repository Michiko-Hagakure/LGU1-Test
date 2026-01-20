<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\FacilityDb;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display the calendar view
     */
    public function index()
    {
        try {
            $userId = session('user_id');
            
            if (!$userId) {
                return redirect()->route('login')->with('error', 'Please login to continue.');
            }

            // Get all facilities for the filter dropdown
            $facilities = FacilityDb::select('facility_id', 'name')
                ->where('is_available', true)
                ->orderBy('name')
                ->get();

            return view('staff.calendar.index', compact('facilities'));
            
        } catch (\Exception $e) {
            \Log::error('Calendar index error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()->with('error', 'Error loading calendar: ' . $e->getMessage());
        }
    }

    /**
     * Get booking events for the calendar (AJAX endpoint)
     * Returns JSON data in FullCalendar format
     */
    public function getEvents(Request $request)
    {
        try {
            $userId = session('user_id');
            
            if (!$userId) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Get filter parameters
            $facilityId = $request->input('facility_id');
            $status = $request->input('status');
            $start = $request->input('start'); // FullCalendar sends start/end dates
            $end = $request->input('end');

        // Build query for bookings
        $query = Booking::with(['facility.lguCity']);

        // Filter by date range (if provided by FullCalendar)
        // Use start_time since event_date may be NULL
        if ($start && $end) {
            $query->where(function($q) use ($start, $end) {
                // Clean the date strings - remove any extra whitespace/timezone that might cause parsing issues
                // FullCalendar sends dates like "2025-11-30T00:00:00" or "2025-11-30"
                $startClean = trim(preg_replace('/\s+.*$/', '', $start)); // Remove everything after first space
                $endClean = trim(preg_replace('/\s+.*$/', '', $end));
                
                $startDate = Carbon::parse($startClean)->startOfDay();
                $endDate = Carbon::parse($endClean)->endOfDay();
                
                $q->whereBetween('start_time', [$startDate, $endDate])
                  ->orWhereBetween('end_time', [$startDate, $endDate]);
            });
        }

        // Filter by facility
        if ($facilityId && $facilityId !== 'all') {
            $query->where('facility_id', $facilityId);
        }

        // Filter by status
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        } else {
            // Default: Show only approved/confirmed bookings (locked slots)
            $query->whereIn('status', ['staff_verified', 'paid', 'confirmed']);
        }

        $bookings = $query->get();

        // Transform bookings to FullCalendar event format
        $events = $bookings->map(function ($booking) {
            // Determine event color based on status
            $color = $this->getEventColor($booking->status);
            
            // Create event object
            return [
                'id' => $booking->id,
                'title' => $booking->facility->name ?? 'Unknown Facility',
                'start' => $booking->start_time->toIso8601String(),
                'end' => $booking->end_time->toIso8601String(),
                'backgroundColor' => $color['bg'],
                'borderColor' => $color['border'],
                'textColor' => $color['text'],
                'extendedProps' => [
                    'bookingId' => 'BK-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
                    'facilityName' => $booking->facility->name ?? 'N/A',
                    'cityName' => $booking->facility->lguCity->name ?? $booking->facility->address ?? '',
                    'purpose' => $booking->purpose ?? 'N/A',
                    'attendees' => $booking->expected_attendees,
                    'status' => $booking->status,
                    'statusLabel' => $this->getStatusLabel($booking->status),
                    'userName' => $booking->user_name ?? 'N/A',
                ],
            ];
        });

            return response()->json($events);
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Calendar getEvents error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return error as JSON instead of HTML error page
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Get color scheme based on booking status
     * 
     * @param string $status
     * @return array
     */
    private function getEventColor($status)
    {
        return match($status) {
            'pending' => [
                'bg' => '#fbbf24',      // Yellow
                'border' => '#f59e0b',
                'text' => '#78350f'
            ],
            'staff_verified' => [
                'bg' => '#34d399',      // Green (approved, awaiting payment)
                'border' => '#10b981',
                'text' => '#064e3b'
            ],
            'paid', 'confirmed' => [
                'bg' => '#60a5fa',      // Blue (confirmed & paid)
                'border' => '#3b82f6',
                'text' => '#1e3a8a'
            ],
            'rejected', 'cancelled' => [
                'bg' => '#f87171',      // Red
                'border' => '#ef4444',
                'text' => '#7f1d1d'
            ],
            default => [
                'bg' => '#9ca3af',      // Gray
                'border' => '#6b7280',
                'text' => '#1f2937'
            ]
        };
    }

    /**
     * Get human-readable status label
     * 
     * @param string $status
     * @return string
     */
    private function getStatusLabel($status)
    {
        return match($status) {
            'pending' => 'Pending Verification',
            'staff_verified' => 'Approved (Awaiting Payment)',
            'paid' => 'Paid & Reserved',
            'confirmed' => 'Confirmed',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            default => ucfirst($status)
        };
    }
}

