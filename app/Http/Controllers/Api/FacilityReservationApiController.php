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
     * ==========================================================================
     * FACILITY RESERVATION API
     * ==========================================================================
     * 
     * Endpoint: https://facilities.local-government-unit-1-ph.com/api/facility-reservation
     * 
     * This API allows external systems to make facility reservations.
     * All reservations created here will appear in the staff dashboard.
     * 
     * ==========================================================================
     */

    /**
     * Receive facility reservation request from external system
     * 
     * POST https://facilities.local-government-unit-1-ph.com/api/facility-reservation
     */
    public function store(Request $request)
    {
        try {
            // Log incoming request
            Log::info('Facility Reservation API: Request received', [
                'ip' => $request->ip(),
                'source_system' => $request->input('source_system'),
            ]);

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
                
                // Valid ID documents (file paths/URLs from external system)
                'valid_id_type' => 'nullable|string|max:100',
                'valid_id_front' => 'nullable|string|max:500',
                'valid_id_back' => 'nullable|string|max:500',
                'valid_id_selfie' => 'nullable|string|max:500',
                
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
                    'valid_id_type' => $validated['valid_id_type'] ?? null,
                    'valid_id_front_path' => $validated['valid_id_front'] ?? null,
                    'valid_id_back_path' => $validated['valid_id_back'] ?? null,
                    'valid_id_selfie_path' => $validated['valid_id_selfie'] ?? null,
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

            $activeStatuses = ['pending', 'staff_verified', 'reserved', 'payment_pending', 'confirmed', 'paid'];

            $conflict = DB::connection('facilities_db')
                ->table('bookings')
                ->where('facility_id', $validated['facility_id'])
                ->whereIn('status', $activeStatuses)
                ->where(function($query) use ($startDateTime, $endDateTime, $bufferHours) {
                    $query->whereRaw('? < DATE_ADD(end_time, INTERVAL ? HOUR)', [$startDateTime, $bufferHours])
                          ->whereRaw('? > start_time', [$endDateTime]);
                })
                ->exists();

            $response = [
                'status' => 'success',
                'available' => !$conflict,
                'message' => $conflict ? 'Time slot is not available.' : 'Time slot is available.',
            ];

            // When conflict exists, return existing bookings for the day and suggest available slots
            if ($conflict) {
                $existingBookings = DB::connection('facilities_db')
                    ->table('bookings')
                    ->where('facility_id', $validated['facility_id'])
                    ->whereDate('start_time', $validated['booking_date'])
                    ->whereIn('status', $activeStatuses)
                    ->select('start_time', 'end_time', 'status')
                    ->orderBy('start_time')
                    ->get()
                    ->map(function($b) {
                        return [
                            'start' => Carbon::parse($b->start_time)->format('H:i'),
                            'end' => Carbon::parse($b->end_time)->format('H:i'),
                        ];
                    });

                $response['existing_bookings'] = $existingBookings;

                // Calculate suggested available 3-hour slots (operating hours 08:00-22:00, 2hr buffer)
                $opStart = 8;
                $opEnd = 22;
                $slotDuration = 3;
                $suggested = [];

                for ($h = $opStart; $h <= $opEnd - $slotDuration; $h++) {
                    $slotStart = Carbon::parse($validated['booking_date'] . ' ' . str_pad($h, 2, '0', STR_PAD_LEFT) . ':00');
                    $slotEnd = $slotStart->copy()->addHours($slotDuration);

                    $slotConflict = DB::connection('facilities_db')
                        ->table('bookings')
                        ->where('facility_id', $validated['facility_id'])
                        ->whereIn('status', $activeStatuses)
                        ->where(function($query) use ($slotStart, $slotEnd, $bufferHours) {
                            $query->whereRaw('? < DATE_ADD(end_time, INTERVAL ? HOUR)', [$slotStart, $bufferHours])
                                  ->whereRaw('? > start_time', [$slotEnd]);
                        })
                        ->exists();

                    if (!$slotConflict) {
                        $suggested[] = [
                            'start' => $slotStart->format('H:i'),
                            'end' => $slotEnd->format('H:i'),
                            'start_display' => $slotStart->format('g:i A'),
                            'end_display' => $slotEnd->format('g:i A'),
                        ];
                    }
                }

                $response['suggested_slots'] = $suggested;
            }

            return response()->json($response);

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
     * Get calendar bookings for a facility in a given month
     * 
     * GET /api/facility-reservation/calendar-bookings
     */
    public function calendarBookings(Request $request)
    {
        try {
            $validated = $request->validate([
                'facility_id' => 'nullable|integer',
                'year' => 'required|integer|min:2020|max:2030',
                'month' => 'required|integer|min:1|max:12',
            ]);

            $year = $validated['year'];
            $month = $validated['month'];
            $facilityId = $validated['facility_id'] ?? null;

            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            $query = DB::connection('facilities_db')
                ->table('bookings')
                ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
                ->whereIn('bookings.status', ['pending', 'staff_verified', 'reserved', 'payment_pending', 'confirmed', 'paid', 'completed'])
                ->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween(DB::raw('DATE(bookings.start_time)'), [$startDate->toDateString(), $endDate->toDateString()]);
                })
                ->select(
                    'bookings.id',
                    'bookings.facility_id',
                    'bookings.start_time',
                    'bookings.end_time',
                    'bookings.status',
                    'bookings.event_name',
                    'facilities.name as facility_name'
                )
                ->orderBy('bookings.start_time');

            if ($facilityId) {
                $query->where('bookings.facility_id', $facilityId);
            }

            $bookings = $query->get();

            $events = $bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'facility_id' => $booking->facility_id,
                    'facility_name' => $booking->facility_name,
                    'date' => Carbon::parse($booking->start_time)->format('Y-m-d'),
                    'start_time' => Carbon::parse($booking->start_time)->format('h:i A'),
                    'end_time' => Carbon::parse($booking->end_time)->format('h:i A'),
                    'status' => $booking->status,
                    'event_name' => $booking->event_name,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $events,
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
     * Get bookings by applicant email
     * 
     * GET /api/facility-reservation/my-bookings?email=user@example.com
     */
    public function myBookings(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|max:255',
            ]);

            $bookings = DB::connection('facilities_db')
                ->table('bookings')
                ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
                ->where('bookings.applicant_email', $validated['email'])
                ->select(
                    'bookings.id',
                    'bookings.status',
                    'bookings.start_time',
                    'bookings.end_time',
                    'bookings.total_amount',
                    'bookings.applicant_name',
                    'bookings.applicant_email',
                    'bookings.rejected_reason',
                    'bookings.created_at',
                    'facilities.name as facility_name'
                )
                ->orderBy('bookings.created_at', 'desc')
                ->limit(50)
                ->get();

            $data = $bookings->map(function ($booking) {
                return [
                    'booking_reference' => 'BK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
                    'facility_name' => $booking->facility_name,
                    'booking_status' => $booking->status,
                    'start_time' => Carbon::parse($booking->start_time)->format('Y-m-d h:i A'),
                    'end_time' => Carbon::parse($booking->end_time)->format('Y-m-d h:i A'),
                    'total_amount' => number_format($booking->total_amount, 2),
                    'applicant_name' => $booking->applicant_name,
                    'applicant_email' => $booking->applicant_email,
                    'rejected_reason' => $booking->rejected_reason,
                    'submitted_at' => Carbon::parse($booking->created_at)->format('Y-m-d h:i A'),
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $data,
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

    /**
     * Handle payment completion notification from external systems (e.g., pure PHP portal)
     */
    public function paymentComplete(Request $request)
    {
        try {
            $validated = $request->validate([
                'booking_reference' => 'required|string|max:20',
                'paymongo_checkout_id' => 'required|string|max:100',
                'source_system' => 'nullable|string|max:100',
            ]);

            // Extract booking ID from reference (BK000001 -> 1)
            $bookingId = (int) preg_replace('/[^0-9]/', '', $validated['booking_reference']);

            $booking = DB::connection('facilities_db')
                ->table('bookings')
                ->where('id', $bookingId)
                ->first();

            if (!$booking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found.',
                ], 404);
            }

            // Get payment slip
            $paymentSlip = DB::connection('facilities_db')
                ->table('payment_slips')
                ->where('booking_id', $bookingId)
                ->first();

            if (!$paymentSlip) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment slip not found.',
                ], 404);
            }

            // Verify payment with Paymongo
            $paymongoService = new \App\Services\PaymongoService();
            $isSuccessful = $paymongoService->isPaymentSuccessful($validated['paymongo_checkout_id']);

            if (!$isSuccessful) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment not verified.',
                ], 400);
            }

            // Get payment details
            $paymentDetails = $paymongoService->getPaymentDetails($validated['paymongo_checkout_id']);

            // Update payment slip
            DB::connection('facilities_db')
                ->table('payment_slips')
                ->where('id', $paymentSlip->id)
                ->update([
                    'status' => 'paid',
                    'payment_method' => $paymentDetails['payment_method'] ?? 'paymongo',
                    'payment_channel' => 'paymongo',
                    'transaction_reference' => $paymentDetails['reference_number'] ?? $validated['paymongo_checkout_id'],
                    'gateway_reference_number' => $paymentDetails['payment_id'] ?? $validated['paymongo_checkout_id'],
                    'paymongo_checkout_id' => $validated['paymongo_checkout_id'],
                    'paid_at' => now(),
                    'updated_at' => now(),
                ]);

            // Update booking status to confirmed
            DB::connection('facilities_db')
                ->table('bookings')
                ->where('id', $bookingId)
                ->update([
                    'status' => 'confirmed',
                    'updated_at' => now(),
                ]);

            Log::info('Payment completed via API', [
                'booking_reference' => $validated['booking_reference'],
                'source_system' => $validated['source_system'] ?? 'unknown',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment confirmed successfully.',
                'data' => [
                    'booking_reference' => $validated['booking_reference'],
                    'booking_status' => 'confirmed',
                    'payment_status' => 'paid',
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Payment complete API error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred processing payment.',
            ], 500);
        }
    }

    /**
     * Handle manual cashless payment submission from external systems (e.g., PF portal)
     * Citizens pay via GCash/Maya on their own and submit the reference number.
     */
    public function submitCashlessPayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'booking_reference' => 'required|string|max:20',
                'payment_channel' => 'required|string|in:gcash,maya',
                'reference_number' => 'required|string|max:20',
                'account_name' => 'nullable|string|max:100',
                'amount' => 'required|string',
                'source_system' => 'nullable|string|max:100',
            ]);

            $bookingId = (int) preg_replace('/[^0-9]/', '', $validated['booking_reference']);

            $booking = DB::connection('facilities_db')
                ->table('bookings')
                ->where('id', $bookingId)
                ->first();

            if (!$booking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found.',
                ], 404);
            }

            $paymentSlip = DB::connection('facilities_db')
                ->table('payment_slips')
                ->where('booking_id', $bookingId)
                ->first();

            if (!$paymentSlip) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment slip not found.',
                ], 404);
            }

            if ($paymentSlip->status === 'paid') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This payment slip has already been paid.',
                ], 400);
            }

            $referenceNumber = strtoupper(trim($validated['reference_number']));
            $paymentChannel = $validated['payment_channel'];
            $paymentMethod = $paymentChannel === 'maya' ? 'paymaya' : $paymentChannel;

            // Check for duplicate reference number
            $duplicate = DB::connection('facilities_db')
                ->table('payment_slips')
                ->where('transaction_reference', $referenceNumber)
                ->where('payment_channel', $paymentChannel)
                ->where('id', '!=', $paymentSlip->id)
                ->exists();

            if ($duplicate) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This reference number has already been used.',
                ], 400);
            }

            // Update payment slip with reference number
            DB::connection('facilities_db')
                ->table('payment_slips')
                ->where('id', $paymentSlip->id)
                ->update([
                    'payment_method' => $paymentMethod,
                    'payment_channel' => $paymentChannel,
                    'transaction_reference' => $referenceNumber,
                    'account_name' => $validated['account_name'] ?? null,
                    'gateway_reference_number' => $referenceNumber,
                    'sent_to_treasurer_at' => now(),
                    'updated_at' => now(),
                ]);

            // Update booking status
            DB::connection('facilities_db')
                ->table('bookings')
                ->where('id', $bookingId)
                ->update([
                    'status' => 'staff_verified',
                    'updated_at' => now(),
                ]);

            Log::info('Manual cashless payment submitted via API', [
                'booking_reference' => $validated['booking_reference'],
                'payment_channel' => $paymentChannel,
                'reference_number' => $referenceNumber,
                'source_system' => $validated['source_system'] ?? 'pf_portal',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment submitted successfully. Treasurer will verify within 24 hours.',
                'data' => [
                    'booking_reference' => $validated['booking_reference'],
                    'payment_channel' => $paymentChannel,
                    'reference_number' => $referenceNumber,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Submit cashless payment API error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred processing payment.',
            ], 500);
        }
    }

    /**
     * Get refund requests for a citizen by email.
     *
     * GET /api/facility-reservation/refunds?email=...
     */
    public function getRefunds(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $email = $request->get('email');

            $refunds = DB::connection('facilities_db')
                ->table('refund_requests')
                ->where('applicant_email', $email)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $refunds,
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
     * Submit refund method selection from PF folder citizen.
     *
     * POST /api/facility-reservation/refunds/{id}/select-method
     */
    public function selectRefundMethod(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'refund_method' => 'required|in:cash,gcash,maya,bank_transfer',
                'account_name' => 'required_unless:refund_method,cash|nullable|string|max:255',
                'account_number' => 'required_unless:refund_method,cash|nullable|string|max:50',
                'bank_name' => 'required_if:refund_method,bank_transfer|nullable|string|max:255',
            ]);

            $refund = DB::connection('facilities_db')
                ->table('refund_requests')
                ->where('id', $id)
                ->where('applicant_email', $validated['email'])
                ->first();

            if (!$refund) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Refund request not found.',
                ], 404);
            }

            if ($refund->status !== 'pending_method') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Refund method has already been selected.',
                ], 400);
            }

            $updateData = [
                'refund_method' => $validated['refund_method'],
                'status' => 'pending_processing',
                'updated_at' => now(),
            ];

            if ($validated['refund_method'] !== 'cash') {
                $updateData['account_name'] = $validated['account_name'];
                $updateData['account_number'] = $validated['account_number'];
                if ($validated['refund_method'] === 'bank_transfer') {
                    $updateData['bank_name'] = $validated['bank_name'];
                }
            }

            DB::connection('facilities_db')
                ->table('refund_requests')
                ->where('id', $id)
                ->update($updateData);

            return response()->json([
                'status' => 'success',
                'message' => 'Refund method selected successfully. Your refund will be processed within 1-3 business days.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
