<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FacilityReservationApiController extends Controller
{
    /**
     * API Key for authentication
     * In production, store this in .env file
     */
    private function getApiKey()
    {
        return config('services.facility_api.key', env('FACILITY_API_KEY', 'your-secure-api-key-here'));
    }

    /**
     * Receive facility reservation request from external system
     * 
     * POST /api/facility-reservation
     */
    public function store(Request $request)
    {
        try {
            // Log incoming request
            Log::info('Facility Reservation API: Request received', [
                'ip' => $request->ip(),
                'source_system' => $request->input('source_system'),
            ]);

            // Validate API key
            $apiKey = $request->header('X-API-Key') ?? $request->input('api_key');
            if ($apiKey !== $this->getApiKey()) {
                Log::warning('Facility Reservation API: Unauthorized access attempt', [
                    'ip' => $request->ip(),
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized. Invalid API key.',
                ], 401);
            }

            // Validate incoming data
            $validated = $request->validate([
                // Source system identification
                'source_system' => 'required|string|max:100',
                'external_reference_id' => 'nullable|string|max:100',
                
                // Applicant information
                'applicant_name' => 'required|string|max:255',
                'applicant_email' => 'required|email|max:255',
                'applicant_phone' => 'required|string|max:20',
                'applicant_address' => 'nullable|string|max:500',
                
                // Facility and timing
                'facility_id' => 'required|integer|exists:facilities_db.facilities,facility_id',
                'booking_date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                
                // Event details
                'purpose' => 'required|string|max:500',
                'event_name' => 'nullable|string|max:255',
                'event_description' => 'nullable|string|max:1000',
                'expected_attendees' => 'required|integer|min:1',
                
                // Location & discounts
                'city_of_residence' => 'nullable|string|max:100',
                'special_discount_type' => 'nullable|in:senior,pwd,student',
                
                // Additional
                'special_requests' => 'nullable|string|max:1000',
                
                // Equipment (optional array)
                'equipment' => 'nullable|array',
                'equipment.*.equipment_id' => 'required_with:equipment|integer|exists:facilities_db.equipment_items,id',
                'equipment.*.quantity' => 'required_with:equipment|integer|min:1',
            ]);

            // Get facility details
            $facility = DB::connection('facilities_db')
                ->table('facilities')
                ->leftJoin('lgu_cities', 'facilities.lgu_city_id', '=', 'lgu_cities.id')
                ->select('facilities.*', 'lgu_cities.city_name')
                ->where('facilities.facility_id', $validated['facility_id'])
                ->whereNull('facilities.deleted_at')
                ->first();

            if (!$facility) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Facility not found or unavailable.',
                ], 404);
            }

            // Validate capacity
            if ($validated['expected_attendees'] > ($facility->capacity ?? 1000)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Expected attendees exceeds facility capacity of ' . $facility->capacity,
                ], 422);
            }

            // Parse date/time
            $startDateTime = Carbon::parse($validated['booking_date'] . ' ' . $validated['start_time']);
            $endDateTime = Carbon::parse($validated['booking_date'] . ' ' . $validated['end_time']);

            // Validate operating hours (8:00 AM to 10:00 PM)
            $operatingStart = Carbon::parse($validated['booking_date'] . ' 08:00:00');
            $operatingEnd = Carbon::parse($validated['booking_date'] . ' 22:00:00');

            if ($startDateTime->lt($operatingStart) || $endDateTime->gt($operatingEnd)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bookings must be within operating hours: 8:00 AM to 10:00 PM.',
                ], 422);
            }

            // Check availability (with 2-hour buffer)
            $bufferHours = 2;
            $conflict = DB::connection('facilities_db')
                ->table('bookings')
                ->where('facility_id', $validated['facility_id'])
                ->whereIn('status', ['pending', 'staff_verified', 'reserved', 'payment_pending', 'confirmed', 'paid'])
                ->where(function($query) use ($startDateTime, $endDateTime, $bufferHours) {
                    $query->whereRaw('? < DATE_ADD(end_time, INTERVAL ? HOUR)', [$startDateTime, $bufferHours])
                          ->whereRaw('? > start_time', [$endDateTime]);
                })
                ->exists();

            if ($conflict) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Time slot is not available. Please choose a different date/time.',
                ], 409);
            }

            // Calculate pricing
            $pricing = $this->calculatePricing($facility, $validated, $startDateTime, $endDateTime);

            // Calculate discounts
            $isResident = false;
            $residentDiscountRate = 0;
            $residentDiscountAmount = 0;

            if (!empty($validated['city_of_residence']) && $facility->city_name) {
                if (strtolower($validated['city_of_residence']) === strtolower($facility->city_name)) {
                    $isResident = true;
                    $residentDiscountRate = 30.00;
                    $residentDiscountAmount = $pricing['subtotal'] * 0.30;
                }
            }

            $specialDiscountRate = 0;
            $specialDiscountAmount = 0;
            if (!empty($validated['special_discount_type'])) {
                $specialDiscountRate = 20.00;
                $afterResidentDiscount = $pricing['subtotal'] - $residentDiscountAmount;
                $specialDiscountAmount = $afterResidentDiscount * 0.20;
            }

            $totalDiscount = $residentDiscountAmount + $specialDiscountAmount;
            $totalAmount = $pricing['subtotal'] - $totalDiscount;

            DB::connection('facilities_db')->beginTransaction();

            try {
                // Create booking
                $booking = Booking::create([
                    'facility_id' => $validated['facility_id'],
                    'user_id' => null, // External API booking - no user account
                    'user_name' => $validated['applicant_name'],
                    'applicant_name' => $validated['applicant_name'],
                    'applicant_email' => $validated['applicant_email'],
                    'applicant_phone' => $validated['applicant_phone'],
                    'applicant_address' => $validated['applicant_address'] ?? null,
                    'event_name' => $validated['event_name'] ?? null,
                    'event_description' => $validated['event_description'] ?? null,
                    'start_time' => $startDateTime,
                    'end_time' => $endDateTime,
                    'purpose' => $validated['purpose'],
                    'expected_attendees' => $validated['expected_attendees'],
                    'special_requests' => $validated['special_requests'] ?? null,
                    'base_rate' => $pricing['base_rate'],
                    'extension_rate' => $pricing['extension_rate'],
                    'equipment_total' => $pricing['equipment_total'],
                    'subtotal' => $pricing['subtotal'],
                    'city_of_residence' => $validated['city_of_residence'] ?? null,
                    'is_resident' => $isResident,
                    'resident_discount_rate' => $residentDiscountRate,
                    'resident_discount_amount' => $residentDiscountAmount,
                    'special_discount_type' => $validated['special_discount_type'] ?? null,
                    'special_discount_rate' => $specialDiscountRate,
                    'special_discount_amount' => $specialDiscountAmount,
                    'total_discount' => $totalDiscount,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'staff_notes' => 'Submitted via API from: ' . $validated['source_system'] . 
                                    ($validated['external_reference_id'] ? ' (Ref: ' . $validated['external_reference_id'] . ')' : ''),
                ]);

                $bookingId = $booking->id;
                $bookingReference = 'BK' . str_pad($bookingId, 6, '0', STR_PAD_LEFT);

                // Add equipment if provided
                if (!empty($validated['equipment'])) {
                    foreach ($validated['equipment'] as $equip) {
                        $item = DB::connection('facilities_db')
                            ->table('equipment_items')
                            ->where('id', $equip['equipment_id'])
                            ->first();

                        if ($item) {
                            DB::connection('facilities_db')->table('booking_equipment')->insert([
                                'booking_id' => $bookingId,
                                'equipment_item_id' => $equip['equipment_id'],
                                'quantity' => $equip['quantity'],
                                'price_per_unit' => $item->price_per_unit,
                                'subtotal' => $item->price_per_unit * $equip['quantity'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                        }
                    }
                }

                DB::connection('facilities_db')->commit();

                Log::info('Facility Reservation API: Booking created', [
                    'booking_id' => $bookingId,
                    'booking_reference' => $bookingReference,
                    'source_system' => $validated['source_system'],
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Reservation request received and pending approval.',
                    'data' => [
                        'booking_id' => $bookingId,
                        'booking_reference' => $bookingReference,
                        'facility_name' => $facility->name,
                        'booking_date' => $validated['booking_date'],
                        'start_time' => $startDateTime->format('h:i A'),
                        'end_time' => $endDateTime->format('h:i A'),
                        'status' => 'pending',
                        'pricing' => [
                            'base_rate' => number_format($pricing['base_rate'], 2),
                            'extension_rate' => number_format($pricing['extension_rate'], 2),
                            'equipment_total' => number_format($pricing['equipment_total'], 2),
                            'subtotal' => number_format($pricing['subtotal'], 2),
                            'resident_discount' => number_format($residentDiscountAmount, 2),
                            'special_discount' => number_format($specialDiscountAmount, 2),
                            'total_amount' => number_format($totalAmount, 2),
                        ],
                    ],
                ], 201);

            } catch (\Exception $e) {
                DB::connection('facilities_db')->rollBack();
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Facility Reservation API: Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your request.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Check facility availability
     * 
     * GET /api/facility-reservation/check-availability
     */
    public function checkAvailability(Request $request)
    {
        try {
            $validated = $request->validate([
                'facility_id' => 'required|integer|exists:facilities_db.facilities,facility_id',
                'booking_date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
            ]);

            $startDateTime = Carbon::parse($validated['booking_date'] . ' ' . $validated['start_time']);
            $endDateTime = Carbon::parse($validated['booking_date'] . ' ' . $validated['end_time']);
            $bufferHours = 2;

            $conflict = DB::connection('facilities_db')
                ->table('bookings')
                ->where('facility_id', $validated['facility_id'])
                ->whereIn('status', ['pending', 'staff_verified', 'reserved', 'payment_pending', 'confirmed', 'paid'])
                ->where(function($query) use ($startDateTime, $endDateTime, $bufferHours) {
                    $query->whereRaw('? < DATE_ADD(end_time, INTERVAL ? HOUR)', [$startDateTime, $bufferHours])
                          ->whereRaw('? > start_time', [$endDateTime]);
                })
                ->exists();

            return response()->json([
                'status' => 'success',
                'available' => !$conflict,
                'message' => $conflict ? 'Time slot is not available.' : 'Time slot is available.',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Get list of available facilities
     * 
     * GET /api/facility-reservation/facilities
     */
    public function listFacilities()
    {
        $facilities = DB::connection('facilities_db')
            ->table('facilities')
            ->leftJoin('lgu_cities', 'facilities.lgu_city_id', '=', 'lgu_cities.id')
            ->select(
                'facilities.facility_id',
                'facilities.name',
                'facilities.description',
                'facilities.address',
                'facilities.capacity',
                'facilities.min_capacity',
                'facilities.per_person_rate',
                'facilities.per_person_extension_rate',
                'facilities.base_hours',
                'lgu_cities.city_name'
            )
            ->whereNull('facilities.deleted_at')
            ->orderBy('facilities.name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $facilities,
        ]);
    }

    /**
     * Get list of available equipment
     * 
     * GET /api/facility-reservation/equipment
     */
    public function listEquipment()
    {
        $equipment = DB::connection('facilities_db')
            ->table('equipment_items')
            ->where('is_available', true)
            ->select('id', 'name', 'description', 'category', 'price_per_unit', 'quantity_available')
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $equipment,
        ]);
    }

    /**
     * Check booking status by reference
     * 
     * GET /api/facility-reservation/status/{reference}
     */
    public function checkStatus($reference)
    {
        // Extract booking ID from reference (BK000001 -> 1)
        $bookingId = (int) preg_replace('/[^0-9]/', '', $reference);

        $booking = DB::connection('facilities_db')
            ->table('bookings')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->where('bookings.id', $bookingId)
            ->select(
                'bookings.id',
                'bookings.status',
                'bookings.start_time',
                'bookings.end_time',
                'bookings.total_amount',
                'bookings.rejected_reason',
                'bookings.created_at',
                'facilities.name as facility_name'
            )
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'booking_reference' => 'BK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
                'facility_name' => $booking->facility_name,
                'booking_status' => $booking->status,
                'start_time' => Carbon::parse($booking->start_time)->format('Y-m-d h:i A'),
                'end_time' => Carbon::parse($booking->end_time)->format('Y-m-d h:i A'),
                'total_amount' => number_format($booking->total_amount, 2),
                'rejected_reason' => $booking->rejected_reason,
                'submitted_at' => Carbon::parse($booking->created_at)->format('Y-m-d h:i A'),
            ],
        ]);
    }

    /**
     * Calculate pricing for the booking
     */
    private function calculatePricing($facility, $validated, $startDateTime, $endDateTime)
    {
        $expectedAttendees = $validated['expected_attendees'];
        $totalHours = $startDateTime->diffInHours($endDateTime);

        $baseRate = 0;
        $extensionRate = 0;

        if (isset($facility->per_person_rate) && $facility->per_person_rate > 0) {
            // Per-person pricing
            $perPersonRate = $facility->per_person_rate;
            $baseHours = $facility->base_hours ?? 3;
            $extensionRatePer2Hours = $facility->per_person_extension_rate ?? 0;

            $baseRate = $perPersonRate * $expectedAttendees;

            $extensionHours = max(0, $totalHours - $baseHours);
            if ($extensionHours > 0 && $extensionRatePer2Hours > 0) {
                $extensionBlocks = ceil($extensionHours / 2);
                $extensionRate = $extensionBlocks * $extensionRatePer2Hours * $expectedAttendees;
            }
        } else {
            // Default pricing
            $baseRate = 7000.00;
            $extensionRatePerTwoHours = 3000.00;
            $baseHours = 3;
            $extensionHours = max(0, $totalHours - $baseHours);
            $extensionBlocks = ceil($extensionHours / 2);
            $extensionRate = $extensionBlocks * $extensionRatePerTwoHours;
        }

        // Calculate equipment total
        $equipmentTotal = 0;
        if (!empty($validated['equipment'])) {
            foreach ($validated['equipment'] as $equip) {
                $item = DB::connection('facilities_db')
                    ->table('equipment_items')
                    ->where('id', $equip['equipment_id'])
                    ->first();
                if ($item) {
                    $equipmentTotal += $item->price_per_unit * $equip['quantity'];
                }
            }
        }

        $subtotal = $baseRate + $extensionRate + $equipmentTotal;

        return [
            'base_rate' => $baseRate,
            'extension_rate' => $extensionRate,
            'equipment_total' => $equipmentTotal,
            'subtotal' => $subtotal,
        ];
    }
}
