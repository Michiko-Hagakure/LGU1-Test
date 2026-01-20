<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\FacilityDb;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display the admin calendar view
     * Shows ALL booking statuses (unlike staff calendar which shows only approved)
     */
    public function index()
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // Get all facilities for the filter dropdown
        $facilities = FacilityDb::select('facility_id', 'name')
            ->where('is_available', true)
            ->orderBy('name')
            ->get();

        return view('admin.calendar.index', compact('facilities'));
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
                    // Clean the date strings - remove any extra whitespace/timezone
                    $startClean = trim(preg_replace('/\s+.*$/', '', $start));
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
            }
            // If no status filter, show all bookings (admin sees everything)

            $bookings = $query->get();

            // Transform bookings to FullCalendar event format
            $events = $bookings->map(function ($booking) {
                // Determine event color based on status
                $color = $this->getEventColor($booking->status);
                
                // Get citizen name from user_name field (direct field, not relationship)
                $citizenName = $booking->user_name ?? $booking->applicant_name ?? 'N/A';
                
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
                        'booking_id' => $booking->id,
                        'facility_location' => $booking->facility->lguCity->city_name ?? 'N/A',
                        'purpose' => $booking->purpose ?? '',
                        'num_attendees' => $booking->expected_attendees ?? 0,
                        'status' => $booking->status,
                        'citizen_name' => $citizenName,
                        'total_amount' => $booking->total_amount ?? 0,
                        'start_time' => $booking->start_time->toIso8601String(),
                        'end_time' => $booking->end_time->toIso8601String(),
                    ],
                ];
            });

            return response()->json($events);
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Admin Calendar getEvents error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return error as JSON
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
     * Admin calendar has more status types than staff calendar
     * 
     * @param string $status
     * @return array
     */
    private function getEventColor($status)
    {
        return match($status) {
            'pending' => [
                'bg' => '#fbbf24',      // Yellow - Pending staff verification
                'border' => '#f59e0b',
                'text' => '#78350f'
            ],
            'staff_verified' => [
                'bg' => '#34d399',      // Green - Approved, awaiting payment
                'border' => '#10b981',
                'text' => '#064e3b'
            ],
            'paid' => [
                'bg' => '#60a5fa',      // Blue - Paid, awaiting admin confirmation
                'border' => '#3b82f6',
                'text' => '#1e3a8a'
            ],
            'confirmed' => [
                'bg' => '#a78bfa',      // Purple - Confirmed (final)
                'border' => '#8b5cf6',
                'text' => '#4c1d95'
            ],
            'rejected', 'cancelled' => [
                'bg' => '#f87171',      // Red - Rejected or cancelled
                'border' => '#ef4444',
                'text' => '#7f1d1d'
            ],
            'expired' => [
                'bg' => '#fb923c',      // Orange - Expired (unpaid after 48h)
                'border' => '#f97316',
                'text' => '#7c2d12'
            ],
            default => [
                'bg' => '#9ca3af',      // Gray - Unknown
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
            'paid' => 'Paid (Awaiting Confirmation)',
            'confirmed' => 'Confirmed',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            'expired' => 'Expired (Unpaid)',
            default => ucfirst($status)
        };
    }
}

