<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HousingResettlementApiController extends Controller
{
    /**
     * Get list of available facilities for Housing and Resettlement
     * 
     * GET /api/housing-resettlement/facilities
     */
    public function listFacilities(Request $request)
    {
        try {
            $query = DB::connection('facilities_db')
                ->table('facilities')
                ->where('is_available', 1)
                ->whereNull('deleted_at');

            // Optional filtering
            if ($request->has('capacity_min')) {
                $query->where('capacity', '>=', (int) $request->capacity_min);
            }

            $facilities = $query->select(
                'facility_id',
                'name',
                'description',
                'capacity'
            )
            ->orderBy('name')
            ->get();

            return response()->json([
                'success' => true,
                'message' => 'Facilities retrieved successfully',
                'data' => [
                    'facilities' => $facilities,
                    'total' => $facilities->count(),
                ],
                'timestamp' => now()->toDateTimeString(),
            ]);

        } catch (\Exception $e) {
            Log::error('Housing Resettlement API - List Facilities Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve facilities',
                'timestamp' => now()->toDateTimeString(),
            ], 500);
        }
    }

    /**
     * Check facility availability for a specific date/time
     * 
     * GET /api/housing-resettlement/check-availability
     */
    public function checkAvailability(Request $request)
    {
        try {
            $validated = $request->validate([
                'facility_id' => 'required|integer',
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
            ]);

            $facility = DB::connection('facilities_db')
                ->table('facilities')
                ->where('facility_id', $validated['facility_id'])
                ->where('is_available', true)
                ->whereNull('deleted_at')
                ->first();

            if (!$facility) {
                return response()->json([
                    'success' => false,
                    'message' => 'Facility not found or not available',
                    'data' => ['available' => false],
                    'timestamp' => now()->toDateTimeString(),
                ], 404);
            }

            // Check for conflicting bookings
            $startDateTime = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
            $endDateTime = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);

            $conflictingBookings = DB::connection('facilities_db')
                ->table('bookings')
                ->where('facility_id', $validated['facility_id'])
                ->whereIn('status', ['pending', 'staff_verified', 'reserved', 'confirmed', 'paid'])
                ->where(function ($query) use ($startDateTime, $endDateTime) {
                    $query->where(function ($q) use ($startDateTime, $endDateTime) {
                        $q->where('start_time', '<', $endDateTime)
                          ->where('end_time', '>', $startDateTime);
                    });
                })
                ->count();

            $isAvailable = $conflictingBookings === 0;

            return response()->json([
                'success' => true,
                'message' => $isAvailable ? 'Facility is available' : 'Facility is not available for the selected time',
                'data' => [
                    'available' => $isAvailable,
                    'facility_name' => $facility->name,
                    'requested_date' => $validated['date'],
                    'requested_time' => $validated['start_time'] . ' - ' . $validated['end_time'],
                ],
                'timestamp' => now()->toDateTimeString(),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'timestamp' => now()->toDateTimeString(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Housing Resettlement API - Check Availability Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to check availability',
                'timestamp' => now()->toDateTimeString(),
            ], 500);
        }
    }

    /**
     * Submit a facility request for beneficiary orientation
     * 
     * POST /api/housing-resettlement/request
     */
    public function submitRequest(Request $request)
    {
        try {
            $validated = $request->validate([
                'facility_id' => 'required|integer',
                'event_name' => 'required|string|max:255',
                'event_description' => 'nullable|string|max:1000',
                'requested_date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'expected_attendees' => 'required|integer|min:1|max:5000',
                'contact_person' => 'required|string|max:255',
                'contact_email' => 'required|email|max:255',
                'contact_phone' => 'required|string|max:20',
                'department' => 'nullable|string|max:255',
                'special_requests' => 'nullable|string|max:1000',
            ]);

            // Verify facility exists
            $facility = DB::connection('facilities_db')
                ->table('facilities')
                ->where('facility_id', $validated['facility_id'])
                ->where('is_available', true)
                ->whereNull('deleted_at')
                ->first();

            if (!$facility) {
                return response()->json([
                    'success' => false,
                    'message' => 'Facility not found or not available',
                    'timestamp' => now()->toDateTimeString(),
                ], 404);
            }

            // Check capacity
            if ($validated['expected_attendees'] > $facility->capacity) {
                return response()->json([
                    'success' => false,
                    'message' => "Expected attendees ({$validated['expected_attendees']}) exceeds facility capacity ({$facility->capacity})",
                    'timestamp' => now()->toDateTimeString(),
                ], 400);
            }

            // Build datetime
            $startDateTime = Carbon::parse($validated['requested_date'] . ' ' . $validated['start_time']);
            $endDateTime = Carbon::parse($validated['requested_date'] . ' ' . $validated['end_time']);

            // Check for conflicts
            $conflictingBookings = DB::connection('facilities_db')
                ->table('bookings')
                ->where('facility_id', $validated['facility_id'])
                ->whereIn('status', ['pending', 'staff_verified', 'reserved', 'confirmed', 'paid'])
                ->where(function ($query) use ($startDateTime, $endDateTime) {
                    $query->where('start_time', '<', $endDateTime)
                          ->where('end_time', '>', $startDateTime);
                })
                ->count();

            if ($conflictingBookings > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Facility is not available for the requested time slot',
                    'timestamp' => now()->toDateTimeString(),
                ], 409);
            }

            // Generate booking reference
            $lastBooking = DB::connection('facilities_db')
                ->table('bookings')
                ->orderBy('id', 'desc')
                ->first();
            $nextId = $lastBooking ? $lastBooking->id + 1 : 1;
            $bookingReference = 'BK' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

            // Create the booking - use only columns that exist in bookings table
            $bookingData = [
                'facility_id' => $validated['facility_id'],
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'user_name' => $validated['contact_person'],
                'applicant_name' => $validated['contact_person'],
                'applicant_email' => $validated['contact_email'],
                'applicant_phone' => $validated['contact_phone'],
                'event_name' => $validated['event_name'],
                'status' => 'paid', // Government inter-agency - skip staff verification, go directly to admin for confirmation
                'base_rate' => 0, // Government inter-agency - no charge
                'subtotal' => 0,
                'total_amount' => 0,
                'purpose' => $validated['event_name'] . ' - ' . ($validated['event_description'] ?? 'Housing and Resettlement'),
                'expected_attendees' => $validated['expected_attendees'],
                'special_requests' => $validated['special_requests'] ?? null,
                'source_system' => 'Housing_Resettlement', // Required for admin UI to find this booking
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Try to add optional columns if they exist
            try {
                $bookingId = DB::connection('facilities_db')
                    ->table('bookings')
                    ->insertGetId($bookingData);
            } catch (\Exception $insertError) {
                Log::error('Housing Resettlement - Insert Error', [
                    'error' => $insertError->getMessage(),
                    'data' => $bookingData,
                ]);
                throw $insertError;
            }

            Log::info('Housing Resettlement - Facility Request Submitted', [
                'booking_id' => $bookingId,
                'booking_reference' => $bookingReference,
                'facility_id' => $validated['facility_id'],
                'contact_person' => $validated['contact_person'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Facility request submitted successfully',
                'data' => [
                    'booking_reference' => $bookingReference,
                    'booking_id' => $bookingId,
                    'facility_name' => $facility->name,
                    'event_name' => $validated['event_name'],
                    'scheduled_date' => $validated['requested_date'],
                    'scheduled_time' => $validated['start_time'] . ' - ' . $validated['end_time'],
                    'status' => 'paid',
                    'next_steps' => 'Your request has been submitted and is awaiting admin confirmation.',
                ],
                'timestamp' => now()->toDateTimeString(),
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'timestamp' => now()->toDateTimeString(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Housing Resettlement API - Submit Request Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit facility request',
                'debug_error' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString(),
            ], 500);
        }
    }

    /**
     * Check status of a facility request
     * 
     * GET /api/housing-resettlement/status/{reference}
     */
    public function checkStatus($reference)
    {
        try {
            $bookingId = (int) preg_replace('/[^0-9]/', '', $reference);

            $booking = DB::connection('facilities_db')
                ->table('bookings')
                ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
                ->where('bookings.id', $bookingId)
                ->where('bookings.source_system', 'Housing_Resettlement')
                ->select(
                    'bookings.id',
                    'bookings.booking_reference',
                    'bookings.event_name',
                    'bookings.start_time',
                    'bookings.end_time',
                    'bookings.status',
                    'bookings.expected_attendees',
                    'bookings.created_at',
                    'facilities.name as facility_name'
                )
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found',
                    'timestamp' => now()->toDateTimeString(),
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking status retrieved',
                'data' => [
                    'booking_reference' => $booking->booking_reference,
                    'facility_name' => $booking->facility_name,
                    'event_name' => $booking->event_name,
                    'status' => $booking->status,
                    'scheduled_date' => Carbon::parse($booking->start_time)->format('Y-m-d'),
                    'scheduled_time' => Carbon::parse($booking->start_time)->format('h:i A') . ' - ' . Carbon::parse($booking->end_time)->format('h:i A'),
                    'expected_attendees' => $booking->expected_attendees,
                    'submitted_at' => Carbon::parse($booking->created_at)->format('Y-m-d h:i A'),
                ],
                'timestamp' => now()->toDateTimeString(),
            ]);

        } catch (\Exception $e) {
            Log::error('Housing Resettlement API - Check Status Error', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve booking status',
                'timestamp' => now()->toDateTimeString(),
            ], 500);
        }
    }
}
