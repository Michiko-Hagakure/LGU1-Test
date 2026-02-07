<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Legacy Energy Fund Bridge API (Backward Compatibility)
|--------------------------------------------------------------------------
| These routes maintain backward compatibility with older Energy Efficiency
| system integrations that use the original endpoint paths.
*/
Route::post('/receive-funds', function (Request $request) {
    $newRequest = \App\Models\FundRequest::create([
        'requester_name' => $request->requester_name,
        'user_id' => $request->user_id,
        'amount' => $request->amount,
        'purpose' => $request->purpose,
        'logistics' => $request->logistics ?? 'None',
        'seminar_info' => $request->seminar_info ?? null,
        'seminar_image' => $request->seminar_image ?? null,
        'seminar_id' => $request->seminar_id ?? null,
        'status' => 'pending',
    ]);

    if ($newRequest) {
        return response()->json(['status' => 'success', 'id' => $newRequest->id]);
    }
    return response()->json(['status' => 'error'], 500);
});

Route::get('/check-status/{id}', function ($id) {
    $fund = \App\Models\FundRequest::find($id);
    if ($fund) {
        return response()->json([
            'status' => $fund->status,
            'feedback' => $fund->feedback,
            'requester_name' => $fund->requester_name
        ]);
    }
    return response()->json(['status' => 'not_found'], 404);
});

/*
|--------------------------------------------------------------------------
| Facility Reservation API
|--------------------------------------------------------------------------
| Base URL: https://facilities.local-government-unit-1-ph.com
|
| Available Endpoints:
| GET  https://facilities.local-government-unit-1-ph.com/api/facility-reservation/facilities
| GET  https://facilities.local-government-unit-1-ph.com/api/facility-reservation/equipment
| GET  https://facilities.local-government-unit-1-ph.com/api/facility-reservation/check-availability
| GET  https://facilities.local-government-unit-1-ph.com/api/facility-reservation/status/{reference}
| POST https://facilities.local-government-unit-1-ph.com/api/facility-reservation
|
| All endpoints are public - no API key required.
*/
Route::prefix('facility-reservation')->group(function () {
    // GET https://facilities.local-government-unit-1-ph.com/api/facility-reservation/facilities
    Route::get('/facilities', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'listFacilities']);
    
    // GET https://facilities.local-government-unit-1-ph.com/api/facility-reservation/equipment
    Route::get('/equipment', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'listEquipment']);
    
    // GET https://facilities.local-government-unit-1-ph.com/api/facility-reservation/check-availability
    Route::get('/check-availability', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'checkAvailability']);
    
    // GET https://facilities.local-government-unit-1-ph.com/api/facility-reservation/calendar-bookings
    Route::get('/calendar-bookings', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'calendarBookings']);
    
    // GET https://facilities.local-government-unit-1-ph.com/api/facility-reservation/my-bookings?email=...
    Route::get('/my-bookings', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'myBookings']);

    // GET https://facilities.local-government-unit-1-ph.com/api/facility-reservation/status/{reference}
    Route::get('/status/{reference}', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'checkStatus']);
    
    // POST https://facilities.local-government-unit-1-ph.com/api/facility-reservation
    Route::post('/', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'store']);
    
    // POST https://facilities.local-government-unit-1-ph.com/api/facility-reservation/payment-complete
    Route::post('/payment-complete', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'paymentComplete']);
    
    // POST https://facilities.local-government-unit-1-ph.com/api/facility-reservation/submit-cashless-payment
    Route::post('/submit-cashless-payment', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'submitCashlessPayment']);

    // GET https://facilities.local-government-unit-1-ph.com/api/facility-reservation/refunds?email=...
    Route::get('/refunds', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'getRefunds']);

    // POST https://facilities.local-government-unit-1-ph.com/api/facility-reservation/refunds/{id}/select-method
    Route::post('/refunds/{id}/select-method', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'selectRefundMethod']);
});

/*
|--------------------------------------------------------------------------
| Housing and Resettlement Management API
|--------------------------------------------------------------------------
| API endpoints for Housing and Resettlement Management system to request
| facilities for beneficiary orientations.
|
| Base URL: https://facilities.local-government-unit-1-ph.com/api/housing-resettlement
*/
Route::prefix('housing-resettlement')->group(function () {
    // GET - List available facilities
    Route::get('/facilities', [\App\Http\Controllers\Api\HousingResettlementApiController::class, 'listFacilities']);
    
    // GET - Check facility availability for a date/time
    Route::get('/check-availability', [\App\Http\Controllers\Api\HousingResettlementApiController::class, 'checkAvailability']);
    
    // POST - Submit facility request
    Route::post('/request', [\App\Http\Controllers\Api\HousingResettlementApiController::class, 'submitRequest']);
    
    // GET - Check booking status
    Route::get('/status/{reference}', [\App\Http\Controllers\Api\HousingResettlementApiController::class, 'checkStatus']);
});

/*
|--------------------------------------------------------------------------
| Energy Efficiency and Conservation Management API
|--------------------------------------------------------------------------
| API endpoint for Energy Efficiency system to submit fund requests
| for facility-related expenses (seminars, orientations, etc.)
|
| Base URL: https://local-government-unit-1-ph.com/api/energy-efficiency
*/
Route::prefix('energy-efficiency')->group(function () {
    // POST - Receive fund request from Energy Efficiency system
    Route::post('/receive-funds', function (Request $request) {
        $newRequest = \App\Models\FundRequest::create([
            'requester_name' => $request->requester_name,
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'purpose' => $request->purpose,
            'logistics' => $request->logistics,
            'seminar_info' => $request->seminar_info ?? null,
            'seminar_image' => $request->seminar_image ?? null,
            'status' => 'pending',
        ]);

        if ($newRequest) {
            return response()->json([
                'status' => 'success',
                'message' => 'Fund request submitted successfully',
                'id' => $newRequest->id,
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Failed to create fund request'], 500);
    });

    // GET - Check fund request status
    Route::get('/status/{id}', function ($id) {
        $request = \App\Models\FundRequest::find($id);

        if (!$request) {
            return response()->json(['status' => 'error', 'message' => 'Request not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $request->id,
                'requester_name' => $request->requester_name,
                'amount' => $request->amount,
                'purpose' => $request->purpose,
                'approval_status' => $request->status,
                'feedback' => $request->feedback,
                'created_at' => $request->created_at,
                'updated_at' => $request->updated_at,
            ],
        ]);
    });
});


/*
|--------------------------------------------------------------------------
| Road and Transportation Infrastructure Monitoring API
|--------------------------------------------------------------------------
| API endpoints for Road and Transportation system to submit road assistance
| requests for events that may cause traffic congestion.
|
| Available Endpoints:
| POST /api/road-assistance/request - Submit a new road assistance request
| GET  /api/road-assistance/status/{id} - Check request status
*/
Route::prefix('road-assistance')->group(function () {
    // POST - Submit a new road assistance request
    Route::post('/request', function (Request $request) {
        $validated = $request->validate([
            'requester_name' => 'required|string|max:255',
            'user_id' => 'nullable|integer',
            'event_name' => 'required|string|max:255',
            'event_description' => 'nullable|string',
            'event_location' => 'required|string|max:500',
            'event_date' => 'required|date',
            'event_start_time' => 'nullable|string|max:10',
            'event_end_time' => 'nullable|string|max:10',
            'expected_attendees' => 'nullable|integer',
            'affected_roads' => 'nullable|string',
            'assistance_type' => 'nullable|string',
            'special_requirements' => 'nullable|string',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
        ]);

        $newRequest = \App\Models\RoadAssistanceRequest::create([
            'requester_name' => $validated['requester_name'],
            'user_id' => $validated['user_id'] ?? null,
            'event_name' => $validated['event_name'],
            'event_description' => $validated['event_description'] ?? null,
            'event_location' => $validated['event_location'],
            'event_date' => $validated['event_date'],
            'event_start_time' => $validated['event_start_time'] ?? null,
            'event_end_time' => $validated['event_end_time'] ?? null,
            'expected_attendees' => $validated['expected_attendees'] ?? null,
            'affected_roads' => $validated['affected_roads'] ?? null,
            'assistance_type' => $validated['assistance_type'] ?? null,
            'special_requirements' => $validated['special_requirements'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'contact_email' => $validated['contact_email'] ?? null,
            'status' => 'pending',
        ]);

        if ($newRequest) {
            return response()->json([
                'status' => 'success',
                'message' => 'Road assistance request submitted successfully',
                'id' => $newRequest->id,
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Failed to create road assistance request'], 500);
    });

    // GET - Check road assistance request status
    Route::get('/status/{id}', function ($id) {
        $request = \App\Models\RoadAssistanceRequest::find($id);

        if (!$request) {
            return response()->json(['status' => 'error', 'message' => 'Request not found'], 404);
        }

        $responseData = $request->response_data ? json_decode($request->response_data, true) : null;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $request->id,
                'requester_name' => $request->requester_name,
                'event_name' => $request->event_name,
                'event_location' => $request->event_location,
                'event_date' => $request->event_date,
                'approval_status' => $request->status,
                'feedback' => $request->feedback,
                'response_data' => $responseData,
                'created_at' => $request->created_at,
                'updated_at' => $request->updated_at,
            ],
        ]);
    });
});
