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
    
    // GET https://facilities.local-government-unit-1-ph.com/api/facility-reservation/status/{reference}
    Route::get('/status/{reference}', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'checkStatus']);
    
    // POST https://facilities.local-government-unit-1-ph.com/api/facility-reservation
    Route::post('/', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'store']);
    
    // POST https://facilities.local-government-unit-1-ph.com/api/facility-reservation/payment-complete
    Route::post('/payment-complete', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'paymentComplete']);
});

/*
|--------------------------------------------------------------------------
| Housing and Resettlement Management API
|--------------------------------------------------------------------------
| API endpoints for Housing and Resettlement Management system to request
| facilities for beneficiary orientations.
|
| Base URL: https://local-government-unit-1-ph.com/api/housing-resettlement
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

