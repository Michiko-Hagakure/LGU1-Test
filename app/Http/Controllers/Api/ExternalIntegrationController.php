<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExternalIntegrationController extends Controller
{
    /**
     * Receive booking request from Energy Efficiency system
     * POST /api/v1/external/government-programs/request
     */
    public function receiveBookingRequest(Request $request)
    {
        try {
            $validated = $request->validate([
                'source_system' => 'required|string',
                'seminar_id' => 'required|integer',
                'organizer_name' => 'required|string',
                'event_name' => 'required|string',
                'event_type' => 'required|string',
                'booking_date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required',
                'expected_attendees' => 'required|integer',
                'contact_number' => 'required|string',
                'contact_email' => 'required|email',
                'purpose' => 'required|string',
                'budget_allocated' => 'nullable|numeric',
            ]);

            // Log the incoming request
            Log::info('External booking request received from Energy Efficiency', [
                'seminar_id' => $validated['seminar_id'],
                'event_name' => $validated['event_name'],
            ]);

            // Store in government_programs table (if you have it)
            // Or create a pending booking record
            
            return response()->json([
                'status' => 'success',
                'message' => 'Booking request received and queued for admin review',
                'data' => [
                    'request_id' => $validated['seminar_id'],
                    'status' => 'pending_admin_review',
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to receive external booking request', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process booking request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available facilities for Energy Efficiency to query
     * GET /api/v1/external/facilities/available
     */
    public function getAvailableFacilities(Request $request)
    {
        try {
            $date = $request->query('date');
            $startTime = $request->query('start_time');
            $endTime = $request->query('end_time');
            $minCapacity = $request->query('min_capacity', 0);

            $facilities = Facility::where('status', 'active')
                ->where('capacity', '>=', $minCapacity)
                ->get()
                ->map(function ($facility) use ($date, $startTime, $endTime) {
                    $isAvailable = true;

                    if ($date && $startTime && $endTime) {
                        // Check for conflicts
                        $conflictExists = Booking::where('facility_id', $facility->id)
                            ->where('booking_date', $date)
                            ->whereIn('status', ['confirmed', 'payment_pending', 'pending_approval'])
                            ->where(function ($query) use ($startTime, $endTime) {
                                $query->whereBetween('start_time', [$startTime, $endTime])
                                    ->orWhereBetween('end_time', [$startTime, $endTime]);
                            })
                            ->exists();

                        $isAvailable = !$conflictExists;
                    }

                    return [
                        'id' => $facility->id,
                        'name' => $facility->name,
                        'capacity' => $facility->capacity,
                        'base_rate' => $facility->base_rate,
                        'city' => $facility->city->city_name ?? null,
                        'available' => $isAvailable,
                        'amenities' => $facility->amenities,
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $facilities
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to fetch available facilities', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch facilities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get booking status for Energy Efficiency to check
     * GET /api/v1/external/government-programs/{bookingId}/status
     */
    public function getBookingStatus($bookingId)
    {
        try {
            $booking = Booking::find($bookingId);

            if (!$booking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'booking_id' => $booking->id,
                    'status' => $booking->status,
                    'facility_name' => $booking->facility->name ?? null,
                    'booking_date' => $booking->booking_date,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'approved_at' => $booking->admin_approved_at,
                    'confirmed_at' => $booking->payment_confirmed_at,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to fetch booking status', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch booking status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm booking (called by Energy Efficiency after payment)
     * POST /api/v1/external/government-programs/{bookingId}/confirm
     */
    public function confirmBooking(Request $request, $bookingId)
    {
        try {
            $booking = Booking::find($bookingId);

            if (!$booking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found'
                ], 404);
            }

            // Update booking status to confirmed
            $booking->update([
                'status' => 'confirmed',
                'payment_confirmed_at' => now(),
            ]);

            Log::info('External booking confirmed', [
                'booking_id' => $bookingId,
                'source' => 'energy_efficiency'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Booking confirmed successfully',
                'data' => [
                    'booking_id' => $booking->id,
                    'status' => $booking->status,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to confirm booking', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to confirm booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
